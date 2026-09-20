@extends('layouts.app')
@section('title', 'Estracto de Cuentas a Cobrar - ' . $empresa->emp_nombre)
@section('style')
    <style type="text/css" media="all">
        table td {
            font-size: 10pt;
        }

        .mystriped {
            background-color: #f2f2f2 !important;
        }

        .cta-list-title {
            font-size: 1rem;
            font-weight: 700;
            margin: 0.25rem 0 0.85rem;
        }

        .cta-card {
            border: 1px solid #cfd8e3;
            border-radius: 0.55rem;
            overflow: hidden;
            margin-bottom: 1rem;
            background: #fff;
        }

        .cta-card-header {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem 1.35rem;
            align-items: center;
            padding: 0.8rem 1rem;
            background: #f4f7fb;
            border-bottom: 1px solid #dbe4f0;
            color: #0d6efd;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .cta-card-header .cta-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            min-width: 0;
        }

        .cta-card-header .cta-meta .fa {
            color: #6c757d;
            width: 1rem;
            text-align: center;
        }

        .cta-card-header .cta-nombre {
            flex: 1 1 200px;
        }

        .cta-card-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.55rem 0.75rem;
            padding: 0.8rem 1rem;
        }

        .cta-stat-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c757d;
            margin-bottom: 0.28rem;
        }

        .cta-stat-value {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .cta-stat-importe .cta-stat-value,
        .cta-detalle-table .cta-importe {
            color: #0f766e;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .cta-stat-saldo .cta-stat-value {
            color: #c82333;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .cta-card-detalle {
            padding: 0 1rem 0.95rem;
        }

        .cta-detalle-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #495057;
            padding: 0.55rem 0 0.55rem;
            border-top: 1px solid #e9ecef;
            margin-bottom: 0.2rem;
        }

        .cta-detalle-title span {
            font-weight: 500;
            color: #6c757d;
        }

        .cta-detalle-table {
            margin: 0;
            background: #fcfdff;
            border: 1px solid #e9ecef;
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .cta-detalle-table thead th {
            background: #eef2f6;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.55rem 0.75rem !important;
            border-bottom: 1px solid #dee2e6;
            white-space: nowrap;
        }

        .cta-detalle-table td {
            padding: 0.55rem 0.75rem !important;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .cta-detalle-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .dark-mode .cta-card {
            background: #343a40;
            border-color: #6c757d;
        }

        .dark-mode .cta-card-header {
            background: #3d444b;
            border-bottom-color: #6c757d;
            color: #9ec5fe;
        }

        .dark-mode .cta-card-header .cta-meta .fa,
        .dark-mode .cta-stat-label,
        .dark-mode .cta-detalle-title span {
            color: #adb5bd;
        }

        .dark-mode .cta-detalle-title {
            color: #ced4da;
            border-top-color: #6c757d;
        }

        .dark-mode .cta-detalle-table {
            background: #3d444b;
            border-color: #6c757d;
        }

        .dark-mode .cta-detalle-table thead th {
            background: #454d55;
            color: #fff;
            border-bottom-color: #6c757d;
        }

        .dark-mode .cta-stat-importe .cta-stat-value,
        .dark-mode .cta-detalle-table .cta-importe {
            color: #3ddc97;
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

            .cta-card {
                break-inside: avoid;
                box-shadow: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
                        <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmexportar"
                            aria-select="false"><strong><span class="fa fa-file-export"></span> Exportar</strong>
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
                                        <div class="cta-list-title">
                                            Cuentas a Cobrar : @{{ formatFecha(filtro.desde) }} - @{{ formatFecha(filtro.hasta) }}
                                        </div>
                                        <cta-cuenta-card
                                            v-for="(c,index) in ctas"
                                            :key="'fecha-' + index + '-' + c.nro_fact_ventas"
                                            :c="c"
                                            :articulos="articulos"
                                            variante="fecha"
                                            tipo="fecha"
                                        ></cta-cuenta-card>
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
                                        <div class="cta-list-title">
                                            Cuentas a Cobrar - Busqueda por Cliente
                                        </div>
                                        <cta-cuenta-card
                                            v-for="(c,index) in ctas"
                                            :key="'cliente-' + index + '-' + c.nro_fact_ventas"
                                            :c="c"
                                            :articulos="articulos"
                                            variante="cliente"
                                            tipo="cliente"
                                        ></cta-cuenta-card>
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
                                        <div class="cta-list-title">
                                            Cuentas a Cobrar - Busqueda por zona
                                        </div>
                                        <cta-cuenta-card
                                            v-for="(c,index) in ctas"
                                            :key="'zona-' + index + '-' + c.nro_fact_ventas"
                                            :c="c"
                                            :articulos="articulos"
                                            variante="zona"
                                            tipo="direccion"
                                        ></cta-cuenta-card>
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

                        <!-- *********** EXPORTAR ************* -->
                        <div class="tab-pane fade" id="frmexportar" role="tabpanel">
                            <div class="p-4">
                                <button class="btn btn-info" @click="exportar"><span class="fa fa-file-excel"></span> Exportar</button>
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

    <script type="text/x-template" id="cta-cuenta-card-tpl">
            <div class="cta-card">
                <div class="cta-card-header">
                    <span class="cta-meta">
                        <span class="fa fa-address-card"></span> @{{ c.cliente_ruc }}
                    </span>
                    <span class="cta-meta cta-nombre">
                        <span class="fa fa-user"></span> @{{ c.cliente_nombre }}
                    </span>
                    <span class="cta-meta">
                        <span class="fa fa-map-marker-alt"></span> @{{ c.cliente_direccion }}
                    </span>
                    <span class="cta-meta">
                        <span class="fa fa-phone-alt"></span> @{{ c.cliente_cel }}
                    </span>
                </div>

                <div class="cta-card-summary">
                    <div class="cta-stat">
                        <span class="cta-stat-label">Nro. Venta</span>
                        <span class="cta-stat-value">@{{ c.nro_fact_ventas }}</span>
                    </div>
                    <div class="cta-stat">
                        <span class="cta-stat-label">Fecha Venta</span>
                        <span class="cta-stat-value">@{{ c.venta_fecha }}</span>
                    </div>

                    <template v-if="variante === 'fecha'">
                        <div class="cta-stat cta-stat-importe">
                            <span class="cta-stat-label">Total Venta</span>
                            <span class="cta-stat-value">@{{ format(c.venta_total) }}</span>
                        </div>
                        <div class="cta-stat">
                            <span class="cta-stat-label">Vencimiento</span>
                            <span class="cta-stat-value">@{{ formatFecha(c.fecha_v) }}</span>
                        </div>
                        <div class="cta-stat cta-stat-saldo">
                            <span class="cta-stat-label">Monto Cuota</span>
                            <span class="cta-stat-value">@{{ format(c.saldo) }}</span>
                        </div>
                        <div class="cta-stat">
                            <span class="cta-stat-label">Atraso</span>
                            <span class="cta-stat-value">@{{ diferenciaFecha(c.fecha_v, c.pagada) }} dias</span>
                        </div>
                    </template>
                    <template v-else>
                        <div class="cta-stat cta-stat-importe">
                            <span class="cta-stat-label">Importe</span>
                            <span class="cta-stat-value">@{{ format(c.total) }}</span>
                        </div>
                        <div class="cta-stat">
                            <span class="cta-stat-label">Cobrado / Cuota</span>
                            <span class="cta-stat-value">@{{ c.pagada }} de @{{ c.cuotas }}</span>
                        </div>
                        <div class="cta-stat cta-stat-importe">
                            <span class="cta-stat-label">Entrega + Cuota Cobrado</span>
                            <span class="cta-stat-value">@{{ format(c.cobrado) }}</span>
                        </div>
                        <div class="cta-stat cta-stat-saldo">
                            <span class="cta-stat-label">Saldo</span>
                            <span class="cta-stat-value">@{{ format(c.saldo) }}</span>
                        </div>
                        <div class="cta-stat">
                            <span class="cta-stat-label">Atraso</span>
                            <span class="cta-stat-value" v-if="variante === 'cliente'">-</span>
                            <span class="cta-stat-value" v-else>@{{ diferenciaFecha(c.fecha_v, c.pagada) }} dias</span>
                        </div>
                    </template>
                </div>

                <div class="cta-card-detalle">
                    <div class="cta-detalle-title">
                        Detalle de Venta
                        <span> · Descuento: @{{ format(c.venta_descuento) }}</span>
                    </div>
                    <table class="table table-sm mb-0 cta-detalle-table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio</th>
                                <th class="text-right">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(dv, i) in detalles" :key="i">
                                <td>@{{ dv.producto_c_barra }}</td>
                                <td>@{{ dv.producto_nombre }}</td>
                                <td class="text-center">@{{ parseInt(dv.venta_cantidad) }}</td>
                                <td class="text-right">@{{ format(dv.venta_precio) }}</td>
                                <td class="text-right cta-importe">@{{ format(dv.venta_cantidad * dv.venta_precio) }}</td>
                            </tr>
                            <tr v-if="!detalles.length">
                                <td colspan="5" class="text-muted">Sin artículos para esta venta.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </script>
@endsection
@section('script')
    <script>
        Vue.component('cta-cuenta-card', {
            props: {
                c: { type: Object, required: true },
                articulos: { type: Array, default: function () { return []; } },
                variante: { type: String, default: 'fecha' },
                tipo: { type: String, default: 'fecha' }
            },
            template: '#cta-cuenta-card-tpl',
            computed: {
                detalles: function () {
                    var nro = this.c.nro_fact_ventas;
                    return (this.articulos || []).filter(function (venta) {
                        return venta.nro_fact_ventas == nro;
                    });
                }
            },
            methods: {
                format: function (numero) {
                    return new Intl.NumberFormat("de-DE").format(numero);
                },
                formatFecha: function (fecha) {
                    if (!fecha) return '';
                    var f = String(fecha).split("-");
                    if (f.length < 3) return fecha;
                    return f[2] + "/" + f[1] + "/" + f[0];
                },
                diferenciaFecha: function (fecha_vent, pagada) {
                    var fechaInicio = new Date(fecha_vent).getTime();
                    var fechaFin = new Date().getTime();
                    var diff = fechaFin - fechaInicio;
                    if (diff < 0) {
                        return "-";
                    }
                    var dia = parseInt(diff / (1000 * 60 * 60 * 24));
                    if (this.tipo == 'fecha') {
                        return dia;
                    }
                    if (pagada == 0) {
                        if ((dia - 30) > 30) {
                            return dia - 30;
                        }
                        return "-";
                    }
                    return dia;
                }
            }
        });
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
                exportar: function(){
                    //let u = new URLSearchParams(params).toString();
                    window.open('excel/ctascobrar');
                }
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
