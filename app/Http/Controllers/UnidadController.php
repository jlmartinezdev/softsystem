<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Articulo;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:unidad,open')->only(['index']);
        $this->middleware('permiso:unidad,add')->only(['create', 'store']);
        $this->middleware('permiso:unidad,edit')->only(['edit', 'update']);
        $this->middleware('permiso:unidad,del')->only(['destroy']);
    }

    public function all()
    {
        return Unidad::withCount('articulos')->orderBy('uni_nombre', 'ASC')->get();
    }

    public function index(Request $request)
    {
        $unidades = $this->all();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($unidades);
        }
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'uni_nombre' => 'required|string|max:100',
            'uni_abreviatura' => 'required|string|max:10'
        ], [
            'uni_nombre.required' => 'El nombre de la unidad es obligatorio.',
            'uni_abreviatura.required' => 'La abreviatura es obligatoria.',
        ]);

        $unidad = new Unidad();
        $unidad->uni_nombre = strtoupper(trim($request->uni_nombre));
        $unidad->uni_abreviatura = strtoupper(trim($request->uni_abreviatura));
        $unidad->save();

        $unidad->articulos_count = 0;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Unidad de medida creada exitosamente.',
                'unidad' => $unidad
            ]);
        }

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad creada exitosamente.');
    }

    public function edit(Unidad $unidad)
    {
        return view('unidades.edit', compact('unidad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uni_nombre' => 'required|string|max:100',
            'uni_abreviatura' => 'required|string|max:10'
        ], [
            'uni_nombre.required' => 'El nombre de la unidad es obligatorio.',
            'uni_abreviatura.required' => 'La abreviatura es obligatoria.',
        ]);

        $unidad = $id instanceof Unidad ? $id : Unidad::findOrFail($id);
        $unidad->uni_nombre = strtoupper(trim($request->uni_nombre));
        $unidad->uni_abreviatura = strtoupper(trim($request->uni_abreviatura));
        $unidad->save();

        $unidad->loadCount('articulos');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Unidad de medida actualizada exitosamente.',
                'unidad' => $unidad
            ]);
        }

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad actualizada exitosamente.');
    }

    public function destroy(Request $request, $id)
    {
        $unidad = $id instanceof Unidad ? $id : Unidad::find($id);
        $codigo = $unidad ? $unidad->uni_codigo : $id;

        $articulosCount = Articulo::where('uni_codigo', $codigo)->count();
        if ($articulosCount > 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => "No se puede eliminar la unidad porque tiene {$articulosCount} artículo(s) vinculado(s). Debe reasignar o desvincular los artículos antes de eliminarla."
                ], 422);
            }
            return redirect()->route('unidades.index')
                ->with('error', "No se puede eliminar la unidad porque tiene {$articulosCount} artículo(s) vinculado(s).");
        }

        if (!$unidad) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'La unidad no existe o ya fue eliminada.'
                ], 404);
            }
            return redirect()->route('unidades.index')
                ->with('error', 'La unidad no existe o ya fue eliminada.');
        }

        $unidad->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Unidad de medida eliminada exitosamente.'
            ]);
        }

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad eliminada exitosamente.');
    }
}
