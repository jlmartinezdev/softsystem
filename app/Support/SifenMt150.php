<?php

namespace App\Support;

class SifenMt150
{
    const VERSION = 150;
    const NOTA_TECNICA_VIGENTE = 'NT23';

    const DOC_DNIT = 'https://www.dnit.gov.py/web/e-kuatia/documentacion-tecnica';
    const PREVALIDADOR_URL = 'https://ekuatia.set.gov.py/prevalidador/';
    const MANUAL_PDF = 'https://www.dnit.gov.py/documents/20123/420592/Manual+T%C3%A9cnico+Versi%C3%B3n+150.pdf';
    const NOTA_NT23_PDF = 'https://www.dnit.gov.py/documents/20123/420595/NT_E_KUATIA_023_MT_V150.pdf';

    public static function catalogos()
    {
        return [
            'tipo_emision' => [
                ['value' => 1, 'label' => '1 - Normal'],
                ['value' => 2, 'label' => '2 - Contingencia'],
            ],
            'tipo_documento' => [
                ['value' => 1, 'label' => '1 - Factura electrónica'],
                ['value' => 4, 'label' => '4 - Autofactura electrónica'],
                ['value' => 5, 'label' => '5 - Nota de crédito electrónica'],
                ['value' => 6, 'label' => '6 - Nota de débito electrónica'],
                ['value' => 7, 'label' => '7 - Nota de remisión electrónica'],
                ['value' => 8, 'label' => '8 - Comprobante de retención electrónico'],
            ],
            'tipo_transaccion' => [
                ['value' => 1, 'label' => '1 - Venta de mercadería'],
                ['value' => 2, 'label' => '2 - Prestación de servicios'],
                ['value' => 3, 'label' => '3 - Mixto (mercadería y servicios)'],
                ['value' => 4, 'label' => '4 - Venta de activo fijo'],
                ['value' => 5, 'label' => '5 - Venta de divisas'],
                ['value' => 6, 'label' => '6 - Compra de divisas'],
                ['value' => 7, 'label' => '7 - Promoción o entrega de muestras'],
                ['value' => 8, 'label' => '8 - Donación'],
                ['value' => 9, 'label' => '9 - Anticipo'],
                ['value' => 10, 'label' => '10 - Compra de productos'],
                ['value' => 11, 'label' => '11 - Compra de servicios'],
                ['value' => 12, 'label' => '12 - Venta de crédito fiscal'],
                ['value' => 13, 'label' => '13 - Muestras médicas'],
            ],
            'tipo_impuesto' => [
                ['value' => 1, 'label' => '1 - IVA'],
                ['value' => 2, 'label' => '2 - ISC'],
                ['value' => 3, 'label' => '3 - Renta'],
                ['value' => 4, 'label' => '4 - Ninguno'],
                ['value' => 5, 'label' => '5 - IVA - Renta'],
            ],
            'tipo_contribuyente' => [
                ['value' => 1, 'label' => '1 - Persona física'],
                ['value' => 2, 'label' => '2 - Persona jurídica'],
            ],
            'indicador_presencia' => [
                ['value' => 1, 'label' => '1 - Operación presencial'],
                ['value' => 2, 'label' => '2 - Operación electrónica'],
                ['value' => 3, 'label' => '3 - Operación telemarketing'],
                ['value' => 4, 'label' => '4 - Venta a domicilio'],
                ['value' => 5, 'label' => '5 - Operación bancaria'],
                ['value' => 6, 'label' => '6 - Operación cíclica'],
                ['value' => 9, 'label' => '9 - Otro'],
            ],
            'condicion_operacion' => [
                ['value' => 1, 'label' => '1 - Contado'],
                ['value' => 2, 'label' => '2 - Crédito'],
            ],
            'natura_receptor' => [
                ['value' => 1, 'label' => '1 - Contribuyente'],
                ['value' => 2, 'label' => '2 - No contribuyente'],
            ],
            'tipo_operacion' => [
                ['value' => 1, 'label' => '1 - B2B'],
                ['value' => 2, 'label' => '2 - B2C'],
                ['value' => 3, 'label' => '3 - B2G'],
                ['value' => 4, 'label' => '4 - B2F'],
            ],
        ];
    }

    public static function notasTecnicas()
    {
        return [
            [
                'codigo' => 'NT23',
                'titulo' => 'Nota Técnica N° 23 (27/08/2024)',
                'vigente' => true,
                'url' => self::NOTA_NT23_PDF,
                'puntos' => [
                    'Ajustes en receptor D200-D299: D208/D210 según D201 y D202.',
                    'Si D201 = Contribuyente, no informar D208 ni D210 (NT23).',
                    'D208 no puede ser innominado (5) cuando corresponda documento de identidad.',
                    'Campo opcional H018 dRucFus para RUC fusionado en documento asociado.',
                    'Cantidad E711 ampliada a 8 decimales; sector energía eléctrica E791 hasta 9 ocurrencias.',
                ],
            ],
        ];
    }

    public static function defaultsConfig()
    {
        return [
            'version_formato' => self::VERSION,
            'nota_tecnica' => self::NOTA_TECNICA_VIGENTE,
            'tipo_emision' => 1,
            'tipo_documento' => 1,
            'tipo_transaccion' => 1,
            'tipo_impuesto' => 1,
            'moneda' => 'PYG',
            'tipo_contribuyente' => 2,
            'indicador_presencia' => 1,
            'condicion_operacion_defecto' => 1,
            'numero_casa' => '0',
            'natura_receptor_defecto' => 2,
            'tipo_operacion_defecto' => 2,
            'pais_receptor_defecto' => 'PRY',
        ];
    }

    public static function reglasReceptorNt23($naturaReceptor, $tipoOperacion)
    {
        $naturaReceptor = (int) $naturaReceptor;
        $tipoOperacion = (int) $tipoOperacion;

        if ($naturaReceptor === 1) {
            return 'No informar D208/D210 (tipo y número de documento del receptor).';
        }

        if ($tipoOperacion === 4) {
            return 'Operación B2F: validar identificación del receptor según Manual v150 y NT23.';
        }

        return 'Informar D208 y D210. D208 ≠ 5 (innominado) cuando aplique NT23.';
    }
}
