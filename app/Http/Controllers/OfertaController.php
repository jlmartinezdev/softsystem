<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Oferta;
use App\Support\CodigoBarra;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ofertas = Oferta::with('articulo')->orderBy('id', 'DESC')->get();
        return view('oferta', compact('ofertas'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $oferta = Oferta::create($data);

        return response()->json([
            'ok' => true,
            'message' => 'Oferta creada',
            'oferta' => $oferta->load('articulo'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $oferta = Oferta::findOrFail($id);
        $data = $this->validated($request);
        $oferta->update($data);

        return response()->json([
            'ok' => true,
            'message' => 'Oferta actualizada',
            'oferta' => $oferta->load('articulo'),
        ]);
    }

    public function destroy($id)
    {
        Oferta::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Oferta eliminada']);
    }

    public function buscarArticulos(Request $request)
    {
        $q = trim((string) $request->input('buscar', ''));
        $query = Articulo::query()
            ->select('ARTICULOS_cod', 'producto_c_barra', 'producto_nombre', 'pre_venta1')
            ->where('producto_c_barra', '!=', 'VARIOS')
            ->where('producto_c_barra', '!=', 'COMBO');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('producto_nombre', 'LIKE', "%{$q}%")
                    ->orWhere('producto_c_barra', 'LIKE', "%{$q}%");
            });
        }

        return $query->orderBy('producto_nombre')->limit(40)->get();
    }

    /**
     * Ofertas activas para aplicar en venta.
     */
    public function activas(Request $request)
    {
        $ofertas = Oferta::with('articulo')->activas()->orderBy('id', 'DESC')->get();

        $out = [];
        foreach ($ofertas as $o) {
            $out[] = [
                'id' => $o->id,
                'nombre' => $o->nombre,
                'codigo' => $o->codigo,
                'articulos_cod' => (int) $o->articulos_cod,
                'tipo' => $o->tipo,
                'cantidad_min' => $o->cantidad_min !== null ? (float) $o->cantidad_min : null,
                'fecha_desde' => $o->fecha_desde,
                'fecha_hasta' => $o->fecha_hasta,
                'descuento_tipo' => $o->descuento_tipo,
                'descuento_valor' => (float) $o->descuento_valor,
                'articulo_nombre' => optional($o->articulo)->producto_nombre,
                'articulo_precio' => optional($o->articulo)->pre_venta1,
            ];
        }

        return response()->json($out);
    }

    public function validarCodigo(Request $request)
    {
        $except = [];
        if ($request->filled('id')) {
            $except['oferta_id'] = (int) $request->input('id');
        }

        return response()->json(CodigoBarra::check($request->input('codigo', ''), $except));
    }

    private function validated(Request $request)
    {
        $nombre = trim((string) $request->input('nombre', ''));
        $articulosCod = (int) $request->input('articulos_cod', 0);
        $tipo = (string) $request->input('tipo', 'cantidad');
        $descuentoTipo = (string) $request->input('descuento_tipo', 'porcentaje');
        $descuentoValor = (float) $request->input('descuento_valor', 0);

        if ($nombre === '') {
            abort(response()->json(['ok' => false, 'message' => 'El nombre es obligatorio.'], 422));
        }
        if ($articulosCod <= 0) {
            abort(response()->json(['ok' => false, 'message' => 'Seleccioná un artículo.'], 422));
        }
        if (!in_array($tipo, ['cantidad', 'fecha', 'ambos'], true)) {
            abort(response()->json(['ok' => false, 'message' => 'Tipo de oferta inválido.'], 422));
        }
        if (!in_array($descuentoTipo, ['porcentaje', 'monto', 'precio_fijo'], true)) {
            abort(response()->json(['ok' => false, 'message' => 'Tipo de descuento inválido.'], 422));
        }
        if ($descuentoValor < 0) {
            abort(response()->json(['ok' => false, 'message' => 'El descuento no puede ser negativo.'], 422));
        }
        if ($descuentoTipo === 'porcentaje' && $descuentoValor > 100) {
            abort(response()->json(['ok' => false, 'message' => 'El porcentaje no puede superar 100.'], 422));
        }

        $except = [];
        if ($request->route('id')) {
            $except['oferta_id'] = (int) $request->route('id');
        }
        $codigo = CodigoBarra::validarOFallar($request->input('codigo', ''), $except);

        $cantidadMin = $request->input('cantidad_min');
        $fechaDesde = $request->input('fecha_desde') ?: null;
        $fechaHasta = $request->input('fecha_hasta') ?: null;

        if ($tipo === 'cantidad' || $tipo === 'ambos') {
            if ($cantidadMin === null || (float) $cantidadMin <= 0) {
                abort(response()->json(['ok' => false, 'message' => 'Indicá la cantidad mínima.'], 422));
            }
        } else {
            $cantidadMin = null;
        }

        if ($tipo === 'fecha' || $tipo === 'ambos') {
            if (!$fechaDesde && !$fechaHasta) {
                abort(response()->json(['ok' => false, 'message' => 'Indicá al menos una fecha (desde o hasta).'], 422));
            }
            if ($fechaDesde && $fechaHasta && $fechaDesde > $fechaHasta) {
                abort(response()->json(['ok' => false, 'message' => 'La fecha desde no puede ser mayor que hasta.'], 422));
            }
        } else {
            $fechaDesde = null;
            $fechaHasta = null;
        }

        return [
            'nombre' => $nombre,
            'codigo' => $codigo,
            'articulos_cod' => $articulosCod,
            'tipo' => $tipo,
            'cantidad_min' => $cantidadMin,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'descuento_tipo' => $descuentoTipo,
            'descuento_valor' => $descuentoValor,
            'activo' => $request->input('activo', 1) ? 1 : 0,
            'observacion' => trim((string) $request->input('observacion', '')),
        ];
    }
}
