<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Apertura;
use App\Sucursal;
use App\MovimientoCaja;
use App\Empresa;
use App\CtaCobrar;
use App\Cobro;
use App\Stock;
use DB;
use Auth;
use PDF;
use App\Support\ArticuloLibre;
use App\Support\ArticuloCombo;
use App\Services\SifenService;
class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:ventas,open')->only(['index']);
        $this->middleware('permiso:ventas,add')->only(['store']);
        $this->middleware('permiso:inf_venta,open')->only(['indexInf', 'getVentaByFecha', 'getVentaByCliente', 'getVentaChart', 'getVentaArticulo']);
        $this->middleware('permiso:inf_venta,export')->only(['imprimir', 'pdfboleta', 'pdfrecibo', 'ticket']);
        $this->middleware('permiso:anular_venta,open')->only(['indexanular']);
        $this->middleware('permiso:anular_venta,del')->only(['destroy']);
    }
    public function index()
    {
        $apertura=Apertura::join('sucursales','apert_cierres_caja.suc_cod','=','sucursales.suc_cod')->join('caja','apert_cierres_caja.caja_cod','=','caja.caja_cod')
        ->where('apert_cierres_caja.apert_fecha','=',date('Y-m-d'))->get();
        $articuloLibreId = ArticuloLibre::id();
        $sifenService = app(SifenService::class);
        $sifenConfig = $sifenService->config();
        $sifenFaltantes = $sifenService->validarConfig();
        $sifenActivo = (bool) $sifenConfig->activo;
        $sifenListo = $sifenActivo && count($sifenFaltantes) === 0;
        $sifenAmbiente = $sifenConfig->ambiente === 'prod' ? 'prod' : 'test';
        $sifenFalta = $sifenFaltantes[0] ?? null;
        $esAdministrador = Auth::user()->esAdministrador();
        $empresa = Empresa::first();
        $nombreLocal = trim((string) ($empresa->emp_nombre ?? ''));
        $localIncompleto = $nombreLocal === '' || mb_strtoupper($nombreLocal, 'UTF-8') === 'EMPRESA';

        return view('venta', compact(
            'apertura',
            'articuloLibreId',
            'sifenActivo',
            'sifenListo',
            'sifenAmbiente',
            'sifenFalta',
            'esAdministrador',
            'nombreLocal',
            'localIncompleto'
        ));
    }
    public function indexanular(){
        $ultimasVentas = Venta::join('clientes as c', 'ventas.clientes_cod', '=', 'c.clientes_cod')
            ->leftJoin('sucursales as s', 'ventas.suc_cod', '=', 's.suc_cod')
            ->select(
                'ventas.nro_fact_ventas',
                'ventas.venta_total',
                DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y %H:%i") AS fecha'),
                'c.cliente_nombre',
                'c.cliente_ruc',
                'ventas.documento',
                'ventas.tipo_factura',
                's.suc_desc'
            )
            ->orderBy('ventas.nro_fact_ventas', 'desc')
            ->limit(8)
            ->get();

        return view('anularventa', compact('ultimasVentas'));
    }
    public function indexInf(){
        $sucursales= Sucursal::all();
        return view('informes.venta',compact('sucursales'));
    }
    public function getVentaByFecha(Request $request){
        return Venta::select(
            'ventas.nro_fact_ventas',
            'ventas.venta_descuento',
            'ventas.documento',
            'ventas.venta_total',
            DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y %H:%i") AS fecha'),
            'c.cliente_nombre',
            'c.cliente_direccion',
            'c.cliente_ruc',
            's.suc_desc',
            'ventas.tipo_factura',
            'u.nom_usuarios as vendedor'
        )
        ->join('clientes as c','ventas.clientes_cod','=','c.clientes_cod')
        ->join('sucursales as s','ventas.suc_cod','=','s.suc_cod')
        ->leftJoin('usuarios as u','ventas.cod_usuarios','=','u.cod_usuarios')
        ->filtrofecha($request->alld,$request->allh)
        ->filtrosuc($request->alls)
        ->orderBy('ventas.nro_fact_ventas','desc')
        ->get();
    }

    public function getVentaByCliente(Request $request){
        return Venta::select(
            'ventas.nro_fact_ventas',
            'ventas.documento',
            'ventas.venta_descuento',
            'ventas.venta_total',
            DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y %H:%i") AS fecha'),
            'c.cliente_nombre',
            'c.cliente_direccion',
            'c.cliente_cel',
            'c.cliente_ruc',
            's.suc_desc',
            'ventas.tipo_factura',
            'u.nom_usuarios as vendedor'
        )
        ->join('clientes as c','ventas.clientes_cod','=','c.clientes_cod')
        ->join('sucursales as s','ventas.suc_cod','=','s.suc_cod')
        ->leftJoin('usuarios as u','ventas.cod_usuarios','=','u.cod_usuarios')
        ->filtrocliente($request->cliente,$request->isNumber)
        ->filtrosuc($request->alls)
        ->orderBy('ventas.nro_fact_ventas','desc')
        ->limit(100)
        ->get();
    }

    public function getVentaArticulo(Request $request){
        $desde = $request->artd ?: date('Y-m-01');
        $hasta = $request->arth ?: date('Y-m-d');
        $sucursal = ($request->arts && $request->arts != '0') ? $request->arts : null;

        $query = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'dv.nro_fact_ventas', '=', 'v.nro_fact_ventas')
            ->join('articulos as a', 'dv.ARTICULOS_cod', '=', 'a.ARTICULOS_cod')
            ->leftJoin('presentacion as p', 'a.present_cod', '=', 'p.present_cod')
            ->select(
                'dv.ARTICULOS_cod',
                'a.producto_c_barra',
                'a.producto_nombre',
                'p.present_descripcion',
                DB::raw('SUM(dv.venta_cantidad) AS vendida'),
                DB::raw('SUM(dv.venta_cantidad * dv.venta_precio) AS monto_total')
            )
            ->whereBetween(DB::raw('DATE(v.venta_fecha)'), [$desde, $hasta]);

        if ($sucursal) {
            $query->where('v.suc_cod', $sucursal);
        }

        $articulos = $query->groupBy(
            'dv.ARTICULOS_cod',
            'a.producto_c_barra',
            'a.producto_nombre',
            'p.present_descripcion'
        )->orderByDesc('vendida')->get();

        $artCodigos = $articulos->pluck('ARTICULOS_cod')->all();
        $stockMap = [];
        if (!empty($artCodigos)) {
            $stockQuery = DB::table('stock')->whereIn('ARTICULOS_cod', $artCodigos);
            if ($sucursal) {
                $stockQuery->where('suc_cod', $sucursal);
            }
            $stockRows = $stockQuery->select('ARTICULOS_cod', DB::raw('SUM(cantidad) as total_stock'))
                ->groupBy('ARTICULOS_cod')
                ->get();

            foreach ($stockRows as $sr) {
                $stockMap[$sr->ARTICULOS_cod] = (float) $sr->total_stock;
            }
        }

        foreach ($articulos as $art) {
            $art->cantidad = $stockMap[$art->ARTICULOS_cod] ?? 0;
            $art->vendida = (float) $art->vendida;
            $art->monto_total = (float) ($art->monto_total ?? 0);
        }

        return $articulos;
    }

    public function getDetalle($nro_venta){
        return DB::select(
            'SELECT dv.*,
                    COALESCE(NULLIF(TRIM(dv.descripcion_libre), \'\'), a.producto_nombre) AS producto_nombre,
                    a.producto_c_barra,
                    p.iva
             FROM detalle_venta dv
             INNER JOIN articulos a ON dv.ARTICULOS_cod = a.ARTICULOS_cod
             INNER JOIN presentacion p ON a.present_cod = p.present_cod
             WHERE dv.nro_fact_ventas = ?',
            [$nro_venta]
        );
    }

    public function getCabecera($nro_venta){
        $cabecera = DB::select(
            'SELECT v.*, c.cliente_ci, c.cliente_nombre, c.cliente_ruc, c.cliente_cel, c.cliente_direccion, s.suc_desc, u.nom_usuarios as vendedor
             FROM ventas v 
             INNER JOIN clientes c ON v.CLIENTES_cod = c.CLIENTES_cod 
             LEFT JOIN sucursales s ON v.suc_cod = s.suc_cod
             LEFT JOIN usuarios u ON v.cod_usuarios = u.cod_usuarios
             WHERE v.nro_fact_ventas = ?',
            [$nro_venta]
        );
        return ["venta" => $cabecera, "detalle" => $this->getDetalle($nro_venta)];
    }

    public function getVentaChart(Request $request){
        $anho = !empty($request->chart['anho']) ? $request->chart['anho'] : date('Y');
        $mes = !empty($request->chart['mes']) ? $request->chart['mes'] : '1';
        $sucursal = !empty($request->chart['sucursal']) ? $request->chart['sucursal'] : 0;

        $query = Venta::select(
            DB::raw("SUM(ventas.venta_total) AS total"),
            DB::raw("DATE_FORMAT(ventas.venta_fecha,'%Y-%m-%d') AS fecha"),
            DB::raw("COUNT(ventas.nro_fact_ventas) AS cantidad")
        )->filtrochart($mes, $anho);

        if ($sucursal != '0' && !empty($sucursal)) {
            $query->where('ventas.suc_cod', $sucursal);
        }

        return $query->groupBy(DB::raw("DATE(ventas.venta_fecha)"))
            ->orderBy(DB::raw("DATE(ventas.venta_fecha)"))
            ->get();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cab = $request->input('ventaCabecera', []);
        if (!is_array($cab) || empty($cab['idSucursal'])) {
            return response()->json([
                'message' => 'Sucursal no definida. Seleccioná una sucursal e intentá de nuevo.',
            ], 422);
        }

        $bloqueoFactura = $this->bloqueoFacturaSifen($cab);
        if ($bloqueoFactura) {
            return response()->json([
                'message' => $bloqueoFactura,
            ], 422);
        }

        // 1. Validar autorización para venta a crédito
        $esCredito = isset($cab['condicionventa']) && (string)$cab['condicionventa'] !== '1';
        if ($esCredito && !Auth::user()->tieneAccion('venta_autorizar_credito')) {
            return response()->json([
                'message' => 'No cuenta con autorización para realizar ventas a crédito.',
            ], 403);
        }

        // 2. Validar autorización para otorgar descuentos
        $montoDescuento = (float)($cab['descuento'] ?? 0);
        if ($montoDescuento > 0 && !Auth::user()->tieneAccion('venta_descuento')) {
            return response()->json([
                'message' => 'No cuenta con autorización para aplicar descuentos en la venta.',
            ], 403);
        }

        // 3. Validar autorización para venta sin stock suficiente
        if (!Auth::user()->tieneAccion('venta_sin_stock') && is_array($request->detalle)) {
            foreach ($request->detalle as $det) {
                if (empty($det['es_libre']) && empty($det['es_combo']) && !empty($det['idstock'])) {
                    $cantDisp = DB::table('stock')->where('id_stock', $det['idstock'])->value('cantidad');
                    $cantPedida = (float)($det['cantidad'] ?? 1);
                    if ($cantDisp !== null && (float)$cantDisp < $cantPedida) {
                        $nombreArt = $det['descripcion'] ?? 'Artículo';
                        return response()->json([
                            'message' => "Stock insuficiente ({$cantDisp} disponible) para '{$nombreArt}'. Su rol no tiene autorización para vender sin stock.",
                        ], 403);
                    }
                }
            }
        }

        $venta = new Venta();
        $venta->clientes_cod= $cab['clienteId'] ?? 1;
        $venta->cod_usuarios= Auth::user()->cod_usuarios;
        $venta->suc_cod= $cab['idSucursal'];
        $venta->venta_total= $cab['total'] ?? 0;
        $venta->venta_fecha = ($cab['fecha'] ?? date('Y-m-d')).date(' H:i');
        $venta->tipo_factura = $cab['condicionventa'] ?? 1;
        $venta->cant_cuotas = (!empty($request->cuotas) && is_array($request->cuotas)) ? count($request->cuotas) : 0;
        $venta->intervalo_venc='2030-01-01'; 
        $venta->venta_estado='2'; 
        $venta->venta_descuento=$cab['descuento'] ?? 0; 
        $venta->forma_cobro= $cab['formacobro'] ?? 1; 
        $venta->documento= $cab['documento'] ?? 'Ticket'; 
        $venta->venta_recibido = (float) $request->input('venta_recibido', 0);
        $venta->venta_vuelto = (float) $request->input('venta_vuelto', 0);
        $venta->save();
        if(($cab['condicionventa'] ?? 1)=='1' || ($cab['condicionventa'] ?? 1)==1){
            if(Auth::user()->cod_usuarios!= 1){
                $this->storeMovimiento($cab['idSucursal'],$cab['nro_operacion'] ?? 0,[$venta->nro_fact_ventas, $venta->venta_total]);
            }
        }else{
            foreach($request->cuotas as $cuota){
                
                $this->storeCtaCobrar($venta->nro_fact_ventas,$cuota);
                if($cuota['tipo']=='Entrega'){
                    $this->storeCobro($cab,$venta->nro_fact_ventas, $cuota);
                    if(Auth::user()->cod_usuarios!= 1){
                        $this->storeMovimiento($cab['idSucursal'],$cab['nro_operacion'] ?? 0,[$venta->nro_fact_ventas,$cuota['monto']]);
                    }
                }
            }
        }
        
        foreach ($request->detalle as $detalle) {
            $esLibre = !empty($detalle['es_libre']);
            $esCombo = !empty($detalle['es_combo']);
            $codigo = $esLibre || $esCombo
                ? ($esCombo ? ArticuloCombo::id() : ArticuloLibre::id())
                : $detalle['codigo'];
            $descripcionLibre = ($esLibre || $esCombo)
                ? trim((string) ($detalle['descripcion_libre'] ?? $detalle['descripcion'] ?? ''))
                : null;

            if (($esLibre || $esCombo) && $descripcionLibre === '') {
                continue;
            }

            DB::insert(
                'INSERT INTO detalle_venta (ARTICULOS_cod, nro_fact_ventas, venta_precio, venta_cantidad, precio_compra, descripcion_libre) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $codigo,
                    $venta->nro_fact_ventas,
                    $detalle['precio'],
                    $detalle['cantidad'],
                    ($esLibre || $esCombo) ? 0 : ($detalle['costo'] ?? 0),
                    $descripcionLibre,
                ]
            );

            if (($cab['descontar_stock'] ?? 1) == 1) {
                if ($esCombo && !empty($detalle['componentes']) && is_array($detalle['componentes'])) {
                    $cantCombo = (float) ($detalle['cantidad'] || 1);
                    foreach ($detalle['componentes'] as $comp) {
                        if (empty($comp['id_stock'])) {
                            continue;
                        }
                        $cantComp = ((float) ($comp['cantidad'] ?? 1)) * $cantCombo;
                        DB::update(
                            'update stock set cantidad = (cantidad - ?) where id_stock=?',
                            [$cantComp, $comp['id_stock']]
                        );
                    }
                } elseif (!$esLibre && !$esCombo && !empty($detalle['idstock'])) {
                    DB::update(
                        'update stock set cantidad = (cantidad - ?) where id_stock=?',
                        [$detalle['cantidad'], $detalle['idstock']]
                    );
                }
            }
        }
        return $venta->nro_fact_ventas;
        
    }
    public function destroy(Request $request){
        $venta = Venta::find($request->id);
        if (!$venta) {
            return response()->json(['ok' => false, 'message' => 'Venta no encontrada.'], 404);
        }
        $sucursalId = $venta->suc_cod;

        // Reponer stock leyendo directamente de detalle_venta
        $detalles = DB::table('detalle_venta')->where('nro_fact_ventas', $request->id)->get();
        if ($detalles->count() > 0) {
            foreach ($detalles as $det) {
                if (ArticuloLibre::esLibre($det->ARTICULOS_cod) || ArticuloCombo::esCombo($det->ARTICULOS_cod)) {
                    continue;
                }
                try {
                    $query = Stock::where('ARTICULOS_cod', $det->ARTICULOS_cod);
                    if ($sucursalId) {
                        $stock = (clone $query)->where('suc_cod', $sucursalId)->first();
                        if (!$stock) {
                            $stock = $query->first();
                        }
                    } else {
                        $stock = $query->first();
                    }
                    if ($stock) {
                        $stock->increment('cantidad', (float)$det->venta_cantidad);
                    }
                } catch (\Throwable $error) {
                    // ignore stock restore errors
                }
            }
        } elseif ($request->has('articulos') && is_array($request->articulos)) {
            foreach ($request->articulos as $articulo) {
                if (ArticuloLibre::esLibre($articulo['id']) || ArticuloCombo::esCombo($articulo['id'])) {
                    continue;
                }
                try {
                    $query = Stock::where('ARTICULOS_cod', $articulo['id']);
                    if ($sucursalId) {
                        $stock = (clone $query)->where('suc_cod', $sucursalId)->first();
                        if (!$stock) {
                            $stock = $query->first();
                        }
                    } else {
                        $stock = $query->first();
                    }
                    if ($stock) {
                        $stock->increment('cantidad', $articulo['cantidad']);
                    }
                } catch (\Throwable $error) {
                    // ignore stock restore errors
                }
            }
        }

        // Anular documento electrónico SIFEN si existiese
        try {
            $sifen = app(SifenService::class);
            if ($sifen->isActivo()) {
                $sifen->anularDocumento($request->id);
            }
        } catch (\Throwable $e) {
            // Ignorar si no existe documento SIFEN o ya fue anulado
        }

        // Limpiar movimientos de caja asociados a esta venta
        DB::table('movimiento_caja')->where('nro_fact_ventas', $request->id)->delete();
        DB::table('cobranza_detalle')->where('nro_fact_ventas', $request->id)->delete();
        DB::table('cobranzas as c')->join('cobranza_detalle as cd', 'c.cc_numero', '=', 'cd.cc_numero')
            ->where('cd.nro_fact_ventas', $request->id)->delete();
        DB::table('ctas_cobrar')->where('nro_fact_ventas', $request->id)->delete();
        DB::table('detalle_venta')->where('nro_fact_ventas', $request->id)->delete();
        DB::table('ventas')->where('nro_fact_ventas', $request->id)->delete();

        return response()->json(['ok' => true, 'message' => 'Venta anulada correctamente. El stock ha sido repuesto.']);
    }

    private function storeCtaCobrar($idventa, $cuota){
        $CtaCobrar= new CtaCobrar();
        $CtaCobrar->nro_cuotas = $cuota['nro'];
        $CtaCobrar->nro_fact_ventas= $idventa;
        $CtaCobrar->monto_cuota= $cuota['monto'];
        $CtaCobrar->monto_cobrado= $cuota['tipo']=="Entrega" ? $cuota['monto'] : 0;
        $CtaCobrar->monto_saldo = $cuota['tipo']== "Entrega" ? 0 : $cuota['monto'];
        $CtaCobrar->fecha_venc= $this->formatFecha($cuota['vencimiento']);
        $CtaCobrar->estado= $cuota['tipo']=="Entrega" ? '0' : '1';
        $CtaCobrar->interes = $cuota['interes'] ;
        $CtaCobrar->save();
        
    }
    private function storeCobro($ventaCabecera,$idventa,$cuota){
        $ultimo= Cobro::orderBy('cc_numero','DESC')->first();
        if ($ultimo) {
            $recibo= $this->reciboUp([$ultimo->recibon1,$ultimo->recibon2,$ultimo->nro_recibo]);
        } else {
            $recibo= $this->reciboUp([1,1,1]);
        }
        $cobro = new Cobro();
        $cobro->nro_operacion = $ventaCabecera['nro_operacion'];
        $cobro->suc_cod = $ventaCabecera['idSucursal'];
        $cobro->cob_fecha = $ventaCabecera['fecha'];
        $cobro->recibon1 = $recibo[0];
        $cobro->recibon2 = $recibo[1];
        $cobro->nro_recibo = $recibo[2];
        $cobro->cob_importe= $cuota['monto'];
        $cobro->estado = "N";
        $cobro->save();

        DB::insert("INSERT INTO cobranza_detalle (cc_numero, nro_fact_ventas, nro_cuotas, importe, cobrado, tipo) VALUES (?,?,?,?,?,?)",[$cobro->cc_numero,$idventa,$cuota['nro'],$cuota['monto'],$cuota['monto'],'1']);

    }
    private function reciboUp($numeros){
        
        $n1 = $numeros[0];
        $n2= str_pad($numeros[1],3,"0",STR_PAD_LEFT);
        $recibo = str_pad($numeros[2],7,"0",STR_PAD_LEFT);
        $nrofinal = ($n1.$n2.$recibo) + 1;
        $strfinal = strval($nrofinal);
        $l= strlen($nrofinal);
        if($l < 7){
            return ["001","001", str_pad($strfinal,7,"0",STR_PAD_LEFT)];
        }else{
            $recibo =substr($strfinal, -7,7);
            if (($l-7)>3){
                $n2= substr($strfinal,-10,3);
                $n1= str_pad(substr($strfinal,0,$l-10),3,"0",STR_PAD_LEFT);
            }else{
                $n2= str_pad(substr($strfinal,0,$l-7),3,"0",STR_PAD_LEFT);
                $n1="000";
            }

        }
        return [$n1,$n2,$recibo];
    }
    private function formatFecha($fecha){
        if (empty($fecha)) {
            return date('Y-m-d');
        }
        $fecha = str_replace('/', '-', trim((string) $fecha));
        $array_fecha = explode("-", $fecha);
        if (count($array_fecha) === 3) {
            if (strlen($array_fecha[0]) === 4) {
                return $fecha; // Already YYYY-MM-DD
            }
            return $array_fecha[2] . "-" . $array_fecha[1] . "-" . $array_fecha[0];
        }
        return date('Y-m-d');
    }

    private function bloqueoFacturaSifen(array $cab)
    {
        $documento = $cab['documento'] ?? 'Ticket';
        if ($documento !== 'Factura') {
            return null;
        }

        $sifen = app(SifenService::class);
        $config = $sifen->config();
        $faltantes = $sifen->validarConfig();
        if (!$config->activo) {
            return 'Factura electrónica apagada. Activá SIFEN para emitir factura.';
        }
        if (count($faltantes)) {
            return 'Falta '.$faltantes[0].' para facturar.';
        }

        $clienteId = (int) ($cab['clienteId'] ?? 1);
        $cliente = DB::table('clientes')->where('CLIENTES_cod', $clienteId)->first();
        $nombre = $cliente ? (string) $cliente->cliente_nombre : '';
        $ruc = $cliente ? trim((string) ($cliente->cliente_ruc ?? '')) : '';
        $esOcasional = $clienteId === 1 || stripos($nombre, 'ocasional') !== false;
        if ($esOcasional || $ruc === '' || $ruc === '0') {
            return 'Elegí un cliente con RUC para factura electrónica.';
        }

        return null;
    }
    private function storeMovimiento($idSucursal,$ope, $datos ){
        $movimiento= new MovimientoCaja();
        $movimiento->nro_operacion= $ope;
        $movimiento->mov_fecha= date('Y-m-d H:i');
        $movimiento->mov_concepto= 'Venta Nº: '.$datos[0];
        $movimiento->mov_tipo= 'Entrada';
        $movimiento->mov_monto= $datos[1];
        $movimiento->nro_fact_ventas= $datos[0];
        $movimiento->suc_cod= $idSucursal;
        $movimiento->save();
        
    }
    public function pdfboleta($id){
       
        $venta =  Venta::join('clientes','clientes.CLIENTES_cod','=','ventas.CLIENTES_cod')->where('ventas.nro_fact_ventas',$id)->first();
        $detalle= $this->getDetalle($id);
        $empresa= Empresa::first();
        //$pdf= PDF::loadView('pdf.venta',compact('venta','detalle','empresa'));
        // return $pdf->stream();
        return view('pdf.venta',compact('venta','detalle','empresa'));
    }
   public function ticketfactura(){
       $empresa= Empresa::first();
       return view('ticket.factura',compact('empresa'));
   }
   public function ticket($id){
    $venta= $this->getCabecera($id);
    $empresa= Empresa::first();
    //return $venta;
    return view('ticket.venta',compact('venta','empresa'));
   }
   public function imprimir(){
       $sifenActivo = app(\App\Services\SifenService::class)->isActivo();
       return view('venta.imprimir', compact('sifenActivo'));
   }
   
}
