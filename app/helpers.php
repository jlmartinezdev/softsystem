<?php 
function setActive($ruta){
	return request()->routeIs($ruta)? 'active' :'';
}
function filtrarCtas($a1,$nroventa){
    return $filteredArray = Arr::where($a1->toArray(), function ($value, $key) use($nroventa) {
        return $value['nro_fact_ventas'] == $nroventa;
    });
   /*  array_filter($a1,function($e){
        return $e->nro_fact_ventas==$nroventa;
    }); */
}
function diferenciaFecha($fecha){
    $date1 = new DateTime($fecha);
    $date2 = new DateTime("now");
    $diff = $date1->diff($date2);
    return $date2 > $date1 ? $diff->days :"-".$diff->days;
}
function subFecha($fecha) {
    $date1 = new DateTime($fecha);
    $date2 = new DateTime("now");
    $diff = $date1->diff($date2);
    return $diff->days;
}
function setMontointeres($vencimiento, $monto, $isSaldo){
    if ($isSaldo==0)
        return 0;
    
    $montoInteres = 0;
    $interes_mora = 100;
    $tmp_vencimiento = subFecha($vencimiento);

    if ($interes_mora > 0 && $tmp_vencimiento > 5) {
        $montoInteres = ($monto * $interes_mora) / 100;
        $montoInteres = $montoInteres / 360;
        $montoInteres = $montoInteres * $tmp_vencimiento;
    }
    return intval($montoInteres);
}
function diferenciaFechaCompleta($fecha){
    $firstDate  = new DateTime($fecha);
    $secondDate = new DateTime("now");
    $intvl = $firstDate->diff($secondDate);
    $anho= '';
    $mes= '';
    $dia= '';
    $dias= $intvl->days;
    if($intvl->y >0 ){
        $anho= $intvl->y > 1 ? $intvl->y." años,": $intvl->y." año,";
    }
    if($intvl->m > 0){
        $mes= $intvl->m > 1 ? $intvl->m." meses": $intvl->m." mes";
    }
    $d= $intvl->d > 1 ? $intvl->d." dias": $intvl->d." dia";
    $dia = strlen($mes)>0 ? " y ".$d : $d;
    if($secondDate>$firstDate ){
        return ['dias' =>$dias, 'detalle' =>" (".$anho.$mes.$dia.")"]; 
    }else{
        return ['dias' => 0,'detalle'=> "Falta: ".$dias." dias"];
    }
    

}
function isFechaMayor($fecha){
    $fecha_inicio = new DateTime("now");
    $fecha_fin    = new DateTime($fecha);
    if($fecha_fin > $fecha_inicio){
        return true;
    }else{
        return false;
    }
        
}
class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false)
    {
        $converted = '';
        $decimales = '';

        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode('.',$number);

        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'CERO ';
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }

        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }

        return $valor_convertido;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}

?>