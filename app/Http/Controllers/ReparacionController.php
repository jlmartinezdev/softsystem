<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reparacion;
use App\ReparacionRepuesto;
use App\ReparacionImagen;
use App\ReparacionPago;
use App\ReparacionHistorial;
use App\ReparacionGarantia;
use App\Sucursal;
use App\Empresa;
use App\Cliente;
use App\Articulo;
use App\Stock;
use App\Seccion;
use App\User;
use App\Apertura;
use App\MovimientoCaja;
use App\Ajuste;
use App\Support\ArticuloLibre;
use App\Support\CameraSettings;
use App\Support\CloudinarySettings;
use App\Support\ReparacionSettings;
use App\Support\ReparacionSchema;
use App\Services\CloudinaryService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Exception;

class ReparacionController extends Controller
{
    public function __construct()
    {
        ReparacionSchema::ensureExists();

        $this->middleware('auth');

        // Middleware para verificar si el módulo está habilitado por el Superadministrador
        $this->middleware(function ($request, $next) {
            $actionMethod = $request->route() ? $request->route()->getActionMethod() : '';
            if ($actionMethod === 'toggleModulo') {
                return $next($request);
            }

            if (!ReparacionSettings::isModuloActivo() && !Auth::user()->esSuperAdministrador()) {
                $msg = 'El módulo de Servicio Técnico y Reparaciones se encuentra deshabilitado. Solo el Superadministrador del sistema puede habilitarlo.';
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

        $this->middleware('permiso:reparacion,open')->only([
            'index', 'show', 'buscarRepuestos', 'getParaVenta'
        ]);
        $this->middleware('permiso:reparacion,add')->only([
            'store', 'subirImagen', 'subirImagenBase64', 'capturarImagenCamara', 'registrarPago'
        ]);
        $this->middleware('permiso:reparacion,edit')->only([
            'updateRecepcion', 'guardarDiagnostico', 'cambiarEstado',
            'agregarRepuesto', 'eliminarRepuesto', 'eliminarImagen',
            'entregarEquipo', 'registrarGarantia', 'actualizarGarantia', 'crearReingresoGarantia'
        ]);
        $this->middleware('permiso:reparacion,del')->only([
            'destroy', 'eliminarPago'
        ]);
        $this->middleware('permiso:reparacion,export')->only([
            'pdfOrden', 'pdfEtiqueta', 'pdfGarantia', 'pdfReciboPago'
        ]);
    }

    /**
     * Activa o desactiva el módulo (Exclusivo Superadministrador).
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
            : !ReparacionSettings::isModuloActivo();

        $activo = ReparacionSettings::setModuloActivo($nuevoEstado);

        return response()->json([
            'success' => true,
            'activo'  => $activo,
            'message' => $activo
                ? 'Módulo de Servicio Técnico y Reparación de Celulares HABILITADO.'
                : 'Módulo de Servicio Técnico y Reparación de Celulares DESHABILITADO.',
        ]);
    }

    /**
     * Vista principal y listado paginado con KPIs en vivo.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $desde = $request->get('desde', '');
            $hasta = $request->get('hasta', '');
            $estado = $request->get('estado', 'TODOS');
            $sucursal = $request->get('sucursal', '0');
            $tecnico = $request->get('tecnico', '0');
            $q = $request->get('q', '');
            $soloGarantias = (int) $request->get('solo_garantias', 0) === 1;

            $query = Reparacion::with([
                'cliente' => function ($c) {
                    $c->select('CLIENTES_cod', 'cliente_nombre', 'cliente_ruc', 'cliente_ci', 'cliente_cel', 'cliente_telef', 'cliente_direccion');
                },
                'tecnico' => function ($u) {
                    $u->select('cod_usuarios', 'nom_usuarios');
                },
                'usuarioRecepcion' => function ($u) {
                    $u->select('cod_usuarios', 'nom_usuarios');
                },
                'sucursal' => function ($s) {
                    $s->select('suc_cod', 'suc_desc');
                },
            ])
            ->withCount(['repuestos', 'imagenes', 'garantias'])
            ->filtroFecha($desde, $hasta)
            ->filtroEstado($estado)
            ->filtroSucursal($sucursal)
            ->filtroTecnico($tecnico)
            ->filtroBusqueda($q);

            if ($soloGarantias) {
                $query->where(function ($w) {
                    $w->where('es_reingreso_garantia', 1)
                      ->orWhere('estado', 'EN_GARANTIA')
                      ->orWhereHas('garantias');
                });
            }

            $ordenes = $query->orderBy('id', 'desc')->paginate(20);

            // KPIs rápidos por sucursal
            $kpiBase = Reparacion::query()->where('estado', '!=', 'ANULADO');
            if (!empty($sucursal) && $sucursal != '0') {
                $kpiBase->where('suc_cod', $sucursal);
            }

            $kpis = [
                'recibidos'       => (clone $kpiBase)->whereIn('estado', ['RECIBIDO', 'EN_DIAGNOSTICO'])->count(),
                'en_taller'       => (clone $kpiBase)->whereIn('estado', ['PRESUPUESTADO', 'APROBADO', 'ESPERANDO_REPUESTO', 'EN_REPARACION'])->count(),
                'listos_entrega'  => (clone $kpiBase)->whereIn('estado', ['REPARADO', 'NO_REPARABLE'])->count(),
                'entregados'      => (clone $kpiBase)->where('estado', 'ENTREGADO')->count(),
                'en_garantia'     => (clone $kpiBase)->where(function ($w) {
                    $w->where('estado', 'EN_GARANTIA')
                      ->orWhereHas('garantias', function ($g) {
                          $g->whereIn('estado', ['ABIERTO', 'EN_REVISION']);
                      });
                })->count(),
                'saldo_por_cobrar' => (float) (clone $kpiBase)->whereIn('estado', ['REPARADO', 'ENTREGADO'])->sum('saldo_pendiente'),
            ];

            return response()->json([
                'ordenes' => $ordenes,
                'kpis'    => $kpis,
            ]);
        }

        $sucursales = Sucursal::all();
        $secciones = Seccion::orderBy('present_descripcion', 'ASC')->get();
        $tecnicos = User::where('activo', 1)->orderBy('nom_usuarios')->get(['cod_usuarios', 'nom_usuarios', 'cod_rol']);
        $empresa = Empresa::first();
        $user = Auth::user();
        $esAdministrador = $user->esAdministrador();
        $esSuperAdministrador = $user->esSuperAdministrador();
        $sucursalActual = session('sucursal_actual') ?? ($user->suc_cod ?? ($sucursales->first()->suc_cod ?? 1));
        $articuloLibreId = ArticuloLibre::id();
        $reparacionConfig = ReparacionSettings::all();
        $camaraConfig = CameraSettings::publicConfig();

        $permisosModulo = [
            'open'          => $user->tienePermiso('reparacion', 'open'),
            'add'           => $user->tienePermiso('reparacion', 'add'),
            'edit'          => $user->tienePermiso('reparacion', 'edit'),
            'del'           => $user->tienePermiso('reparacion', 'del'),
            'export'        => $user->tienePermiso('reparacion', 'export'),
            'diagnosticar'  => $user->tieneAccion('reparacion_diagnosticar'),
            'repuestos'     => $user->tieneAccion('reparacion_repuestos'),
            'entregar'      => $user->tieneAccion('reparacion_entregar'),
            'garantia'      => $user->tieneAccion('reparacion_garantia'),
            'ver_costo'     => $user->tieneAccion('reparacion_ver_costo'),
        ];

        return view('reparacion', compact(
            'sucursales',
            'secciones',
            'tecnicos',
            'empresa',
            'esAdministrador',
            'esSuperAdministrador',
            'sucursalActual',
            'articuloLibreId',
            'reparacionConfig',
            'camaraConfig',
            'permisosModulo'
        ));
    }

    /**
     * Genera el siguiente código de orden (ej. ST-000001).
     */
    protected function generarCodigoOrden($nextId = null)
    {
        $cfg = ReparacionSettings::all();
        $prefijo = $cfg['prefijo_orden'] ?: 'ST-';
        if (!$nextId) {
            $nextId = (int) (Reparacion::max('id') ?: 0) + 1;
        }
        $codigo = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        while (Reparacion::where('codigo_orden', $codigo)->exists()) {
            $nextId++;
            $codigo = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }
        return $codigo;
    }

    /**
     * Registra una nueva recepción de celular / dispositivo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'      => 'required|integer',
            'marca'           => 'required|string|max:80',
            'modelo'          => 'required|string|max:120',
            'falla_reportada' => 'required|string',
        ], [
            'cliente_id.required'      => 'Seleccioná el cliente propietario del equipo.',
            'marca.required'           => 'Ingresá la marca del dispositivo.',
            'modelo.required'          => 'Ingresá el modelo del dispositivo.',
            'falla_reportada.required' => 'Describí la falla o motivo de ingreso.',
        ]);

        DB::beginTransaction();
        try {
            $cfg = ReparacionSettings::all();
            $nextId = (int) (Reparacion::max('id') ?: 0) + 1;
            $codigoOrden = $this->generarCodigoOrden($nextId);

            $sucCod = (int) ($request->input('suc_cod') ?: 1);
            $manoObra = max(0, (float) $request->input('costo_mano_obra', 0));
            $descuento = max(0, (float) $request->input('descuento', 0));
            $adelanto = max(0, (float) $request->input('monto_adelanto', 0));

            $reparacion = new Reparacion();
            $reparacion->codigo_orden          = $codigoOrden;
            $reparacion->CLIENTES_cod          = (int) $request->input('cliente_id');
            $reparacion->suc_cod               = $sucCod;
            $reparacion->cod_usuario_recepcion = Auth::user()->cod_usuarios;
            $reparacion->cod_usuario_tecnico   = $request->filled('cod_usuario_tecnico') ? (int) $request->input('cod_usuario_tecnico') : null;
            $reparacion->reparacion_origen_id  = $request->filled('reparacion_origen_id') ? (int) $request->input('reparacion_origen_id') : null;
            $reparacion->es_reingreso_garantia = (bool) $request->input('es_reingreso_garantia', false);

            $reparacion->tipo_equipo        = trim($request->input('tipo_equipo', 'Celular'));
            $reparacion->marca              = trim($request->input('marca'));
            $reparacion->modelo             = trim($request->input('modelo'));
            $reparacion->color              = trim($request->input('color', ''));
            $reparacion->imei_serie         = trim($request->input('imei_serie', ''));
            $reparacion->tipo_bloqueo       = trim($request->input('tipo_bloqueo', 'NINGUNO'));
            $reparacion->clave_bloqueo      = trim($request->input('clave_bloqueo', ''));
            $reparacion->patron_bloqueo     = trim($request->input('patron_bloqueo', ''));
            $reparacion->accesorios_dejados = trim($request->input('accesorios_dejados', ''));
            $reparacion->checklist_recepcion = $request->input('checklist_recepcion', []);
            $reparacion->estado_fisico_obs  = trim($request->input('estado_fisico_obs', ''));
            $reparacion->falla_reportada    = trim($request->input('falla_reportada'));
            $reparacion->prioridad          = trim($request->input('prioridad', 'NORMAL'));
            $reparacion->fecha_recepcion    = $request->filled('fecha_recepcion')
                ? $request->input('fecha_recepcion')
                : date('Y-m-d H:i:s');
            $reparacion->fecha_prometida    = $request->filled('fecha_prometida')
                ? $request->input('fecha_prometida')
                : null;

            $reparacion->diagnostico_tecnico    = trim($request->input('diagnostico_tecnico', ''));
            $reparacion->observaciones_internas = trim($request->input('observaciones_internas', ''));
            $reparacion->estado                 = 'RECIBIDO';
            $reparacion->costo_mano_obra        = $manoObra;
            $reparacion->descuento              = $descuento;
            $reparacion->tiene_garantia         = (bool) $request->input('tiene_garantia', true);
            $reparacion->dias_garantia          = (int) $request->input('dias_garantia', $cfg['garantia_dias_default']);
            $reparacion->condiciones_garantia   = $cfg['condiciones_garantia'];
            $reparacion->save();

            // Guardar imágenes pre-subidas durante la recepción
            $imagenesIniciales = $request->input('imagenes_iniciales', []);
            if (is_array($imagenesIniciales)) {
                foreach ($imagenesIniciales as $img) {
                    if (!empty($img['url'])) {
                        ReparacionImagen::create([
                            'reparacion_id' => $reparacion->id,
                            'etapa'         => $img['etapa'] ?? 'RECEPCION',
                            'url'           => $img['url'],
                            'filename'      => $img['filename'] ?? null,
                            'public_id'     => $img['public_id'] ?? null,
                            'driver'        => $img['driver'] ?? 'local',
                            'descripcion'   => $img['descripcion'] ?? 'Foto de recepción',
                            'cod_usuarios'  => Auth::user()->cod_usuarios,
                        ]);
                    }
                }
            }

            // Registrar seña / adelanto si se ingresó en la recepción
            if ($adelanto > 0) {
                $formaCobro = (int) $request->input('forma_cobro_adelanto', 1);
                $registrarCaja = (bool) $request->input('registrar_caja_adelanto', false);
                $nroOpCaja = null;

                if ($registrarCaja) {
                    $nroOpCaja = $this->registrarIngresoCaja(
                        $sucCod,
                        $adelanto,
                        "Seña / Adelanto Servicio Técnico {$reparacion->codigo_orden} ({$reparacion->marca} {$reparacion->modelo})"
                    );
                }

                ReparacionPago::create([
                    'reparacion_id'      => $reparacion->id,
                    'tipo_pago'          => 'ADELANTO',
                    'forma_cobro'        => $formaCobro,
                    'monto'              => $adelanto,
                    'registrar_caja'     => $nroOpCaja !== null,
                    'nro_operacion_caja' => $nroOpCaja,
                    'observacion'        => 'Adelanto / Seña en recepción',
                    'cod_usuarios'       => Auth::user()->cod_usuarios,
                    'fecha_pago'         => date('Y-m-d H:i:s'),
                ]);
            }

            // Recalcular totales
            $reparacion->recalcularTotales();

            // Registrar en bitácora / historial
            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => null,
                'estado_nuevo'    => 'RECIBIDO',
                'titulo'          => $reparacion->es_reingreso_garantia
                    ? 'Ingreso de equipo por Garantía'
                    : 'Recepción de equipo en Servicio Técnico',
                'comentario'      => "Equipo {$reparacion->marca} {$reparacion->modelo} recibido. Falla reportada: {$reparacion->falla_reportada}",
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return response()->json([
                'success'      => true,
                'id'           => $reparacion->id,
                'codigo_orden' => $reparacion->codigo_orden,
                'reparacion'   => $reparacion->fresh(['cliente', 'tecnico', 'sucursal', 'imagenes', 'pagos']),
                'message'      => "Orden de Reparación {$reparacion->codigo_orden} registrada exitosamente.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar recepción: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene el detalle completo de una orden de reparación.
     */
    public function show($id)
    {
        $reparacion = Reparacion::with([
            'cliente.ciudad',
            'sucursal',
            'usuarioRecepcion:cod_usuarios,nom_usuarios',
            'tecnico:cod_usuarios,nom_usuarios',
            'usuarioEntrega:cod_usuarios,nom_usuarios',
            'ordenOrigen:id,codigo_orden,fecha_recepcion,fecha_entrega,marca,modelo',
            'reingresos:id,codigo_orden,reparacion_origen_id,fecha_recepcion,estado',
            'repuestos.usuario:cod_usuarios,nom_usuarios',
            'imagenes.usuario:cod_usuarios,nom_usuarios',
            'pagos.usuario:cod_usuarios,nom_usuarios',
            'historial.usuario:cod_usuarios,nom_usuarios',
            'garantias.usuario:cod_usuarios,nom_usuarios',
            'garantias.ordenReingreso:id,codigo_orden,estado',
        ])->findOrFail($id);

        // Si el usuario no tiene permiso para ver costos de repuestos, ocultarlos
        if (!Auth::user()->tieneAccion('reparacion_ver_costo')) {
            $reparacion->costo_repuestos = 0;
            foreach ($reparacion->repuestos as $rep) {
                $rep->costo_unitario = 0;
            }
        }

        // Calcular estado de vigencia de garantía
        $garantiaVigente = false;
        $diasRestantesGarantia = 0;
        if ($reparacion->tiene_garantia && !empty($reparacion->fecha_vencimiento_garantia)) {
            $hoy = strtotime(date('Y-m-d'));
            $venc = strtotime($reparacion->fecha_vencimiento_garantia);
            $diff = (int) floor(($venc - $hoy) / 86400);
            $diasRestantesGarantia = $diff;
            $garantiaVigente = $diff >= 0;
        }

        return response()->json([
            'success'                 => true,
            'reparacion'              => $reparacion,
            'garantia_vigente'        => $garantiaVigente,
            'dias_restantes_garantia' => $diasRestantesGarantia,
        ]);
    }

    /**
     * Actualiza los datos de recepción y del dispositivo.
     */
    public function updateRecepcion(Request $request, $id)
    {
        $reparacion = Reparacion::findOrFail($id);

        $request->validate([
            'cliente_id'      => 'required|integer',
            'marca'           => 'required|string|max:80',
            'modelo'          => 'required|string|max:120',
            'falla_reportada' => 'required|string',
        ]);

        $reparacion->CLIENTES_cod          = (int) $request->input('cliente_id');
        $reparacion->tipo_equipo           = trim($request->input('tipo_equipo', $reparacion->tipo_equipo));
        $reparacion->marca                 = trim($request->input('marca'));
        $reparacion->modelo                = trim($request->input('modelo'));
        $reparacion->color                 = trim($request->input('color', ''));
        $reparacion->imei_serie            = trim($request->input('imei_serie', ''));
        $reparacion->tipo_bloqueo          = trim($request->input('tipo_bloqueo', 'NINGUNO'));
        $reparacion->clave_bloqueo         = trim($request->input('clave_bloqueo', ''));
        $reparacion->patron_bloqueo        = trim($request->input('patron_bloqueo', ''));
        $reparacion->accesorios_dejados    = trim($request->input('accesorios_dejados', ''));
        $reparacion->checklist_recepcion   = $request->input('checklist_recepcion', $reparacion->checklist_recepcion);
        $reparacion->estado_fisico_obs     = trim($request->input('estado_fisico_obs', ''));
        $reparacion->falla_reportada       = trim($request->input('falla_reportada'));
        $reparacion->prioridad             = trim($request->input('prioridad', 'NORMAL'));
        $reparacion->fecha_prometida       = $request->filled('fecha_prometida') ? $request->input('fecha_prometida') : null;
        $reparacion->cod_usuario_tecnico   = $request->filled('cod_usuario_tecnico') ? (int) $request->input('cod_usuario_tecnico') : null;
        $reparacion->save();

        ReparacionHistorial::create([
            'reparacion_id'   => $reparacion->id,
            'estado_anterior' => $reparacion->estado,
            'estado_nuevo'    => $reparacion->estado,
            'titulo'          => 'Datos de recepción actualizados',
            'comentario'      => "Equipo: {$reparacion->marca} {$reparacion->modelo} | IMEI: {$reparacion->imei_serie}",
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Datos de recepción actualizados correctamente.',
            'reparacion' => $reparacion,
        ]);
    }

    /**
     * Guarda el diagnóstico técnico, mano de obra, solución aplicada y estado del taller.
     */
    public function guardarDiagnostico(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('reparacion_diagnosticar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar diagnósticos o actualizar el taller.',
            ], 403);
        }

        $reparacion = Reparacion::findOrFail($id);
        $estadoAnterior = $reparacion->estado;
        $nuevoEstado = $request->input('estado', $reparacion->estado);

        $reparacion->diagnostico_tecnico    = trim($request->input('diagnostico_tecnico', ''));
        $reparacion->solucion_aplicada      = trim($request->input('solucion_aplicada', ''));
        $reparacion->observaciones_internas = trim($request->input('observaciones_internas', ''));
        $reparacion->costo_mano_obra        = max(0, (float) $request->input('costo_mano_obra', 0));
        $reparacion->descuento              = max(0, (float) $request->input('descuento', 0));
        $reparacion->tiene_garantia         = (bool) $request->input('tiene_garantia', $reparacion->tiene_garantia);
        $reparacion->dias_garantia          = max(0, (int) $request->input('dias_garantia', $reparacion->dias_garantia));

        if ($request->filled('cod_usuario_tecnico')) {
            $reparacion->cod_usuario_tecnico = (int) $request->input('cod_usuario_tecnico');
        } elseif (!$reparacion->cod_usuario_tecnico) {
            $reparacion->cod_usuario_tecnico = Auth::user()->cod_usuarios;
        }

        if (!empty($reparacion->diagnostico_tecnico) && empty($reparacion->fecha_diagnostico)) {
            $reparacion->fecha_diagnostico = date('Y-m-d H:i:s');
        }

        if (in_array($nuevoEstado, ['REPARADO', 'NO_REPARABLE']) && empty($reparacion->fecha_reparado)) {
            $reparacion->fecha_reparado = date('Y-m-d H:i:s');
        }

        $reparacion->estado = $nuevoEstado;
        $reparacion->save();
        $reparacion->recalcularTotales();

        $comentarioHist = [];
        if ($estadoAnterior !== $nuevoEstado) {
            $comentarioHist[] = "Estado cambiado de {$estadoAnterior} a {$nuevoEstado}.";
        }
        if (!empty($reparacion->diagnostico_tecnico)) {
            $comentarioHist[] = "Diagnóstico: " . mb_substr($reparacion->diagnostico_tecnico, 0, 180);
        }
        if (!empty($reparacion->solucion_aplicada)) {
            $comentarioHist[] = "Solución: " . mb_substr($reparacion->solucion_aplicada, 0, 180);
        }

        ReparacionHistorial::create([
            'reparacion_id'   => $reparacion->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
            'titulo'          => ($estadoAnterior !== $nuevoEstado)
                ? "Diagnóstico y cambio de estado a {$nuevoEstado}"
                : 'Actualización de diagnóstico y presupuesto técnico',
            'comentario'      => implode(' | ', $comentarioHist) ?: 'Diagnóstico y costos actualizados.',
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->show($reparacion->id);
    }

    /**
     * Cambio rápido de estado con registro en bitácora.
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:RECIBIDO,EN_DIAGNOSTICO,PRESUPUESTADO,APROBADO,ESPERANDO_REPUESTO,EN_REPARACION,REPARADO,NO_REPARABLE,ENTREGADO,EN_GARANTIA,ANULADO',
        ]);

        $reparacion = Reparacion::findOrFail($id);
        $estadoAnterior = $reparacion->estado;
        $nuevoEstado = $request->input('estado');
        $comentario = trim($request->input('comentario', ''));

        if ($nuevoEstado === 'ANULADO') {
            return $this->destroy($id);
        }

        if (in_array($nuevoEstado, ['REPARADO', 'NO_REPARABLE']) && empty($reparacion->fecha_reparado)) {
            $reparacion->fecha_reparado = date('Y-m-d H:i:s');
        }

        $reparacion->estado = $nuevoEstado;
        $reparacion->save();

        ReparacionHistorial::create([
            'reparacion_id'   => $reparacion->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
            'titulo'          => "Estado actualizado a {$nuevoEstado}",
            'comentario'      => $comentario ?: "Cambio de estado de {$estadoAnterior} a {$nuevoEstado}.",
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'estado'  => $reparacion->estado,
            'message' => "Estado de la orden {$reparacion->codigo_orden} actualizado a {$nuevoEstado}.",
        ]);
    }

    /**
     * Buscador de repuestos / artículos del inventario por sucursal.
     */
    public function buscarRepuestos(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $suc = $request->get('suc_cod', session('sucursal_actual') ?: 1);
        $seccion = $request->get('seccion', 0);

        $query = Articulo::join('presentacion', 'articulos.present_cod', '=', 'presentacion.present_cod')
            ->leftJoin('unidad', 'articulos.uni_codigo', '=', 'unidad.uni_codigo')
            ->leftJoin('stock', function ($join) use ($suc) {
                $join->on('articulos.articulos_cod', '=', 'stock.articulos_cod');
                if (!empty($suc) && $suc != '0') {
                    $join->where('stock.suc_cod', '=', $suc);
                }
            })
            ->select(
                'articulos.ARTICULOS_cod',
                'articulos.articulos_cod',
                'articulos.producto_c_barra',
                'articulos.producto_nombre',
                'articulos.producto_costo_compra',
                'articulos.pre_venta1',
                'articulos.pre_venta2',
                'articulos.foto',
                'articulos.producto_ubicacion',
                'presentacion.present_descripcion',
                'presentacion.iva',
                'unidad.uni_abreviatura',
                DB::raw('COALESCE(SUM(stock.cantidad), 0) AS stock_disponible'),
                DB::raw('MIN(stock.id_stock) AS id_stock')
            )
            ->where('articulos.producto_c_barra', '!=', ArticuloLibre::CODIGO_BARRA)
            ->where('articulos.activo', 1);

        if (!empty($seccion) && (int) $seccion > 0) {
            $query->where('articulos.present_cod', (int) $seccion);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('articulos.producto_nombre', 'LIKE', "%{$q}%")
                  ->orWhere('articulos.producto_c_barra', 'LIKE', "%{$q}%")
                  ->orWhere('articulos.producto_ubicacion', 'LIKE', "%{$q}%");
            });
        }

        $articulos = $query->groupBy('articulos.articulos_cod')
            ->orderBy('articulos.producto_nombre', 'ASC')
            ->limit(40)
            ->get();

        if (!Auth::user()->tieneAccion('reparacion_ver_costo')) {
            foreach ($articulos as $a) {
                $a->producto_costo_compra = 0;
            }
        }

        return response()->json($articulos);
    }

    /**
     * Agrega un repuesto (de inventario o libre/externo) a la orden y descuenta stock si corresponde.
     */
    public function agregarRepuesto(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('reparacion_repuestos')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para asignar repuestos a la reparación.',
            ], 403);
        }

        $reparacion = Reparacion::findOrFail($id);
        if ($reparacion->estado === 'ANULADO') {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden agregar repuestos a una orden anulada.',
            ], 422);
        }

        $esLibre = (bool) $request->input('es_libre', false);
        $cantidad = max(0.01, (float) $request->input('cantidad', 1));
        $precioUnitario = max(0, (float) $request->input('precio_unitario', 0));
        $costoUnitario = max(0, (float) $request->input('costo_unitario', 0));
        $garantiaDias = max(0, (int) $request->input('garantia_repuesto_dias', $reparacion->dias_garantia ?: 30));
        $observacion = trim((string) $request->input('observacion', ''));
        $sucCod = (int) ($reparacion->suc_cod ?: 1);

        DB::beginTransaction();
        try {
            $cfg = ReparacionSettings::all();
            $descontarStockCfg = $cfg['descontar_stock'];
            $venderSinStock = Ajuste::flag('vender_sin_stock', 1);

            $artCod = null;
            $idStock = null;
            $codigoBarra = '';
            $descripcion = trim((string) $request->input('descripcion', ''));
            $iva = (int) $request->input('iva', 10);
            $stockDescontado = false;

            if (!$esLibre) {
                $artCod = (int) $request->input('articulos_cod');
                $articulo = Articulo::where('ARTICULOS_cod', $artCod)->first();
                if (!$articulo) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El repuesto seleccionado no existe en el inventario.',
                    ], 422);
                }

                $codigoBarra = $articulo->producto_c_barra ?? '';
                if ($descripcion === '') {
                    $descripcion = $articulo->producto_nombre;
                }
                if ($costoUnitario <= 0) {
                    $costoUnitario = (float) ($articulo->producto_costo_compra ?? 0);
                }
                if ($precioUnitario <= 0 && !$reparacion->es_reingreso_garantia) {
                    $precioUnitario = (float) ($articulo->pre_venta1 ?? 0);
                }

                $stockRow = DB::table('stock')
                    ->where('ARTICULOS_cod', $artCod)
                    ->where('suc_cod', $sucCod)
                    ->orderByDesc('cantidad')
                    ->first();

                $disponible = $stockRow ? (float) $stockRow->cantidad : 0;
                $idStock = $stockRow ? (int) $stockRow->id_stock : null;

                if ($venderSinStock !== 1 && !Auth::user()->tieneAccion('venta_sin_stock') && $disponible < $cantidad) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para '{$descripcion}' (Disponible: {$disponible}).",
                    ], 422);
                }

                // Descontar del inventario de la sucursal
                if ($descontarStockCfg) {
                    if ($idStock) {
                        DB::update('UPDATE stock SET cantidad = (cantidad - ?) WHERE id_stock = ?', [$cantidad, $idStock]);
                    } else {
                        $nuevoStock = new Stock();
                        $nuevoStock->articulos_cod = $artCod;
                        $nuevoStock->suc_cod = $sucCod;
                        $nuevoStock->cantidad = 0 - $cantidad;
                        $nuevoStock->stock_fech_venc = '2030-01-01';
                        $nuevoStock->lote_nro = '';
                        $nuevoStock->save();
                        $idStock = $nuevoStock->id_stock;
                    }
                    $stockDescontado = true;
                }
            } else {
                if ($descripcion === '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ingresá la descripción del repuesto o insumo.',
                    ], 422);
                }
                $artCod = ArticuloLibre::id();
                $codigoBarra = 'REPUESTO-EXT';
            }

            $subtotal = $cantidad * $precioUnitario;

            $repuesto = ReparacionRepuesto::create([
                'reparacion_id'          => $reparacion->id,
                'ARTICULOS_cod'          => $artCod,
                'id_stock'               => $idStock,
                'suc_cod'                => $sucCod,
                'es_libre'               => $esLibre,
                'codigo_barra'           => $codigoBarra,
                'descripcion'            => $descripcion,
                'cantidad'               => $cantidad,
                'costo_unitario'         => $costoUnitario,
                'precio_unitario'        => $precioUnitario,
                'subtotal'               => $subtotal,
                'iva'                    => $iva,
                'stock_descontado'       => $stockDescontado,
                'garantia_repuesto_dias' => $garantiaDias,
                'observacion'            => $observacion,
                'cod_usuarios'           => Auth::user()->cod_usuarios,
            ]);

            $reparacion->recalcularTotales();

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $reparacion->estado,
                'estado_nuevo'    => $reparacion->estado,
                'titulo'          => 'Repuesto agregado a la orden',
                'comentario'      => "{$cantidad}x {$descripcion} (Gs. " . number_format($subtotal, 0, ',', '.') . ")" . ($stockDescontado ? ' [Stock descontado]' : ''),
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($reparacion->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar repuesto: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Elimina un repuesto de la orden y repone el stock si había sido descontado.
     */
    public function eliminarRepuesto($id, $repuestoId)
    {
        if (!Auth::user()->tieneAccion('reparacion_repuestos')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para quitar repuestos.',
            ], 403);
        }

        $reparacion = Reparacion::findOrFail($id);
        $repuesto = ReparacionRepuesto::where('reparacion_id', $reparacion->id)
            ->where('id', $repuestoId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            if ($repuesto->stock_descontado && !$repuesto->es_libre && $repuesto->ARTICULOS_cod) {
                $this->reponerStockRepuesto($repuesto);
            }

            $desc = $repuesto->descripcion;
            $cant = $repuesto->cantidad;
            $repuesto->delete();

            $reparacion->recalcularTotales();

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $reparacion->estado,
                'estado_nuevo'    => $reparacion->estado,
                'titulo'          => 'Repuesto retirado de la orden',
                'comentario'      => "Se retiró {$cant}x {$desc} y se actualizó el inventario.",
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($reparacion->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al quitar repuesto: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Repone en la tabla stock la cantidad descontada por un repuesto.
     */
    protected function reponerStockRepuesto(ReparacionRepuesto $repuesto)
    {
        $cantidad = (float) $repuesto->cantidad;
        if ($cantidad <= 0 || !$repuesto->stock_descontado) {
            return;
        }

        if ($repuesto->id_stock) {
            DB::update('UPDATE stock SET cantidad = (cantidad + ?) WHERE id_stock = ?', [$cantidad, $repuesto->id_stock]);
        } elseif ($repuesto->ARTICULOS_cod) {
            $sucCod = $repuesto->suc_cod ?: 1;
            $stockRow = DB::table('stock')
                ->where('ARTICULOS_cod', $repuesto->ARTICULOS_cod)
                ->where('suc_cod', $sucCod)
                ->orderBy('id_stock')
                ->first();
            if ($stockRow) {
                DB::update('UPDATE stock SET cantidad = (cantidad + ?) WHERE id_stock = ?', [$cantidad, $stockRow->id_stock]);
            }
        }

        $repuesto->stock_descontado = false;
        $repuesto->save();
    }

    /**
     * Sube una imagen desde archivo (PC o cámara móvil) con soporte Cloudinary y Storage local.
     */
    public function subirImagen(Request $request)
    {
        $request->validate([
            'imagen'        => 'required|file|mimes:jpeg,jpg,png,webp|max:10240',
            'etapa'         => 'nullable|string|in:RECEPCION,DIAGNOSTICO,REPARACION,ENTREGA,GARANTIA',
            'descripcion'   => 'nullable|string|max:255',
            'reparacion_id' => 'nullable|integer',
        ], [
            'imagen.required' => 'Seleccioná una imagen válida.',
            'imagen.mimes'    => 'Formatos permitidos: JPG, PNG o WEBP.',
            'imagen.max'      => 'El tamaño máximo por foto es 10 MB.',
        ]);

        $file = $request->file('imagen');
        $etapa = $request->input('etapa', 'RECEPCION');
        $descripcion = trim((string) $request->input('descripcion', ''));

        $stored = $this->almacenarImagenBinariaOArchivo($file);
        if (!$stored['success']) {
            return response()->json([
                'success' => false,
                'message' => $stored['message'] ?? 'No se pudo almacenar la imagen.',
            ], 422);
        }

        $imagenModel = null;
        if ($request->filled('reparacion_id') && (int) $request->input('reparacion_id') > 0) {
            $imagenModel = ReparacionImagen::create([
                'reparacion_id' => (int) $request->input('reparacion_id'),
                'etapa'         => $etapa,
                'url'           => $stored['url'],
                'filename'      => $stored['filename'],
                'public_id'     => $stored['public_id'],
                'driver'        => $stored['driver'],
                'descripcion'   => $descripcion ?: "Evidencia ({$etapa})",
                'cod_usuarios'  => Auth::user()->cod_usuarios,
            ]);
        }

        return response()->json([
            'success'   => true,
            'url'       => $stored['url'],
            'filename'  => $stored['filename'],
            'public_id' => $stored['public_id'],
            'driver'    => $stored['driver'],
            'etapa'     => $etapa,
            'descripcion' => $descripcion ?: "Evidencia ({$etapa})",
            'imagen'    => $imagenModel,
            'message'   => 'Fotografía guardada correctamente.',
        ]);
    }

    /**
     * Sube una imagen capturada desde Webcam HTML5 (Base64 Data URI).
     */
    public function subirImagenBase64(Request $request)
    {
        $request->validate([
            'base64'        => 'required|string',
            'etapa'         => 'nullable|string|in:RECEPCION,DIAGNOSTICO,REPARACION,ENTREGA,GARANTIA',
            'descripcion'   => 'nullable|string|max:255',
            'reparacion_id' => 'nullable|integer',
        ]);

        $raw = (string) $request->input('base64');
        if (strpos($raw, 'base64,') !== false) {
            $parts = explode('base64,', $raw, 2);
            $binary = base64_decode($parts[1]);
        } else {
            $binary = base64_decode($raw);
        }

        if (!$binary || strlen($binary) < 100) {
            return response()->json([
                'success' => false,
                'message' => 'La captura de cámara no contiene una imagen válida.',
            ], 422);
        }

        $etapa = $request->input('etapa', 'RECEPCION');
        $descripcion = trim((string) $request->input('descripcion', ''));

        $stored = $this->almacenarImagenBinariaOArchivo($binary);
        if (!$stored['success']) {
            return response()->json([
                'success' => false,
                'message' => $stored['message'] ?? 'No se pudo guardar la captura.',
            ], 422);
        }

        $imagenModel = null;
        if ($request->filled('reparacion_id') && (int) $request->input('reparacion_id') > 0) {
            $imagenModel = ReparacionImagen::create([
                'reparacion_id' => (int) $request->input('reparacion_id'),
                'etapa'         => $etapa,
                'url'           => $stored['url'],
                'filename'      => $stored['filename'],
                'public_id'     => $stored['public_id'],
                'driver'        => $stored['driver'],
                'descripcion'   => $descripcion ?: "Captura de cámara ({$etapa})",
                'cod_usuarios'  => Auth::user()->cod_usuarios,
            ]);
        }

        return response()->json([
            'success'     => true,
            'url'         => $stored['url'],
            'filename'    => $stored['filename'],
            'public_id'   => $stored['public_id'],
            'driver'      => $stored['driver'],
            'etapa'       => $etapa,
            'descripcion' => $descripcion ?: "Captura de cámara ({$etapa})",
            'imagen'      => $imagenModel,
            'message'     => 'Captura fotográfica guardada exitosamente.',
        ]);
    }

    /**
     * Captura instantánea desde Cámara IP configurada en Ajustes del Sistema.
     */
    public function capturarImagenCamara(Request $request)
    {
        $cfg = CameraSettings::all();
        $url = CameraSettings::snapshotUrl($cfg);

        if ($url === '') {
            return response()->json([
                'success' => false,
                'message' => 'Configurá la URL/IP de la cámara en Ajustes Generales o usá la Cámara Web del navegador.',
            ], 422);
        }

        try {
            $options = [
                'verify'  => false,
                'timeout' => 12,
            ];
            if ($cfg['user'] !== '') {
                $options['auth'] = [$cfg['user'], $cfg['password'], 'digest'];
            }

            $client = new Client($options);
            $response = $client->get($url);

            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al capturar desde Cámara IP. Código HTTP: ' . $response->getStatusCode(),
                ], 422);
            }

            $body = (string) $response->getBody();
            if (strlen($body) < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cámara IP no devolvió una imagen válida.',
                ], 422);
            }

            $etapa = $request->input('etapa', 'RECEPCION');
            $descripcion = trim((string) $request->input('descripcion', ''));

            $stored = $this->almacenarImagenBinariaOArchivo($body);
            if (!$stored['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $stored['message'] ?? 'No se pudo almacenar la captura IP.',
                ], 422);
            }

            $imagenModel = null;
            if ($request->filled('reparacion_id') && (int) $request->input('reparacion_id') > 0) {
                $imagenModel = ReparacionImagen::create([
                    'reparacion_id' => (int) $request->input('reparacion_id'),
                    'etapa'         => $etapa,
                    'url'           => $stored['url'],
                    'filename'      => $stored['filename'],
                    'public_id'     => $stored['public_id'],
                    'driver'        => $stored['driver'],
                    'descripcion'   => $descripcion ?: "Cámara IP ({$etapa})",
                    'cod_usuarios'  => Auth::user()->cod_usuarios,
                ]);
            }

            return response()->json([
                'success'     => true,
                'url'         => $stored['url'],
                'filename'    => $stored['filename'],
                'public_id'   => $stored['public_id'],
                'driver'      => $stored['driver'],
                'etapa'       => $etapa,
                'descripcion' => $descripcion ?: "Cámara IP ({$etapa})",
                'imagen'      => $imagenModel,
                'message'     => 'Captura desde Cámara IP guardada.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo conectar con la Cámara IP: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Almacena archivo o buffer binario en Cloudinary (si está activo) o en Storage público local.
     */
    protected function almacenarImagenBinariaOArchivo($fileOrBinary)
    {
        if (CloudinarySettings::isEnabled()) {
            $baseFolder = rtrim(CloudinarySettings::getFolder(), '/');
            $cloudRes = CloudinaryService::upload($fileOrBinary, [
                'folder' => $baseFolder . '/reparaciones',
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

            Log::warning('Fallo subida de imagen de reparación a Cloudinary, usando almacenamiento local: ' . ($cloudRes['message'] ?? ''));
        }

        if ($fileOrBinary instanceof \Illuminate\Http\UploadedFile) {
            $ext = strtolower($fileOrBinary->getClientOriginalExtension() ?: 'jpg');
            $nombre = 'rep_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            Storage::disk('public')->putFileAs('reparaciones', $fileOrBinary, $nombre);
        } else {
            $nombre = 'rep_' . time() . '_' . mt_rand(1000, 9999) . '.jpg';
            Storage::disk('public')->put('reparaciones/' . $nombre, $fileOrBinary);
        }

        return [
            'success'   => true,
            'url'       => asset('storage/reparaciones/' . $nombre),
            'filename'  => $nombre,
            'public_id' => null,
            'driver'    => 'local',
        ];
    }

    /**
     * Elimina una imagen de una orden de reparación.
     */
    public function eliminarImagen($id, $imagenId)
    {
        $reparacion = Reparacion::findOrFail($id);
        $img = ReparacionImagen::where('reparacion_id', $reparacion->id)
            ->where('id', $imagenId)
            ->firstOrFail();

        if ($img->driver === 'cloudinary' && !empty($img->public_id)) {
            CloudinaryService::delete($img->public_id);
        } elseif ($img->driver === 'local' && !empty($img->filename)) {
            if (Storage::disk('public')->exists('reparaciones/' . $img->filename)) {
                Storage::disk('public')->delete('reparaciones/' . $img->filename);
            }
        }

        $img->delete();

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada correctamente.',
        ]);
    }

    /**
     * Registra un pago (Adelanto/Seña, Abono o Cobro en Entrega).
     */
    public function registrarPago(Request $request, $id)
    {
        $reparacion = Reparacion::findOrFail($id);

        $request->validate([
            'monto'       => 'required|numeric|min:1',
            'tipo_pago'   => 'nullable|in:ADELANTO,ABONO,COBRO_ENTREGA,DEVOLUCION',
            'forma_cobro' => 'nullable|integer',
        ]);

        $monto = (float) $request->input('monto');
        $tipoPago = $request->input('tipo_pago', 'ABONO');
        $formaCobro = (int) $request->input('forma_cobro', 1);
        $registrarCaja = (bool) $request->input('registrar_caja', false);
        $observacion = trim((string) $request->input('observacion', ''));

        DB::beginTransaction();
        try {
            $nroOpCaja = null;
            if ($registrarCaja && $tipoPago !== 'DEVOLUCION') {
                $nroOpCaja = $this->registrarIngresoCaja(
                    $reparacion->suc_cod,
                    $monto,
                    "Cobro Servicio Técnico {$reparacion->codigo_orden} ({$tipoPago}) - {$reparacion->marca} {$reparacion->modelo}"
                );
            }

            $pago = ReparacionPago::create([
                'reparacion_id'      => $reparacion->id,
                'tipo_pago'          => $tipoPago,
                'forma_cobro'        => $formaCobro,
                'monto'              => $monto,
                'nro_recibo'         => $request->input('nro_recibo'),
                'registrar_caja'     => $nroOpCaja !== null,
                'nro_operacion_caja' => $nroOpCaja,
                'observacion'        => $observacion ?: "Pago registrado ({$tipoPago})",
                'cod_usuarios'       => Auth::user()->cod_usuarios,
                'fecha_pago'         => date('Y-m-d H:i:s'),
            ]);

            $reparacion->recalcularTotales();

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $reparacion->estado,
                'estado_nuevo'    => $reparacion->estado,
                'titulo'          => "Pago registrado ({$tipoPago}): Gs. " . number_format($monto, 0, ',', '.'),
                'comentario'      => ($observacion ? $observacion . ' | ' : '') . "Saldo pendiente: Gs. " . number_format($reparacion->saldo_pendiente, 0, ',', '.'),
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($reparacion->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Elimina un pago registrado.
     */
    public function eliminarPago($id, $pagoId)
    {
        $reparacion = Reparacion::findOrFail($id);
        $pago = ReparacionPago::where('reparacion_id', $reparacion->id)
            ->where('id', $pagoId)
            ->firstOrFail();

        $monto = $pago->monto;
        $pago->delete();
        $reparacion->recalcularTotales();

        ReparacionHistorial::create([
            'reparacion_id'   => $reparacion->id,
            'estado_anterior' => $reparacion->estado,
            'estado_nuevo'    => $reparacion->estado,
            'titulo'          => 'Pago anulado',
            'comentario'      => 'Se eliminó pago por Gs. ' . number_format($monto, 0, ',', '.'),
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->show($reparacion->id);
    }

    /**
     * Registra la entrega del celular al cliente, cobra saldo opcionalmente y activa la garantía.
     */
    public function entregarEquipo(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('reparacion_entregar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar la entrega de equipos.',
            ], 403);
        }

        $reparacion = Reparacion::with('cliente')->findOrFail($id);

        DB::beginTransaction();
        try {
            $estadoAnterior = $reparacion->estado;
            $cfg = ReparacionSettings::all();

            $tieneGarantia = (bool) $request->input('tiene_garantia', true);
            $diasGarantia = max(0, (int) $request->input('dias_garantia', $reparacion->dias_garantia ?: $cfg['garantia_dias_default']));
            $fechaEntrega = date('Y-m-d H:i:s');
            $fechaVencGarantia = ($tieneGarantia && $diasGarantia > 0)
                ? date('Y-m-d', strtotime("+{$diasGarantia} days"))
                : null;

            $retiradoNombre = trim((string) $request->input('retirado_por_nombre', ''));
            if ($retiradoNombre === '' && $reparacion->cliente) {
                $retiradoNombre = $reparacion->cliente->cliente_nombre;
            }
            $retiradoCi = trim((string) $request->input('retirado_por_ci', ''));
            if ($retiradoCi === '' && $reparacion->cliente) {
                $retiradoCi = $reparacion->cliente->cliente_ci ?: $reparacion->cliente->cliente_ruc;
            }

            if ($request->filled('solucion_aplicada')) {
                $reparacion->solucion_aplicada = trim($request->input('solucion_aplicada'));
            }

            // Si se indicó cobrar el saldo pendiente en el acto de entrega
            $montoCobroEntrega = max(0, (float) $request->input('monto_cobro_entrega', 0));
            if ($montoCobroEntrega > 0) {
                $formaCobro = (int) $request->input('forma_cobro', 1);
                $registrarCaja = (bool) $request->input('registrar_caja', false);
                $nroOpCaja = null;

                if ($registrarCaja) {
                    $nroOpCaja = $this->registrarIngresoCaja(
                        $reparacion->suc_cod,
                        $montoCobroEntrega,
                        "Cobro de Entrega Servicio Técnico {$reparacion->codigo_orden} ({$reparacion->marca} {$reparacion->modelo})"
                    );
                }

                ReparacionPago::create([
                    'reparacion_id'      => $reparacion->id,
                    'tipo_pago'          => 'COBRO_ENTREGA',
                    'forma_cobro'        => $formaCobro,
                    'monto'              => $montoCobroEntrega,
                    'registrar_caja'     => $nroOpCaja !== null,
                    'nro_operacion_caja' => $nroOpCaja,
                    'observacion'        => 'Cobro al entregar el equipo',
                    'cod_usuarios'       => Auth::user()->cod_usuarios,
                    'fecha_pago'         => $fechaEntrega,
                ]);
            }

            $reparacion->estado                     = 'ENTREGADO';
            $reparacion->cod_usuario_entrega        = Auth::user()->cod_usuarios;
            $reparacion->fecha_entrega              = $fechaEntrega;
            $reparacion->retirado_por_nombre        = $retiradoNombre;
            $reparacion->retirado_por_ci            = $retiradoCi;
            $reparacion->conformidad_cliente        = trim((string) $request->input('conformidad_cliente', 'Equipo entregado probado y en conformidad.'));
            $reparacion->tiene_garantia             = $tieneGarantia;
            $reparacion->dias_garantia              = $diasGarantia;
            $reparacion->fecha_vencimiento_garantia = $fechaVencGarantia;
            $reparacion->condiciones_garantia       = trim((string) $request->input('condiciones_garantia', $reparacion->condiciones_garantia ?: $cfg['condiciones_garantia']));
            $reparacion->save();

            $reparacion->recalcularTotales();

            $garantiaTexto = ($tieneGarantia && $diasGarantia > 0)
                ? "Garantía activa por {$diasGarantia} días (vence el " . date('d/m/Y', strtotime($fechaVencGarantia)) . ")."
                : "Entregado sin cobertura de garantía.";

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => 'ENTREGADO',
                'titulo'          => 'Equipo entregado al cliente',
                'comentario'      => "Retirado por: {$retiradoNombre} ({$retiradoCi}). {$garantiaTexto}",
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => $fechaEntrega,
            ]);

            DB::commit();

            return $this->show($reparacion->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar entrega: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registra un reclamo de garantía sobre una orden entregada.
     */
    public function registrarGarantia(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('reparacion_garantia')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para gestionar garantías.',
            ], 403);
        }

        $reparacion = Reparacion::findOrFail($id);

        $request->validate([
            'motivo_reclamo' => 'required|string|min:3',
        ], [
            'motivo_reclamo.required' => 'Describí el motivo del reclamo de garantía.',
        ]);

        DB::beginTransaction();
        try {
            $estadoAnterior = $reparacion->estado;
            $cubre = (bool) $request->input('cubre_garantia', true);
            $generarReingreso = (bool) $request->input('generar_reingreso', false);

            $garantia = new ReparacionGarantia();
            $garantia->reparacion_id        = $reparacion->id;
            $garantia->fecha_reclamo        = date('Y-m-d H:i:s');
            $garantia->motivo_reclamo       = trim($request->input('motivo_reclamo'));
            $garantia->evaluacion_tecnica   = trim((string) $request->input('evaluacion_tecnica', ''));
            $garantia->cubre_garantia       = $cubre;
            $garantia->resolucion           = trim((string) $request->input('resolucion', ''));
            $garantia->estado               = $cubre ? 'ABIERTO' : 'RECHAZADO';
            $garantia->costo_asumido_taller = max(0, (float) $request->input('costo_asumido_taller', 0));
            $garantia->cod_usuarios         = Auth::user()->cod_usuarios;
            if (!$cubre) {
                $garantia->fecha_resolucion = date('Y-m-d H:i:s');
            }
            $garantia->save();

            if ($cubre) {
                $reparacion->estado = 'EN_GARANTIA';
                $reparacion->save();
            }

            $ordenReingreso = null;
            if ($generarReingreso && $cubre) {
                $ordenReingreso = $this->generarOrdenReingresoInterna($reparacion, $garantia);
            }

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $reparacion->estado,
                'titulo'          => $cubre ? 'Reclamo de Garantía registrado' : 'Reclamo de Garantía rechazado (Fuera de cobertura)',
                'comentario'      => "Motivo: {$garantia->motivo_reclamo}" . ($ordenReingreso ? " | Se generó orden de reingreso {$ordenReingreso->codigo_orden}." : ''),
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return $this->show($reparacion->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar garantía: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualiza el estado o resolución de un reclamo de garantía existente.
     */
    public function actualizarGarantia(Request $request, $id, $garantiaId)
    {
        if (!Auth::user()->tieneAccion('reparacion_garantia')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para gestionar garantías.',
            ], 403);
        }

        $reparacion = Reparacion::findOrFail($id);
        $garantia = ReparacionGarantia::where('reparacion_id', $reparacion->id)
            ->where('id', $garantiaId)
            ->firstOrFail();

        $garantia->evaluacion_tecnica   = trim((string) $request->input('evaluacion_tecnica', $garantia->evaluacion_tecnica));
        $garantia->cubre_garantia       = (bool) $request->input('cubre_garantia', $garantia->cubre_garantia);
        $garantia->resolucion           = trim((string) $request->input('resolucion', $garantia->resolucion));
        $garantia->estado               = $request->input('estado', $garantia->estado);
        $garantia->costo_asumido_taller = max(0, (float) $request->input('costo_asumido_taller', $garantia->costo_asumido_taller));

        if (in_array($garantia->estado, ['RESUELTO', 'RECHAZADO']) && empty($garantia->fecha_resolucion)) {
            $garantia->fecha_resolucion = date('Y-m-d H:i:s');
        }
        $garantia->save();

        // Si todas las garantías están resueltas o rechazadas, volver la orden original a ENTREGADO
        $abiertas = ReparacionGarantia::where('reparacion_id', $reparacion->id)
            ->whereIn('estado', ['ABIERTO', 'EN_REVISION'])
            ->count();

        if ($abiertas === 0 && $reparacion->estado === 'EN_GARANTIA') {
            $reparacion->estado = 'ENTREGADO';
            $reparacion->save();
        }

        ReparacionHistorial::create([
            'reparacion_id'   => $reparacion->id,
            'estado_anterior' => 'EN_GARANTIA',
            'estado_nuevo'    => $reparacion->estado,
            'titulo'          => "Garantía #{$garantia->id} actualizada a {$garantia->estado}",
            'comentario'      => $garantia->resolucion ?: $garantia->evaluacion_tecnica,
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $this->show($reparacion->id);
    }

    /**
     * Genera una nueva Orden de Reingreso por Garantía vinculada a una orden original.
     */
    public function crearReingresoGarantia(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('reparacion_garantia')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para generar reingresos por garantía.',
            ], 403);
        }

        $reparacionOrigen = Reparacion::findOrFail($id);
        $garantiaId = $request->input('garantia_id');
        $garantia = $garantiaId
            ? ReparacionGarantia::where('reparacion_id', $reparacionOrigen->id)->where('id', $garantiaId)->first()
            : null;

        DB::beginTransaction();
        try {
            if (!$garantia) {
                $garantia = ReparacionGarantia::create([
                    'reparacion_id'      => $reparacionOrigen->id,
                    'fecha_reclamo'      => date('Y-m-d H:i:s'),
                    'motivo_reclamo'     => trim($request->input('motivo_reclamo', 'Reingreso del equipo por garantía de reparación previa.')),
                    'cubre_garantia'     => true,
                    'estado'             => 'EN_REVISION',
                    'cod_usuarios'       => Auth::user()->cod_usuarios,
                ]);
            }

            $nuevaOrden = $this->generarOrdenReingresoInterna($reparacionOrigen, $garantia);

            DB::commit();

            return response()->json([
                'success'        => true,
                'reingreso_id'   => $nuevaOrden->id,
                'codigo_orden'   => $nuevaOrden->codigo_orden,
                'message'        => "Orden de Reingreso por Garantía {$nuevaOrden->codigo_orden} creada y vinculada a {$reparacionOrigen->codigo_orden}.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear reingreso por garantía: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function generarOrdenReingresoInterna(Reparacion $origen, ReparacionGarantia $garantia)
    {
        $nextId = (int) (Reparacion::max('id') ?: 0) + 1;
        $codigo = $this->generarCodigoOrden($nextId);

        $reingreso = new Reparacion();
        $reingreso->codigo_orden          = $codigo;
        $reingreso->CLIENTES_cod          = $origen->CLIENTES_cod;
        $reingreso->suc_cod               = $origen->suc_cod;
        $reingreso->cod_usuario_recepcion = Auth::user()->cod_usuarios;
        $reingreso->cod_usuario_tecnico   = $origen->cod_usuario_tecnico;
        $reingreso->reparacion_origen_id  = $origen->id;
        $reingreso->es_reingreso_garantia = true;

        $reingreso->tipo_equipo         = $origen->tipo_equipo;
        $reingreso->marca               = $origen->marca;
        $reingreso->modelo              = $origen->modelo;
        $reingreso->color               = $origen->color;
        $reingreso->imei_serie          = $origen->imei_serie;
        $reingreso->tipo_bloqueo        = $origen->tipo_bloqueo;
        $reingreso->clave_bloqueo       = $origen->clave_bloqueo;
        $reingreso->patron_bloqueo      = $origen->patron_bloqueo;
        $reingreso->accesorios_dejados  = $origen->accesorios_dejados;
        $reingreso->checklist_recepcion = $origen->checklist_recepcion;
        $reingreso->estado_fisico_obs   = "Reingreso por Garantía de Orden {$origen->codigo_orden}";
        $reingreso->falla_reportada     = "[GARANTÍA {$origen->codigo_orden}] " . $garantia->motivo_reclamo;
        $reingreso->prioridad           = 'ALTA';
        $reingreso->fecha_recepcion     = date('Y-m-d H:i:s');
        $reingreso->estado              = 'EN_DIAGNOSTICO';
        $reingreso->estado_pago         = 'SIN_CARGO';
        $reingreso->tiene_garantia      = true;
        $reingreso->dias_garantia       = $origen->dias_garantia;
        $reingreso->condiciones_garantia = $origen->condiciones_garantia;
        $reingreso->save();

        $garantia->reparacion_reingreso_id = $reingreso->id;
        $garantia->estado = 'EN_REVISION';
        $garantia->save();

        ReparacionHistorial::create([
            'reparacion_id'   => $reingreso->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'EN_DIAGNOSTICO',
            'titulo'          => "Reingreso por Garantía de Orden {$origen->codigo_orden}",
            'comentario'      => "Motivo de garantía: {$garantia->motivo_reclamo}",
            'cod_usuarios'    => Auth::user()->cod_usuarios,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return $reingreso;
    }

    /**
     * Anula una orden de reparación y repone los repuestos descontados.
     */
    public function destroy($id)
    {
        $reparacion = Reparacion::with('repuestos')->findOrFail($id);
        $estadoAnterior = $reparacion->estado;

        DB::beginTransaction();
        try {
            foreach ($reparacion->repuestos as $rep) {
                if ($rep->stock_descontado && !$rep->es_libre) {
                    $this->reponerStockRepuesto($rep);
                }
            }

            $reparacion->estado = 'ANULADO';
            $reparacion->save();

            ReparacionHistorial::create([
                'reparacion_id'   => $reparacion->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => 'ANULADO',
                'titulo'          => 'Orden de Reparación ANULADA',
                'comentario'      => 'Se anuló la orden y se repusieron al stock los repuestos asignados.',
                'cod_usuarios'    => Auth::user()->cod_usuarios,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Orden {$reparacion->codigo_orden} anulada correctamente y repuestos devueltos al stock.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al anular orden: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Prepara los ítems de la reparación (repuestos + mano de obra - señas) para enviar al Punto de Venta (POS).
     */
    public function getParaVenta($id)
    {
        $reparacion = Reparacion::with(['cliente', 'repuestos'])->findOrFail($id);
        $libreId = ArticuloLibre::id();

        $items = [];

        // 1. Mano de obra del servicio técnico
        if ((float) $reparacion->costo_mano_obra > 0) {
            $items[] = [
                'codigo'              => $libreId,
                'c_barra'             => 'VARIOS',
                'descripcion'         => "Servicio Técnico {$reparacion->codigo_orden} - {$reparacion->marca} {$reparacion->modelo}",
                'descripcion_libre'   => "Servicio Técnico {$reparacion->codigo_orden} - {$reparacion->marca} {$reparacion->modelo}",
                'es_libre'            => true,
                'cantidad'            => 1,
                'precio'              => (float) $reparacion->costo_mano_obra,
                'descuento'           => 0,
                'iva'                 => 10,
                'costo'               => 0,
                'subtotal'            => (float) $reparacion->costo_mano_obra,
                'stock_ya_descontado' => true,
            ];
        }

        // 2. Repuestos utilizados
        foreach ($reparacion->repuestos as $rep) {
            if ((float) $rep->precio_unitario <= 0 && $reparacion->es_reingreso_garantia) {
                continue;
            }
            $esLibreItem = $rep->es_libre || !$rep->ARTICULOS_cod || $rep->ARTICULOS_cod == $libreId;
            $items[] = [
                'codigo'              => $esLibreItem ? $libreId : $rep->ARTICULOS_cod,
                'c_barra'             => $rep->codigo_barra ?: 'REPUESTO',
                'descripcion'         => $rep->descripcion . " ({$reparacion->codigo_orden})",
                'descripcion_libre'   => $esLibreItem ? ($rep->descripcion . " ({$reparacion->codigo_orden})") : null,
                'es_libre'            => $esLibreItem,
                'cantidad'            => (float) $rep->cantidad,
                'precio'              => (float) $rep->precio_unitario,
                'descuento'           => 0,
                'iva'                 => (int) ($rep->iva ?: 10),
                'costo'               => (float) $rep->costo_unitario,
                'subtotal'            => (float) $rep->subtotal,
                'stock_ya_descontado' => (bool) $rep->stock_descontado,
            ];
        }

        if (empty($items) && (float) $reparacion->total_general > 0) {
            $items[] = [
                'codigo'              => $libreId,
                'c_barra'             => 'VARIOS',
                'descripcion'         => "Reparación de Celular {$reparacion->codigo_orden} ({$reparacion->marca} {$reparacion->modelo})",
                'descripcion_libre'   => "Reparación de Celular {$reparacion->codigo_orden} ({$reparacion->marca} {$reparacion->modelo})",
                'es_libre'            => true,
                'cantidad'            => 1,
                'precio'              => (float) $reparacion->total_general,
                'descuento'           => 0,
                'iva'                 => 10,
                'costo'               => (float) $reparacion->costo_repuestos,
                'subtotal'            => (float) $reparacion->total_general,
                'stock_ya_descontado' => true,
            ];
        }

        // Descuento total en POS = descuento de la orden + señas/adelantos ya abonados
        $descuentoPos = (float) $reparacion->descuento + (float) $reparacion->total_pagado;

        return response()->json([
            'success'     => true,
            'presupuesto' => [
                'pre_numero'    => $reparacion->codigo_orden,
                'reparacion_id' => $reparacion->id,
                'cliente'       => $reparacion->cliente,
                'condicion'     => 1,
                'descuento'     => $descuentoPos,
                'observaciones' => "Orden de Servicio Técnico {$reparacion->codigo_orden}",
            ],
            'items'       => $items,
        ]);
    }

    /**
     * Impresión de Orden de Recepción / Servicio Técnico (A4 y formato Ticket 80mm).
     */
    public function pdfOrden(Request $request, $id)
    {
        $reparacion = Reparacion::with([
            'cliente',
            'sucursal',
            'usuarioRecepcion',
            'tecnico',
            'repuestos',
            'imagenes',
            'pagos',
        ])->findOrFail($id);

        $empresa = Empresa::first();
        $config = ReparacionSettings::all();
        $formato = $request->get('formato', 'a4'); // a4 | ticket

        return view('pdf.reparacion_orden', compact('reparacion', 'empresa', 'config', 'formato'));
    }

    /**
     * Impresión de Etiqueta / Sticker adhesivo para pegar al dispositivo.
     */
    public function pdfEtiqueta($id)
    {
        $reparacion = Reparacion::with(['cliente', 'sucursal', 'tecnico'])->findOrFail($id);
        $empresa = Empresa::first();

        return view('pdf.reparacion_etiqueta', compact('reparacion', 'empresa'));
    }

    /**
     * Impresión de Certificado de Entrega y Garantía.
     */
    public function pdfGarantia($id)
    {
        $reparacion = Reparacion::with([
            'cliente',
            'sucursal',
            'usuarioRecepcion',
            'tecnico',
            'usuarioEntrega',
            'repuestos',
            'pagos',
            'garantias',
        ])->findOrFail($id);

        $empresa = Empresa::first();
        $config = ReparacionSettings::all();

        return view('pdf.reparacion_garantia', compact('reparacion', 'empresa', 'config'));
    }

    /**
     * Registra un ingreso en la caja abierta de la sucursal si existe.
     */
    protected function registrarIngresoCaja($sucCod, $monto, $concepto)
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
        $mov->mov_tipo        = 'Entrada';
        $mov->mov_monto       = $monto;
        $mov->nro_fact_ventas = '-';
        $mov->suc_cod         = $sucCod;
        $mov->save();

        return $apertura->nro_operacion;
    }
}
