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
                                    <select v-model="caja[0].value" class="form-control form-control-sm">
                                        <option value="1">Dia a dia por Fecha</option>
                                        <option value="2">Cada 24 horas</option>
                                        <option value="3">Indefinido</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <select v-model="caja[1].value" class="form-control form-control-sm">
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
                                <div class="form-group">
                                    <label>Prederminado Compobante</label>
                                    <select v-model="venta.tipo_comprobante" class="form-control form-control-sm">
                                        <option value="Ticket">Ticket</option>
                                        <option value="Comprobante">Comprobante</option>
                                        <option value="Factura">Factura</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Descontar Stock</label>
                                    <select v-model="venta.descontar_stock" class="form-control form-control-sm">
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Vender articulos sin stock</label>
                                    <select v-model="venta.vender_sin_stock" class="form-control form-control-sm">
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-footer">
                <button class="btn btn-success btn-flat" @click="update"><span class="fa fa-save"></span> Guardar</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
        });
        var app = new Vue({
            el: '#app',
            data: {
                caja: [{
                    name: 'validez',
                    value: {{ $ajuste[0]->value}},
                }, {
                    name: 'usuario',
                    value: {{ $ajuste[1]->value}}
                }],
                venta: {
                    tipo_comprobante: "Ticket",
                    descontar_stock: 1,
                    vender_sin_stock: 1
                }
            },
            methods: {
                updateCaja() {
                    axios.post('ajustes', {
                            caja: this.caja
                        })
                        .then(response => {
                            Toast.fire({
                                title: 'Ajustes actualizados...',
                                icon: 'info'
                            })
                        })
                        .catch(error => {
                            console.log(error)
                        })
                },
                updateVenta() {
                    localStorage.setItem('config_venta', JSON.stringify(this.venta));
                },
                getConfigVenta() {
                    var venta = localStorage.getItem('config_venta');
                    if (venta != null) {
                        this.venta = JSON.parse(venta);
                    }

                },
                update() {
                    this.updateVenta();
                    this.updateCaja();
                }
            },
            mounted() {
                this.getConfigVenta();
            }
        })
    </script>
@endsection
