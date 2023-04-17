@extends('layouts.app')
@section('title', 'Imprimir Ventas')
@section('style')
    <style>
        table {
            font-family: Arial, Helvetica, sans-serif;

        }

        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            height: 390px;
            overflow-y: auto;
        }

        @font-face {
            font-family: "Sofia";
            font-style: normal;
            font-weight: 400;
            font-display: auto;
            src: url({{ asset('webfonts/SofiaSans-Regular.ttf') }}) format("truetype");
        }

        #main {
            font-family: 'Sofia';
        }
    </style>
@endsection
@section('main')

    <div class="container" id="app">
        <h4>:: IMPRIMIR VENTA ::</h4>
        <div class="input-group">
            <input type="text" v-model="txtbuscar" @keyup.enter="getVenta()" class="form-control"
                placeholder="Buscar Cliente...." tabindex="1" />
            <div class="input-group-append">
                <button class="btn btn-secondary" @click="getVenta()">
                    <template v-if="requestSend">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        <span class="sr-only">Buscando...</span> Cargando...
                    </template>
                    <template v-else>
                        <span class="fa fa-search"></span> Buscar
                    </template>
                </button>
            </div>
        </div>
        <hr>
        <div class="table-responsive-sm">
            <table class="table table-sm table-hover table-striped" style="font-size: 11pt;">
                <tr>
                    <th>Opciones</th>
                    <th>Nro Venta</th>
                    <th>Fecha Hora</th>
                    <th>Cliente</th>
                    <th>Celular</th>
                    <th>Tipo</th>
                    <th class="text-right">Total</th>

                </tr>
                <template v-if="ventas.length==0">
                    <tr>
                        <td colspan="8">No hay resultado para mostrar</td>
                    </tr>
                </template>
                <template v-for="venta in ventas">
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <span class="fa fa-bars"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <button class="dropdown-item  text-primary" @click="showDetalle(venta)">
                                        <span class="fa fa-file-alt"></span> Detalle</button>
                                    <a :href="'{{ env('APP_URL') }}' + 'ticket/venta/'+ venta.nro_fact_ventas "
                                        class="dropdown-item text-primary"><span class="fa fa-print"></span>
                                        Ticket</a>
                                    <a :href="'{{ env('APP_URL') }}' + 'pdf/boletaventa/' + venta.nro_fact_ventas + '/'"
                                        class="dropdown-item text-primary"><span class="fa fa-print"></span>
                                        Comprobante</a>
                                    <a :href="'facturar/' + venta.nro_fact_ventas + ''"
                                        class="dropdown-item text-primary"><span class="fa fa-print"></span>
                                        Facturar</a>
                                </div>
                            </div>
                            <!-- <button class="btn btn-link" @click="showDetalle(venta)"><span class="fa fa-file-alt"></span> Detalle</button>
                                        <a :href="'facturar/' + venta.nro_fact_ventas + ''" class="btn btn-link"><span class="fa fa-print"></span> Facturar</a>
                                    -->
                        </td>
                        <td>@{{ venta.nro_fact_ventas }}</td>
                        <td>@{{ venta.fecha }}</td>
                        <td>@{{ venta.cliente_nombre }}</td>
                        <td>@{{ venta.cliente_cel }}</td>
                        <td>@{{ venta.documento }}</td>
                        <td class="text-right font-weight-bold">@{{ new Intl.NumberFormat("de-DE").format(venta.venta_total) }}</td>


                    </tr>
                </template>
            </table>
        </div>


        <div class="modal fade" id="frmdetalle">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h6 class="modal-title">Detalle Venta</h6>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-3 border-right">
                                <div class="text-center">
                                    <span class="fa fa-grip-horizontal text-primary"></span>
                                    <strong> Nro de Venta</strong>
                                    <span class="d-block"> @{{ venta.nro_fact_ventas }}</span>
                                </div>

                            </div>
                            <div class="col-sm-3 border-right">
                                <div class="text-center">
                                    <span class="fa fa-calendar text-warning"></span><strong> Fecha</strong>
                                    <span class="d-block"> @{{ venta.fecha }}</span>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <span class="fa fa-user-circle text-info"></span><strong> Cliente</strong>
                                    <span class="d-block">@{{ venta.cliente_nombre }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                Condicion de Venta:<strong> @{{ venta.tipo_factura == 1 ? "Contado" : "Credito" }}</strong>
                            </div>
                            <div class="col-4">

                                Descuento: <strong>@{{ new Intl.NumberFormat("de-DE").format(venta.venta_descuento) }} Gs.</strong>
                            </div>
                            <div class="col-4">
                                Total: <strong>
                                    @{{ new Intl.NumberFormat("de-DE").format(venta.venta_total) }} Gs.</strong>
                            </div>
                        </div>
                        <span class="badge bg-info">Detalle Venta</span>
                        <table class="table table-sm">
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Importe</th>
                            </tr>
                            <template v-for="d in detalleVenta">
                                <tr>
                                    <td>@{{ d.producto_c_barra }}</td>
                                    <td>@{{ d.producto_nombre }}</td>
                                    <td>@{{ parseInt(d.venta_cantidad) }}</td>
                                    <td>@{{ new Intl.NumberFormat("de-DE").format(d.venta_precio) }}</td>
                                    <td>@{{ new Intl.NumberFormat("de-DE").format(d.venta_cantidad * d.venta_precio) }}</td>
                                </tr>
                            </template>
                        </table>
                        <template v-if="venta.tipo_factura==2">
                            <span class="badge bg-success">Detalle Cuotas</span>
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th>Nro Cuota</th>
                                    <th>Vencimiento</th>
                                    <th>Monto Cuota</th>
                                    <th>Monto Cobrado</th>
                                    <th>Saldo</th>
                                </tr>
                                <template v-for="c in cuotas">
                                    <tr>
                                        <td>@{{ c.nro_cuotas }}</td>
                                        <td>@{{ formatFecha(c.fecha_venc) }}</td>
                                        <td>@{{ new Intl.NumberFormat("de-DE").format(c.monto_cuota) }}</td>
                                        <td>@{{ new Intl.NumberFormat("de-DE").format(c.monto_cobrado) }}</td>
                                        <td>@{{ new Intl.NumberFormat("de-DE").format(c.monto_saldo) }}</td>
                                    </tr>
                                </template>
                            </table>
                            <div class="row">
                                <div class="col-4">
                                    Monto Cobrado: <strong>@{{ new Intl.NumberFormat("de-DE").format(Cuenta.montoCobrado) }}</strong>
                                </div>
                                <div class="col-4">
                                    Saldo: <strong>@{{ new Intl.NumberFormat("de-DE").format(Cuenta.saldo) }}</strong>
                                </div>
                            </div>
                        </template>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                class="fa fa-times"></span> Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                ventas: [],
                cuotas: [],
                Cuenta: {
                    cantitad: 0,
                    montoCuota: 0,
                    saldo: 0,
                    cobrado: 0,
                    montoCobrado: 0
                },
                txtbuscar: '',
                requestSend: false,
                idSucursal: 1,
                error: '',
                venta: {},
                detalleVenta: {}

            },
            methods: {
                showDetalle: function(venta) {
                    this.venta = venta;
                    $('#frmdetalle').modal('show');
                    this.getDetalle();
                },
                getDetalle: function() {
                    axios.get('{{ env('APP_URL') }}' + 'infventa/detalle/' + this.venta.nro_fact_ventas)
                        .then(response => {
                            this.detalleVenta = response.data;
                        })
                        .catch(error => {
                            console.log(error.message);
                        })
                    if (this.venta.tipo_factura == '2') {
                        this.getCta();
                    }
                },
                getCta: function() {
                    axios.get('{{ env('APP_URL') }}' + 'cuotas/' + this.venta.nro_fact_ventas)
                        .then(response => {
                            const c = response.data;
                            this.cuotas = c;
                            let saldo = 0;
                            let cobrado = 0;

                            for (let i = 0; i < c.length; i++) {
                                saldo += parseInt(c[i].monto_saldo);
                                cobrado += parseInt(c[i].monto_cobrado);

                            }
                            this.Cuenta.saldo = saldo;
                            this.Cuenta.montoCobrado = cobrado;
                        })
                        .catch(error => {
                            console.log(error.message);
                        })
                },
                showBuscar: function() {

                },
                getVenta: function() {
                    this.requestSend = true;
                    const isNumber = isNaN(parseFloat(this.txtbuscar)) ? 0 : 1;

                    axios.get('{{ Route('infventa.cliente') }}', {
                            params: {
                                cliente: this.txtbuscar,
                                alls: this.idSucursal,
                                isNumber: isNumber
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            this.ventas = response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        })
                },
                getSucursal: function() {
                    var obj = document.getElementById("sucursal");
                    if (obj.getAttribute('data-id') != null)
                        this.idSucursal = obj.getAttribute('data-id');
                },
                formatFecha: function(fecha) {
                    const f = fecha.split("-");
                    return f[2] + "/" + f[1] + "/" + f[0];
                }
            },
            mounted() {
                this.getSucursal();
                this.getVenta();
            }
        })
    </script>
@endsection
