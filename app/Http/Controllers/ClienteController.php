<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Ciudad;
use App\Support\MapSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:clientes,open')->only(['index']);
        $this->middleware('permiso:clientes,add')->only(['store', 'subirFotoDocumento']);
        $this->middleware('permiso:clientes,edit')->only(['update', 'eliminarFotoDocumento']);
        $this->middleware('permiso:clientes,del')->only(['destroy']);
    }

    /**
     * Muestra la vista principal del directorio de clientes.
     */
    public function index()
    {
        $ciudades = Ciudad::select('CIUDAD_cod', 'ciudad_nombre')->orderBy('ciudad_nombre', 'ASC')->get();
        $mapConfig = MapSettings::publicConfig();
        return view('cliente', compact('ciudades', 'mapConfig'));
    }

    /**
     * Búsqueda dinámica de clientes con datos de contacto, GPS y documentos.
     */
    public function buscar(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = (int) $request->get('limit', 50);
        if ($limit < 1) {
            $limit = 50;
        }
        if ($limit > 1000) {
            $limit = 1000;
        }

        $query = Cliente::select(
            'clientes.CLIENTES_cod as clientes_cod',
            'clientes.CLIENTES_cod',
            'clientes.cliente_ci',
            'clientes.cliente_nombre',
            'clientes.cliente_direccion',
            'clientes.cliente_latitud',
            'clientes.cliente_longitud',
            'clientes.cliente_ubicacion_url',
            'clientes.cliente_foto_ci_dorso',
            'clientes.cliente_foto_ci_reverso',
            'clientes.cliente_cel',
            'clientes.cliente_telef',
            'clientes.cliente_correo',
            'clientes.CIUDAD_cod',
            'clientes.cliente_ruc',
            'clientes.cliente_referente_nombre',
            'clientes.cliente_profesion',
            'clientes.cliente_referencia_laboral',
            'ciudad.ciudad_nombre'
        )->leftJoin('ciudad', 'clientes.CIUDAD_cod', '=', 'ciudad.CIUDAD_cod');

        if ($q !== '') {
            $likeNombre = '%' . strtoupper($q) . '%';
            $likeDoc = '%' . $q . '%';

            $query->where(function ($builder) use ($likeNombre, $likeDoc) {
                $builder->whereRaw('UPPER(clientes.cliente_nombre) LIKE ?', [$likeNombre])
                    ->orWhere('clientes.cliente_ci', 'LIKE', $likeDoc)
                    ->orWhere('clientes.cliente_ruc', 'LIKE', $likeDoc)
                    ->orWhere('clientes.cliente_cel', 'LIKE', $likeDoc);
            });
        } elseif ($request->filled('nombre') || $request->filled('documento')) {
            $query->nombre(strtoupper((string) $request->nombre))
                ->documento($request->documento);
        }

        if ($request->filled('ciudad') && (int)$request->ciudad > 0) {
            $query->where('clientes.CIUDAD_cod', (int)$request->ciudad);
        }

        $clientes = $query->orderBy('clientes.cliente_nombre', 'ASC')
            ->limit($limit)
            ->get();

        // Mapear URLs de fotos y datos GPS
        $clientes->transform(function ($c) {
            $c->foto_frente_url = !empty($c->cliente_foto_ci_dorso)
                ? (filter_var($c->cliente_foto_ci_dorso, FILTER_VALIDATE_URL) ? $c->cliente_foto_ci_dorso : asset('storage/clientes/' . $c->cliente_foto_ci_dorso))
                : null;
            $c->foto_dorso_url = !empty($c->cliente_foto_ci_reverso)
                ? (filter_var($c->cliente_foto_ci_reverso, FILTER_VALIDATE_URL) ? $c->cliente_foto_ci_reverso : asset('storage/clientes/' . $c->cliente_foto_ci_reverso))
                : null;
            $c->tiene_gps = !empty($c->cliente_latitud) && !empty($c->cliente_longitud);
            $c->gps_url = $c->tiene_gps
                ? "https://www.google.com/maps?q={$c->cliente_latitud},{$c->cliente_longitud}"
                : ($c->cliente_ubicacion_url ?? null);
            return $c;
        });

        return response()->json($clientes);
    }

    /**
     * Guarda un nuevo cliente con soporte para GPS y fotos de documentos.
     */
    public function store(Request $request)
    {
        $cData = $request->input('cliente', []);

        $request->validate([
            'cliente.nombre' => 'required|string|max:70',
            'cliente.doc'    => 'required|string|max:15',
        ], [
            'cliente.nombre.required' => 'El nombre del cliente es obligatorio.',
            'cliente.doc.required'    => 'El documento/RUC es obligatorio.',
        ]);

        $ultimo = (int) Cliente::max('CLIENTES_cod');

        $cliente = new Cliente();
        $cliente->CLIENTES_cod = $ultimo + 1;
        $cliente->CIUDAD_cod = $cData['idciudad'] ?? 1;
        $cliente->cliente_ci = trim($cData['doc'] ?? '');
        $cliente->cliente_nombre = trim($cData['nombre'] ?? '');
        $cliente->cliente_ruc = trim($cData['doc'] ?? '');
        $cliente->cliente_direccion = trim($cData['direccion'] ?? '');
        $cliente->cliente_latitud = !empty($cData['latitud']) ? trim($cData['latitud']) : null;
        $cliente->cliente_longitud = !empty($cData['longitud']) ? trim($cData['longitud']) : null;
        $cliente->cliente_ubicacion_url = !empty($cData['ubicacion_url']) ? trim($cData['ubicacion_url']) : null;
        $cliente->cliente_foto_ci_dorso = !empty($cData['foto_ci_dorso']) ? trim($cData['foto_ci_dorso']) : null;
        $cliente->cliente_foto_ci_reverso = !empty($cData['foto_ci_reverso']) ? trim($cData['foto_ci_reverso']) : null;
        $cliente->cliente_telef = trim($cData['telefono'] ?? '');
        $cliente->cliente_cel = trim($cData['celular'] ?? '');
        $cliente->cliente_correo = trim($cData['correo'] ?? '');
        $cliente->cliente_referente_nombre = trim($cData['celfamiliar'] ?? '');
        $cliente->cliente_profesion = trim($cData['ocupacion'] ?? '');
        $cliente->cliente_referencia_laboral = trim($cData['reflaboral'] ?? '');
        $cliente->save();

        return response()->json([
            'ok' => true,
            'message' => 'Cliente registrado exitosamente.',
            'id' => $cliente->CLIENTES_cod,
            'cliente' => $cliente
        ]);
    }

    /**
     * Actualiza los datos del cliente incluyendo ubicación GPS y fotos de CI.
     */
    public function update(Request $request)
    {
        $data = $request->input('cliente', []);
        if (!array_key_exists('id', $data) || $data['id'] === null || $data['id'] === '') {
            return response()->json(['ok' => false, 'message' => 'Identificador de cliente no válido.'], 422);
        }
        $id = (int) $data['id'];

        $cliente = Cliente::where('CLIENTES_cod', $id)->first();
        if (!$cliente) {
            return response()->json(['ok' => false, 'message' => 'Cliente no encontrado.'], 404);
        }

        $updateData = [
            'CIUDAD_cod' => $data['idciudad'] ?? 1,
            'cliente_ci' => trim($data['doc'] ?? ''),
            'cliente_nombre' => trim($data['nombre'] ?? ''),
            'cliente_ruc' => trim($data['doc'] ?? ''),
            'cliente_direccion' => trim($data['direccion'] ?? ''),
            'cliente_telef' => trim($data['telefono'] ?? ''),
            'cliente_cel' => trim($data['celular'] ?? ''),
            'cliente_correo' => trim($data['correo'] ?? ''),
            'cliente_referente_nombre' => trim($data['celfamiliar'] ?? ''),
            'cliente_profesion' => trim($data['ocupacion'] ?? ''),
            'cliente_referencia_laboral' => trim($data['reflaboral'] ?? ''),
        ];

        // Actualizar coordenadas GPS si se enviaron
        if (array_key_exists('latitud', $data)) {
            $updateData['cliente_latitud'] = !empty($data['latitud']) ? trim($data['latitud']) : null;
        }
        if (array_key_exists('longitud', $data)) {
            $updateData['cliente_longitud'] = !empty($data['longitud']) ? trim($data['longitud']) : null;
        }
        if (array_key_exists('ubicacion_url', $data)) {
            $updateData['cliente_ubicacion_url'] = !empty($data['ubicacion_url']) ? trim($data['ubicacion_url']) : null;
        }

        // Actualizar nombres de fotos si se enviaron explícitamente
        if (array_key_exists('foto_ci_dorso', $data)) {
            $updateData['cliente_foto_ci_dorso'] = !empty($data['foto_ci_dorso']) ? trim($data['foto_ci_dorso']) : null;
        }
        if (array_key_exists('foto_ci_reverso', $data)) {
            $updateData['cliente_foto_ci_reverso'] = !empty($data['foto_ci_reverso']) ? trim($data['foto_ci_reverso']) : null;
        }

        Cliente::where('CLIENTES_cod', $id)->update($updateData);

        return response()->json([
            'ok' => true,
            'message' => 'Cliente actualizado correctamente.',
            'id' => $id
        ]);
    }

    /**
     * Sube una foto de documento (frente o reverso / dorso) en almacenamiento público.
     */
    public function subirFotoDocumento(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'tipo' => 'required|in:frente,dorso',
            'id_cliente' => 'nullable|integer',
        ], [
            'foto.required' => 'Debe adjuntar una imagen válida del documento.',
            'foto.image' => 'El archivo adjunto debe ser una imagen.',
            'foto.mimes' => 'Formato permitido: JPG, PNG o WebP.',
            'foto.max' => 'El tamaño máximo de imagen es 10MB.',
            'tipo.in' => 'El tipo de foto debe ser "frente" o "dorso".',
        ]);

        $tipo = $request->tipo;
        $file = $request->file('foto');
        $ext = $file->getClientOriginalExtension();
        $filename = 'ci_' . $tipo . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;

        // Guardar archivo en storage/app/public/clientes
        $path = $file->storeAs('clientes', $filename, 'public');

        // Si se envió ID del cliente, actualizar la columna en la BD de inmediato
        if ($request->filled('id_cliente') && (int)$request->id_cliente > 0) {
            $col = ($tipo === 'frente') ? 'cliente_foto_ci_dorso' : 'cliente_foto_ci_reverso';
            
            // Eliminar imagen anterior si existía
            $fotoAnterior = Cliente::where('CLIENTES_cod', $request->id_cliente)->value($col);
            if ($fotoAnterior && Storage::disk('public')->exists('clientes/' . $fotoAnterior)) {
                Storage::disk('public')->delete('clientes/' . $fotoAnterior);
            }

            Cliente::where('CLIENTES_cod', $request->id_cliente)->update([$col => $filename]);
        }

        return response()->json([
            'ok' => true,
            'filename' => $filename,
            'url' => asset('storage/clientes/' . $filename),
            'message' => 'Foto ' . ($tipo === 'frente' ? 'frontal' : 'del reverso') . ' guardada con éxito.'
        ]);
    }

    /**
     * Elimina una foto de documento del almacenamiento y de la base de datos.
     */
    public function eliminarFotoDocumento(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:frente,dorso',
            'id_cliente' => 'nullable|integer',
            'filename' => 'nullable|string',
        ]);

        $tipo = $request->tipo;
        $col = ($tipo === 'frente') ? 'cliente_foto_ci_dorso' : 'cliente_foto_ci_reverso';
        $filename = $request->filename;

        if ($request->filled('id_cliente') && (int)$request->id_cliente > 0) {
            $fotoBd = Cliente::where('CLIENTES_cod', $request->id_cliente)->value($col);
            if ($fotoBd) {
                $filename = $fotoBd;
                Cliente::where('CLIENTES_cod', $request->id_cliente)->update([$col => null]);
            }
        }

        if ($filename && Storage::disk('public')->exists('clientes/' . $filename)) {
            Storage::disk('public')->delete('clientes/' . $filename);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Foto eliminada correctamente.'
        ]);
    }

    /**
     * Elimina el registro del cliente si no posee movimientos comerciales asociados.
     */
    public function destroy($id)
    {
        Cliente::where('CLIENTES_cod', '=', $id)->delete();
        return response()->json(['ok' => true, 'message' => 'Cliente eliminado']);
    }
}
