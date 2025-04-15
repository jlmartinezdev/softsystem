<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function all(){
        return Unidad::all();
    }
    public function index()
    {
        
        $unidades = Unidad::all();
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'uni_nombre' => 'required|string|max:255',
            'uni_abreviatura' => 'required|string|max:10'
        ]);

        Unidad::create($request->all());

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad creada exitosamente.');
    }

    public function edit(Unidad $unidad)
    {
        return view('unidades.edit', compact('unidad'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $request->validate([
            'uni_nombre' => 'required|string|max:255',
            'uni_abreviatura' => 'required|string|max:10'
        ]);

        $unidad->update($request->all());

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad actualizada exitosamente.');
    }

    public function destroy(Unidad $unidad)
    {
        $unidad->delete();

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad eliminada exitosamente.');
    }
}
