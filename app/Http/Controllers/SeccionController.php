<?php

namespace App\Http\Controllers;
use App\Seccion;
use App\Articulo;
use Illuminate\Http\Request;
use Auth;

class SeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:presentacion,open')->only(['index']);
        $this->middleware('permiso:presentacion,add')->only(['store']);
        $this->middleware('permiso:presentacion,edit')->only(['update']);
        $this->middleware('permiso:presentacion,del')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($this->All());
        }
        $secciones = $this->All();
        return view('seccion', compact('secciones'));
    }

    public function All()
    {
        return Seccion::withCount('articulos')->orderBy('present_descripcion', 'ASC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:100',
            'iva' => 'required|in:0,5,10',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'iva.required' => 'El tipo de impuesto es obligatorio.',
        ]);

        $seccion = new Seccion();
        $seccion->present_descripcion = strtoupper(trim($request->descripcion));
        $seccion->iva = $request->iva;
        $seccion->save();

        $seccion->articulos_count = 0;

        return response()->json([
            'status' => 'OK',
            'message' => 'Sección creada correctamente.',
            'seccion' => $seccion
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:100',
            'iva' => 'required|in:0,5,10',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'iva.required' => 'El tipo de impuesto es obligatorio.',
        ]);

        $seccion = Seccion::findOrFail($id);
        $seccion->present_descripcion = strtoupper(trim($request->descripcion));
        $seccion->iva = $request->iva;
        $seccion->save();

        $seccion->loadCount('articulos');

        return response()->json([
            'status' => 'OK',
            'message' => 'Sección actualizada correctamente.',
            'seccion' => $seccion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articulosCount = Articulo::where('present_cod', $id)->count();
        if ($articulosCount > 0) {
            return response()->json([
                'status' => 'ERROR',
                'message' => "No se puede eliminar la sección porque tiene {$articulosCount} artículo(s) vinculado(s). Debe reasignar o desvincular los artículos antes de eliminarla."
            ], 422);
        }

        $seccion = Seccion::find($id);
        if (!$seccion) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'La sección no existe o ya fue eliminada.'
            ], 404);
        }

        $seccion->delete();

        return response()->json([
            'status' => 'OK',
            'message' => 'Sección eliminada con éxito.'
        ]);
    }
}

