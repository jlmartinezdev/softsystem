<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\Ciudad;
use Auth;
use DB;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ciudades = Ciudad::orderBy('ciudad_nombre', 'ASC')->get();
        $proveedores = $this->getAll();
        return view('proveedor', compact('proveedores', 'ciudades'));
    }
 
    public function getAll()
    {
        return Proveedor::orderBy('proveedor_nombre', 'ASC')->get();
    }

    public function buscar(Request $request)
    {
        $proveedor = Proveedor::select('PROVEEDOR_cod', 'proveedor_ruc', 'proveedor_nombre', 'proveedor_direc', 'proveedor_telef')
            ->nombre(strtoupper($request->nombre))
            ->orderBy('proveedor_nombre', 'ASC')
            ->limit(100)
            ->get();
        return $proveedor;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        $ultimo = $this->ultimo();
        $ultimoCod = is_null($ultimo) ? 0 : (int)$ultimo->PROVEEDOR_cod;

        $p = new Proveedor();
        $p->PROVEEDOR_cod = $ultimoCod + 1;
        $p->nacio_cod = $request->idnacionalidad ?: 1; 
        $p->CIUDAD_cod = $request->idciudad ?: 1; 
        $p->proveedor_nombre = trim($request->nombre);
        $p->proveedor_direc = trim($request->direccion);
        $p->proveedor_telef = trim($request->telefono);
        $p->proveedor_ruc = trim($request->ruc);
        $p->save();

        return response()->json([
            'ok' => true,
            'message' => 'Proveedor registrado correctamente',
            'proveedor' => $p
        ]);
    }

    private function ultimo()
    {
        return Proveedor::orderBy('PROVEEDOR_cod', 'DESC')->first();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        Proveedor::where('PROVEEDOR_cod', $id)->update([
            'nacio_cod' => $request->idnacionalidad ?: 1,
            'CIUDAD_cod' => $request->idciudad ?: 1,
            'proveedor_nombre' => trim($request->nombre),
            'proveedor_direc' => trim($request->direccion),
            'proveedor_telef' => trim($request->telefono),
            'proveedor_ruc' => trim($request->ruc)
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Proveedor actualizado correctamente'
        ]);
    }

    public function destroy($id)
    {
        $tieneCompras = DB::table('compra')->where('PROVEEDOR_cod', $id)->exists();
        if ($tieneCompras) {
            return response()->json([
                'ok' => false,
                'message' => 'No es posible eliminar el proveedor porque posee compras registradas en el sistema.'
            ], 422);
        }

        Proveedor::where('PROVEEDOR_cod', $id)->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Proveedor eliminado correctamente'
        ]);
    }
}
