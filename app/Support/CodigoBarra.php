<?php

namespace App\Support;

use App\Articulo;
use App\Combo;
use App\Oferta;
use Illuminate\Support\Facades\Schema;

class CodigoBarra
{
    const RESERVADOS = ['VARIOS', 'COMBO'];

    public static function normalizar($codigo)
    {
        return trim((string) $codigo);
    }

    /**
     * @param  string  $codigo
     * @param  array   $except  ['combo_id' => int|null, 'oferta_id' => int|null]
     * @return array{ok:bool,message:string,codigo:string}
     */
    public static function check($codigo, array $except = [])
    {
        $codigo = self::normalizar($codigo);

        if ($codigo === '') {
            return ['ok' => false, 'message' => 'El código de barras es obligatorio.', 'codigo' => ''];
        }

        if (mb_strlen($codigo) < 3) {
            return ['ok' => false, 'message' => 'El código de barras debe tener al menos 3 caracteres.', 'codigo' => $codigo];
        }

        if (mb_strlen($codigo) > 40) {
            return ['ok' => false, 'message' => 'El código de barras no puede superar 40 caracteres.', 'codigo' => $codigo];
        }

        if (!preg_match('/^[A-Za-z0-9\-_\/\.]+$/', $codigo)) {
            return [
                'ok' => false,
                'message' => 'El código solo puede tener letras, números y los símbolos - _ / .',
                'codigo' => $codigo,
            ];
        }

        if (in_array(strtoupper($codigo), self::RESERVADOS, true)) {
            return ['ok' => false, 'message' => 'Ese código está reservado por el sistema.', 'codigo' => $codigo];
        }

        $articulo = Articulo::where('producto_c_barra', $codigo)->first();
        if ($articulo) {
            $nombre = $articulo->producto_nombre ?: ('#' . $articulo->ARTICULOS_cod);
            return [
                'ok' => false,
                'message' => 'El código ya existe en un artículo (' . $nombre . ').',
                'codigo' => $codigo,
            ];
        }

        $comboQuery = Combo::where('codigo', $codigo);
        if (!empty($except['combo_id'])) {
            $comboQuery->where('id', '!=', (int) $except['combo_id']);
        }
        if ($comboQuery->exists()) {
            return ['ok' => false, 'message' => 'El código ya está usado en otro combo.', 'codigo' => $codigo];
        }

        if (Schema::hasColumn('ofertas', 'codigo')) {
            $ofertaQuery = Oferta::where('codigo', $codigo);
            if (!empty($except['oferta_id'])) {
                $ofertaQuery->where('id', '!=', (int) $except['oferta_id']);
            }
            if ($ofertaQuery->exists()) {
                return ['ok' => false, 'message' => 'El código ya está usado en otra oferta.', 'codigo' => $codigo];
            }
        }

        return ['ok' => true, 'message' => 'Código disponible.', 'codigo' => $codigo];
    }

    public static function validarOFallar($codigo, array $except = [])
    {
        $result = self::check($codigo, $except);
        if (!$result['ok']) {
            abort(response()->json(['ok' => false, 'message' => $result['message']], 422));
        }

        return $result['codigo'];
    }
}
