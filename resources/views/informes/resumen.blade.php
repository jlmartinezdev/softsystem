@extends('layouts.app')
@section('title', 'Resumen General')
@section('style')
    <style>
        .chart {
            min-height: 250px;
        }

        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            height: 390px;
            overflow-y: auto;
        }

        .borderless td,
        .borderless th {
            border: none;
        }
    </style>

@endsection
@section('main')
    <div class="container" id="app">
        <div class="card">
            <div class="card-header">
                <span class="text-primary"><strong>RESUMEN GENERAL</strong></span>
            </div>
            <div class="card-body">
                <div class="form-inline mb-3">
                    <strong><label for="desde">Desde: </label></strong>
                    <input type="date" class="form-control form-control-sm mx-2" v-model="fecha.desde" name="desde"
                        placeholder="Desde Fecha">

                    <strong><label for="hasta">Hasta: </label></strong>
                    <input type="date" class="form-control form-control-sm  mx-2" v-model="fecha.hasta" name="hasta"
                        placeholder="Hasta Fecha">

                    <strong><label for="hasta">Sucursal: </label></strong>
                    <select class="form-control form-control-sm mx-2" v-model="idSucursal">
                        <option value="0">Todas</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                        @endforeach
                    </select>


                    <strong><label for="hasta">&nbsp;</label></strong>
                    <button @click="getData" class="btn btn-primary btn-sm">
                        <template v-if="requestSend">
                            <span class="spinner-border spinner-border-sm" role="status"></span><span
                                class="sr-only">Cargando...</span> Cargando...
                        </template>
                        <template v-else>
                            <span class="fa fa-search"></span> Buscar
                        </template>
                    </button>&nbsp;
                </div>
            </div>
        </div>
        <div class="row">
            <!-- div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cantidad Venta</span>
                                        <span class="info-box-number">
                                            10
                                        </span>
                                    </div>

                                </div>

                            </div-->

            <div class="col-12 col-sm-4">
                <div class="card">
                    <div class="p-2 d-flex align-items-center bg-success">
                        <span class="text-xl pr-3"><i class="fas fa-shopping-cart"></i></span>
                        RESUMEN VENTA
                    </div>

                    <table class="table table-sm borderless">
                        <tr>
                            <td>Cantidad Venta</td>
                            <td align="right"><strong>@{{ ventas.cantidad }}</strong></td>
                        </tr>
                        <tr>
                            <td>Venta Contado</td>
                            <td align="right"><strong>Gs <template>@{{ ventas.contado }}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Venta Credito</td>
                            <td align="right"><strong>Gs <template>@{{ ventas.credito }}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Monto Venta</td>
                            <td align="right"><strong>Gs <template>@{{ ventas.total }}</template></strong></td>
                        </tr>

                    </table>
                    <div class="card-footer">
                        <span class="text-success"><strong>Ganancia: Gs <template>@{{ ventas.ganancia }}</template>
                                </strong></span>
                    </div>

                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="card">
                    <div class="p-2 d-flex align-items-center bg-maroon">
                        <span class="text-xl pr-3"><i class="fas fa-shopping-bag"></i></span>
                        RESUMEN COMPRA
                    </div>

                    <table class="table table-sm borderless">
                        <tr>
                            <td>Cantidad Compra</td>
                            <td align="right"><strong>@{{ compras.cantidad }}</strong></td>
                        </tr>
                        <tr>
                            <td>Compra Contado</td>
                            <td align="right"><strong>Gs <template>@{{ compras.contado }}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Compra Credito</td>
                            <td align="right"><strong>Gs <template>@{{ compras.credito }}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Monto Compra</td>
                            <td align="right"><strong>Gs <template>@{{ compras.total }}</template></strong></td>
                        </tr>

                    </table>
                    <div class="card-footer">
                        &nbsp;
                    </div>

                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="card">
                    <div class="p-2 d-flex align-items-center bg-olive">
                        <span class="text-xl pr-3"><i class="fas fa-box-open"></i></span>
                        ARTICULOS
                    </div>

                    <table class="table table-sm borderless">
                        <tr>
                            <td>Cantidad Articulos</td>
                            <td align="right"><strong><template>@{{articulo.cantidad}}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Total Stock</td>
                            <td align="right"><strong><template>@{{articulo.stock}}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Total Costo:</td>
                            <td align="right"><strong>Gs. <template>@{{articulo.costo}}</template></strong></td>
                        </tr>
                        <tr>
                            <td>Total Venta:</td>
                            <td align="right"><strong><template>Gs. @{{articulo.venta}}</template></strong></td>
                        </tr>

                    </table>
                    <div class="card-footer">
                        <span class="text-olive"><strong>Articulo con Stock 0: <template>@{{articulo.stock0}}</template> </strong></span>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script type="text/javascript" src="chart/raphael.min.js"></script>
    <script type="text/javascript" src="chart/morris.min.js"></script>

    <script type="text/javascript">
        Vue.prototype.Funciones = window.Funciones;
        var app = new Vue({
            el: '#app',
            data: {
                meses: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre",
                    "Octubre", "Noviembre", "Diciembre"
                ],
                anhos: ["2023", "2022", "2021", "2020", "2019", "2018", "2017"],
                fecha: {
                    desde: '2020-01-01',
                    hasta: '2020-01-01'
                },
                filtro: {
                    ordenarpor: 1,
                    orden: 'ASC'
                },
                ventas: {
                    cantidad: 0,
                    contado: 0,
                    credito: 0,
                    total: 0,
                    ganancia: 0
                },
                compras: {
                    cantidad: 0,
                    contado: 0,
                    credito: 0,
                    total: 0,
                },
                articulo: {
                    cantidad: 0,
                    stock: 0,
                    costo: 0,
                    venta: 0,
                    stock0: 0
                },
                idSucursal: 0,
                requestSend: false,

            },
            methods: {

                getFecha: function(flag) {

                    var f = new Date();
                    var dia = flag == 1 ? 1 : f.getDate();
                    var mes = (f.getMonth() + 1);
                    return f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString()
                        .padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                getData: function() {
                    if (this.requestSend) {
                        return false;
                    }
                    this.requestSend = true
                    axios.get('resumen/datos', {
                            params: {
                                desde: this.fecha.desde,
                                hasta: this.fecha.hasta,
                                idsucursal: this.idSucursal
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            if (response.data.venta) {
                                const venta = response.data.venta;
                                this.ventas.cantidad = venta.cantidad;
                                this.ventas.contado = this.millares(venta.contado);
                                this.ventas.credito = this.millares(venta.credito);
                                this.ventas.total = this.millares(venta.total);
                                this.ventas.ganancia =this.millares(venta.ganancia);
                                const compra= response.data.compra;
                                this.compras.cantidad = compra.cantidad;
                                this.compras.contado = this.millares(compra.contado);
                                this.compras.credito = this.millares(compra.credito);
                                this.compras.total = this.millares(compra.total -compra.descuento);
                                const articulo= response.data.articulo;
                                this.articulo.cantidad = articulo.cantidad;
                                this.articulo.stock = articulo.stock;
                                this.articulo.costo = this.millares(articulo.costo);
                                this.articulo.venta = this.millares(articulo.venta);
                                this.articulo.stock0 = articulo.sin_stock;

                            }
                        })
                        .catch(error => {
                            this.requestSend = false;
                            console.log(error);
                        });
                },
                millares(value) {
                    return new Intl.NumberFormat("de-DE").format(value);
                },

            },
            computed: {

            },
            mounted() {
                this.getFecha(2); // Configura mes actual
                this.fecha = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.getData();

            }
        });
        activarMenu('m_informe', 'm_iresumen');
    </script>
@endsection
