@extends('layouts.app')
@section('title', 'Ajuste Sistema')
@section('main')
    <br>
    <div class="container" id="app">
        <div class="card">
            <div class="card-header">
                Ajustes
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header"><span class="text-muted"><strong>Caja</strong></span></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Validez Apertura</label>
                                    <select class="form-control form-control-sm">
                                        <option value="1">Dia a dia por Fecha</option>
                                        <option value="2">Cada 24 horas</option>
                                        <option value="3">Indefinido</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <select class="form-control form-control-sm">
                                        <option value="1">Una apertura multiusuario</option>
                                        <option value="2">Una apertura por cada usuario</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header"><span class="text-muted"><strong>Venta</strong></span></div>
                            <div class="card-body">
                                <label ">Prederminado Compobante</label>
                                <select class="form-control form-control-sm">
                                    <option value="ticket">Ticket</option>
                                    <option value="documento">Comprabante</option>
                                    <option value="factura">Factura</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
