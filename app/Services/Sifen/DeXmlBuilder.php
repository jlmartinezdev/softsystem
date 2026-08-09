<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use App\SifenDocumento;

class DeXmlBuilder
{
    protected $config;
    protected $documento;
    protected $venta;
    protected $cdc;
    protected $codigoSeguridad;
    protected $fechaEmision;

    public function __construct(SifenConfig $config, SifenDocumento $documento, array $venta, $cdc, $codigoSeguridad, $fechaEmision)
    {
        $this->config = $config;
        $this->documento = $documento;
        $this->venta = $venta;
        $this->cdc = $cdc;
        $this->codigoSeguridad = $codigoSeguridad;
        $this->fechaEmision = $fechaEmision;
    }

    public function build()
    {
        $config = $this->config;
        $totales = $this->venta['totales'];
        $receptor = $this->venta['receptor'];
        $cabecera = $this->venta['cabecera'];
        $condicion = ((string) ($cabecera->tipo_factura ?? '1') === '1') ? 1 : 2;
        $nombreEmisor = $config->ambiente === 'test'
            ? 'DE generado en ambiente de prueba - sin valor comercial ni fiscal'
            : $config->razon_social;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rDE xmlns="http://ekuatia.set.gov.py/sifen/xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="https://ekuatia.set.gov.py/sifen/xsd siRecepDE_v150.xsd">';
        $xml .= '<dVerFor>' . (int) $config->version_formato . '</dVerFor>';
        $xml .= '<DE Id="' . $this->esc($this->cdc) . '">';
        $xml .= '<dDVId>' . substr($this->cdc, -1) . '</dDVId>';
        $xml .= '<dFecFirma>' . $this->esc($this->fechaEmision) . '</dFecFirma>';
        $xml .= '<dSisFact>1</dSisFact>';
        $xml .= $this->bloqueOpeDe($config);
        $xml .= $this->bloqueTimbrado();
        $xml .= '<gDatGralOpe>';
        $xml .= '<dFeEmiDE>' . $this->esc($this->fechaEmision) . '</dFeEmiDE>';
        $xml .= $this->bloqueOpeCom();
        $xml .= $this->bloqueEmisor($nombreEmisor);
        $xml .= $this->bloqueReceptor($receptor);
        $xml .= '</gDatGralOpe>';
        $xml .= '<gDtipDE>';
        $xml .= '<gCamFE>';
        $xml .= '<iIndPres>' . (int) $config->indicador_presencia . '</iIndPres>';
        $xml .= '<dDesIndPres>' . $this->esc($this->descIndPresencia($config->indicador_presencia)) . '</dDesIndPres>';
        $xml .= '</gCamFE>';
        $xml .= '<gCamCond>';
        $xml .= '<iCondOpe>' . $condicion . '</iCondOpe>';
        $xml .= '<dDCondOpe>' . $this->esc($condicion === 1 ? 'Contado' : 'Crédito') . '</dDCondOpe>';
        if ($condicion === 1) {
            $xml .= '<gPaConEIni>';
            $xml .= '<iTiPago>1</iTiPago>';
            $xml .= '<dDesTiPag>Efectivo</dDesTiPag>';
            $xml .= '<dMonTiPag>' . $this->num($totales['total_gral'], 4) . '</dMonTiPag>';
            $xml .= '<cMoneTiPag>' . $this->esc($config->moneda) . '</cMoneTiPag>';
            $xml .= '<dDMoneTiPag>Guarani</dDMoneTiPag>';
            $xml .= '</gPaConEIni>';
        }
        $xml .= '</gCamCond>';
        $xml .= $this->bloqueItems();
        $xml .= '</gDtipDE>';
        $xml .= $this->bloqueTotales($totales);
        $xml .= '</DE>';
        $xml .= '</rDE>';

        return $xml;
    }

    protected function bloqueOpeDe(SifenConfig $config)
    {
        $xml = '<gOpeDE>';
        $xml .= '<iTipEmi>' . (int) $config->tipo_emision . '</iTipEmi>';
        $xml .= '<dDesTipEmi>' . $this->esc((int) $config->tipo_emision === 2 ? 'Contingencia' : 'Normal') . '</dDesTipEmi>';
        $xml .= '<dCodSeg>' . $this->esc($this->codigoSeguridad) . '</dCodSeg>';
        if ($config->ambiente === 'test') {
            $xml .= '<dInfoEmi>Documento generado en ambiente de prueba</dInfoEmi>';
        }
        $xml .= '</gOpeDE>';

        return $xml;
    }

