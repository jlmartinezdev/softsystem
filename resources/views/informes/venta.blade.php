@extends('layouts.app')
@section('title', 'Informe de Ventas')
@section('style')
    <style>
        .chart {
            min-height: 250px;
        }

	.modal-dialog{
		overflow-y: initial !important
	}
	.modal-body{
		height: 390px;
		overflow-y: auto;
	}
</style>

@endsection
@section('main')
    <div class="container" id="app">
        <div class="card">
            <div class="card-header">
                <div class="nav nav-tabs card-header-tabs" role="tablist">
                    <a class="nav-item nav-link active" href="#frmlista" data-toggle="tab" role="tab"
                        aria-select="true"><strong><span class="fa fa-calendar"></span> Fecha</strong>
                    </a>
                    <a class="nav-item nav-link" href="#frmcliente" data-toggle="tab" role="tab"
                        aria-select="true"><strong><span class="fa fa-users"></span> Cliente</strong>
                    </a>
                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmchart"
                        aria-select="false"><strong><span class="fa fa-chart-line"></span> Grafica</strong>
                    </a>
                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmarticulo"
                        aria-select="false"><strong><span class="fa fa-shopping-cart"></span> Articulos Vendidos</strong>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- *********** SECCION FECHA ************* -->
                    <div class="tab-pane fade show active" id="frmlista" role="tabpanel">
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
                            <button @click="getVenta" class="btn btn-primary btn-sm">
                                <template v-if="requestSend">
                                    <span class="spinner-border spinner-border-sm" role="status"></span><span
                                        class="sr-only">Cargando...</span> Cargando...
                                </template>
                                <template v-else>
                                    <span class="fa fa-search"></span> Buscar
                                </template>
                            </button>&nbsp;


                        </div>

                        <template>
                            <div class="form-inline">
                                <h6 class="text-muted"><span class="fa fa-calendar-minus"></span> Total de Ventas <span
                                        class="badge badge-pill badge-info">@{{ totalVenta }}</span></h6>
                                <h6 class="ml-4 text-muted"><span class="fa fa-money-bill"></span> Monto de Gs <span
                                        class="badge badge-pill badge-info">@{{ new Intl.NumberFormat("de-DE").format(totalGuaranies) }}</span></h6>
                            </div>
                        </template>


                        <template>
                            <div>
                              <vue-good-table
                                :columns="columns"
                                :rows="rows"
                                style-class="vgt-table striped"/>
                            </div>
                        </template>
                    </div>
                    <!--  ********** SECCION CLIENTE *********** -->
                    <div class="tab-pane fade show" id="frmcliente" role="tabpanel">
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
                                        <option value="5">Total</option>
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

                        <table class="table table-sm table-hover table-striped table-responsive-sm ">
                            <tr>
                                <th>Nro Venta</th>
                                <th>Fecha Hora</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th class="text-right">Total</th>
                                <th>Sucursal</th>
                                <th><span class="fa fa-list"></span> Detalles</th>
                            </tr>
                            <template v-if="clientes.length==0">
                                <tr>
                                    <td colspan="8">No hay resultado para cliente...</td>
                                </tr>
                            </template>
                            <template v-for="venta in clientes">
                                <tr style="font-family: Arial,Helvetica,sans-serif;">
                                    <td>@{{ venta.nro_fact_ventas }}</td>
                                    <td>@{{ venta.fecha }}</td>
                                    <td>@{{ venta.cliente_nombre }}</td>
                                    <td>@{{ venta.tipo_factura == '1' ? "Contado" : "Credito" }}</td>
                                    <td>@{{ venta.documento }}</td>
                                    <td class="text-right font-weight-bold">@{{ new Intl.NumberFormat("de-DE").format(venta.venta_total) }}</td>
                                    <td>@{{ venta.suc_desc }}</td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-link" @click="showDetalle(venta.nro_fact_ventas,'clientes')"><span
                                                class="fa fa-file-alt"></span> Detalle</button>
                                        <a :href="'pdf/boletaventa/'+venta.nro_fact_ventas+'/'" class="btn btn-link"><span
                                                class="fa fa-file-pdf"></span> Imprimir</button>
                                    </td>
                                </tr>
                            </template>
                        </table>


                    </div>
                    <!-- *********** SECCION CHART ************* -->
                    <div class="tab-pane fade" id="frmchart" role="tabpanel">
                        <div class="form-inline">
                            <label for="filtro"><strong>Año</strong></label>
                            <select class="custom-select mx-2" name="filtro" v-model="chart.anho">
                                @for($i=2017;$i<=date('Y');$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <label for="opcion"><strong>Mes</strong></label>
                            <select class="custom-select mx-2" name="opcion" v-model="chart.mes">
                                <template>
                                    <option value="0">Seleccion un Mes</option>
                                    <template v-for="(m,index) in meses">
                                        <option :value="index+1">@{{ m }}</option>
                                    </template>
                                    <option value="13">Todos los meses</option>
                                </template>
                            </select>

                            <button @click="getVentaAgrupado(true)" class="btn btn-primary mx-2">
                                <template v-if="requestSend">
                                    <span class="spinner-border spinner-border-sm" role="status"></span><span
                                        class="sr-only">Cargando...</span> Cargando...
                                </template>
                                <template v-else>
                                    <span class="fa fa-search"></span> Buscar
                                </template>
                            </button>
                        </div>
                        <template v-if="datos.length==0">
                            <span class="m-4 pt-4 font-weight-bold">No hay datos para Mostrar...</span>
                        </template>
                        
                        <div class="chart" id="line_chart_1"></div>
                        <br><br><br>
                        <div class="chart" id="column_chart_1"></div>
                        

                    </div>
                    <!-- *********** SECCION ARTICULOS VENDIDOS ************* -->
                    <div class="tab-pane fade" id="frmarticulo" role="tabpanel">
                        <div class="form-inline mb-3">
                            <strong><label for="desde">Desde: </label></strong>
                            <input type="date" class="form-control form-control-sm mx-2" v-model="articulo.desde"
                                name="desde" placeholder="Desde Fecha">

                            <strong><label for="hasta">Hasta: </label></strong>
                            <input type="date" class="form-control form-control-sm  mx-2" v-model="articulo.hasta"
                                name="hasta" placeholder="Hasta Fecha">

                            <strong><label for="hasta">Sucursal: </label></strong>
                            <select class="form-control form-control-sm mx-2" v-model="idSucursal">
                                <option value="0">Todas</option>
                                @foreach ($sucursales as $s)
                                    <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                                @endforeach
                            </select>
                            <strong><label for="hasta">&nbsp;</label></strong>
                            <button @click="getArticulo" class="btn btn-primary btn-sm">
                                <template v-if="requestSend">
                                    <span class="spinner-border spinner-border-sm" role="status"></span><span
                                        class="sr-only">Cargando...</span> Cargando...
                                </template>
                                <template v-else>
                                    <span class="fa fa-search"></span> Buscar
                                </template>
                            </button>&nbsp;
                            <br>
                            <br>
                            <table class="table table-striped table-sm">
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Seccion</th>
                                    <th>Cant. Vendida</th>
                                    <th>En stock</th>
                                </tr>

                                <template v-if="ventas.length==0">
                                    <tr>
                                        <td colspan="5">No hay resultado para fecha!👆📆</td>
                                    </tr>
                                </template>
                                <template v-for="a in articulos">
                                    <tr style="font-family: Arial,Helvetica,sans-serif;"
                                        :class="{'text-danger': a.cantidad==0}">
                                        <td>@{{ a.producto_c_barra }}</td>
                                        <td>@{{ a.producto_nombre }}</td>
                                        <td>@{{ a.present_descripcion }}</td>
                                        <td class="font-weight-bold">@{{ parseInt(a.vendida) }}</td>
                                        <td>@{{ a.cantidad }}</td>
                                    </tr>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
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
                                 Condicion de Venta:<strong> @{{ venta.tipo_factura== 1 ? "Contado" : "Credito" }}</strong>
                            </div>
                            <div class="col-4">
                                
                                Descuento: <strong>@{{new Intl.NumberFormat("de-DE").format(venta.venta_descuento) }} Gs.</strong>
                            </div>
                            <div class="col-4">
                                 Total: <strong>
                                @{{new Intl.NumberFormat("de-DE").format(venta.venta_total) }} Gs.</strong>
                            </div>
                        </div>
                        
                        
                        <div class="mt-3">
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
                        </div>
                        <template v-if="venta.tipo_factura==2">
                            <span class="badge bg-success">Detalle Cuotas</span>
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th>Nro Cuota</th>
                                    <th>Vencimiento</th>
                                    <th>Monto Cuota</th>
                                    <th>Monto Cobrado</th>
                                    <th>Saldo</th>
                                    <td>Mora</td>
                                    <td>Interes</td>
                                    <td>Estado</td>
                                </tr>
                                <template v-for="c in cuotas">
                                    <tr>
                                        <td>@{{c.nro_cuotas}}</td>
                                        <td>@{{Funciones.formatFecha(c.fecha_venc)}}</td>
                                        <td>@{{c.monto_cuota.toLocaleString('es-ES')}}</td>
                                        <td>@{{c.monto_cobrado.toLocaleString('es-ES')}}</td>
                                        <td>@{{ parseInt(c.monto_saldo).toLocaleString('es-ES')}}</td>
                                        <td>@{{ Funciones.diferenciaFecha(c.fecha_venc, c.monto_saldo) }}</td>
                                        <td>@{{ Funciones.setMontointeres(c.fecha_venc, c.monto_cuota, c.monto_saldo).toLocaleString('es-ES')}}</td>
                                        <td>
                                            <template v-if="c.monto_cobrado == c.monto_cuota">
                                                <span class="badge badge-success">Cobrado</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-danger">Pendiente</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </table>
                            <div class="row">
                                <div class="col-4">
                                    Monto Cobrado: <strong>@{{ Cuenta.montoCobrado.toLocaleString('es-ES')}}</strong> 
                                </div>
                                <div class="col-4">
                                    Saldo: <strong>@{{ Cuenta.saldo.toLocaleString('es-ES')}}</strong> 
                                </div>
                            </div>
                        </template>
                        
                    </div>
                    <div class="modal-footer">
                        <a :href="'documento/extractocuenta/'+venta.nro_fact_ventas" class="btn btn-success">
                            <span class="fa fa-print"> </span> Imprimir
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                class="fa fa-times"></span> Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="chart/raphael.min.js"></script>
    <script type="text/javascript" src="chart/morris.min.js"></script>
  
    <script type="text/javascript">
    Vue.prototype.Funciones= window.Funciones;
        var app = new Vue({
            el: '#app',
            data: {
                url: 'controller/VentasController.php',
                meses: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre",
                    "Octubre", "Noviembre", "Diciembre"
                ],
                anhos: ["2022", "2021", "2020", "2019", "2018", "2017"],
                fecha: {
                    desde: '2020-01-01',
                    hasta: '2020-01-01'
                },
                filtro: { ordenarpor: 1, orden: 'ASC'},
                articulo: {
                    desde: '2020-01-01',
                    hasta: '2020-01-01'
                },
                chart: {
                    mes: '1',
                    anho: {{date('Y')}},
                    byYear: false
                },
                txtbuscar: '',
                venta: {},
                detalleVenta: {},
                isDataChart: false,
                ventas: [],
                clientes: [],
                articulos: [],
                cuotas: [],
                Cuenta: {cantitad: 0, montoCuota: 0, saldo: 0, cobrado : 0, montoCobrado: 0},
                cantidadVenta: 0,
                montoVenta: 0,
                error: '',
                datos: [],
                isVisibleChart: false,
                requestSend: false,
                alturaChart: 'alturaChart',
                alturaSinDatos: 'alturaSinDatos',
                idSucursal: 0,
                columns: [
                    {
                    label: 'Nro Venta',
                    field: 'codigo',
                    type: 'number'
                    },
                    {
                    label: 'Fecha Hora',
                    field: 'fecha',
                    type: 'date',
                    dateInputFormat: 'dd/MM/yyyy HH:mm', // expects 2018-03-16
                    dateOutputFormat: 'dd/MM/yyyy HH:mm', // outputs Mar 16th 2018
                    },
                    {
                    label: 'Cliente',
                    field: 'cliente',
                    },
                    {
                    label: 'Tipo',
                    field: 'tipo',
                    },
                    {
                        label: 'Documento',
                        field: 'documento'
                    },
                    {
                    label: 'Total',
                    field: 'total',
                    type: 'number',
                    tdClass: 'font-weight-bold',
                    },
                    {
                    label: 'Sucursal',
                    field: 'sucursal',
                    },
                    {
                    label: 'Detalle',
                    field: 'detalle',
                    html: true
                    },
                
                ],
                rows: []

            },
            methods: {
                buscar: function(tipo){
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
                            this.clientes = response.data;
                            
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        })
                },
                showChart: function() {
                    if (this.isVisibleChart) {
                        return
                    }
                    if (this.datos.length > 0) {
                        var config = {
                            element: 'line_chart_1',
                            resize: true,
                            data: this.datos,
                            xkey: 'fecha',
                            ykeys: ['total'],
                            labels: ['Total Monto Venta '],
                            fillOpacity: 0.6,
                            lineColors: ['#0000ff'],
                            hideHover: 'auto'
                        }
                        var line = Morris.Area(config);
                        config.element = "column_chart_1";
                        Morris.Bar(config);
                        this.isVisibleChart = true;
                    }

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
                getVenta: function() {
                    this.requestSend = true;
                    axios.get('infventa/fecha', {
                            params: {
                                alld: this.fecha.desde,
                                allh: this.fecha.hasta,
                                alls: this.idSucursal
                            }
                        })
                        .then(response => {
                            this.rows= [];

                            this.requestSend = false;
                            this.ventas = response.data;
                            for (let i = 0; i < this.ventas.length; i++) {
                                const item = {
                                    codigo: this.ventas[i].nro_fact_ventas,
                                    fecha: this.ventas[i].fecha,
                                    cliente : this.ventas[i].cliente_nombre,
                                    tipo : this.ventas[i].tipo_factura =='1' ? "Contado" : "Credito",
                                    documento: this.ventas[i].documento,
                                    total : new Intl.NumberFormat("de-DE").format(this.ventas[i].venta_total),
                                    sucursal: this.ventas[i].suc_desc,
                                    detalle : '<div class="btn-group"><button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-bars"></span></button><div class="dropdown-menu dropdown-menu-right"><button class="dropdown-item" onclick="app.showDetalle('+this.ventas[i].nro_fact_ventas+',\'ventas\');"><span class="fa fa-file-alt"></span> Detalle</button><div class="dropdown-divider"></div><a href="pdf/boletaventa/'+this.ventas[i].nro_fact_ventas+'/" class="dropdown-item"><span class="fa fa-file-pdf"></span> Imprimir</a></div></div>'

                                    }
                                this.rows.push(item);
                                
                            }
                            

                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        })
                },
                getVentaAgrupado: function(show) {
                    this.requestSend = true;
                    document.getElementById("line_chart_1").innerHTML = "&nbsp;";
                    document.getElementById("column_chart_1").innerHTML = "&nbsp;";
                    this.isDataChart = true;
                    axios.post('infventa/chart', {
                            chart: this.chart
                        })
                        .then(response => {
                            this.requestSend = false;
                            this.datos = response.data;
                            if (this.datos.length == 0) {
                                this.isDataChart = false;
                            }
                            if (show) {
                                this.isVisibleChart = false;
                                this.showChart();
                            }
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        })
                },
                Procesar: function() {
                    alert("Prueba ");
                },
                showDetalle: function(id,tab) {
                    if(tab=='ventas'){
                        this.venta = this.ventas[this.ventas.findIndex(x => x.nro_fact_ventas== id)] ;
                    }else{
                        this.venta = this.clientes[this.clientes.findIndex(x => x.nro_fact_ventas== id)] ;
                    }
                    
                    $('#frmdetalle').modal('show');
                    this.getDetalle();
                },
                getDetalle: function() {
                    axios.get('infventa/detalle/' + this.venta.nro_fact_ventas)
                    .then(response => {
                        this.detalleVenta = response.data;
                    })
                    .catch(error => {
                        console.log(error.message);
                    })
                    if(this.venta.tipo_factura=='2'){
                        this.getCta();
                    }
                    
                },
                getCta: function(){
                    axios.get('cuotas/' + this.venta.nro_fact_ventas)
                    .then(response => {
                        const c= response.data;
                        this.cuotas= c;
                        let saldo= 0;
                        let cobrado= 0;
                        
                        for (let i = 0; i < c.length; i++) {
                            saldo += parseInt(c[i].monto_saldo);
                            cobrado += parseInt(c[i].monto_cobrado);

                        }
                        this.Cuenta.saldo= saldo;
                        this.Cuenta.montoCobrado= cobrado;
                    })
                    .catch(error => {
                        console.log(error.message);
                    })
                },
                getArticulo: function() {
                    this.requestSend = true;
                    axios.get('infventa/articulo', {
                            params: {
                                artd: this.articulo.desde,
                                arth: this.articulo.hasta,
                                arts: this.idSucursal
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            this.articulos = response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        })
                },
               
            },
            computed: {
                totalVenta() {
                    this.cantidadVenta = this.ventas.length;
                    return this.cantidadVenta;
                },
                totalGuaranies() {
                    this.montoVenta = 0;
                    for (i = 0; i < this.ventas.length; i++) {
                        this.montoVenta += parseInt(this.ventas[i].venta_total);
                    }
                    return this.montoVenta;
                }
            },
            mounted() {
                this.getFecha(2); // Configura mes actual
                this.fecha = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.articulo = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.getVenta();
                this.getVentaAgrupado(false);
            }
        });
        $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
            app.showChart();
        });

        activarMenu('m_informe', 'm_iventa');
    </script>
@endsection
