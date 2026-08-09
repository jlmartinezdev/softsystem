<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SifenSoapClient
{
    protected $certificate;

    public function __construct(CertificateService $certificate)
    {
        $this->certificate = $certificate;
    }

    public function enviarDocumento(SifenConfig $config, $xmlFirmado, $fallarSiRechazado = true)
    {
        $urlConfig = $config->ambiente === 'prod' ? $config->url_prod : $config->url_test;
        $endpoint = $this->resolverEndpoint($urlConfig, $config->ambiente);

        return $this->enviarConGuzzle($config, $endpoint, $xmlFirmado, $fallarSiRechazado);
    }

    public function probarConexion(SifenConfig $config)
    {
        $ambiente = $config->ambiente === 'prod' ? 'prod' : 'test';
        $endpoint = $this->resolverEndpoint(
            $ambiente === 'prod' ? $config->url_prod : $config->url_test,
            $ambiente
        );

        $certDiag = $this->certificate->diagnostico($config);
        $material = $this->certificate->cargarDesdeConfig($config);
        $temp = $this->certificate->escribirTemporales($material);
        $curlOpts = $this->opcionesSsl($config, $temp);

        $client = new Client([
            'verify' => true,
            'timeout' => 30,
            'connect_timeout' => 15,
            'allow_redirects' => false,
            'http_errors' => false,
        ]);

        try {
            $response = $client->post($endpoint, [
                'body' => '<?xml version="1.0" encoding="UTF-8"?><probe/>',
                'headers' => [
                    'Content-Type' => 'application/xml; charset=UTF-8',
                ],
                'curl' => $curlOpts,
            ]);
        } catch (RequestException $e) {
            return [
                'ok' => false,
                'endpoint' => $endpoint,
                'http_code' => null,
                'mensaje' => 'No se pudo conectar con SET: ' . $e->getMessage(),
                'certificado' => $certDiag,
            ];
        }

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        $location = $response->getHeaderLine('Location');
        $tlsOk = $status < 300 || ($status >= 400 && trim($body) !== '');

        if ($status >= 300 && $status < 400) {
            return [
                'ok' => false,
                'endpoint' => $endpoint,
                'http_code' => $status,
                'location' => $location,
                'mensaje' => $this->mensajeRedireccion($status, $location, $endpoint, $certDiag),
                'certificado' => $certDiag,
            ];
        }

        return [
            'ok' => $tlsOk,
            'endpoint' => $endpoint,
            'http_code' => $status,
            'mensaje' => $tlsOk
                ? 'Conexión mTLS aceptada por SET (HTTP ' . $status . '). Puede enviar documentos.'
                : 'SET respondió sin aceptar la conexión (HTTP ' . $status . ').',
            'certificado' => $certDiag,
        ];
    }

    public static function normalizarUrlServicio($url, $ambiente = 'test')
    {
        $url = trim((string) $url);
        $url = preg_replace('/\s+/', '', $url);

        if ($url === '') {
            return $ambiente === 'prod'
                ? 'https://sifen.set.gov.py/de/ws/sync/recibe'
                : 'https://sifen-test.set.gov.py/de/ws/sync/recibe';
        }

        if (preg_match('/\.wsdl(\?.*)?$/i', $url)) {
            $url = preg_replace('/\.wsdl(\?.*)?$/i', '', $url);
        }

        return rtrim($url, '/');
    }

    protected function enviarConGuzzle(SifenConfig $config, $endpoint, $xmlFirmado, $fallarSiRechazado = true)
    {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('URL SIFEN inválida: "' . $endpoint . '". Use https://sifen-test.set.gov.py/de/ws/sync/recibe');
        }

        $material = $this->certificate->cargarDesdeConfig($config);
        $temp = $this->certificate->escribirTemporales($material);
        $dId = $this->generarDId();
        $envelope = $this->construirEnvelope($dId, $xmlFirmado, $config->ambiente);

        $client = new Client([
            'verify' => true,
            'timeout' => 90,
            'connect_timeout' => 30,
            'allow_redirects' => false,
            'http_errors' => false,
        ]);

        $curlOpts = $this->opcionesSsl($config, $temp);

        try {
            $response = $client->post($endpoint, [
                'body' => $envelope,
                'headers' => [
                    'Content-Type' => 'application/xml; charset=UTF-8',
                ],
                'curl' => $curlOpts,
            ]);
        } catch (RequestException $e) {
            $detalle = $e->getMessage();
            if (stripos($detalle, 'error 3') !== false) {
                $detalle .= ' (URL: ' . $endpoint . ', cert: ' . $temp['cert'] . ')';
            }
            throw new \RuntimeException('Error de conexión SIFEN: ' . $detalle, 0, $e);
        }

        $status = $response->getStatusCode();
        $rawResponse = (string) $response->getBody();

        if ($status >= 300 && $status < 400) {
            $location = $response->getHeaderLine('Location');
            $certDiag = $this->certificate->diagnostico($config);
            throw new \RuntimeException($this->mensajeRedireccion($status, $location, $endpoint, $certDiag));
        }

        if ($status >= 400) {
            if (trim($rawResponse) !== '' && $this->extraerValor($rawResponse, 'dCodRes')) {
                return $this->parsearRespuesta(null, $rawResponse, $fallarSiRechazado);
            }

            throw new \RuntimeException(
                'SIFEN respondió HTTP ' . $status . '. '
                . substr(strip_tags($rawResponse), 0, 250)
            );
        }

        if (trim($rawResponse) === '') {
            throw new \RuntimeException(
                'SIFEN devolvió respuesta vacía en ' . $endpoint
                . '. Verifique certificado para ambiente test, CSC y conectividad con sifen-test.set.gov.py.'
            );
        }

        return $this->parsearRespuesta(null, $rawResponse, $fallarSiRechazado);
    }

    protected function mensajeRedireccion($status, $location, $endpoint, array $certDiag = [])
    {
        $msg = 'El servidor SET redirigió la petición (HTTP ' . $status
            . ') desde ' . $endpoint
            . ($location ? ' hacia ' . $location : '')
            . '. Esto indica que SET no aceptó la autenticación mTLS (certificado no habilitado en ambiente test, contribuyente no registrado en e-Kuatia test, o red bloqueada).';

        if (!empty($certDiag['titular'])) {
            $msg .= ' Certificado: ' . $certDiag['titular']
                . ' (' . ($certDiag['tipo_certificado'] ?? '') . ', ' . ($certDiag['identificacion_cert'] ?? '') . ').';
        }

        if (!empty($certDiag['advertencias'])) {
            $msg .= ' ' . implode(' ', $certDiag['advertencias']);
        }

        return $msg;
    }

    protected function opcionesSsl(SifenConfig $config, array $temp)
    {
        $password = (string) $config->cert_password;
        $p12Path = realpath((string) $config->cert_path);
        $opts = [
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
        ];

        if ($p12Path && is_file($p12Path) && preg_match('/\.(p12|pfx)$/i', $p12Path)) {
            $opts[CURLOPT_SSLCERT] = str_replace('\\', '/', $p12Path);
            $opts[CURLOPT_SSLCERTTYPE] = 'P12';
            $opts[CURLOPT_SSLCERTPASSWD] = $password;

            return $opts;
        }

        $opts[CURLOPT_SSLCERT] = $temp['cert'];
        $opts[CURLOPT_SSLKEY] = $temp['key'];
        if ($password !== '') {
            $opts[CURLOPT_SSLKEYPASSWD] = $password;
        }

        return $opts;
    }

    protected function generarDId()
    {
        return (string) random_int(100000000, 999999999);
    }

    protected function resolverEndpoint($urlConfig, $ambiente)
    {
        return self::normalizarUrlServicio($urlConfig, $ambiente);
    }

    protected function construirEnvelope($dId, $xmlFirmado, $ambiente)
    {
        $deXml = preg_replace('/<\?xml[^?]*\?>\s*/i', '', (string) $xmlFirmado);
        $deXml = trim($deXml);

        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">'
            . '<env:Header/>'
            . '<env:Body>'
            . '<rEnviDe xmlns="http://ekuatia.set.gov.py/sifen/xsd">'
            . '<dId>' . htmlspecialchars($dId, ENT_XML1, 'UTF-8') . '</dId>'
            . '<xDE>' . $deXml . '</xDE>'
            . '</rEnviDe>'
            . '</env:Body>'
            . '</env:Envelope>';
    }

    protected function parsearRespuesta($response, $rawResponse, $fallarSiRechazado = true)
    {
        $xml = is_string($rawResponse) ? $rawResponse : '';
        if (is_object($response)) {
            $xml .= ' ' . json_encode($response);
        }

        $codigo = $this->extraerValor($xml, 'dCodRes');
        $mensaje = $this->extraerValor($xml, 'dMsgRes');
        $cdc = $this->extraerValor($xml, 'dCDC');
        $estado = $this->extraerValor($xml, 'dEstRes');

        $aprobado = in_array($codigo, ['0260', '0300'], true)
            || stripos((string) $estado, 'Aprobado') !== false
            || stripos((string) $mensaje, 'Aprobado') !== false;

        if (!$aprobado && $codigo && !in_array($codigo, ['0260', '0300'], true)) {
            if ($fallarSiRechazado) {
                throw new \RuntimeException('SIFEN rechazó el documento: [' . $codigo . '] ' . ($mensaje ?: 'Sin detalle'));
            }
        }

        return [
            'codigo' => $codigo ?: ($aprobado ? '0260' : '9999'),
            'mensaje' => $mensaje ?: ($aprobado ? 'Documento aprobado por SET' : 'Respuesta SET sin detalle'),
            'cdc' => $cdc,
            'aprobado' => $aprobado,
            'raw' => $rawResponse,
        ];
    }

    protected function extraerValor($xml, $tag)
    {
        if (!is_string($xml) || $xml === '') {
            return null;
        }

        if (preg_match('/<' . preg_quote($tag, '/') . '>(.*?)<\/' . preg_quote($tag, '/') . '>/s', $xml, $m)) {
            return html_entity_decode(trim($m[1]), ENT_QUOTES, 'UTF-8');
        }

        return null;
    }
}