    protected function bloqueTimbrado()
    {
        $config = $this->config;
        $xml = '<gTimb>';
        $xml .= '<iTiDE>' . (int) $config->tipo_documento . '</iTiDE>';
        $xml .= '<dDesTiDE>Factura electrónica</dDesTiDE>';
        $xml .= '<dNumTim>' . $this->esc($this->documento->timbrado) . '</dNumTim>';
        $xml .= '<dEst>' . $this->esc($this->documento->establecimiento) . '</dEst>';
        $xml .= '<dPunExp>' . $this->esc($this->documento->punto_expedicion) . '</dPunExp>';
        $xml .= '<dNumDoc>' . str_pad($this->documento->numero, 7, '0', STR_PAD_LEFT) . '</dNumDoc>';
        $xml .= '<dFeIniT>' . $this->esc($this->fechaSolo($config->vigencia_desde)) . '</dFeIniT>';
        if (!empty($config->vigencia_hasta)) {
            $xml .= '<dFeFinT>' . $this->esc($this->fechaSolo($config->vigencia_hasta)) . '</dFeFinT>';
        }
        $xml .= '</gTimb>';

        return $xml;
    }

    protected function bloqueOpeCom()
    {
        $config = $this->config;

        return '<gOpeCom>'
            . '<iTipTra>' . (int) $config->tipo_transaccion . '</iTipTra>'
            . '<dDesTipTra>' . $this->esc($this->descTipoTransaccion($config->tipo_transaccion)) . '</dDesTipTra>'
            . '<iTImp>' . (int) $config->tipo_impuesto . '</iTImp>'
            . '<dDesTImp>IVA</dDesTImp>'
            . '<cMoneOpe>' . $this->esc($config->moneda) . '</cMoneOpe>'
            . '<dDesMoneOpe>Guarani</dDesMoneOpe>'
            . '</gOpeCom>';
    }

    protected function bloqueEmisor($nombreEmisor)
    {
        $config = $this->config;
        $rucParsed = SifenRuc::parse($config->ruc);
        $telefono = preg_replace('/\D/', '', (string) $config->telefono_emisor);
        if (strlen($telefono) < 6) {
            $telefono = str_pad($telefono ?: '0', 6, '0', STR_PAD_LEFT);
        }

        $xml = '<gEmis>';
        $xml .= '<dRucEm>' . $this->esc($rucParsed['base']) . '</dRucEm>';
        $xml .= '<dDVEmi>' . $this->esc($rucParsed['dv']) . '</dDVEmi>';
        $xml .= '<iTipCont>' . (int) $config->tipo_contribuyente . '</iTipCont>';
        $xml .= '<dNomEmi>' . $this->esc($nombreEmisor) . '</dNomEmi>';
        $xml .= '<dDirEmi>' . $this->esc($config->direccion_emisor) . '</dDirEmi>';
        $xml .= '<dNumCas>' . $this->esc($config->numero_casa ?: '0') . '</dNumCas>';
        $xml .= '<cDepEmi>' . $this->esc($config->cod_departamento) . '</cDepEmi>';
        $xml .= '<dDesDepEmi>' . $this->esc($config->desc_departamento) . '</dDesDepEmi>';
        $xml .= '<cDisEmi>' . $this->esc($config->cod_distrito) . '</cDisEmi>';
        $xml .= '<dDesDisEmi>' . $this->esc($config->desc_distrito) . '</dDesDisEmi>';
        $xml .= '<cCiuEmi>' . $this->esc($config->cod_ciudad) . '</cCiuEmi>';
        $xml .= '<dDesCiuEmi>' . $this->esc($config->desc_ciudad) . '</dDesCiuEmi>';
        $xml .= '<dTelEmi>' . $this->esc($telefono) . '</dTelEmi>';
        $xml .= '<dEmailE>' . $this->esc($config->email_emisor) . '</dEmailE>';
        $xml .= '<gActEco>';
        $xml .= '<cActEco>' . $this->esc($config->cod_actividad_economica ?: '00000') . '</cActEco>';
        $xml .= '<dDesActEco>' . $this->esc($config->desc_actividad_economica ?: 'Actividad económica del emisor') . '</dDesActEco>';
        $xml .= '</gActEco>';
        $xml .= '</gEmis>';

        return $xml;
    }

