@extends('layouts.app')
@section('title', 'Estracto de Cuentas a Cobrar - ' . $empresa->emp_nombre)
@section('style')
    <style type="text/css" media="all">
        table td {
            font-size: 10pt;
            padding: 0px;
            /**/
        }

        .mystriped {
            background-color: #f2f2f2 !important;
        }

        .trsimple {
            line-height: 0.8em;
        }

        body {
            background-color: white;
            -webkit-print-color-adjust: exact;
        }


        @media print {
            .mystriped {
                background-color: #f2f2f2 !important;
            }

            #frmparametro {
                display: none;
            }

            .content-wrapper {
                background-color: white;
            }
        }

    </style>
@endsection
@section('main')
    <div id="app">
        <div class="container">
            <div class="card" id="frmparametro">
                <div class="card-header bg-dark font-weight-bold text-white">Informes de cuentas a cobrar</div>
                <div class="card-body">
                    <form action="{{ route('infctacobrar@pdf') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">

                                        <select class="custom-select" v-model="filtro.busquedapor">
                                            <option value="cliente">Cliente</option>
                                            <option value="direccion">Direccion</option>
                                        </select>
                                    </div>
                                    <input type="text" v-model="txtbuscar" name="buscar"
                                        @keydown.enter="$event.preventDefault();" @keyup.enter="buscar(false)"
                                        class="form-control" placeholder="Buscar...." tabindex="1" />
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" @click="$event.preventDefault();buscar(false)"
                                            type="button">
                                            <template v-if="requestSend">
                                                <span class="spinner-border spinner-border-sm" role="status"></span><span
                                                    class="sr-only">Buscando...</span> Cargando...
                                            </template>
                                            <template v-else>
                                                <span class="fa fa-search"></span> Buscar
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="d-flex">

                                    <div class="ml-2 pl-2">
                                        <select class="form-control" name="ordenarpor" @change="buscar(false)"
                                            v-model="filtro.ordenarpor">
                                            <option value="0">Ordenar Por</option>
                                            <option value="1">Nro. Venta</option>
                                            <option value="2">Documento</option>
                                            <option value="3">Cliente</option>
                                            <option value="4">Fecha</option>
                                            <option value="5">Cant. cuota</option>
                                            <option value="6">Total</option>
                                            <option value="7">Saldo</option>
                                        </select>
                                    </div>
                                    <div class="ml-2 pl-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="ASC" @click="buscar(false)"
                                                name="ord" v-model="filtro.orden" id="defaultCheck3">
                                            <label class="form-check-label" for="defaultCheck3">
                                                ASC
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="DESC" @click="buscar(false)"
                                                v-model="filtro.orden" name="ord" id="defaultCheck4">
                                            <label class="form-check-label" for="defaultCheck4">
                                                DESC
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ml-2 pl-2">
                                        <select class="form-control" name="ordenarpor" @change="buscar(false)"
                                            v-model="filtro.tipo">
                                            <option value="1">Detallado</option>
                                            <option value="2">Resumido</option>
                                        </select>
                                    </div>
                                    <div class="ml-2 pb-2">
                                        <button class="btn btn-primary" onclick="javascript:window.print()"><span
                                                class="fa fa-print"></span>
                                            Imprimir</button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <template v-if="ctas.length > 0">
                <div class="card">
                    <div class="card-body">
                        <template v-if="filtro.tipo==1">
                            <table class="table table-sm table-borderless ">
                                <tr>
                                    <th>Resultado de busqueda para <span
                                            class="text-primary">@{{ txtbuscar }}</span>
                                        Cuentas a Cobrar</th>
                                </tr>
                                <template v-for="(c,index) in ctas">
                                    <tr>
                                        <!-- :class="{'mystriped': index % 2==0}" -->
                                        <td>
                                            <table class="table table-sm border border-top">

                                                <tr class="mb-4 font-weight-bold text-primary">
                                                    <td><span class="fa fa-address-card text-secondary"></span>
                                                        @{{ c.cliente_ruc }}</td>
                                                    <td colspan="2"><span class="fa fa-user text-secondary"></span>
                                                        @{{ c.cliente_nombre }}</td>
                                                    <td colspan="2"><span
                                                            class="fa fa-map-marker-alt text-secondary"></span>
                                                        @{{ c.cliente_direccion }}</td>
                                                    <td colspan="2"><span class="fa fa-phone-alt text-secondary"></span>
                                                        @{{ c.cliente_cel }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Nro. Venta</th>
                                                    <th>Fecha Venta</th>
                                                    <th>Importe</th>
                                                    <th>Cobrado/ Cuota</th>
                                                    <th>Entrega + Cuota Cobrado</th>
                                                    <th>Saldo</th>
                                                    <th>Atraso</th>
                                                </tr>
                                                <tr class="trsimple">
                                                    <td>@{{ c.nro_fact_ventas }}</td>
                                                    <td>@{{ c.venta_fecha }}</td>
                                                    <td>@{{ format(c.total) }}</td>
                                                    <td>@{{ (c.pagada) + " de " + (c.cuotas) }}</td>
                                                    <td>@{{ format(c.cobrado) }}</td>
                                                    <td class="text-danger font-weight-bold">@{{ format(c.saldo) }}</td>
                                                    <td>@{{ diferenciaFecha(c.fecha_v, c.pagada) + " dias" }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="border-bottom"><strong>Detalle de Venta</strong>
                                                        -
                                                        Descuento: @{{ format(c.venta_descuento) }} </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Codigo</strong></td>
                                                    <td colspan="2"><strong>Descripcion</strong></td>
                                                    <td><strong>Cantidad</strong></td>
                                                    <td class="text-right"><strong>Precio</strong></td>
                                                    <td class="text-right" colspan="2"><strong>Importe</strong></td>
                                                </tr>
                                                <template v-for="dv in detalleVenta(c.nro_fact_ventas)">
                                                    <tr>
                                                        <td>@{{ dv.producto_c_barra }}</td>
                                                        <td colspan="2">@{{ dv.producto_nombre }}</td>
                                                        <td>@{{ parseInt(dv.venta_cantidad) }}</td>
                                                        <td class="text-right">@{{ format(dv.venta_precio) }}</td>
                                                        <td class="text-right" colspan="2">@{{ format(dv.venta_cantidad * dv.venta_precio) }}</td>
                                                    </tr>
                                                </template>

                                            </table>
                                        </td>
                                    </tr>
                                </template>
                            </table>
                        </template>
                        <template v-else>
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Celular</th>
                                    <th>Fecha</th>
                                    <th>Venta Monto</th>
                                    <th>Saldo</th>
                                    <th>Atraso</th>
                                </tr>
                                <template v-for="(c,index) in ctas">
                                    <tr>
                                        <td>@{{ c.cliente_ruc }}</td>
                                        <td>@{{ c.cliente_nombre }}</td>
                                        <td>@{{ c.cliente_direccion }}</td>
                                        <td>@{{ c.cliente_cel }}</td>
                                        <td>@{{ c.venta_fecha }}</td>
                                        <td>@{{ format(c.total) }}</td>
                                        <td class="text-danger font-weight-bold">@{{ format(c.saldo) }}</td>
                                        <td>@{{ diferenciaFecha(c.fecha_v, c.pagada - 1) + " dias" }}</td>
                                    </tr>
                                </template>
                            </table>
                        </template>

                    </div>
                </div>
            </template>


        </div>
        <div class="modal fade" id="frmcompania">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Compañias</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Cerrar</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                filtro: {
                    orden: 'ASC',
                    busquedapor: 'cliente',
                    ordenarpor: '0',
                    tipo: 1,
                    ci: false
                },
                txtbuscar: '',
                ctas: [],
                articulos: [],
                error: ''
            },
            methods: {
                buscar: function(p1) {
                    this.requestSend = true;
                    if (this.filtro.busquedapor == 'cliente') {
                        var t = parseFloat(this.txtbuscar);
                        if (isNaN(t)) {
                            this.filtro.ci = false;
                        } else {
                            this.filtro.ci = true;
                        }
                    }
                    axios.get('ctas_cobrar/buscar', {
                            params: {
                                buscar: this.txtbuscar,
                                ci: this.filtro.ci,
                                buscarpor: this.filtro.busquedapor,
                                ordenarpor: this.filtro.ordenarpor,
                                ord: this.filtro.orden
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            if (response.data == 'NO') {
                                Swal.fire('No se encontrado resultado!', 'Para:  ' + this.txtbuscar,
                                    'info');
                            } else {
                                this.ctas = response.data.ctas;
                                this.articulos = response.data.articulos;
                                // this.paginacion= response.data.paginacion;
                                //this.paginacion.pagina_actual=1;
                            }
                            this.requestSend = false;
                            //this.error=response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        });
                },
                format: function(numero) {
                    return new Intl.NumberFormat("de-DE").format(numero);
                },
                detalleVenta: function(nroventa) {
                    return this.articulos.filter(function(venta) {
                        return venta.nro_fact_ventas == nroventa
                    })
                },
                getFecha: function(flag) {

                    var f = new Date();
                    var dia = flag == 1 ? 1 : f.getDate();
                    var mes = (f.getMonth() + 1);
                    if (flag == 2) {
                        this.chart.mes = mes;
                        return;
                    }
                    return f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString()
                        .padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                showComunidades: function() {
                    $('#frmcompania').modal('show');
                },
                diferenciaFecha: function(fecha_vent, pagada) {
                    //2016-07-12
                    var fechaInicio = new Date(fecha_vent).getTime();
                    var fechaFin = new Date().getTime();
                    if (fechaFin > fechaInicio) {
                        return "-";
                    }
                    var diff = fechaFin - fechaInicio;
                    var dia = parseInt(diff / (1000 * 60 * 60 * 24));
                    var diferenciaFecha = 0;

                    if (pagada == 0) {
                        if ((dia - 30) > 30) {
                            return dia - 30;
                        } else {
                            return "-";
                        }
                    } else {
                        /*  diferenciaFecha = dia - (pagada * 30);
                         if (diferenciaFecha > 30) {
                             return diferenciaFecha - 30;
                         } else {
                             return "-"
                         } */

                        return dia > 30 ? dia : "-";
                    }
                },
                getCobroMes() {
                    axios.get('ctas_cobrar/fecha', {
                            params: {
                                desde: this.getFecha(1),
                                hasta: this.getFecha(0),
                                buscarpor: this.filtro.busquedapor,
                                ordenarpor: this.filtro.ordenarpor,
                                ord: this.filtro.orden
                            }
                        })
                        .then(response => {
                            this.requestSend = false;

                            this.ctas = response.data.ctas;
                            this.articulos = response.data.articulos;
                            // this.paginacion= response.data.paginacion;
                            //this.paginacion.pagina_actual=1;

                            this.requestSend = false;
                            //this.error=response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        });
                }
            },
            mounted() {
                //this.getCobroMes();
            }
        })
        activarMenu('m_informe', 'm_ictacobrar');
    </script>
@endsection
