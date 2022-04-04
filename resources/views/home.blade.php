@extends('layouts.app')
@section('title', 'SOFTSYSTEM')
@section('style')
<style>
    @font-face {
    font-family: "Cairo";
    font-style: normal;
    font-weight: 400;
    font-display: auto;
    src: url({{ asset("webfonts/Cairo-Bold.ttf")}}) format("truetype");
}
.font-cairo {
    font-family: Cairo;
}
</style>
@endsection
@section('main')
    <div class="container">
        <h3 class="text-uppercase text-center font-cairo text-primary">{{ $empresa->emp_nombre }}</h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{$n_articulos}}</h3>
                    <p>Articulos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-box"></i>
                </div>
                <a href="articulo" class="small-box-footer">Abrir <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-lightblue">
                <div class="inner">
                    <h3>{{$n_clientes}}</h3>
                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="cliente" class="small-box-footer">Abrir <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$n_ventas}}</h3>
                    <p>Ventas {{$mes}}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="venta" class="small-box-footer">Abrir <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$n_cobros}}</h3>
                    <p>Cobro {{$mes}}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-check"></i>
                </div>
                <a href="cobro" class="small-box-footer">Abrir <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
    </div>
    <hr>
@endsection
