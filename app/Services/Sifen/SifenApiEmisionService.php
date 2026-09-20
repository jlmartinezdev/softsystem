<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use App\SifenDocumento;
use App\Services\SifenService;

class SifenApiEmisionService
{
    public function emitir(SifenDocumento $documento, SifenConfig $config)
    {
        $cliente = new SifenApiClient($config);
        $nroVenta = (int) $documento->nro_fact_ventas;

        $apiId = $documento->api_documento_id ? (int) $documento->api_documento_id : null;

        if (!$apiId) {
            $existente = $this->buscarPorReferencia($cliente, $nroVenta);
            if ($existente) {
                $apiId = (int) $existente['id'];
                $documento->update(['api_documento_id' => $apiId]);
            }
        }

        if (!$apiId) {
            $creado = $cliente->crearDocumento($this->payloadDesdeVenta($nroVenta, $config));
            $data = $creado['data'] ?? $creado;
            $apiId = (int) ($data['id'] ?? 0);
            if ($apiId <= 0) {
                throw new \RuntimeException('La API no devolvió ID de documento.');
            }
            $documento->update(['api_documento_id' => $apiId]);
        }

        $actual = $cliente->obtenerDocumento($apiId);
        $data = $actual['data'] ?? $actual;
        $estadoApi = (string) ($data['estado'] ?? '');

        if ($estadoApi === 'emitida') {
            return $this->sincronizarDesdeApi($documento, $data, $config, $cliente);
        }

        if ($estadoApi !== 'borrador') {
            $this->aplicarEstadoIntermedio($documento, $data);
            throw new \RuntimeException(
                'Documento API en estado "' . $estadoApi . '": ' . ($data['sifen']['mensaje'] ?? 'revisar en api_sifen')
            );
        }

        // Preparar (CDC + XML) si aún no tiene CDC
        if (empty($data['sifen']['cdc'])) {
            $prep = $cliente->preparar($apiId);
            $data = $prep['data'] ?? $data;
        }

        $payloadEmitir = [
            'enviar_sifen' => (bool) $config->api_enviar_sifen,
            'enviar_correo' => (bool) $config->api_enviar_correo,
        ];

        if ($config->ambiente === 'prod' && $payloadEmitir['enviar_sifen']) {
            $payloadEmitir['confirmar_produccion'] = true;
        }

        try {
            $emitido = $cliente->emitir($apiId, $payloadEmitir);
            $data = $emitido['data'] ?? ($cliente->obtenerDocumento($apiId)['data'] ?? $data);
            $qrUrl = $emitido['qr_url'] ?? ($data['sifen']['qr_url'] ?? null);
            if ($qrUrl) {
                $data['sifen']['qr_url'] = $qrUrl;
            }
            if (!empty($emitido['cdc'])) {
                $data['sifen']['cdc'] = $emitido['cdc'];
            }
            if (isset($emitido['sifen']) && is_array($emitido['sifen'])) {
                $data['sifen'] = array_merge($data['sifen'] ?? [], $emitido['sifen']);
            }
        } catch (\Throwable $e) {
            try {
                $refresco = $cliente->obtenerDocumento($apiId);
                $data = $refresco['data'] ?? $data;
                $this->aplicarEstadoIntermedio($documento, $data);
            } catch (\Throwable $ignored) {
            }
            throw $e;
        }

        return $this->sincronizarDesdeApi($documento, $data, $config, $cliente);
    }

    public function payloadDesdeVenta($nroFactVenta, SifenConfig $config)
    {
        $venta = SifenVentaData::cargar($nroFactVenta);
        $cab = $venta['cabecera'];
        $receptor = $venta['receptor'];

        $documentoReceptor = '';
        if (!empty($receptor['ruc'])) {
            $documentoReceptor = $receptor['ruc'] . (isset($receptor['dv']) ? '-' . $receptor['dv'] : '');
        } elseif (!empty($receptor['num_id'])) {
            $documentoReceptor = (string) $receptor['num_id'];
        } else {
            $documentoReceptor = '0';
        }

        $contado = ((string) ($cab->tipo_factura ?? '1') === '1');
        $tipoDocumento = $contado ? 'factura_contado' : 'factura_credito';

        $items = [];
        foreach ($venta['lineas'] as $linea) {
            $item = $linea['item'];
            $tasa = (int) $linea['tasa'];
            $impuesto = 'EXENTO';
            if ($tasa === 10) {
                $impuesto = 'IVA10';
            } elseif ($tasa === 5) {
                $impuesto = 'IVA5';
            }

            $items[] = [
                'descripcion' => mb_substr((string) ($item->producto_nombre ?? 'Ítem'), 0, 120),
                'cantidad' => (float) $linea['cant'],
                'precio_unitario' => (float) $linea['precio'],
                'codigo_item' => (string) ($item->producto_c_barra ?? $item->ARTICULOS_cod ?? ''),
                'impuesto_codigo' => $impuesto,
            ];
        }

        $payload = [
            'referencia_externa' => 'VTA-' . $nroFactVenta,
            'tipo_documento' => $tipoDocumento,
            'fecha_emision' => date('Y-m-d', strtotime(SifenVentaData::fechaEmision($cab))),
            'moneda' => strtoupper((string) ($config->moneda ?: 'PYG')),
            'observaciones' => 'Venta SoftSystem #' . $nroFactVenta,
            'receptor' => [
                'nombre' => (string) ($receptor['nombre'] ?? $cab->cliente_nombre),
                'documento' => $documentoReceptor,
                'direccion' => (string) ($cab->cliente_direccion ?? ''),
            ],
            'items' => $items,
        ];

        return $payload;
    }

