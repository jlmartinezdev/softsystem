<?php

namespace App\Http\Controllers;

use App\Ajuste;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $ajuste= Ajuste::all();
        return view('configuracion',compact('ajuste'));
    }
    public function update( Request $request){
        foreach($request->caja as $caja){
            Ajuste::where('name',$caja['name'])->update(['value'=>$caja['value']]);
        }
        return "ok";
        
    }
    public function escribe_ini($matriz, $archivo, $multi_secciones = true, $modo = 'w') {
        $salida = '';
    
        # saltos de línea (usar "\r\n" para Windows)
        define('SALTO', "\n");
    
        if (!is_array(current($matriz))) {
            $tmp = $matriz;
            $matriz['tmp'] = $tmp; # no importa el nombre de la sección, no se usará
            unset($tmp);
        }
    
        foreach($matriz as $clave => $matriz_interior) {
            if ($multi_secciones) {
                $salida .= '['.$clave.']'.SALTO;
            }
    
            foreach($matriz_interior as $clave2 => $valor)
                $salida .= $clave2.' = "'.$valor.'"'.SALTO;
    
            if ($multi_secciones) {
                $salida .= SALTO;
            }
        }
    
        $puntero_archivo = fopen($archivo, $modo);
    
        if ($puntero_archivo !== false) {
            $escribo = fwrite($puntero_archivo, $salida);
    
            if ($escribo === false) {
                $devolver = -2;
            } else {
                $devolver = $escribo;
            }
    
            fclose($puntero_archivo);
        } else {
            $devolver = -1;
        }
    
        return $devolver;
    }
}