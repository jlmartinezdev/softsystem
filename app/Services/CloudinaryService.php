<?php

namespace App\Services;

use App\Support\CloudinarySettings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Sube una imagen a Cloudinary (soporta UploadedFile, ruta en disco o string binario).
     *
     * @param mixed $file Archivo UploadedFile, ruta de archivo o buffer binario.
     * @param array $options Opciones adicionales (folder, public_id, auto_optimize, etc.)
     * @param array|null $credentials Credenciales personalizadas opcionales.
     * @return array
     */
    public static function upload($file, array $options = [], array $credentials = null)
    {
        $config = $credentials ?: CloudinarySettings::all();

        $cloudName = $config['cloud_name'] ?? '';
        $apiKey = $config['api_key'] ?? '';
        $apiSecret = $config['api_secret'] ?? '';

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            return [
                'success' => false,
                'message' => 'Credenciales de Cloudinary incompletas o no configuradas.',
            ];
        }

        $folder = $options['folder'] ?? ($config['folder'] ?: 'articulos');
        $timestamp = time();

        // Parámetros a firmar (deben estar en orden alfabético)
        $paramsToSign = [
            'folder'    => $folder,
            'timestamp' => $timestamp,
        ];

        if (!empty($options['public_id'])) {
            $paramsToSign['public_id'] = $options['public_id'];
        }

        ksort($paramsToSign);

        // Construir string para firma: "k1=v1&k2=v2" . $apiSecret
        $signString = '';
        foreach ($paramsToSign as $k => $v) {
            $signString .= ($signString === '' ? '' : '&') . $k . '=' . $v;
        }
        $signString .= $apiSecret;
        $signature = sha1($signString);

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

        // Fallback automático con cURL nativo si la carpeta vendor/guzzlehttp no fue subida al hosting
        if (!class_exists(\GuzzleHttp\Client::class)) {
            return self::uploadViaCurl($url, $file, $apiKey, $timestamp, $folder, $signature, $options, $config);
        }

        // Armar multipart para Guzzle
        $multipart = [
            ['name' => 'api_key',   'contents' => $apiKey],
            ['name' => 'timestamp', 'contents' => (string) $timestamp],
            ['name' => 'folder',    'contents' => $folder],
            ['name' => 'signature', 'contents' => $signature],
        ];

        if (!empty($options['public_id'])) {
            $multipart[] = ['name' => 'public_id', 'contents' => $options['public_id']];
        }

        // Manejar el archivo según su tipo
        if ($file instanceof UploadedFile) {
            $multipart[] = [
                'name'     => 'file',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName() ?: 'upload.jpg',
            ];
        } elseif (is_string($file) && file_exists($file)) {
            $multipart[] = [
                'name'     => 'file',
                'contents' => fopen($file, 'r'),
                'filename' => basename($file),
            ];
        } elseif (is_string($file) && (strpos($file, 'data:') === 0 || filter_var($file, FILTER_VALIDATE_URL))) {
            // Data URI o URL remota
            $multipart[] = [
                'name'     => 'file',
                'contents' => $file,
            ];
        } else {
            // Buffer binario directo (ej. captura JPEG de cámara CCTV)
            $multipart[] = [
                'name'     => 'file',
                'contents' => $file,
                'filename' => 'captura_' . $timestamp . '.jpg',
            ];
        }

        try {
            $client = new Client(['timeout' => 25]);
            $response = $client->post($url, [
                'multipart' => $multipart,
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if (!empty($body['secure_url'])) {
                $secureUrl = $body['secure_url'];

                // Si auto_optimize está activo, aplicar transformación automática f_auto,q_auto
                $autoOptimize = isset($options['auto_optimize']) ? $options['auto_optimize'] : ($config['auto_optimize'] ?? true);
                if ($autoOptimize) {
                    $secureUrl = self::optimizeUrl($secureUrl);
                }

                return [
                    'success'    => true,
                    'url'        => $secureUrl,
                    'raw_url'    => $body['secure_url'],
                    'public_id'  => $body['public_id'] ?? null,
                    'format'     => $body['format'] ?? null,
                    'width'      => $body['width'] ?? null,
                    'height'     => $body['height'] ?? null,
                    'bytes'      => $body['bytes'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Respuesta inesperada de Cloudinary.',
            ];
        } catch (RequestException $e) {
            $errorMsg = 'Error al comunicarse con Cloudinary: ' . $e->getMessage();
            if ($e->hasResponse()) {
                $errBody = json_decode((string) $e->getResponse()->getBody(), true);
                if (!empty($errBody['error']['message'])) {
                    $errorMsg = $errBody['error']['message'];
                }
            }

            Log::error('CloudinaryService::upload error: ' . $errorMsg);

            return [
                'success' => false,
                'message' => $errorMsg,
            ];
        } catch (\Exception $e) {
            Log::error('CloudinaryService::upload exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Elimina una imagen de Cloudinary a partir de su public_id.
     *
     * @param string $publicId
     * @param array|null $credentials
     * @return array
     */
    public static function delete($publicId, array $credentials = null)
    {
        $config = $credentials ?: CloudinarySettings::all();

        $cloudName = $config['cloud_name'] ?? '';
        $apiKey = $config['api_key'] ?? '';
        $apiSecret = $config['api_secret'] ?? '';

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret) || empty($publicId)) {
            return [
                'success' => false,
                'message' => 'Parámetros incompletos para eliminar imagen en Cloudinary.',
            ];
        }

        $timestamp = time();
        $paramsToSign = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        ksort($paramsToSign);

        $signString = "public_id={$publicId}&timestamp={$timestamp}" . $apiSecret;
        $signature = sha1($signString);

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy";

        if (!class_exists(\GuzzleHttp\Client::class)) {
            return self::deleteViaCurl($url, [
                'public_id' => $publicId,
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);
        }

        try {
            $client = new Client(['timeout' => 15]);
            $response = $client->post($url, [
                'form_params' => [
                    'public_id' => $publicId,
                    'api_key'   => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            if (($body['result'] ?? '') === 'ok') {
                return ['success' => true];
            }

            return ['success' => false, 'message' => $body['result'] ?? 'No se pudo eliminar'];
        } catch (\Exception $e) {
            Log::error('CloudinaryService::delete error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sube una imagen a Cloudinary usando cURL nativo de PHP (sin depender de GuzzleHttp).
     */
    protected static function uploadViaCurl($url, $file, $apiKey, $timestamp, $folder, $signature, array $options, array $config)
    {
        $postFields = [
            'api_key'   => $apiKey,
            'timestamp' => (string) $timestamp,
            'folder'    => $folder,
            'signature' => $signature,
        ];

        if (!empty($options['public_id'])) {
            $postFields['public_id'] = $options['public_id'];
        }

        if ($file instanceof UploadedFile) {
            $postFields['file'] = new \CURLFile(
                $file->getRealPath(),
                $file->getMimeType() ?: 'image/jpeg',
                $file->getClientOriginalName() ?: 'upload.jpg'
            );
        } elseif (is_string($file) && file_exists($file)) {
            $mime = function_exists('mime_content_type') ? (mime_content_type($file) ?: 'image/jpeg') : 'image/jpeg';
            $postFields['file'] = new \CURLFile($file, $mime, basename($file));
        } elseif (is_string($file) && (strpos($file, 'data:') === 0 || filter_var($file, FILTER_VALIDATE_URL))) {
            $postFields['file'] = $file;
        } else {
            $postFields['file'] = 'data:image/jpeg;base64,' . base64_encode((string) $file);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $rawResponse = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false) {
            Log::error('CloudinaryService::uploadViaCurl error: ' . $curlErr);
            return [
                'success' => false,
                'message' => 'Error de conexión cURL con Cloudinary: ' . $curlErr,
            ];
        }

        $body = json_decode((string) $rawResponse, true);

        if (!empty($body['secure_url'])) {
            $secureUrl = $body['secure_url'];
            $autoOptimize = isset($options['auto_optimize']) ? $options['auto_optimize'] : ($config['auto_optimize'] ?? true);
            if ($autoOptimize) {
                $secureUrl = self::optimizeUrl($secureUrl);
            }

            return [
                'success'   => true,
                'url'       => $secureUrl,
                'raw_url'   => $body['secure_url'],
                'public_id' => $body['public_id'] ?? null,
                'format'    => $body['format'] ?? null,
                'width'     => $body['width'] ?? null,
                'height'    => $body['height'] ?? null,
                'bytes'     => $body['bytes'] ?? null,
            ];
        }

        $errorMsg = $body['error']['message'] ?? 'Respuesta inesperada de Cloudinary.';
        Log::error('CloudinaryService::uploadViaCurl api error: ' . $errorMsg);

        return [
            'success' => false,
            'message' => $errorMsg,
        ];
    }

    /**
     * Elimina una imagen en Cloudinary usando cURL nativo de PHP.
     */
    protected static function deleteViaCurl($url, array $postFields)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postFields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $rawResponse = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false) {
            Log::error('CloudinaryService::deleteViaCurl error: ' . $curlErr);
            return ['success' => false, 'message' => $curlErr];
        }

        $body = json_decode((string) $rawResponse, true);
        if (($body['result'] ?? '') === 'ok') {
            return ['success' => true];
        }

        return ['success' => false, 'message' => $body['result'] ?? 'No se pudo eliminar'];
    }

    /**
     * Inyecta f_auto,q_auto en la URL de entrega de Cloudinary para optimización automática.
     *
     * @param string $url
     * @return string
     */
    public static function optimizeUrl($url)
    {
        if (empty($url) || strpos($url, 'res.cloudinary.com') === false) {
            return $url;
        }

        // Si ya contiene transformaciones, no duplicar
        if (strpos($url, 'f_auto') !== false || strpos($url, 'q_auto') !== false) {
            return $url;
        }

        return preg_replace('#(/upload/)(v\d+/)#', '$1f_auto,q_auto/$2', $url);
    }

    /**
     * Prueba la conexión con Cloudinary subiendo un archivo temporal y eliminándolo.
     *
     * @param array $credentials
     * @return array
     */
    public static function testConnection(array $credentials)
    {
        $cloudName = trim((string) ($credentials['cloud_name'] ?? ''));
        $apiKey = trim((string) ($credentials['api_key'] ?? ''));
        $apiSecret = trim((string) ($credentials['api_secret'] ?? ''));

        // Si api_secret viene vacío pero hay uno guardado, reutilizarlo
        if ($apiSecret === '' || strpos($apiSecret, '***') !== false) {
            $saved = CloudinarySettings::all();
            $apiSecret = $saved['api_secret'] ?? '';
        }

        if (empty($cloudName)) {
            return ['ok' => false, 'message' => 'El Cloud Name es obligatorio.'];
        }
        if (empty($apiKey)) {
            return ['ok' => false, 'message' => 'El API Key es obligatorio.'];
        }
        if (empty($apiSecret)) {
            return ['ok' => false, 'message' => 'El API Secret es obligatorio.'];
        }

        $testCreds = [
            'cloud_name'    => $cloudName,
            'api_key'       => $apiKey,
            'api_secret'    => $apiSecret,
            'folder'        => 'softsystem_test',
            'auto_optimize' => false,
        ];

        // 1x1 pixel transparente en base64
        $tinyImage = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

        $uploadResult = self::upload($tinyImage, [
            'folder' => 'softsystem_test',
        ], $testCreds);

        if (!$uploadResult['success']) {
            return [
                'ok'      => false,
                'message' => 'Error de autenticación con Cloudinary: ' . $uploadResult['message'],
            ];
        }

        // Si se subió, eliminarlo inmediatamente para no dejar basura
        if (!empty($uploadResult['public_id'])) {
            self::delete($uploadResult['public_id'], $testCreds);
        }

        return [
            'ok'      => true,
            'message' => '¡Conexión exitosa con Cloudinary! Tu cuenta (' . $cloudName . ') está lista para alojar imágenes.',
            'url'     => $uploadResult['url'] ?? null,
        ];
    }
}
