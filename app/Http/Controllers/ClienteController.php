<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;
use App\Ciudad;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ciudades= Ciudad::select('CIUDAD_cod','ciudad_nombre')->get();
        return view('cliente',compact('ciudades'));
    }
    public function buscar(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = (int) $request->get('limit', 50);
        if ($limit < 1) {
            $limit = 50;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $query = Cliente::select(
            'clientes_cod',
            'cliente_ci',
            'cliente_nombre',
            'cliente_direccion',
            'cliente_cel',
            'cliente_telef',
            'cliente_correo',
            'CIUDAD_cod',
            'cliente_ruc',
            'cliente_referente_nombre',
            'cliente_profesion',
            'cliente_referencia_laboral'
        );

        if ($q !== '') {
            $likeNombre = '%' . strtoupper($q) . '%';
            $likeDoc = '%' . $q . '%';

            $query->where(function ($builder) use ($likeNombre, $likeDoc) {
                $builder->whereRaw('UPPER(clientes.cliente_nombre) LIKE ?', [$likeNombre])
                    ->orWhere('clientes.cliente_ci', 'LIKE', $likeDoc)
                    ->orWhere('clientes.cliente_ruc', 'LIKE', $likeDoc);
            });
        } elseif ($request->filled('nombre') || $request->filled('documento')) {
            $query->nombre(strtoupper((string) $request->nombre))
                ->documento($request->documento);
        }

        return $query->orderBy('cliente_nombre', 'ASC')
            ->limit($limit)
            ->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ultimo= Cliente::max('CLIENTES_cod');
        
        $cliente = new Cliente();
        $cliente->CLIENTES_cod= $ultimo +1;
        $cliente->CIUDAD_cod =$request->cliente['idciudad'];
        $cliente->cliente_ci =$request->cliente['doc'];
        $cliente->cliente_nombre =$request->cliente['nombre'];
        $cliente->cliente_ruc =$request->cliente['doc'];
        $cliente->cliente_direccion =$request->cliente['direccion'];
        $cliente->cliente_telef =$request->cliente['telefono'];
        $cliente->cliente_cel =$request->cliente['celular'];
        $cliente->cliente_correo =$request->cliente['correo'];
        $cliente->cliente_referente_nombre = $request->cliente['celfamiliar'];
        $cliente->cliente_profesion = $request->cliente['ocupacion'];
        $cliente->cliente_referencia_laboral = $request->cliente['reflaboral'];
        $cliente->save();
        return 'OK';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->input('cliente', []);
        // Permitir CLIENTES_cod = 0 (no usar !$id: en PHP 0 es falsy)
        if (!array_key_exists('id', $data) || $data['id'] === null || $data['id'] === '') {
            return response()->json(['ok' => false, 'message' => 'Cliente no válido'], 422);
        }
        $id = (int) $data['id'];

        $existe = Cliente::where('CLIENTES_cod', $id)->exists();
        if (!$existe) {
            return response()->json(['ok' => false, 'message' => 'Cliente no encontrado'], 404);
        }

        Cliente::where('CLIENTES_cod', $id)->update([
            'CIUDAD_cod' => $data['idciudad'] ?? 1,
            'cliente_ci' => $data['doc'] ?? '',
            'cliente_nombre' => $data['nombre'] ?? '',
            'cliente_ruc' => $data['doc'] ?? '',
            'cliente_direccion' => $data['direccion'] ?? '',
            'cliente_telef' => $data['telefono'] ?? '',
            'cliente_cel' => $data['celular'] ?? '',
            'cliente_correo' => $data['correo'] ?? '',
            'cliente_referente_nombre' => $data['celfamiliar'] ?? '',
            'cliente_profesion' => $data['ocupacion'] ?? '',
            'cliente_referencia_laboral' => $data['reflaboral'] ?? '',
        ]);

        return response()->json(['ok' => true, 'message' => 'OK']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cliente::where('clientes_cod','=',$id)->delete();
        return 'OK';
    }
}
