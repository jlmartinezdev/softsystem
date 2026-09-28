<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Combo;
use App\ComboItem;
use App\Support\ArticuloCombo;
use App\Support\CodigoBarra;
use DB;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        ArticuloCombo::ensureExists();
        $combos = Combo::with(['items.articulo'])->orderBy('id', 'DESC')->get();

        return view('combo', compact('combos'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::beginTransaction();
        try {
            $combo = Combo::create([
                'nombre' => $data['nombre'],
                'codigo' => $data['codigo'],
                'precio' => $data['precio'],
                'precio_credito' => $data['precio_credito'],
                'precio_lista' => $data['precio_lista'],
                'activo' => $data['activo'],
                'observacion' => $data['observacion'],
            ]);

            $this->syncItems($combo, $data['items']);
            DB::commit();

            return response()->json([
                'ok' => true,
                'message' => 'Combo creado',
                'combo' => $combo->load('items.articulo'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo guardar: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);
        $data = $this->validated($request);

        DB::beginTransaction();
        try {
            $combo->update([
                'nombre' => $data['nombre'],
                'codigo' => $data['codigo'],
                'precio' => $data['precio'],
                'precio_credito' => $data['precio_credito'],
                'precio_lista' => $data['precio_lista'],
                'activo' => $data['activo'],
                'observacion' => $data['observacion'],
            ]);

            ComboItem::where('combo_id', $combo->id)->delete();
            $this->syncItems($combo, $data['items']);
            DB::commit();

            return response()->json([
                'ok' => true,
                'message' => 'Combo actualizado',
                'combo' => $combo->load('items.articulo'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo actualizar: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        $combo = Combo::findOrFail($id);
        $combo->delete();

        return response()->json(['ok' => true, 'message' => 'Combo eliminado']);
    }

    public function buscarArticulos(Request $request)
    {
        $q = trim((string) $request->input('buscar', ''));
        $query = Articulo::query()
            ->select(
                'ARTICULOS_cod',
                'producto_c_barra',
                'producto_nombre',
                'pre_venta1',
                'producto_costo_compra',
                'foto'
            )
            ->where('producto_c_barra', '!=', ArticuloCombo::CODIGO_BARRA)
            ->where('producto_c_barra', '!=', 'VARIOS');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('producto_nombre', 'LIKE', "%{$q}%")
                    ->orWhere('producto_c_barra', 'LIKE', "%{$q}%");
            });
        }

        return $query->orderBy('producto_nombre')->limit(40)->get();
    }

    public function listActivos(Request $request)
    {
        $suc = $request->input('suc');
        $combos = Combo::with(['items.articulo'])
            ->activos()
            ->orderBy('nombre')
            ->get();

        $result = [];
        foreach ($combos as $combo) {
            $componentes = [];
            $okStock = true;
            foreach ($combo->items as $item) {
                $art = $item->articulo;
                if (!$art) {
                    continue;
                }

                $stockQuery = DB::table('stock')->where('ARTICULOS_cod', $item->articulos_cod);
                if ($suc) {
                    $stockQuery->where('suc_cod', $suc);
                }
                $stockRow = $stockQuery->orderByDesc('cantidad')->first();

                $disponible = $stockRow ? (float) $stockRow->cantidad : 0;
                $necesario = (float) $item->cantidad;
                if ($disponible < $necesario) {
                    $okStock = false;
                }

                $componentes[] = [
                    'articulos_cod' => $item->articulos_cod,
                    'cantidad' => (float) $item->cantidad,
                    'precio_ref' => (float) $item->precio_ref,
                    'nombre' => $art->producto_nombre,
                    'id_stock' => $stockRow ? $stockRow->id_stock : null,
                    'stock' => $disponible,
                ];
            }

            $result[] = [
                'id' => $combo->id,
                'nombre' => $combo->nombre,
                'codigo' => $combo->codigo,
                'precio' => (float) $combo->precio,
                'precio_credito' => (float) ($combo->precio_credito ?? 0),
                'precio_lista' => (float) $combo->precio_lista,
                'items' => $componentes,
                'stock_ok' => $okStock,
            ];
        }

        return response()->json($result);
    }

    public function redondear(Request $request)
    {
        $monto = (float) $request->input('monto', 0);
        $multiplo = (int) $request->input('multiplo', 500);

        return response()->json([
            'precio' => ArticuloCombo::redondear($monto, $multiplo),
        ]);
    }

    public function validarCodigo(Request $request)
    {
        $except = [];
        if ($request->filled('id')) {
            $except['combo_id'] = (int) $request->input('id');
        }

        return response()->json(CodigoBarra::check($request->input('codigo', ''), $except));
    }

    private function validated(Request $request)
    {
        $nombre = trim((string) $request->input('nombre', ''));
        $items = $request->input('items', []);
        if ($nombre === '') {
            abort(response()->json(['ok' => false, 'message' => 'El nombre del combo es obligatorio.'], 422));
        }
        if (!is_array($items) || count($items) < 2) {
            abort(response()->json(['ok' => false, 'message' => 'Seleccioná al menos 2 artículos para el combo.'], 422));
        }

        $except = [];
        if ($request->route('id')) {
            $except['combo_id'] = (int) $request->route('id');
        }
        $codigo = CodigoBarra::validarOFallar($request->input('codigo', ''), $except);

        $precioLista = 0;
        $cleanItems = [];
        foreach ($items as $item) {
            $cod = (int) ($item['articulos_cod'] ?? 0);
            $cant = (float) ($item['cantidad'] ?? 0);
            $pref = (float) ($item['precio_ref'] ?? 0);
            if ($cod <= 0 || $cant <= 0) {
                continue;
            }
            $precioLista += $pref * $cant;
            $cleanItems[] = [
                'articulos_cod' => $cod,
                'cantidad' => $cant,
                'precio_ref' => $pref,
            ];
        }

        if (count($cleanItems) < 2) {
            abort(response()->json(['ok' => false, 'message' => 'Seleccioná al menos 2 artículos válidos.'], 422));
        }

        $precio = (float) $request->input('precio', 0);
        if ($precio <= 0) {
            $precio = $precioLista;
        }

        $precioCredito = (float) $request->input('precio_credito', 0);
        if ($precioCredito < 0) {
            $precioCredito = 0;
        }

        return [
            'nombre' => $nombre,
            'codigo' => $codigo,
            'precio' => $precio,
            'precio_credito' => $precioCredito,
            'precio_lista' => $precioLista,
            'activo' => $request->input('activo', 1) ? 1 : 0,
            'observacion' => trim((string) $request->input('observacion', '')),
            'items' => $cleanItems,
        ];
    }

    private function syncItems(Combo $combo, array $items)
    {
        foreach ($items as $item) {
            ComboItem::create([
                'combo_id' => $combo->id,
                'articulos_cod' => $item['articulos_cod'],
                'cantidad' => $item['cantidad'],
                'precio_ref' => $item['precio_ref'],
            ]);
        }
    }
}