    protected function bloqueReceptor(array $receptor)
    {
        $xml = '<gDatRec>';
        $xml .= '<iNatRec>' . (int) $receptor['natura'] . '</iNatRec>';
        $xml .= '<iTiOpe>' . (int) $receptor['tipo_operacion'] . '</iTiOpe>';
        $xml .= '<cPaisRec>' . $this->esc($receptor['pais']) . '</cPaisRec>';
        $xml .= '<dDesPaisRe>Paraguay</dDesPaisRe>';

        if ((int) $receptor['natura'] === 1) {
            $xml .= '<iTiContRec>1</iTiContRec>';
            $xml .= '<dRucRec>' . $this->esc($receptor['ruc']) . '</dRucRec>';
            $xml .= '<dDVRec>' . $this->esc($receptor['dv']) . '</dDVRec>';
            $xml .= '<dNomRec>' . $this->esc($receptor['nombre']) . '</dNomRec>';
        } else {
            $tipoId = (int) $receptor['tipo_id'];
            $xml .= '<iTipIDRec>' . $tipoId . '</iTipIDRec>';
            $xml .= '<dDTipIDRec>' . $this->esc($this->descTipoDocumentoReceptor($tipoId)) . '</dDTipIDRec>';
            $xml .= '<dNumIDRec>' . $this->esc($receptor['num_id']) . '</dNumIDRec>';
            $xml .= '<dNomRec>' . $this->esc($receptor['nombre']) . '</dNomRec>';
        }

        $xml .= '</gDatRec>';

        return $xml;
    }

    protected function bloqueItems()
    {
        $xml = '';
        $n = 1;
        $lineas = $this->venta['lineas'] ?? [];

        foreach ($lineas as $linea) {
            $item = $linea['item'];
            $xml .= '<gCamItem>';
            $xml .= '<dCodInt>' . $this->esc($item->producto_c_barra ?: ('ITEM' . $n)) . '</dCodInt>';
            $xml .= '<dDesProSer>' . $this->esc($item->producto_nombre) . '</dDesProSer>';
            $xml .= '<cUniMed>77</cUniMed>';
            $xml .= '<dDesUniMed>UNI</dDesUniMed>';
            $xml .= '<dCantProSer>' . $this->qty($linea['cant']) . '</dCantProSer>';
            $xml .= '<gValorItem>';
            $xml .= '<dPUniProSer>' . $this->num($linea['precio'], 4) . '</dPUniProSer>';
            $xml .= '<dTotBruOpeItem>' . $this->num($linea['bruto'], 4) . '</dTotBruOpeItem>';
            $xml .= '<gValorRestaItem>';
            $xml .= '<dDescItem>0.0000</dDescItem>';
            $xml .= '<dPorcDesIt>0</dPorcDesIt>';
            $xml .= '<dDescGloItem>0.0000</dDescGloItem>';
            $xml .= '<dAntPreUniIt>0.0000</dAntPreUniIt>';
            $xml .= '<dAntGloPreUniIt>0.0000</dAntGloPreUniIt>';
            $xml .= '<dTotOpeItem>' . $this->num($linea['tot_ope'], 4) . '</dTotOpeItem>';
            $xml .= '</gValorRestaItem>';
            $xml .= '</gValorItem>';
            $xml .= '<gCamIVA>';
            $xml .= '<iAfecIVA>' . (int) $linea['afectacion'] . '</iAfecIVA>';
            $xml .= '<dDesAfecIVA>' . $this->esc($linea['desc_afectacion']) . '</dDesAfecIVA>';
            $xml .= '<dPropIVA>100</dPropIVA>';
            $xml .= '<dTasaIVA>' . (int) $linea['tasa'] . '</dTasaIVA>';
            $xml .= '<dBasGravIVA>' . $this->num($linea['base_grav'], 4) . '</dBasGravIVA>';
            $xml .= '<dLiqIVAItem>' . $this->num($linea['liq_iva'], 4) . '</dLiqIVAItem>';
            $xml .= '<dBasExe>0.0000</dBasExe>';
            $xml .= '</gCamIVA>';
            $xml .= '</gCamItem>';
            $n++;
        }

        return $xml;
    }

