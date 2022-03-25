@extends('layouts.app')
@section('title', 'SOFTSYSTEM')
@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="container d-flex align-items-center justify-content-center">
                            <article class="px-3 py-3">
                                <img src="img/logo-softsystem.PNG">
                                <center><h4>SOFTSYSTEM</h4></center> 
                                <hr>
                                <a class="btn btn-app">
                                    <span class="badge bg-success">300</span>
                                    <i class="fas fa-barcode"></i> Articulo
                                </a>
                                <a class="btn btn-app">
                                    <span class="badge bg-purple">3</span>
                                    <i class="fas fa-users"></i> Clientes
                                </a>
                                <a class="btn btn-app">
                                    <span class="badge bg-teal">30</span>
                                    <i class="fas fa-inbox"></i> Clientes
                                </a>
                            </article>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
