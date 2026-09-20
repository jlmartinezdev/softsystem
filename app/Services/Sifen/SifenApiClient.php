<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SifenApiClient
{
    protected $config;
    protected $client;

    public function __construct(SifenConfig $config)
    {
        $this->config = $config;
        $base = rtrim((string) $config->api_url, '/');
        if ($base !== '' && substr($base, -7) !== '/api/v1') {
            $base .= '/api/v1';
        }

        $this->client = new Client([
            'base_uri' => $base === '' ? null : $base . '/',
            'timeout' => 120,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . trim((string) $config->api_token),
            ],
        ]);
    }

    public static function normalizarUrlBase($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }
        return rtrim($url, '/');
    }

    public function status()
    {
        return $this->request('GET', 'sifen/status');
    }

    public function listarDocumentos(array $query = [])
    {
        return $this->request('GET', 'documentos', ['query' => $query]);
    }

    public function crearDocumento(array $payload)
    {
        return $this->request('POST', 'documentos', ['json' => $payload]);
    }

    public function obtenerDocumento($id)
    {
        return $this->request('GET', 'documentos/' . $id);
    }

    public function preparar($id)
    {
        return $this->request('POST', 'documentos/' . $id . '/preparar');
    }

    public function emitir($id, array $payload = [])
    {
        return $this->request('POST', 'documentos/' . $id . '/emitir', ['json' => $payload]);
    }

    public function descargarXml($id)
    {
        return $this->requestBinary('GET', 'documentos/' . $id . '/xml');
    }

    public function descargarKude($id)
    {
        return $this->requestBinary('GET', 'documentos/' . $id . '/kude');
    }

    public function login($email, $password, $deviceName = 'softsystem')
    {
        $base = self::normalizarUrlBase($this->config->api_url);
        if ($base === '') {
            throw new \RuntimeException('URL de la API SIFEN no configurada.');
        }
        if (substr($base, -7) !== '/api/v1') {
            $base .= '/api/v1';
        }

        $client = new Client([
            'base_uri' => $base . '/',
            'timeout' => 30,
            'http_errors' => false,
            'headers' => ['Accept' => 'application/json'],
        ]);

        $response = $client->request('POST', 'auth/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
                'device_name' => $deviceName,
            ],
        ]);

        return $this->decodeResponse($response);
    }

    protected function request($method, $uri, array $options = [])
    {
        if (empty($this->config->api_url) || empty($this->config->api_token)) {
            throw new \RuntimeException('Configure URL y token Bearer de la API SIFEN.');
        }

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (RequestException $e) {
            throw new \RuntimeException('Error de conexión con API SIFEN: ' . $e->getMessage(), 0, $e);
        }

        return $this->decodeResponse($response);
    }

    protected function requestBinary($method, $uri)
    {
        if (empty($this->config->api_url) || empty($this->config->api_token)) {
            throw new \RuntimeException('Configure URL y token Bearer de la API SIFEN.');
        }

        try {
            $response = $this->client->request($method, $uri, [
                'headers' => ['Accept' => '*/*'],
            ]);
        } catch (RequestException $e) {
            throw new \RuntimeException('Error de conexión con API SIFEN: ' . $e->getMessage(), 0, $e);
        }

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');

        if ($status >= 400) {
            $json = json_decode($body, true);
            $msg = is_array($json) ? ($json['message'] ?? $body) : $body;
            throw new \RuntimeException('API SIFEN HTTP ' . $status . ': ' . $msg);
        }

        return [
            'status' => $status,
            'body' => $body,
            'content_type' => $contentType ?: 'application/octet-stream',
        ];
    }

    protected function decodeResponse($response)
    {
        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        if (!is_array($json)) {
            throw new \RuntimeException('Respuesta inválida de API SIFEN (HTTP ' . $status . ').');
        }

        if ($status >= 400) {
            $msg = $json['message'] ?? ('Error HTTP ' . $status);
            if (!empty($json['errors']) && is_array($json['errors'])) {
                $detalles = [];
                foreach ($json['errors'] as $campo => $errs) {
                    $detalles[] = $campo . ': ' . (is_array($errs) ? implode(', ', $errs) : $errs);
                }
                $msg .= ' — ' . implode('; ', $detalles);
            }
            throw new \RuntimeException($msg);
        }

        return $json;
    }
}
