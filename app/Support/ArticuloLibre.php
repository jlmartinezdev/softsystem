<?php

namespace App\Support;

use App\Articulo;
use App\Stock;
use App\Sucursal;
use DB;

class ArticuloLibre
{
    const CODIGO_BARRA = 'VARIOS';
    const NOMBRE = 'VARIOS / ITEM LIBRE';

    public static function ensureExists()
    {
        $articulo = Articulo::where('producto_c_barra', self::CODIGO_BARRA)->first();

        if (!$articulo) {
            $id = (int) (Articulo::max('ARTICULOS_cod') ?: 0) + 1;

            DB::table('articulos')->insert([
                'ARTICULOS_cod' => $id,
                'uni_codigo' => 1,
                'present_cod' => 1,
                'producto_c_barra' => self::CODIGO_BARRA,
                'producto_nombre' => self::NOMBRE,
                'producto_costo_compra' => 0,
                'producto_costo_venta' => 0,
                'foto' => '',
                'producto_fecHab' => '0',
                'producto_vencimiento' => '2030-01-01',
                'pre_venta1' => 0,
                'pre_venta2' => 0,
                'pre_venta3' => 0,
                'pre_venta4' => 0,
                'pre_venta5' => 0,
                'producto_ubicacion' => '',
                'producto_peso' => '0',
                'producto_factor' => 1,
                'pre_margen1' => 0,
                'pre_margen2' => 0,
                'pre_margen3' => 0,
                'pre_margen4' => 0,
                'pre_margen5' => 0,
                'producto_indicaciones' => '',
                'producto_dosis' => '',
                'producto_formula' => '',
                'producto_dimagen' => '',
            ]);

            $articulo = Articulo::where('ARTICULOS_cod', $id)->first();
        }

        $articuloId = $articulo->ARTICULOS_cod ?? $articulo->articulos_cod;

        foreach (Sucursal::all() as $sucursal) {
            $exists = Stock::where('ARTICULOS_cod', $articuloId)
                ->where('suc_cod', $sucursal->suc_cod)
                ->exists();

            if (!$exists) {
                $stock = new Stock();
                $stock->articulos_cod = $articuloId;
                $stock->suc_cod = $sucursal->suc_cod;
                $stock->cantidad = 0;
                $stock->stock_fech_venc = '2030-01-01';
                $stock->lote_nro = '';
                $stock->save();
            }
        }

        return (int) $articuloId;
    }

    public static function id()
    {
        return self::ensureExists();
    }

    public static function esLibre($articuloId)
    {
        if (!$articuloId) {
            return false;
        }

        return Articulo::where('ARTICULOS_cod', $articuloId)
            ->where('producto_c_barra', self::CODIGO_BARRA)
            ->exists();
    }
}
