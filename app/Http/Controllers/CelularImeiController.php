<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CelularImei;
use App\CelularCanje;
use App\CelularImeiImagen;
use App\CelularImeiHistorial;
use App\Reparacion;
use App\ReparacionHistorial;
use App\Sucursal;
use App\Empresa;
use App\Cliente;
use App\Proveedor;
use App\User;
use App\Apertura;
use App\MovimientoCaja;
use App\Support\ArticuloLibre;
use App\Support\CameraSettings;
use App\Support\CloudinarySettings;
use App\Support\ReparacionSettings;
use App\Support\CelularImeiSettings;
use App\Support\CelularImeiSchema;
use App\Services\CloudinaryService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Exception;

class CelularImeiController extends Controller
{
    public function __construct()
    {
        CelularImeiSchema::ensureExists();

        $this->middleware('auth');

        // Verificar si el módulo está habilitado por el Superadministrador
        $this->middleware(function ($request, $next) {
            $actionMethod = $request->route() ? $request->route()->getActionMethod() : '';
            if ($actionMethod === 'toggleModulo') {
                return $next($request);
            }

            if (!CelularImeiSettings::isModuloActivo() && !Auth::user()->esSuperAdministrador()) {
                $msg = 'El módulo de Equipos por IMEI y Plan Canje se encuentra deshabilitado. Solo el Superadministrador del sistema puede habilitarlo.';
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error'   => $msg,
                        'message' => $msg,
                    ], 403);
                }
                return redirect()->route('home')->with('error', $msg);
            }

