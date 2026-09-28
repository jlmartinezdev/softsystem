<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciudad;
use App\Departamento;
use App\Cliente;
use App\Proveedor;
use Auth;
use DB;

class CiudadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:ciudad,open')->only(['index', 'all']);
        $this->middleware('permiso:ciudad,add')->only(['store']);
        $this->middleware('permiso:ciudad,edit')->only(['update']);
        $this->middleware('permiso:ciudad,del')->only(['destroy']);
    }

    /**
     * Muestra el panel principal de gestión de ciudades con estadísticas y listado.
     */
    public function index(Request $request)
    {
        $departamentos = Departamento::orderBy('depart_nombre', 'ASC')->get();

        $ciudades = Ciudad::with('departamento')
            ->withCount(['clientes', 'proveedores'])
            ->orderBy('ciudad_nombre', 'ASC')
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ciudades' => $ciudades,
                'departamentos' => $departamentos
            ]);
        }

        $totalCiudades = $ciudades->count();
        $totalDepartamentos = $ciudades->pluck('depart_codigo')->unique()->count();
        $totalClientesAsignados = Cliente::whereNotNull('CIUDAD_cod')->where('CIUDAD_cod', '>', 0)->count();
        $totalProveedoresAsignados = Proveedor::whereNotNull('CIUDAD_cod')->where('CIUDAD_cod', '>', 0)->count();

        return view('ciudad', compact(
            'departamentos',
            'ciudades',
            'totalCiudades',
            'totalDepartamentos',
            'totalClientesAsignados',
            'totalProveedoresAsignados'
        ));
    }

    /**
     * Retorna todas las ciudades con sus departamentos para selectores o APIs.
     */
    public function all()
    {
        return response()->json(
            Ciudad::with('departamento')
                ->withCount(['clientes', 'proveedores'])
                ->orderBy('ciudad_nombre', 'ASC')
                ->get()
        );
    }

    /**
     * Registra una nueva ciudad en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|integer|exists:departamento,depart_codigo',
        ], [
            'ciudad.required' => 'El nombre de la ciudad es obligatorio.',
            'ciudad.max' => 'El nombre de la ciudad no debe superar los 100 caracteres.',
            'departamento.required' => 'Debe seleccionar un departamento válido.',
            'departamento.exists' => 'El departamento seleccionado no existe en el sistema.',
        ]);

        $nombre = trim($request->ciudad);

        // Prevenir duplicados en el mismo departamento
        $existe = Ciudad::where('depart_codigo', $request->departamento)
            ->whereRaw('LOWER(TRIM(ciudad_nombre)) = ?', [strtolower($nombre)])
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una ciudad con el nombre "' . $nombre . '" en este departamento.'
            ], 422);
        }

        $ultimo = (int) Ciudad::max('CIUDAD_cod');
        $ciudad = new Ciudad();
        $ciudad->CIUDAD_cod = $ultimo + 1;
        $ciudad->ciudad_nombre = $nombre;
        $ciudad->depart_codigo = $request->departamento;
        $ciudad->save();

        $ciudad->load('departamento');
        $ciudad->clientes_count = 0;
        $ciudad->proveedores_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'Ciudad "' . $ciudad->ciudad_nombre . '" registrada correctamente.',
            'ciudad' => $ciudad
        ]);
    }

    /**
     * Actualiza los datos de una ciudad existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|integer|exists:departamento,depart_codigo',
        ], [
            'ciudad.required' => 'El nombre de la ciudad es obligatorio.',
            'ciudad.max' => 'El nombre de la ciudad no debe superar los 100 caracteres.',
            'departamento.required' => 'Debe seleccionar un departamento válido.',
            'departamento.exists' => 'El departamento seleccionado no existe.',
        ]);

        $ciudad = Ciudad::find($id);
        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ciudad no encontrada.'
            ], 404);
        }

        $nombre = trim($request->ciudad);

        // Validar duplicados en el mismo departamento excluyendo el ID actual
        $existe = Ciudad::where('depart_codigo', $request->departamento)
            ->where('CIUDAD_cod', '!=', $id)
            ->whereRaw('LOWER(TRIM(ciudad_nombre)) = ?', [strtolower($nombre)])
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe otra ciudad con el nombre "' . $nombre . '" en este departamento.'
            ], 422);
        }

        $ciudad->ciudad_nombre = $nombre;
        $ciudad->depart_codigo = $request->departamento;
        $ciudad->save();

        $ciudad->load('departamento');
        $ciudad->loadCount(['clientes', 'proveedores']);

        return response()->json([
            'success' => true,
            'message' => 'Ciudad "' . $ciudad->ciudad_nombre . '" actualizada correctamente.',
            'ciudad' => $ciudad
        ]);
    }

    /**
     * Elimina una ciudad si no tiene clientes o proveedores asignados.
     */
    public function destroy($id)
    {
        $ciudad = Ciudad::withCount(['clientes', 'proveedores'])->find($id);
        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'La ciudad solicitada no existe o ya fue eliminada.'
            ], 404);
        }

        $cantClientes = (int) $ciudad->clientes_count;
        $cantProveedores = (int) $ciudad->proveedores_count;

        if ($cantClientes > 0 || $cantProveedores > 0) {
            $detalles = [];
            if ($cantClientes > 0) {
                $detalles[] = $cantClientes . ' ' . ($cantClientes === 1 ? 'cliente' : 'clientes');
            }
            if ($cantProveedores > 0) {
                $detalles[] = $cantProveedores . ' ' . ($cantProveedores === 1 ? 'proveedor' : 'proveedores');
            }

            return response()->json([
                'success' => false,
                'message' => 'No es posible eliminar la ciudad "' . $ciudad->ciudad_nombre . '" porque está vinculada a ' . implode(' y ', $detalles) . '. Modifique o reasigne los registros previamente.'
            ], 422);
        }

        $nombre = $ciudad->ciudad_nombre;
        $ciudad->delete();

        return response()->json([
            'success' => true,
            'message' => 'La ciudad "' . $nombre . '" ha sido eliminada con éxito.'
        ]);
    }
}