    protected function bloqueTotales(array $totales)
    {
        $tBasGra = ($totales['base_grav_5'] ?? 0) + ($totales['base_grav_10'] ?? 0);
        $totOpe = $totales['tot_ope'] ?? ($totales['sub_exe'] + $totales['sub_5'] + $totales['sub_10']);

        $xml = '<gTotSub>';
        $xml .= '<dSubExe>' . $this->num($totales['sub_exe'], 4) . '</dSubExe>';
        $xml .= '<dSub5>' . $this->num($totales['sub_5'], 4) . '</dSub5>';
        $xml .= '<dSub10>' . $this->num($totales['sub_10'], 4) . '</dSub10>';
        $xml .= '<dTotOpe>' . $this->num($totOpe, 4) . '</dTotOpe>';
        $xml .= '<dTotDesc>' . $this->num($totales['descuento'], 4) . '</dTotDesc>';
        $xml .= '<dTotDescGlotem>0.0000</dTotDescGlotem>';
        $xml .= '<dTotAntItem>0.0000</dTotAntItem>';
        $xml .= '<dTotAnt>0.0000</dTotAnt>';
        $xml .= '<dPorcDescTotal>0</dPorcDescTotal>';
        $xml .= '<dDescTotal>' . $this->num($totales['descuento'], 4) . '</dDescTotal>';
        $xml .= '<dAnticipo>0.0000</dAnticipo>';
        $xml .= '<dRedon>0.0000</dRedon>';
        $xml .= '<dTotGralOpe>' . $this->num($totales['total_gral'], 4) . '</dTotGralOpe>';

        $iva5 = (float) ($totales['iva_5'] ?? 0);
        $iva10 = (float) ($totales['iva_10'] ?? 0);
        if ($iva5 > 0 || ($totales['sub_5'] ?? 0) > 0) {
            $xml .= '<dIVA5>' . $this->num($iva5, 4) . '</dIVA5>';
        }
        if ($iva10 > 0 || ($totales['sub_10'] ?? 0) > 0) {
            $xml .= '<dIVA10>' . $this->num($iva10, 4) . '</dIVA10>';
        }
        $xml .= '<dTotIVA>' . $this->num($iva5 + $iva10, 4) . '</dTotIVA>';

        if (($totales['base_grav_5'] ?? 0) > 0) {
            $xml .= '<dBaseGrav5>' . $this->num($totales['base_grav_5'], 4) . '</dBaseGrav5>';
        }
        if (($totales['base_grav_10'] ?? 0) > 0) {
            $xml .= '<dBaseGrav10>' . $this->num($totales['base_grav_10'], 4) . '</dBaseGrav10>';
        }
        $xml .= '<dTBasGraIVA>' . $this->num($tBasGra, 4) . '</dTBasGraIVA>';
        $xml .= '</gTotSub>';

        return $xml;
    }

    protected function descTipoDocumentoReceptor($codigo)
    {
        $map = [
            1 => 'Cédula paraguaya',
            2 => 'Pasaporte',
            3 => 'Cédula extranjera',
            4 => 'Carnet de residencia',
            5 => 'Innominado',
            6 => 'Tarjeta Diplomática de exoneración fiscal',
            9 => 'Otro',
        ];

        return $map[(int) $codigo] ?? 'Cédula paraguaya';
    }

    protected function descIndPresencia($codigo)
    {
        $map = [
            1 => 'Operación presencial',
            2 => 'Operación electrónica',
            3 => 'Operación telemarketing',
            4 => 'Venta a domicilio',
            5 => 'Operación bancaria',
            6 => 'Operación cíclica',
            9 => 'Otro',
        ];

        return $map[(int) $codigo] ?? 'Operación presencial';
    }

    protected function descTipoTransaccion($codigo)
    {
        $map = [
            1 => 'Venta de mercadería',
            2 => 'Prestación de servicios',
            3 => 'Mixto',
        ];

        return $map[(int) $codigo] ?? 'Venta de mercadería';
    }

    protected function fechaSolo($fecha)
    {
        if (empty($fecha)) {
            return date('Y-m-d');
        }

        return substr((string) $fecha, 0, 10);
    }

    protected function esc($value)
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    protected function num($value, $decimales = 0)
    {
        return number_format((float) $value, $decimales, '.', '');
    }

    protected function qty($value)
    {
        $qty = (float) $value;
        if (floor($qty) == $qty) {
            return number_format($qty, 0, '.', '');
        }

        return number_format($qty, 4, '.', '');
    }
}