    protected function buscarPorReferencia(SifenApiClient $cliente, $nroVenta)
    {
        $ref = 'VTA-' . $nroVenta;
        try {
            $lista = $cliente->listarDocumentos([
                'referencia_externa' => $ref,
                'per_page' => 5,
            ]);
        } catch (\Throwable $e) {
            return null;
        }

        $items = $lista['data'] ?? [];
        if (isset($lista['data']['data']) && is_array($lista['data']['data'])) {
            $items = $lista['data']['data'];
        }
        if (!is_array($items)) {
            return null;
        }

        foreach ($items as $row) {
            if (!is_array($row)) {
                continue;
            }
            if (($row['referencia_externa'] ?? '') === $ref) {
                return $row;
            }
        }

        return isset($items[0]) && is_array($items[0]) ? $items[0] : null;
    }

    protected function sincronizarDesdeApi(SifenDocumento $documento, array $data, SifenConfig $config, SifenApiClient $cliente)
    {
        $sifen = $data['sifen'] ?? [];
        $cdc = $sifen['cdc'] ?? $documento->cdc;
        $qrUrl = $sifen['qr_url'] ?? $documento->qr_url;
        $aprobado = !empty($sifen['aprobado']) || (($data['estado'] ?? '') === 'emitida' && !empty($cdc));

        $establecimiento = $this->pad($data['establecimiento'] ?? $documento->establecimiento, 3);
        $punto = $this->pad($data['punto_emision'] ?? $data['punto_expedicion'] ?? $documento->punto_expedicion, 3);
        $numero = (int) ($data['numero'] ?? $documento->numero ?? 0);

        $xmlEnviado = $documento->xml_enviado;
        try {
            if (!empty($documento->api_documento_id) || !empty($data['id'])) {
                $id = (int) ($documento->api_documento_id ?: $data['id']);
                $bin = $cliente->descargarXml($id);
                if (!empty($bin['body'])) {
                    $xmlEnviado = $bin['body'];
                }
            }
        } catch (\Throwable $e) {
            // KuDE/XML opcionales al sincronizar
        }

        $estado = $aprobado ? SifenService::ESTADO_APROBADO : SifenService::ESTADO_ENVIADO;
        if (($data['estado'] ?? '') === 'emitida' && empty($cdc) && empty($sifen['aprobado'])) {
            $estado = SifenService::ESTADO_ENVIADO;
        }
        if (isset($sifen['aprobado']) && $sifen['aprobado'] === false) {
            $estado = SifenService::ESTADO_RECHAZADO;
        }

        $documento->update([
            'api_documento_id' => (int) ($data['id'] ?? $documento->api_documento_id),
            'cdc' => $cdc,
            'qr_url' => $qrUrl,
            'timbrado' => $documento->timbrado ?: $config->timbrado,
            'establecimiento' => $establecimiento,
            'punto_expedicion' => $punto,
            'numero' => $numero > 0 ? $numero : $documento->numero,
            'estado' => $estado,
            'codigo_respuesta' => (string) ($sifen['codigo'] ?? ''),
            'mensaje_respuesta' => (string) ($sifen['mensaje'] ?? ($data['estado'] ?? 'emitida')),
            'xml_enviado' => $xmlEnviado,
            'fecha_envio' => date('Y-m-d H:i:s'),
        ]);

        if ($estado === SifenService::ESTADO_RECHAZADO) {
            throw new \RuntimeException(
                'SIFEN rechazó el DE vía API: [' . ($sifen['codigo'] ?? '') . '] ' . ($sifen['mensaje'] ?? '')
            );
        }

        return $documento->fresh();
    }

    protected function aplicarEstadoIntermedio(SifenDocumento $documento, array $data)
    {
        $sifen = $data['sifen'] ?? [];
        $documento->update([
            'api_documento_id' => (int) ($data['id'] ?? $documento->api_documento_id),
            'cdc' => $sifen['cdc'] ?? $documento->cdc,
            'qr_url' => $sifen['qr_url'] ?? $documento->qr_url,
            'estado' => SifenService::ESTADO_ENVIADO,
            'codigo_respuesta' => (string) ($sifen['codigo'] ?? ''),
            'mensaje_respuesta' => (string) ($sifen['mensaje'] ?? ($data['estado'] ?? '')),
            'fecha_envio' => date('Y-m-d H:i:s'),
        ]);
    }

    protected function pad($valor, $len)
    {
        $texto = preg_replace('/\D/', '', (string) $valor);
        if ($texto === '' || $texto === null) {
            return str_repeat('0', $len);
        }
        return str_pad($texto, $len, '0', STR_PAD_LEFT);
    }
}
