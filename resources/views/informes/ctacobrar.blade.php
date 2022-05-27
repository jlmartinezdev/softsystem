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
            <div class="card">
                <div class="card-header" id="frmparametro">
                    <div class="nav nav-tabs card-header-tabs" role="tablist">
                        <a class="nav-item nav-link active" href="#frmfecha" data-toggle="tab" role="tab"
                            aria-select="true"><strong><span class="fa fa-calendar"></span> Fecha</strong>
                        </a>
                        <a class="nav-item nav-link" href="#frmcliente" data-toggle="tab" role="tab"
                            aria-select="true"><strong><span class="fa fa-users"></span> Cliente</strong>
                        </a>
                        <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmzona"
                            aria-select="false"><strong><span class="fa fa-map-marked"></span> Zona</strong>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- *********** SECCION FECHA ************* -->
                        <div class="tab-pane fade show active" id="frmfecha" role="tabpanel">

                            <div class="form-inline mb-3">
                                <strong><label for="desde">Desde: </label></strong>
                                <input type="date" class="form-control form-control-sm mx-2" v-model="filtro.desde"
                                    name="desde" placeholder="Desde Fecha" />

                                <strong><label for="hasta">Hasta: </label></strong>
                                <input type="date" class="form-control form-control-sm  mx-2" v-model="filtro.hasta"
                                    name="hasta" placeholder="Hasta Fecha" />
                                <div class="ml-2">
                                    <select class="form-control form-control-sm" name="ordenarpor"
                                        v-model="filtro.ordenarpor">
                                        <option value="1">Nro. Venta</option>
                                        <option value="2">Documento</option>
                                        <option value="3">Cliente</option>
                                        <option value="4">Fecha</option>
                                        <option value="5">Cant. cuota</option>
                                        <option value="6">Total</option>
                                        <option value="7">Saldo</option>
                                        <option value="8">Direccion</option>
                                    </select>
                                </div>


                                <div class="ml-2 pl-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="ASC" name="ord1"
                                            v-model="filtro.orden" id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3">
                                            ASC
                                        </label>
                                    </div>
                                    <div class="form-check ml-md-2">
                                        <input class="form-check-input" type="radio" value="DESC" v-model="filtro.orden"
                                            name="ord1" id="defaultCheck4">
                                        <label class="form-check-label" for="defaultCheck4">
                                            DESC
                                        </label>
                                    </div>
                                </div>
                                <div class="ml-2 pl-2">
                                    <select class="form-control form-control-sm" name="ordenarpor"
                                        v-model="filtro.presentacion">
                                        <option value="1">Detallado</option>
                                        <option value="2">Resumido</option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <button @click="buscar('fecha')" class="btn btn-primary btn-sm">
                                        <template v-if="requestSend">
                                            <span class="spinner-border spinner-border-sm" role="status"></span><span
                                                class="sr-only">Cargando...</span> Cargando...
                                        </template>
                                        <template v-else>
                                            <span class="fa fa-search"></span> Buscar
                                        </template>
                                    </button>
                                </div>


                            </div>
                            <div class="table-responsive-sm">
                                <template v-if="ctas.length > 0  && filtro.tipo=='fecha'">
                                    <template v-if="filtro.presentacion==1">
                                        <hr>
                                        <table class="table table-sm table-borderless ">
                                            <tr>
                                                <th>
                                                    Cuentas a Cobrar : @{{ formatFecha(filtro.desde) }} - @{{ formatFecha(filtro.hasta) }}
                                                </th>
                                            </tr>
                                            <template v-for="(c,index) in ctas">
                                                <tr>
                                                    <!-- :class="{'mystriped': index % 2==0}" -->
                                                    <td>
                                                        <table class="table table-sm border border-top">

                                                            <tr class="mb-4 font-weight-bold text-primary">
                                                                <td width="150"><span
                                                                        class="fa fa-address-card text-secondary"></span>
                                                                    @{{ c.cliente_ruc }}</td>
                                                                <td width="350" colspan="2"><span
                                                                        class="fa fa-user text-secondary"></span>
                                                                    @{{ c.cliente_nombre }}</td>
                                                                <td width="330" colspan="2"><span
                                                                        class="fa fa-map-marker-alt text-secondary"></span>
                                                                    @{{ c.cliente_direccion }}</td>
                                                                <td><span class="fa fa-phone-alt text-secondary"></span>
                                                                    @{{ c.cliente_cel }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Nro. Venta</th>
                                                                <th>Fecha Venta</th>
                                                                <th>Total Venta</th>
                                                                <th>Vencimiento</th>
                                                                <th>Monto Cuota</th>
                                                                <th>Atraso</th>
                                                            </tr>
                                                            <tr class="trsimple">
                                                                <td>@{{ c.nro_fact_ventas }}</td>
                                                                <td>@{{ c.venta_fecha }}</td>
                                                                <td>@{{ format(c.venta_total) }}</td>
                                                                <td>@{{ formatFecha(c.fecha_v) }}</td>
                                                                <td class="text-danger font-weight-bold">
                                                                    @{{ format(c.saldo) }}
                                                                </td>
                                                                <td>@{{ diferenciaFecha(c.fecha_v, c.pagada) + " dias" }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="7" class="border-bottom"><strong>Detalle de
                                                                        Venta</strong>
                                                                    -
                                                                    Descuento: @{{ format(c.venta_descuento) }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Codigo</strong></td>
                                                                <td colspan="2"><strong>Descripcion</strong></td>
                                                                <td><strong>Cantidad</strong></td>
                                                                <td class="text-right"><strong>Precio</strong></td>
                                                                <td class="text-right" colspan="2">
                                                                    <strong>Importe</strong>
                                                                </td>
                                                            </tr>
                                                            <template v-for="dv in detalleVenta(c.nro_fact_ventas)">
                                                                <tr>
                                                                    <td>@{{ dv.producto_c_barra }}</td>
                                                                    <td colspan="2">@{{ dv.producto_nombre }}</td>
                                                                    <td>@{{ parseInt(dv.venta_cantidad) }}</td>
                                                                    <td class="text-right">@{{ format(dv.venta_precio) }}</td>
                                                                    <td class="text-right" colspan="2">
                                                                        @{{ format(dv.venta_cantidad * dv.venta_precio) }}</td>
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
                                                <th>Cuota</th>
                                                <th>Atraso</th>
                                            </tr>
                                            <template v-for="(c,index) in ctas">
                                                <tr>
                                                    <td>@{{ c.cliente_ruc }}</td>
                                                    <td>@{{ c.cliente_nombre }}</td>
                                                    <td>@{{ c.cliente_direccion }}</td>
                                                    <td>@{{ c.cliente_cel }}</td>
                                                    <td>@{{ c.venta_fecha }}</td>
                                                    <td>@{{ format(c.venta_total) }}</td>
                                                    <td class="text-danger font-weight-bold">@{{ format(c.saldo) }}</td>
                                                    <td>@{{ diferenciaFecha(c.fecha_v, c.pagada - 1) + " dias" }}</td>
                                                </tr>
                                            </template>
                                        </table>
                                    </template>
                                </template>
                            </div>


                        </div>



                        <!-- *********** SECCION CLIENTE ************* -->
                        <div class="tab-pane fade" id="frmcliente" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mt-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><span class="fa fa-user"></span> </span>
                                        </div>
                                        <input type="text" v-model="txtbuscar" name="buscar"
                                            @keydown.enter="$event.preventDefault();" @keyup.enter="buscar('cliente')"
                                            class="form-control" placeholder="Buscar por Nombre o CI..." tabindex="1" />
                                    </div>



                                </div>
                                <div class="col-md-6 form-inline">
                                    <div>
                                        <select class="form-control" name="ordenarpor" v-model="filtro.ordenarpor">
                                            <option value="1">Nro. Venta</option>
                                            <option value="2">Documento</option>
                                            <option value="3">Cliente</option>
                                            <option value="4">Fecha</option>
                                            <option value="5">Cant. cuota</option>
                                            <option value="6">Total</option>
                                            <option value="7">Saldo</option>
                                            <option value="8">Direccion</option>
                                        </select>
                                    </div>


                                    <div class="ml-2 pl-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="ASC" name="ord2"
                                                v-model="filtro.orden" id="defaultCheck3">
                                            <label class="form-check-label" for="defaultCheck3">
                                                ASC
                                            </label>
                                        </div>
                                        <div class="form-check ml-md-2">
                                            <input class="form-check-input" type="radio" value="DESC" v-model="filtro.orden"
                                                name="ord2" id="defaultCheck4">
                                            <label class="form-check-label" for="defaultCheck4">
                                                DESC
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ml-2 pl-2">
                                        <select class="form-control" name="ordenarpor" v-model="filtro.presentacion">
                                            <option value="1">Detallado</option>
                                            <option value="2">Resumido</option>
                                        </select>
                                    </div>
                                    <div class="ml-2">
                                        <button @click="buscar('cliente')" class="btn btn-primary">
                                            <template v-if="requestSend">
                                                <span class="spinner-border" role="status"></span><span
                                                    class="sr-only">Cargando...</span> Cargando...
                                            </template>
                                            <template v-else>
                                                <span class="fa fa-search"></span> Buscar
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive-sm">
                                <template v-if="ctas.length > 0  && filtro.tipo=='cliente'">
                                    <template v-if="filtro.presentacion==1">
                                        <hr>
                                        <table class="table table-sm table-borderless ">
                                            <tr>
                                                <th>
                                                    Cuentas a Cobrar - Busqueda por Cliente
                                                </th>
                                            </tr>
                                            <template v-for="(c,index) in ctas">
                                                <tr>
                                                    <!-- :class="{'mystriped': index % 2==0}" -->
                                                    <td>
                                                        <table class="table table-sm border border-top">

                                                            <tr class="mb-4 font-weight-bold text-primary">
                                                                <td width="150"><span class="fa fa-address-card text-secondary"></span>
                                                                    @{{ c.cliente_ruc }}</td>
                                                                <td width="330" colspan="2"><span
                                                                        class="fa fa-user text-secondary"></span>
                                                                    @{{ c.cliente_nombre }}</td>
                                                                <td colspan="2"><span
                                                                        class="fa fa-map-marker-alt text-secondary"></span>
                                                                    @{{ c.cliente_direccion }}</td>
                                                                <td colspan="2"><span
                                                                        class="fa fa-phone-alt text-secondary"></span>
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
                                                                <td class="text-danger font-weight-bold">
                                                                    @{{ format(c.saldo) }}
                                                                </td>
                                                                <td>@{{ diferenciaFecha(c.fecha_v, c.pagada) + " dias" }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="7" class="border-bottom"><strong>Detalle de
                                                                        Venta</strong>
                                                                    -
                                                                    Descuento: @{{ format(c.venta_descuento) }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Codigo</strong></td>
                                                                <td colspan="2"><strong>Descripcion</strong></td>
                                                                <td><strong>Cantidad</strong></td>
                                                                <td class="text-right"><strong>Precio</strong></td>
                                                                <td class="text-right" colspan="2">
                                                                    <strong>Importe</strong>
                                                                </td>
                                                            </tr>
                                                            <template v-for="dv in detalleVenta(c.nro_fact_ventas)">
                                                                <tr>
                                                                    <td>@{{ dv.producto_c_barra }}</td>
                                                                    <td colspan="2">@{{ dv.producto_nombre }}</td>
                                                                    <td>@{{ parseInt(dv.venta_cantidad) }}</td>
                                                                    <td class="text-right">@{{ format(dv.venta_precio) }}</td>
                                                                    <td class="text-right" colspan="2">
                                                                        @{{ format(dv.venta_cantidad * dv.venta_precio) }}</td>
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
                                </template>
                            </div>

                        </div>



                        <!-- *********** SECCION DIRECCION ************* -->
                        <div class="tab-pane fade" id="frmzona" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mt-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><span class="fa fa-user"></span> </span>
                                        </div>
                                        <input type="text" v-model="txtbuscar" name="buscar"
                                            @keydown.enter="$event.preventDefault();" @keyup.enter="buscar('direccion')"
                                            class="form-control" placeholder="Buscar por direccion..." tabindex="1" />
                                    </div>

                                </div>
                                <div class="col-md-6 form-inline">
                                    <div>
                                        <select class="form-control" name="ordenarpor" v-model="filtro.ordenarpor">
                                            <option value="1">Nro. Venta</option>
                                            <option value="2">Documento</option>
                                            <option value="3">Cliente</option>
                                            <option value="4">Fecha</option>
                                            <option value="5">Cant. cuota</option>
                                            <option value="6">Total</option>
                                            <option value="7">Saldo</option>
                                            <option value="8">Direccion</option>
                                        </select>
                                    </div>


                                    <div class="ml-2 pl-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="ASC" name="ord3"
                                                v-model="filtro.orden" id="defaultCheck3">
                                            <label class="form-check-label" for="defaultCheck3">
                                                ASC
                                            </label>
                                        </div>
                                        <div class="form-check ml-md-2">
                                            <input class="form-check-input" type="radio" value="DESC" v-model="filtro.orden"
                                                name="ord3" id="defaultCheck4">
                                            <label class="form-check-label" for="defaultCheck4">
                                                DESC
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ml-2 pl-2">
                                        <select class="form-control" name="ordenarpor" v-model="filtro.presentacion">
                                            <option value="1">Detallado</option>
                                            <option value="2">Resumido</option>
                                        </select>
                                    </div>
                                    <div class="ml-2">
                                        <button @click="buscar('direccion')" class="btn btn-primary">
                                            <template v-if="requestSend">
                                                <span class="spinner-border spinner-border-sm" role="status"></span><span
                                                    class="sr-only">Cargando...</span> Cargando...
                                            </template>
                                            <template v-else>
                                                <span class="fa fa-search"></span> Buscar
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive-sm">
                                <template v-if="ctas.length > 0  && filtro.tipo=='direccion'">
                                    <template v-if="filtro.presentacion==1">
                                        <hr>
                                        <table class="table table-sm table-borderless ">
                                            <tr>
                                                <th>
                                                    Cuentas a Cobrar - Busqueda por zona
                                                </th>
                                            </tr>
                                            <template v-for="(c,index) in ctas">
                                                <tr>
                                                    <!-- :class="{'mystriped': index % 2==0}" -->
                                                    <td>
                                                        <table class="table table-sm border border-top">

                                                            <tr class="mb-4 font-weight-bold text-primary">
                                                                <td width="150"><span class="fa fa-address-card text-secondary"></span>
                                                                    @{{ c.cliente_ruc }}</td>
                                                                <td width="330" colspan="2"><span
                                                                        class="fa fa-user text-secondary"></span>
                                                                    @{{ c.cliente_nombre }}</td>
                                                                <td colspan="2"><span
                                                                        class="fa fa-map-marker-alt text-secondary"></span>
                                                                    @{{ c.cliente_direccion }}</td>
                                                                <td colspan="2"><span
                                                                        class="fa fa-phone-alt text-secondary"></span>
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
                                                                <td class="text-danger font-weight-bold">
                                                                    @{{ format(c.saldo) }}
                                                                </td>
                                                                <td>@{{ diferenciaFecha(c.fecha_v, c.pagada) + " dias" }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="7" class="border-bottom"><strong>Detalle de
                                                                        Venta</strong>
                                                                    -
                                                                    Descuento: @{{ format(c.venta_descuento) }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Codigo</strong></td>
                                                                <td colspan="2"><strong>Descripcion</strong></td>
                                                                <td><strong>Cantidad</strong></td>
                                                                <td class="text-right"><strong>Precio</strong></td>
                                                                <td class="text-right" colspan="2">
                                                                    <strong>Importe</strong>
                                                                </td>
                                                            </tr>
                                                            <template v-for="dv in detalleVenta(c.nro_fact_ventas)">
                                                                <tr>
                                                                    <td>@{{ dv.producto_c_barra }}</td>
                                                                    <td colspan="2">@{{ dv.producto_nombre }}</td>
                                                                    <td>@{{ parseInt(dv.venta_cantidad) }}</td>
                                                                    <td class="text-right">@{{ format(dv.venta_precio) }}</td>
                                                                    <td class="text-right" colspan="2">
                                                                        @{{ format(dv.venta_cantidad * dv.venta_precio) }}</td>
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
                                </template>
                            </div>

                        </div>
                    </div>


                </div>
            </div>








        </div>
        <div class="modal fade" id="frmavanzado">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Filtro de Datos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Cerrar</span>
                        </button>

                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Buscar</button>
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
                    desde: '',
                    hasta: '',
                    orden: 'ASC',
                    busquedapor: '',
                    ordenarpor: 1,
                    presentacion: 1,
                    tipo: '',
                },

                txtbuscar: '',
                ctas: [],
                articulos: [],
                error: ''
            },
            methods: {
                buscar: function(tipo) {
                    if(this.requestSend){
                        return false;
                    }
                    this.filtro.tipo = tipo;
                    this.requestSend = true;
                    if (tipo == 'cliente') {
                        var t = parseFloat(this.txtbuscar);
                        if (isNaN(t)) {
                            this.filtro.busquedapor = 'nombre';
                        } else {
                            this.filtro.busquedapor = 'ci';
                        }
                    }
                    axios.get('ctas_cobrar/buscar', {
                            params: {
                                tipo: tipo,
                                buscar: this.txtbuscar,
                                desde: this.filtro.desde,
                                hasta: this.filtro.hasta,
                                buscarpor: this.filtro.busquedapor,
                                ordenarpor: this.filtro.ordenarpor,
                                ord: this.filtro.orden,
                                from: 'inf'
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
                        dia = new Date(f.getFullYear(), f.getMonth() + 1, 0).getDate();
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

                    var diff = fechaFin - fechaInicio;
                    if (diff < 0) {
                        return "-";
                    }
                    var dia = parseInt(diff / (1000 * 60 * 60 * 24));

                    var diferenciaFecha = 0;
                    if (this.filtro.tipo == 'fecha') {
                        return dia;
                    }
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

                        return dia;
                    }
                },
                formatFecha: function(fecha) {
                    const f = fecha.split("-");
                    return f[2] + "/" + f[1] + "/" + f[0];
                },
            },
            mounted() {
                this.filtro.desde = this.getFecha(1);
                this.filtro.hasta = this.getFecha(2);
                //this.getCobroMes();
                this.buscar('fecha');
            }
        })
        activarMenu('m_informe', 'm_ictacobrar');
    </script>
@endsection
