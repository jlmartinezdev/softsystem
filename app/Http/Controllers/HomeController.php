<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\Cliente;
use App\Articulo;
use App\Venta;
use App\Cobro;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"];
        $mes= $meses[date('m')-1];
        $empresa = Empresa::first();
        $n_clientes= Cliente::count();
        $n_articulos = Articulo::count();
        $n_cobros= Cobro::whereBetween('cobranzas.cob_fecha',[date('Y').'-'.date('m').'-01',date('Y-m-d')])->count();
        $n_ventas = Venta::whereBetween(DB::raw("date(ventas.venta_fecha)"),[date('Y').'-'.date('m').'-01',date('Y-m-d')])->count();
        return view('home',compact('empresa','n_clientes','n_articulos','n_ventas','mes','n_cobros'));
    }
}