            return $next($request);
        });

        $this->middleware('permiso:celulares_imei,open')->only([
            'index', 'show', 'buscarStockDisponible', 'getParaVenta'
        ]);
        $this->middleware('permiso:celulares_imei,add')->only([
            'storeEquipo', 'registrarPlanCanje', 'subirImagen', 'subirImagenBase64', 'capturarImagenCamara'
        ]);
        $this->middleware('permiso:celulares_imei,edit')->only([
            'updateEquipo', 'enviarATaller', 'finalizarReacondicionamiento', 'venderDirecto', 'eliminarImagen'
        ]);
        $this->middleware('permiso:celulares_imei,del')->only([
            'destroy'
        ]);
        $this->middleware('permiso:celulares_imei,export')->only([
            'pdfEtiqueta', 'pdfContratoCanje', 'pdfCertificadoGarantia'
        ]);
    }

    /**
     * Habilita o deshabilita el módulo (Exclusivo Superadministrador).
     */
    public function toggleModulo(Request $request)
    {
        if (!Auth::user()->esSuperAdministrador()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso exclusivo para el Superadministrador del sistema.',
            ], 403);
        }

        $nuevoEstado = $request->has('activo')
            ? (bool) $request->input('activo')
            : !CelularImeiSettings::isModuloActivo();

        $activo = CelularImeiSettings::setModuloActivo($nuevoEstado);

        return response()->json([
            'success' => true,
            'activo'  => $activo,
            'message' => $activo
                ? 'Módulo de Compra-Venta por IMEI y Plan Canje HABILITADO.'
                : 'Módulo de Compra-Venta por IMEI y Plan Canje DESHABILITADO.',
        ]);
    }

    /**
     * Vista principal y listado AJAX con KPIs de inventario por IMEI y Plan Canje.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $tipoLista = $request->get('lista', 'equipos'); // equipos | canjes
            $estado = $request->get('estado', 'TODOS');
            $condicion = $request->get('condicion', 'TODOS');
            $sucursal = $request->get('sucursal', '0');
            $q = trim((string) $request->get('q', ''));

            if ($tipoLista === 'canjes') {
                $canjesQuery = CelularCanje::with([
                    'cliente:CLIENTES_cod,cliente_nombre,cliente_ruc,cliente_ci,cliente_cel',
                    'celularEntregado:id,codigo_interno,marca,modelo,capacidad_gb,color,imei_1,bateria_salud,estado',
                    'celularLlevado:id,codigo_interno,marca,modelo,capacidad_gb,color,imei_1',
                    'reparacion:id,codigo_orden,estado',
                    'usuario:cod_usuarios,nom_usuarios',
                    'sucursal:suc_cod,suc_desc',
                ]);

                if (!empty($sucursal) && $sucursal != '0') {
                    $canjesQuery->where('suc_cod', $sucursal);
                }
                if ($q !== '') {
                    $canjesQuery->where(function ($w) use ($q) {
                        $w->where('codigo_canje', 'like', "%{$q}%")
                          ->orWhereHas('cliente', function ($c) use ($q) {
                              $c->where('cliente_nombre', 'like', "%{$q}%")
                                ->orWhere('cliente_ci', 'like', "%{$q}%")
                                ->orWhere('cliente_ruc', 'like', "%{$q}%");
                          })
                          ->orWhereHas('celularEntregado', function ($e) use ($q) {
                              $e->where('imei_1', 'like', "%{$q}%")
                                ->orWhere('marca', 'like', "%{$q}%")
                                ->orWhere('modelo', 'like', "%{$q}%");
                          });
                    });
                }

                $canjes = $canjesQuery->orderBy('id', 'desc')->paginate(20);
                return response()->json([
                    'canjes' => $canjes,
                ]);
            }

            $query = CelularImei::with([
                'sucursal:suc_cod,suc_desc',
                'clienteOrigen:CLIENTES_cod,cliente_nombre,cliente_ci,cliente_ruc,cliente_cel',
                'clienteComprador:CLIENTES_cod,cliente_nombre,cliente_ci,cliente_ruc,cliente_cel',
                'proveedor:PROVEEDOR_cod,proveedor_nombre,proveedor_ruc',
                'reparacionActual:id,codigo_orden,estado,total_general,costo_repuestos',
            ])
            ->withCount('imagenes')
            ->filtroEstado($estado)
            ->filtroCondicion($condicion)
            ->filtroSucursal($sucursal)
            ->filtroBusqueda($q)
            ->orderBy('id', 'desc');

            $equipos = $query->paginate(20);

            if (!Auth::user()->tieneAccion('imei_ver_costo')) {
                foreach ($equipos as $eq) {
                    $eq->costo_adquisicion = 0;
                    $eq->costo_reacondicionamiento = 0;
                    $eq->costo_total = 0;
                }
            }

            // KPIs
            $baseKpi = CelularImei::query()->where('estado', '!=', 'BAJA');
            if (!empty($sucursal) && $sucursal != '0') {
                $baseKpi->where('suc_cod', $sucursal);
            }

            $verCosto = Auth::user()->tieneAccion('imei_ver_costo');
            $kpis = [
                'en_stock'             => (clone $baseKpi)->where('estado', 'EN_STOCK')->count(),
                'en_taller'            => (clone $baseKpi)->where('estado', 'EN_REACONDICIONAMIENTO')->count(),
                'vendidos'             => (clone $baseKpi)->where('estado', 'VENDIDO')->count(),
                'recibidos_canje'      => (clone $baseKpi)->whereIn('origen_tipo', ['PLAN_CANJE', 'COMPRA_PARTICULAR'])->count(),
                'valor_venta_stock'    => (float) (clone $baseKpi)->where('estado', 'EN_STOCK')->sum('precio_venta_contado'),
                'costo_invertido_stock'=> $verCosto ? (float) (clone $baseKpi)->whereIn('estado', ['EN_STOCK', 'EN_REACONDICIONAMIENTO'])->sum('costo_total') : 0,
            ];

            return response()->json([
                'equipos' => $equipos,
                'kpis'    => $kpis,
            ]);
        }

        $sucursales = Sucursal::all();
        $proveedores = Proveedor::orderBy('proveedor_nombre')->get(['PROVEEDOR_cod', 'proveedor_nombre', 'proveedor_ruc']);
        $tecnicos = User::where('activo', 1)->orderBy('nom_usuarios')->get(['cod_usuarios', 'nom_usuarios']);
        $empresa = Empresa::first();
        $user = Auth::user();
        $esAdministrador = $user->esAdministrador();
        $esSuperAdministrador = $user->esSuperAdministrador();
        $sucursalActual = session('sucursal_actual') ?? ($user->suc_cod ?? ($sucursales->first()->suc_cod ?? 1));
        $imeiConfig = CelularImeiSettings::all();
        $camaraConfig = CameraSettings::publicConfig();

        $permisosModulo = [
            'open'           => $user->tienePermiso('celulares_imei', 'open'),
            'add'            => $user->tienePermiso('celulares_imei', 'add'),
            'edit'           => $user->tienePermiso('celulares_imei', 'edit'),
            'del'            => $user->tienePermiso('celulares_imei', 'del'),
            'export'         => $user->tienePermiso('celulares_imei', 'export'),
            'compra_canje'   => $user->tieneAccion('imei_registrar_compra_canje'),
            'vender'         => $user->tieneAccion('imei_vender'),
            'reacondicionar' => $user->tieneAccion('imei_reacondicionar'),
            'ver_costo'      => $user->tieneAccion('imei_ver_costo'),
        ];

        return view('celulares_imei', compact(
            'sucursales',
            'proveedores',
            'tecnicos',
            'empresa',
            'esAdministrador',
            'esSuperAdministrador',
            'sucursalActual',
            'imeiConfig',
            'camaraConfig',
            'permisosModulo'
        ));
    }

    /**
     * Busca equipos EN_STOCK disponibles para venta o para entregar en un Plan Canje.
     */
    public function buscarStockDisponible(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $suc = $request->get('suc_cod', 0);

        $query = CelularImei::where('estado', 'EN_STOCK');
        if (!empty($suc) && $suc != '0') {
            $query->where('suc_cod', $suc);
        }
        if ($q !== '') {
            $query->filtroBusqueda($q);
        }

        $equipos = $query->orderBy('marca')->orderBy('modelo')->limit(40)->get();
        return response()->json($equipos);
    }

    protected function generarCodigoEquipo($nextId = null)
    {
        $cfg = CelularImeiSettings::all();
        $prefijo = $cfg['prefijo_equipo'] ?: 'EQ-';
        if (!$nextId) {
            $nextId = (int) (CelularImei::max('id') ?: 0) + 1;
        }
        $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        while (CelularImei::where('codigo_interno', $cod)->exists()) {
            $nextId++;
            $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }
        return $cod;
    }

    protected function generarCodigoCanje($nextId = null)
    {
        $cfg = CelularImeiSettings::all();
        $prefijo = $cfg['prefijo_canje'] ?: 'CJ-';
        if (!$nextId) {
            $nextId = (int) (CelularCanje::max('id') ?: 0) + 1;
        }
        $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        while (CelularCanje::where('codigo_canje', $cod)->exists()) {
            $nextId++;
            $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }
        return $cod;
    }

    /**
     * Registra el ingreso de un nuevo equipo por IMEI al inventario.
     */
    public function storeEquipo(Request $request)
    {
        if (!Auth::user()->tieneAccion('imei_registrar_compra_canje')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar ingresos de equipos por IMEI.',
            ], 403);
        }

        $request->validate([
            'marca'                => 'required|string|max:80',
            'modelo'               => 'required|string|max:120',
            'imei_1'               => 'required|string|max:80',
            'precio_venta_contado' => 'required|numeric|min:0',
        ], [
            'marca.required'  => 'Ingresá la marca del dispositivo.',
            'modelo.required' => 'Ingresá el modelo del dispositivo.',
            'imei_1.required' => 'El número de IMEI o Serie es obligatorio.',
        ]);

        $imei1 = trim($request->input('imei_1'));
        $duplicadoActivo = CelularImei::where('imei_1', $imei1)
            ->whereIn('estado', ['EN_STOCK', 'EN_REACONDICIONAMIENTO', 'RESERVADO'])
            ->first();

        if ($duplicadoActivo) {
            return response()->json([
                'success' => false,
                'message' => "El IMEI {$imei1} ya se encuentra activo en inventario ({$duplicadoActivo->codigo_interno} - {$duplicadoActivo->marca} {$duplicadoActivo->modelo}).",
            ], 422);
        }

        DB::beginTransaction();
        try {
            $cfg = CelularImeiSettings::all();
            $condicion = $request->input('condicion_ingreso', 'NUEVO');
            $diasGarantiaDefault = ($condicion === 'NUEVO') ? $cfg['garantia_nuevo_dias'] : $cfg['garantia_usado_dias'];

            $costoAdq = max(0, (float) $request->input('costo_adquisicion', 0));
            $precioContado = max(0, (float) $request->input('precio_venta_contado', 0));
            $precioCredito = max(0, (float) $request->input('precio_venta_credito', 0));

            $eq = new CelularImei();
            $eq->codigo_interno            = $this->generarCodigoEquipo();
            $eq->suc_cod                   = (int) ($request->input('suc_cod') ?: 1);
            $eq->tipo_equipo               = trim($request->input('tipo_equipo', 'Celular'));
            $eq->marca                     = trim($request->input('marca'));
            $eq->modelo                    = trim($request->input('modelo'));
            $eq->capacidad_gb              = trim($request->input('capacidad_gb', ''));
            $eq->ram_gb                    = trim($request->input('ram_gb', ''));
            $eq->color                     = trim($request->input('color', ''));
            $eq->imei_1                    = $imei1;
            $eq->imei_2                    = trim($request->input('imei_2', ''));
            $eq->condicion_ingreso         = $condicion;
            $eq->grado_estetico            = $request->input('grado_estetico', $condicion === 'NUEVO' ? 'SELLADO' : 'A+');
            $eq->bateria_salud             = max(1, min(100, (int) $request->input('bateria_salud', 100)));
            $eq->libre_operadora           = (bool) $request->input('libre_operadora', true);
            $eq->icloud_google_limpio      = (bool) $request->input('icloud_google_limpio', true);
            $eq->accesorios_incluidos      = trim($request->input('accesorios_incluidos', ''));
            $eq->observaciones_equipo      = trim($request->input('observaciones_equipo', ''));
            $eq->origen_tipo               = $request->input('origen_tipo', 'COMPRA_PROVEEDOR');
            $eq->PROVEEDOR_cod             = $request->filled('PROVEEDOR_cod') ? (int) $request->input('PROVEEDOR_cod') : null;
            $eq->CLIENTES_cod_origen       = $request->filled('CLIENTES_cod_origen') ? (int) $request->input('CLIENTES_cod_origen') : null;
            $eq->nro_documento_compra      = trim($request->input('nro_documento_compra', ''));
            $eq->fecha_ingreso             = date('Y-m-d H:i:s');
            $eq->cod_usuario_ingreso       = Auth::user()->cod_usuarios;
            $eq->costo_adquisicion         = $costoAdq;
            $eq->costo_reacondicionamiento = 0;
            $eq->costo_total               = $costoAdq;
            $eq->precio_venta_contado      = $precioContado;
            $eq->precio_venta_credito      = $precioCredito;
            $eq->estado                    = 'EN_STOCK';
            $eq->tiene_garantia            = true;
            $eq->dias_garantia             = (int) $request->input('dias_garantia', $diasGarantiaDefault);
            $eq->condiciones_garantia      = $cfg['clausula_garantia'];
            $eq->save();

            // Imágenes iniciales
            $imagenes = $request->input('imagenes_iniciales', []);
            if (is_array($imagenes)) {
                foreach ($imagenes as $img) {
                    if (!empty($img['url'])) {
                        CelularImeiImagen::create([
                            'celular_imei_id' => $eq->id,
                            'tipo'            => $img['tipo'] ?? 'EQUIPO',
                            'url'             => $img['url'],
                            'filename'        => $img['filename'] ?? null,
                            'public_id'       => $img['public_id'] ?? null,
                            'driver'          => $img['driver'] ?? 'local',
                            'descripcion'     => $img['descripcion'] ?? 'Foto de ingreso',
                            'cod_usuarios'    => Auth::user()->cod_usuarios,
                        ]);
                    }
                }
            }

            CelularImeiHistorial::create([
                'celular_imei_id' => $eq->id,
                'evento'          => 'INGRESO_STOCK',
                'titulo'          => "Ingreso a inventario ({$eq->condicion_ingreso})",
                'detalle'         => "Equipo {$eq->marca} {$eq->modelo} {$eq->capacidad_gb} | IMEI: {$eq->imei_1} | Batería: {$eq->bateria_salud}%",
                'costo_anterior'  => 0,
                'costo_nuevo'     => $eq->costo_total,
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'id'      => $eq->id,
                'equipo'  => $eq,
                'message' => "Equipo {$eq->codigo_interno} ({$eq->marca} {$eq->modelo}) registrado exitosamente en stock.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar equipo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detalle completo de un equipo por IMEI con su trazabilidad, fotos, canje y taller.
     */
    public function show($id)
    {
        $equipo = CelularImei::with([
            'sucursal',
            'proveedor',
            'clienteOrigen',
            'clienteComprador',
            'usuarioIngreso:cod_usuarios,nom_usuarios',
            'usuarioVenta:cod_usuarios,nom_usuarios',
            'reparacionActual.repuestos',
            'canjeOrigen',
            'imagenes.usuario:cod_usuarios,nom_usuarios',
            'historial.usuario:cod_usuarios,nom_usuarios',
        ])->findOrFail($id);

        if (!Auth::user()->tieneAccion('imei_ver_costo')) {
            $equipo->costo_adquisicion = 0;
            $equipo->costo_reacondicionamiento = 0;
            $equipo->costo_total = 0;
        }

        $garantiaVigente = false;
        $diasRestantesGarantia = 0;
        if ($equipo->estado === 'VENDIDO' && $equipo->tiene_garantia && !empty($equipo->fecha_vencimiento_garantia)) {
            $hoy = strtotime(date('Y-m-d'));
            $venc = strtotime($equipo->fecha_vencimiento_garantia);
            $diasRestantesGarantia = (int) floor(($venc - $hoy) / 86400);
            $garantiaVigente = $diasRestantesGarantia >= 0;
        }

        return response()->json([
            'success'                 => true,
            'equipo'                  => $equipo,
            'garantia_vigente'        => $garantiaVigente,
            'dias_restantes_garantia' => $diasRestantesGarantia,
        ]);
    }

    /**
     * Actualiza los datos técnicos y precios de un equipo por IMEI.
     */
    public function updateEquipo(Request $request, $id)
    {
        $eq = CelularImei::findOrFail($id);

        $request->validate([
            'marca'  => 'required|string|max:80',
            'modelo' => 'required|string|max:120',
            'imei_1' => 'required|string|max:80',
        ]);

        $eq->marca                = trim($request->input('marca'));
        $eq->modelo               = trim($request->input('modelo'));
        $eq->capacidad_gb         = trim($request->input('capacidad_gb', ''));
        $eq->ram_gb               = trim($request->input('ram_gb', ''));
        $eq->color                = trim($request->input('color', ''));
        $eq->imei_1               = trim($request->input('imei_1'));
        $eq->imei_2               = trim($request->input('imei_2', ''));
        $eq->condicion_ingreso    = $request->input('condicion_ingreso', $eq->condicion_ingreso);
        $eq->grado_estetico       = $request->input('grado_estetico', $eq->grado_estetico);
        $eq->bateria_salud        = max(1, min(100, (int) $request->input('bateria_salud', $eq->bateria_salud)));
        $eq->libre_operadora      = (bool) $request->input('libre_operadora', $eq->libre_operadora);
        $eq->icloud_google_limpio = (bool) $request->input('icloud_google_limpio', $eq->icloud_google_limpio);
        $eq->accesorios_incluidos = trim($request->input('accesorios_incluidos', ''));
        $eq->observaciones_equipo = trim($request->input('observaciones_equipo', ''));
        $eq->precio_venta_contado = max(0, (float) $request->input('precio_venta_contado', $eq->precio_venta_contado));
        $eq->precio_venta_credito = max(0, (float) $request->input('precio_venta_credito', $eq->precio_venta_credito));
        $eq->dias_garantia        = max(0, (int) $request->input('dias_garantia', $eq->dias_garantia));

        if (Auth::user()->tieneAccion('imei_ver_costo') && $request->has('costo_adquisicion')) {
            $eq->costo_adquisicion = max(0, (float) $request->input('costo_adquisicion'));
        }

        $eq->recalcularCostoTotal();

        return $this->show($eq->id);
    }

    /**
     * Registra una operación de PLAN CANJE (Trade-In) o COMPRA DIRECTA de un celular usado.
     */
    public function registrarPlanCanje(Request $request)
    {
        if (!Auth::user()->tieneAccion('imei_registrar_compra_canje')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar operaciones de Plan Canje.',
            ], 403);
        }

        $request->validate([
            'cliente_id'           => 'required|integer',
            'tipo_operacion'       => 'required|in:PLAN_CANJE,COMPRA_DIRECTA',
            'usado.marca'          => 'required|string|max:80',
            'usado.modelo'         => 'required|string|max:120',
            'usado.imei_1'         => 'required|string|max:80',
            'valor_tasacion_usado' => 'required|numeric|min:0',
        ], [
            'cliente_id.required'   => 'Seleccioná el cliente que entrega el equipo usado.',
            'usado.marca.required'  => 'Ingresá la marca del equipo recibido en canje.',
            'usado.modelo.required' => 'Ingresá el modelo del equipo recibido en canje.',
            'usado.imei_1.required' => 'El IMEI del equipo recibido en canje es obligatorio para el contrato legal.',
        ]);

        $tipoOp = $request->input('tipo_operacion', 'PLAN_CANJE');
        $celularLlevadoId = $request->input('celular_llevado_id');
        $modoCobro = $request->input('modo_cobro', 'DIRECTO'); // DIRECTO | POS

        if ($tipoOp === 'PLAN_CANJE' && empty($celularLlevadoId)) {
            return response()->json([
                'success' => false,
                'message' => 'Para un Plan Canje seleccioná el celular en stock que el cliente se lleva (o elegí "Compra Directa de Usado").',
            ], 422);
        }

        $usadoData = $request->input('usado', []);
        $imeiUsado = trim((string) ($usadoData['imei_1'] ?? ''));

        $duplicado = CelularImei::where('imei_1', $imeiUsado)
            ->whereIn('estado', ['EN_STOCK', 'EN_REACONDICIONAMIENTO', 'RESERVADO'])
            ->first();
        if ($duplicado) {
            return response()->json([
                'success' => false,
                'message' => "El IMEI {$imeiUsado} ya figura actualmente en el stock del local ({$duplicado->codigo_interno}).",
            ], 422);
        }

        DB::beginTransaction();
        try {
            $cfg = CelularImeiSettings::all();
            $sucCod = (int) ($request->input('suc_cod') ?: 1);
            $clienteId = (int) $request->input('cliente_id');
            $valorTasacion = max(0, (float) $request->input('valor_tasacion_usado', 0));
            $precioVentaSugeridoUsado = max(0, (float) ($usadoData['precio_venta_sugerido'] ?? ($valorTasacion * 1.3)));
            $enviarATaller = (bool) $request->input('enviar_a_taller', false);
            $fallaReacondicionar = trim((string) $request->input('motivo_taller', 'Revisión general y reacondicionamiento de equipo recibido en Plan Canje.'));

            // 1. Crear el registro en celulares_imei para el equipo usado que ingresa al local
            $eqEntregado = new CelularImei();
            $eqEntregado->codigo_interno            = $this->generarCodigoEquipo();
            $eqEntregado->suc_cod                   = $sucCod;
            $eqEntregado->tipo_equipo               = 'Celular';
            $eqEntregado->marca                     = trim($usadoData['marca']);
            $eqEntregado->modelo                    = trim($usadoData['modelo']);
            $eqEntregado->capacidad_gb              = trim($usadoData['capacidad_gb'] ?? '');
            $eqEntregado->ram_gb                    = trim($usadoData['ram_gb'] ?? '');
            $eqEntregado->color                     = trim($usadoData['color'] ?? '');
            $eqEntregado->imei_1                    = $imeiUsado;
            $eqEntregado->imei_2                    = trim($usadoData['imei_2'] ?? '');
            $eqEntregado->condicion_ingreso         = 'USADO_CANJE';
            $eqEntregado->grado_estetico            = $usadoData['grado_estetico'] ?? 'A';
            $eqEntregado->bateria_salud             = max(1, min(100, (int) ($usadoData['bateria_salud'] ?? 85)));
            $eqEntregado->libre_operadora           = (bool) ($usadoData['libre_operadora'] ?? true);
            $eqEntregado->icloud_google_limpio      = (bool) ($usadoData['icloud_google_limpio'] ?? true);
            $eqEntregado->accesorios_incluidos      = trim($usadoData['accesorios_incluidos'] ?? 'Solo equipo');
            $eqEntregado->observaciones_equipo      = trim($usadoData['observaciones_equipo'] ?? '');
            $eqEntregado->origen_tipo               = ($tipoOp === 'PLAN_CANJE') ? 'PLAN_CANJE' : 'COMPRA_PARTICULAR';
            $eqEntregado->CLIENTES_cod_origen       = $clienteId;
            $eqEntregado->fecha_ingreso             = date('Y-m-d H:i:s');
            $eqEntregado->cod_usuario_ingreso       = Auth::user()->cod_usuarios;
            $eqEntregado->costo_adquisicion         = $valorTasacion;
            $eqEntregado->costo_reacondicionamiento = 0;
            $eqEntregado->costo_total               = $valorTasacion;
            $eqEntregado->precio_venta_contado      = $precioVentaSugeridoUsado;
            $eqEntregado->estado                    = $enviarATaller ? 'EN_REACONDICIONAMIENTO' : 'EN_STOCK';
            $eqEntregado->tiene_garantia            = true;
            $eqEntregado->dias_garantia             = $cfg['garantia_usado_dias'];
            $eqEntregado->condiciones_garantia      = $cfg['clausula_garantia'];
            $eqEntregado->save();

            // Guardar fotos adjuntas al equipo recibido / C.I. del cliente
            $imagenesCanje = $request->input('imagenes_canje', []);
            if (is_array($imagenesCanje)) {
                foreach ($imagenesCanje as $img) {
                    if (!empty($img['url'])) {
                        CelularImeiImagen::create([
                            'celular_imei_id' => $eqEntregado->id,
                            'tipo'            => $img['tipo'] ?? 'EQUIPO',
                            'url'             => $img['url'],
                            'filename'        => $img['filename'] ?? null,
                            'public_id'       => $img['public_id'] ?? null,
                            'driver'          => $img['driver'] ?? 'local',
                            'descripcion'     => $img['descripcion'] ?? 'Evidencia Plan Canje',
                            'cod_usuarios'    => Auth::user()->cod_usuarios,
                        ]);
                    }
                }
            }

            // 2. Si se solicitó enviar a Servicio Técnico para reacondicionamiento
            $ordenTaller = null;
            if ($enviarATaller) {
                $ordenTaller = $this->crearOrdenTallerReacondicionamiento(
                    $eqEntregado,
                    $fallaReacondicionar,
                    $request->input('cod_usuario_tecnico')
                );
                $eqEntregado->reparacion_actual_id = $ordenTaller->id;
                $eqEntregado->save();
            }

            // 3. Procesar el equipo que el cliente se lleva (si es PLAN_CANJE)
            $eqLlevado = null;
            $precioLlevado = 0;
            $descuentoAdicional = max(0, (float) $request->input('descuento_adicional', 0));
            $diferencia = 0;

            if ($tipoOp === 'PLAN_CANJE' && $celularLlevadoId) {
                $eqLlevado = CelularImei::where('id', (int) $celularLlevadoId)
                    ->where('estado', 'EN_STOCK')
                    ->first();

                if (!$eqLlevado) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El celular seleccionado para entregar al cliente ya no está en estado EN_STOCK.',
                    ], 422);
                }

                $precioLlevado = max(0, (float) $request->input('precio_equipo_llevado', $eqLlevado->precio_venta_contado));
                $diferencia = max(0, $precioLlevado - $valorTasacion - $descuentoAdicional);
            }

            // 4. Registrar la operación en celular_canjes
            $canje = new CelularCanje();
            $canje->codigo_canje          = $this->generarCodigoCanje();
            $canje->tipo_operacion        = $tipoOp;
            $canje->suc_cod               = $sucCod;
            $canje->CLIENTES_cod          = $clienteId;
            $canje->celular_entregado_id  = $eqEntregado->id;
            $canje->celular_llevado_id    = $eqLlevado ? $eqLlevado->id : null;
            $canje->valor_tasacion_usado  = $valorTasacion;
            $canje->precio_equipo_llevado = $precioLlevado;
            $canje->descuento_adicional   = $descuentoAdicional;
            $canje->diferencia_a_pagar    = $diferencia;
            $canje->forma_pago            = (int) $request->input('forma_pago', 1);
            $canje->enviar_a_taller       = $enviarATaller;
            $canje->reparacion_id         = $ordenTaller ? $ordenTaller->id : null;
            $canje->estado                = ($tipoOp === 'PLAN_CANJE' && $modoCobro === 'POS') ? 'PENDIENTE_POS' : 'COMPLETADO';
            $canje->observaciones         = trim($request->input('observaciones', ''));
            $canje->cod_usuarios          = Auth::user()->cod_usuarios;
            $canje->fecha_operacion       = date('Y-m-d H:i:s');
            $canje->save();

            $eqEntregado->canje_id = $canje->id;
            $eqEntregado->save();

            // Historial del equipo recibido
            CelularImeiHistorial::create([
                'celular_imei_id' => $eqEntregado->id,
                'evento'          => 'PLAN_CANJE_RECIBIDO',
                'titulo'          => ($tipoOp === 'PLAN_CANJE')
                    ? "Recibido en Plan Canje {$canje->codigo_canje}"
                    : "Compra Directa a Particular {$canje->codigo_canje}",
                'detalle'         => "Tasado en Gs. " . number_format($valorTasacion, 0, ',', '.') . ($ordenTaller ? " | Enviado a Taller Orden {$ordenTaller->codigo_orden}" : ' | Ingresado directo a Stock'),
                'costo_anterior'  => 0,
                'costo_nuevo'     => $valorTasacion,
                'referencia_id'   => $canje->id,
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            // 5. Movimiento de Caja y Estado del Equipo Llevado
            $registrarCaja = (bool) $request->input('registrar_caja', false);
            $nroOpCaja = null;

            if ($tipoOp === 'COMPRA_DIRECTA') {
                if ($registrarCaja && $valorTasacion > 0) {
                    $nroOpCaja = $this->registrarMovimientoCaja(
                        $sucCod,
                        'Salida',
                        $valorTasacion,
                        "Compra de Celular Usado {$canje->codigo_canje} ({$eqEntregado->marca} {$eqEntregado->modelo} IMEI {$eqEntregado->imei_1})"
                    );
                    $canje->registrar_caja = $nroOpCaja !== null;
                    $canje->nro_operacion_caja = $nroOpCaja;
                    $canje->save();
                }
            } elseif ($tipoOp === 'PLAN_CANJE' && $eqLlevado) {
                if ($modoCobro === 'DIRECTO') {
                    $diasGar = max(0, (int) $request->input('dias_garantia_llevado', $eqLlevado->dias_garantia ?: 90));
                    $vencGar = $diasGar > 0 ? date('Y-m-d', strtotime("+{$diasGar} days")) : null;

                    $eqLlevado->estado                     = 'VENDIDO';
                    $eqLlevado->CLIENTES_cod_comprador     = $clienteId;
                    $eqLlevado->fecha_venta                = date('Y-m-d H:i:s');
                    $eqLlevado->precio_vendido             = $precioLlevado;
                    $eqLlevado->descuento_venta            = $descuentoAdicional;
                    $eqLlevado->monto_canje_descontado     = $valorTasacion;
                    $eqLlevado->saldo_cobrado              = $diferencia;
                    $eqLlevado->cod_usuario_venta          = Auth::user()->cod_usuarios;
                    $eqLlevado->tiene_garantia             = $diasGar > 0;
                    $eqLlevado->dias_garantia              = $diasGar;
                    $eqLlevado->fecha_vencimiento_garantia = $vencGar;
                    $eqLlevado->save();

                    CelularImeiHistorial::create([
                        'celular_imei_id' => $eqLlevado->id,
                        'evento'          => 'VENDIDO',
                        'titulo'          => "Vendido en Plan Canje {$canje->codigo_canje}",
                        'detalle'         => "Precio: Gs. " . number_format($precioLlevado, 0, ',', '.') . " | Canje tomado: Gs. " . number_format($valorTasacion, 0, ',', '.') . " | Diferencia cobrada: Gs. " . number_format($diferencia, 0, ',', '.'),
                        'costo_anterior'  => $eqLlevado->costo_total,
                        'costo_nuevo'     => $eqLlevado->costo_total,
                        'referencia_id'   => $canje->id,
                        'cod_usuarios'    => Auth::user()->cod_usuarios,
                        'created_at'      => date('Y-m-d H:i:s'),
                    ]);

                    if ($registrarCaja && $diferencia > 0) {
                        $nroOpCaja = $this->registrarMovimientoCaja(
                            $sucCod,
                            'Entrada',
                            $diferencia,
                            "Diferencia Plan Canje {$canje->codigo_canje} - Venta {$eqLlevado->marca} {$eqLlevado->modelo} (IMEI {$eqLlevado->imei_1})"
                        );
                        $canje->registrar_caja = $nroOpCaja !== null;
                        $canje->nro_operacion_caja = $nroOpCaja;
                        $canje->save();
                    }
                } else {
                    // Modo POS: reservar el equipo llevado hasta que se facture en POS
                    $eqLlevado->estado                 = 'RESERVADO';
                    $eqLlevado->CLIENTES_cod_comprador = $clienteId;
                    $eqLlevado->precio_vendido         = $precioLlevado;
                    $eqLlevado->descuento_venta        = $descuentoAdicional;
                    $eqLlevado->monto_canje_descontado = $valorTasacion;
                    $eqLlevado->save();
                }
            }

            DB::commit();

            // Si eligió facturar en POS, devolver también el payload listo para Punto de Venta
            $posPayload = null;
            if ($tipoOp === 'PLAN_CANJE' && $modoCobro === 'POS' && $eqLlevado) {
                $posPayload = $this->armarPayloadPos($eqLlevado, $canje);
            }

            return response()->json([
                'success'            => true,
                'canje_id'           => $canje->id,
                'codigo_canje'       => $canje->codigo_canje,
                'equipo_recibido_id' => $eqEntregado->id,
                'equipo_llevado_id'  => $eqLlevado ? $eqLlevado->id : null,
                'pos_payload'        => $posPayload,
                'message'            => "Operación {$canje->codigo_canje} registrada exitosamente.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar Plan Canje: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crea una orden en Servicio Técnico (Reparacion) vinculada a un equipo por IMEI para reacondicionarlo.
     */
    protected function crearOrdenTallerReacondicionamiento(CelularImei $eq, $motivo, $tecnicoId = null)
    {
        $cfgRep = ReparacionSettings::all();
        $prefijo = $cfgRep['prefijo_orden'] ?: 'ST-';
        $nextId = (int) (Reparacion::max('id') ?: 0) + 1;
        $codOrden = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        while (Reparacion::where('codigo_orden', $codOrden)->exists()) {
            $nextId++;
            $codOrden = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }

        $rep = new Reparacion();
        $rep->codigo_orden          = $codOrden;
        $rep->CLIENTES_cod          = $eq->CLIENTES_cod_origen ?: 1;
        $rep->suc_cod               = $eq->suc_cod ?: 1;
        $rep->cod_usuario_recepcion = Auth::user()->cod_usuarios;
        $rep->cod_usuario_tecnico   = $tecnicoId ? (int) $tecnicoId : Auth::user()->cod_usuarios;
        $rep->tipo_equipo           = $eq->tipo_equipo ?: 'Celular';
        $rep->marca                 = $eq->marca;
        $rep->modelo                = trim($eq->modelo . ' ' . $eq->capacidad_gb);
        $rep->color                 = $eq->color;
        $rep->imei_serie            = $eq->imei_1;
        $rep->tipo_bloqueo          = 'NINGUNO';
        $rep->accesorios_dejados    = $eq->accesorios_incluidos ?: 'Equipo interno de stock';
        $rep->estado_fisico_obs     = "EQUIPO DE STOCK INTERNO [{$eq->codigo_interno}] — Salud Batería: {$eq->bateria_salud}%";
        $rep->falla_reportada       = "[REACONDICIONAMIENTO STOCK {$eq->codigo_interno}] " . $motivo;
        $rep->prioridad             = 'ALTA';
        $rep->fecha_recepcion       = date('Y-m-d H:i:s');
        $rep->estado                = 'EN_DIAGNOSTICO';
        $rep->estado_pago           = 'SIN_CARGO';
        $rep->tiene_garantia        = false;
        $rep->dias_garantia         = 0;
        $rep->save();

        ReparacionHistorial::create([
            'reparacion_id'   => $rep->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'EN_DIAGNOSTICO',
            'titulo'          => "Ingreso para Reacondicionamiento de Stock ({$eq->codigo_interno})",
            'comentario'      => "IMEI: {$eq->imei_1} | Tarea: {$motivo}",
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $rep;
    }

    /**
     * Envía un equipo en stock al Taller de Servicio Técnico para reacondicionamiento.
     */
    public function enviarATaller(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('imei_reacondicionar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para enviar equipos a reacondicionamiento.',
            ], 403);
        }

        $eq = CelularImei::findOrFail($id);
        $motivo = trim((string) $request->input('motivo_taller', 'Reacondicionamiento técnico para puesta en venta.'));
        $tecnicoId = $request->input('cod_usuario_tecnico');

        DB::beginTransaction();
        try {
            $rep = $this->crearOrdenTallerReacondicionamiento($eq, $motivo, $tecnicoId);

            $eq->estado               = 'EN_REACONDICIONAMIENTO';
            $eq->reparacion_actual_id = $rep->id;
            $eq->save();

            CelularImeiHistorial::create([
                'celular_imei_id' => $eq->id,
                'evento'          => 'ENVIADO_TALLER',
                'titulo'          => "Enviado a Servicio Técnico (Orden {$rep->codigo_orden})",
                'detalle'         => $motivo,
                'costo_anterior'  => $eq->costo_total,
                'costo_nuevo'     => $eq->costo_total,
                'referencia_id'   => $rep->id,
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($eq->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar a taller: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Finaliza el reacondicionamiento en taller, suma el costo de repuestos/taller al costo del celular y lo devuelve a vitrina (EN_STOCK).
     */
    public function finalizarReacondicionamiento(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('imei_reacondicionar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para finalizar reacondicionamientos.',
            ], 403);
        }

        $eq = CelularImei::with('reparacionActual')->findOrFail($id);

        DB::beginTransaction();
        try {
            $costoAnterior = (float) $eq->costo_total;
            $rep = $eq->reparacionActual;

            // Calcular costo invertido en taller (costo de repuestos + mano de obra interna o monto indicado)
            $costoSumar = 0;
            if ($request->filled('costo_reacondicionamiento_adicional')) {
                $costoSumar = max(0, (float) $request->input('costo_reacondicionamiento_adicional'));
            } elseif ($rep) {
                $rep->recalcularTotales();
                $costoSumar = (float) $rep->costo_repuestos + (float) $rep->costo_mano_obra;
            }

            $eq->costo_reacondicionamiento = (float) $eq->costo_reacondicionamiento + $costoSumar;
            $eq->costo_total               = (float) $eq->costo_adquisicion + (float) $eq->costo_reacondicionamiento;
            $eq->condicion_ingreso         = 'REACONDICIONADO';
            $eq->estado                    = 'EN_STOCK';

            if ($request->filled('bateria_salud')) {
                $eq->bateria_salud = max(1, min(100, (int) $request->input('bateria_salud')));
            }
            if ($request->filled('precio_venta_contado') && (float) $request->input('precio_venta_contado') > 0) {
                $eq->precio_venta_contado = (float) $request->input('precio_venta_contado');
            }
            $eq->save();

            if ($rep && $rep->estado !== 'ENTREGADO') {
                $rep->estado = 'ENTREGADO';
                $rep->fecha_reparado = $rep->fecha_reparado ?: date('Y-m-d H:i:s');
                $rep->fecha_entrega = date('Y-m-d H:i:s');
                $rep->solucion_aplicada = $request->input('detalle_trabajo', $rep->solucion_aplicada ?: 'Reacondicionamiento de stock completado.');
                $rep->save();
            }

            CelularImeiHistorial::create([
                'celular_imei_id' => $eq->id,
                'evento'          => 'REACONDICIONADO',
                'titulo'          => 'Reacondicionamiento finalizado — Disponible en Vitrina',
                'detalle'         => "Costo taller sumado: Gs. " . number_format($costoSumar, 0, ',', '.') . " | Nuevo Costo Total del Equipo: Gs. " . number_format($eq->costo_total, 0, ',', '.'),
                'costo_anterior'  => $costoAnterior,
                'costo_nuevo'     => $eq->costo_total,
                'referencia_id'   => $rep ? $rep->id : null,
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($eq->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar reacondicionamiento: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registra la venta directa de un celular por IMEI desde el módulo y activa su garantía.
     */
    public function venderDirecto(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('imei_vender')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para vender equipos por IMEI.',
            ], 403);
        }

        $eq = CelularImei::findOrFail($id);
        if (!in_array($eq->estado, ['EN_STOCK', 'RESERVADO'])) {
            return response()->json([
                'success' => false,
                'message' => "El equipo {$eq->codigo_interno} no se encuentra disponible para la venta (Estado actual: {$eq->estado}).",
            ], 422);
        }

        $request->validate([
            'cliente_id'     => 'required|integer',
            'precio_vendido' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $clienteId = (int) $request->input('cliente_id');
            $precioVendido = max(0, (float) $request->input('precio_vendido'));
            $descuento = max(0, (float) $request->input('descuento_venta', 0));
            $cobrado = max(0, $precioVendido - $descuento);
            $diasGarantia = max(0, (int) $request->input('dias_garantia', $eq->dias_garantia ?: 90));
            $tieneGarantia = (bool) $request->input('tiene_garantia', $diasGarantia > 0);
            $vencGarantia = ($tieneGarantia && $diasGarantia > 0)
                ? date('Y-m-d', strtotime("+{$diasGarantia} days"))
                : null;

            $eq->estado                     = 'VENDIDO';
            $eq->CLIENTES_cod_comprador     = $clienteId;
            $eq->fecha_venta                = date('Y-m-d H:i:s');
            $eq->precio_vendido             = $precioVendido;
            $eq->descuento_venta            = $descuento;
            $eq->saldo_cobrado              = $cobrado;
            $eq->cod_usuario_venta          = Auth::user()->cod_usuarios;
            $eq->tiene_garantia             = $tieneGarantia;
            $eq->dias_garantia              = $diasGarantia;
            $eq->fecha_vencimiento_garantia = $vencGarantia;
            $eq->save();

            if ($request->input('registrar_caja') && $cobrado > 0) {
                $this->registrarMovimientoCaja(
                    $eq->suc_cod,
                    'Entrada',
                    $cobrado,
                    "Venta Equipo {$eq->codigo_interno} ({$eq->marca} {$eq->modelo} IMEI {$eq->imei_1})"
                );
            }

            CelularImeiHistorial::create([
                'celular_imei_id' => $eq->id,
                'evento'          => 'VENDIDO',
                'titulo'          => 'Equipo Vendido con Garantía por IMEI',
                'detalle'         => "Total cobrado: Gs. " . number_format($cobrado, 0, ',', '.') . ($tieneGarantia ? " | Garantía: {$diasGarantia} días (hasta " . date('d/m/Y', strtotime($vencGarantia)) . ")" : ' | Sin garantía'),
                'costo_anterior'  => $eq->costo_total,
                'costo_nuevo'     => $eq->costo_total,
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($eq->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar venta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Prepara un equipo por IMEI (con o sin Plan Canje) para facturarlo en el Punto de Venta (POS).
     */
    public function getParaVenta(Request $request, $id)
    {
        $eq = CelularImei::with('clienteComprador')->findOrFail($id);
        $canjeId = $request->get('canje_id');
        $canje = $canjeId ? CelularCanje::with('cliente')->find($canjeId) : null;

        return response()->json($this->armarPayloadPos($eq, $canje));
    }

    protected function armarPayloadPos(CelularImei $eq, CelularCanje $canje = null)
    {
        $libreId = ArticuloLibre::id();
        $cliente = ($canje && $canje->cliente) ? $canje->cliente : $eq->clienteComprador;

        $precio = ($canje && $canje->precio_equipo_llevado > 0)
            ? (float) $canje->precio_equipo_llevado
            : (float) ($eq->precio_venta_contado ?: $eq->precio_vendido);

        $descTexto = trim("{$eq->marca} {$eq->modelo} {$eq->capacidad_gb} {$eq->color} (IMEI: {$eq->imei_1} | {$eq->condicion_ingreso})");

        $items = [
            [
                'codigo'              => $libreId,
                'c_barra'             => $eq->imei_1,
                'descripcion'         => $descTexto,
                'descripcion_libre'   => $descTexto,
                'es_libre'            => true,
                'cantidad'            => 1,
                'precio'              => $precio,
                'descuento'           => 0,
                'iva'                 => 10,
                'costo'               => (float) $eq->costo_total,
                'subtotal'            => $precio,
                'stock_ya_descontado' => true,
            ],
        ];

        $descuentoTotal = 0;
        if ($canje) {
            $descuentoTotal = (float) $canje->valor_tasacion_usado + (float) $canje->descuento_adicional;
        } else {
            $descuentoTotal = (float) $eq->descuento_venta + (float) $eq->monto_canje_descontado;
        }

        return [
            'success'     => true,
            'presupuesto' => [
                'pre_numero'      => $canje ? $canje->codigo_canje : $eq->codigo_interno,
                'celular_imei_id' => $eq->id,
                'canje_id'        => $canje ? $canje->id : null,
                'cliente'         => $cliente,
                'condicion'       => 1,
                'descuento'       => $descuentoTotal,
                'observaciones'   => $canje
                    ? "Plan Canje {$canje->codigo_canje} - Equipo IMEI {$eq->imei_1}"
                    : "Venta Equipo {$eq->codigo_interno} - IMEI {$eq->imei_1}",
            ],
            'items'       => $items,
        ];
    }

    /**
     * Sube imagen de equipo o C.I. de cliente.
     */
    public function subirImagen(Request $request)
    {
        $request->validate([
            'imagen'          => 'required|file|mimes:jpeg,jpg,png,webp|max:10240',
            'tipo'            => 'nullable|string|in:EQUIPO,CI_FRENTE,CI_DORSO,IMEI_CAPTURA,ESTADO_BATERIA',
            'descripcion'     => 'nullable|string|max:255',
            'celular_imei_id' => 'nullable|integer',
        ]);

        $file = $request->file('imagen');
        $tipo = $request->input('tipo', 'EQUIPO');
        $descripcion = trim((string) $request->input('descripcion', ''));

        $stored = $this->almacenarImagen($file);
        if (!$stored['success']) {
            return response()->json([
                'success' => false,
                'message' => $stored['message'] ?? 'No se pudo almacenar la imagen.',
            ], 422);
        }

        $imgModel = null;
        if ($request->filled('celular_imei_id') && (int) $request->input('celular_imei_id') > 0) {
            $imgModel = CelularImeiImagen::create([
                'celular_imei_id' => (int) $request->input('celular_imei_id'),
                'tipo'            => $tipo,
                'url'             => $stored['url'],
                'filename'        => $stored['filename'],
                'public_id'       => $stored['public_id'],
                'driver'          => $stored['driver'],
                'descripcion'     => $descripcion ?: "Foto ({$tipo})",
                'cod_usuarios'    => Auth::user()->cod_usuarios,
            ]);
        }

        return response()->json([
            'success'     => true,
            'url'         => $stored['url'],
            'filename'    => $stored['filename'],
            'public_id'   => $stored['public_id'],
            'driver'      => $stored['driver'],
            'tipo'        => $tipo,
            'descripcion' => $descripcion ?: "Foto ({$tipo})",
            'imagen'      => $imgModel,
        ]);
    }

    public function subirImagenBase64(Request $request)
    {
        $request->validate([
            'base64'          => 'required|string',
            'tipo'            => 'nullable|string|in:EQUIPO,CI_FRENTE,CI_DORSO,IMEI_CAPTURA,ESTADO_BATERIA',
            'descripcion'     => 'nullable|string|max:255',
            'celular_imei_id' => 'nullable|integer',
        ]);

        $raw = (string) $request->input('base64');
        $binary = (strpos($raw, 'base64,') !== false)
            ? base64_decode(explode('base64,', $raw, 2)[1])
            : base64_decode($raw);

        if (!$binary || strlen($binary) < 100) {
            return response()->json(['success' => false, 'message' => 'Captura inválida.'], 422);
        }

        $tipo = $request->input('tipo', 'EQUIPO');
        $descripcion = trim((string) $request->input('descripcion', ''));

        $stored = $this->almacenarImagen($binary);
        if (!$stored['success']) {
            return response()->json(['success' => false, 'message' => 'Error al guardar captura.'], 422);
        }

        $imgModel = null;
        if ($request->filled('celular_imei_id') && (int) $request->input('celular_imei_id') > 0) {
            $imgModel = CelularImeiImagen::create([
                'celular_imei_id' => (int) $request->input('celular_imei_id'),
                'tipo'            => $tipo,
                'url'             => $stored['url'],
                'filename'        => $stored['filename'],
                'public_id'       => $stored['public_id'],
                'driver'          => $stored['driver'],
                'descripcion'     => $descripcion ?: "Captura ({$tipo})",
                'cod_usuarios'    => Auth::user()->cod_usuarios,
            ]);
        }

        return response()->json([
            'success'     => true,
            'url'         => $stored['url'],
            'filename'    => $stored['filename'],
            'public_id'   => $stored['public_id'],
            'driver'      => $stored['driver'],
            'tipo'        => $tipo,
            'descripcion' => $descripcion ?: "Captura ({$tipo})",
            'imagen'      => $imgModel,
        ]);
    }

    public function capturarImagenCamara(Request $request)
    {
        $cfg = CameraSettings::all();
        $url = CameraSettings::snapshotUrl($cfg);
        if ($url === '') {
            return response()->json(['success' => false, 'message' => 'Cámara IP no configurada.'], 422);
        }

        try {
            $options = ['verify' => false, 'timeout' => 12];
            if ($cfg['user'] !== '') {
                $options['auth'] = [$cfg['user'], $cfg['password'], 'digest'];
            }
            $client = new Client($options);
            $response = $client->get($url);
            $body = (string) $response->getBody();
            if ($response->getStatusCode() !== 200 || strlen($body) < 100) {
                return response()->json(['success' => false, 'message' => 'Sin respuesta válida de Cámara IP.'], 422);
            }

            $tipo = $request->input('tipo', 'EQUIPO');
            $descripcion = trim((string) $request->input('descripcion', ''));
            $stored = $this->almacenarImagen($body);

            $imgModel = null;
            if ($request->filled('celular_imei_id') && (int) $request->input('celular_imei_id') > 0) {
                $imgModel = CelularImeiImagen::create([
                    'celular_imei_id' => (int) $request->input('celular_imei_id'),
                    'tipo'            => $tipo,
                    'url'             => $stored['url'],
                    'filename'        => $stored['filename'],
                    'public_id'       => $stored['public_id'],
                    'driver'          => $stored['driver'],
                    'descripcion'     => $descripcion ?: "Cámara IP ({$tipo})",
                    'cod_usuarios'    => Auth::user()->cod_usuarios,
                ]);
            }

            return response()->json([
                'success'     => true,
                'url'         => $stored['url'],
                'filename'    => $stored['filename'],
                'public_id'   => $stored['public_id'],
                'driver'      => $stored['driver'],
                'tipo'        => $tipo,
                'descripcion' => $descripcion ?: "Cámara IP ({$tipo})",
                'imagen'      => $imgModel,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    protected function almacenarImagen($fileOrBinary)
    {
        if (CloudinarySettings::isEnabled()) {
            $baseFolder = rtrim(CloudinarySettings::getFolder(), '/');
            $cloudRes = CloudinaryService::upload($fileOrBinary, [
                'folder' => $baseFolder . '/celulares_imei',
            ]);
            if (!empty($cloudRes['success'])) {
                return [
                    'success'   => true,
                    'url'       => $cloudRes['url'],
                    'filename'  => $cloudRes['url'],
                    'public_id' => $cloudRes['public_id'] ?? null,
                    'driver'    => 'cloudinary',
                ];
            }
        }

        if ($fileOrBinary instanceof \Illuminate\Http\UploadedFile) {
            $ext = strtolower($fileOrBinary->getClientOriginalExtension() ?: 'jpg');
            $nombre = 'imei_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            Storage::disk('public')->putFileAs('celulares_imei', $fileOrBinary, $nombre);
        } else {
            $nombre = 'imei_' . time() . '_' . mt_rand(1000, 9999) . '.jpg';
            Storage::disk('public')->put('celulares_imei/' . $nombre, $fileOrBinary);
        }

        return [
            'success'   => true,
            'url'       => asset('storage/celulares_imei/' . $nombre),
            'filename'  => $nombre,
            'public_id' => null,
            'driver'    => 'local',
        ];
    }

    public function eliminarImagen($id, $imagenId)
    {
        $eq = CelularImei::findOrFail($id);
        $img = CelularImeiImagen::where('celular_imei_id', $eq->id)->where('id', $imagenId)->firstOrFail();

        if ($img->driver === 'cloudinary' && !empty($img->public_id)) {
            CloudinaryService::delete($img->public_id);
        } elseif ($img->driver === 'local' && !empty($img->filename)) {
            if (Storage::disk('public')->exists('celulares_imei/' . $img->filename)) {
                Storage::disk('public')->delete('celulares_imei/' . $img->filename);
            }
        }

        $img->delete();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $eq = CelularImei::findOrFail($id);
        $eq->estado = 'BAJA';
        $eq->save();

        CelularImeiHistorial::create([
            'celular_imei_id' => $eq->id,
            'evento'          => 'ANULACION',
            'titulo'          => 'Equipo dado de baja del inventario',
            'detalle'         => 'Baja registrada por ' . Auth::user()->nom_usuarios,
            'costo_anterior'  => $eq->costo_total,
            'costo_nuevo'     => $eq->costo_total,
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Equipo {$eq->codigo_interno} dado de baja.",
        ]);
    }

    /**
     * 1. Impresión de Etiqueta / Sticker de Vitrina para Exhibición.
     */
    public function pdfEtiqueta($id)
    {
        $equipo = CelularImei::with('sucursal')->findOrFail($id);
        $empresa = Empresa::first();
        return view('pdf.imei_etiqueta', compact('equipo', 'empresa'));
    }

    /**
     * 2. Impresión de Acta / Contrato Legal de Plan Canje o Compra de Equipo Usado.
     */
    public function pdfContratoCanje($canjeId)
    {
        $canje = CelularCanje::with([
            'cliente',
            'sucursal',
            'celularEntregado.imagenes',
            'celularLlevado',
            'usuario',
        ])->findOrFail($canjeId);

        $empresa = Empresa::first();
        $config = CelularImeiSettings::all();

        return view('pdf.imei_contrato_canje', compact('canje', 'empresa', 'config'));
    }

    /**
     * 3. Impresión de Certificado de Venta y Garantía por IMEI.
     */
    public function pdfCertificadoGarantia($id)
    {
        $equipo = CelularImei::with([
            'sucursal',
            'clienteComprador',
            'usuarioVenta',
            'canjeOrigen',
        ])->findOrFail($id);

        $empresa = Empresa::first();
        $config = CelularImeiSettings::all();

        return view('pdf.imei_garantia', compact('equipo', 'empresa', 'config'));
    }

    protected function registrarMovimientoCaja($sucCod, $tipo, $monto, $concepto)
    {
        $apertura = Apertura::where('suc_cod', $sucCod)
            ->where('apert_estado', '1')
            ->orderBy('nro_operacion', 'desc')
            ->first();

        if (!$apertura) {
            return null;
        }

        $mov = new MovimientoCaja();
        $mov->nro_operacion   = $apertura->nro_operacion;
        $mov->mov_fecha       = date('Y-m-d H:i');
        $mov->mov_concepto    = mb_substr($concepto, 0, 250);
        $mov->mov_tipo        = $tipo;
        $mov->mov_monto       = $monto;
        $mov->nro_fact_ventas = '-';
        $mov->suc_cod         = $sucCod;
        $mov->save();

        return $apertura->nro_operacion;
    }
}
