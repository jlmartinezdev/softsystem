@extends('layouts.app')
@section('title','Informe de Compras')
@section('main')
 <div class="container" id="app">
        <h4>Informe de Compra</h4>
        <div class="card">

            <div class="card-body">
                <div class="form-inline mb-3">    
                    <strong><label for="desde">Desde: </label></strong>
                    <input type="date" class="form-control form-control-sm mx-2" v-model="fecha.desde" name="desde" placeholder="Desde Fecha">

                    <strong><label for="hasta">Hasta: </label></strong>
                    <input type="date" class="form-control form-control-sm  mx-2" v-model="fecha.hasta"name="hasta" placeholder="Hasta Fecha">

                    <strong><label for="hasta">Sucursal: </label></strong>
                    <select class="form-control form-control-sm mx-2" v-model="idSucursal">
                        <option value="0">Todas</option>
                        @foreach($sucursales as $s)
                            <option value="{{$s['suc_cod']}}">{{ $s['suc_desc']}}</option>
                        @endforeach
                    </select>
                    

                    <strong><label for="hasta">&nbsp;</label></strong>
                    <button @click="getCompra" class="btn btn-primary btn-sm">
                        <template v-if="requestSend">
                            <span class="spinner-border spinner-border-sm" role="status"></span><span class="sr-only">Cargando...</span> Cargando...
                        </template>
                        <template v-else>
                            <span class="fa fa-search"></span> Buscar
                        </template>
                    </button>&nbsp;
                    
                    
                </div>
                
                    <template>
                        <div class="form-inline">
                        <h6 class="text-muted"><span class="fa fa-calendar-minus"></span> Total de Compras <span class="badge badge-pill badge-info" >@{{ totalCompra }}</span></h6>
                        <h6 class="ml-4 text-muted"><span class="fa fa-money-bill"></span> Monto de Gs <span class="badge badge-pill badge-info">@{{ new Intl.NumberFormat("de-DE").format(totalGuaranies) }}</span></h6>
                        </div>
                    </template>
                
                
                <table class="table table-sm table-hover table-striped table-responsive-sm ">
                    <tr>
                        <th>Nro Compra</th>
                        <th>Fecha Hora</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th class="text-right">Total</th>
                        <th>Sucursal</th>
                        <th><span class="fa fa-list"></span> Detalles</th>
                    </tr>
                    <template v-if="compras.length==0">
                        <tr>
                            <td colspan="7">No hay resultado para fecha!👆📆</td>
                        </tr>
                    </template>
                    <template v-for="compra in compras">
                        <tr style="font-family: Arial,Helvetica,sans-serif;">
                            <td>@{{compra.compra_cod}}</td>
                            <td>@{{compra.compra_fecha}}</td>
                            <td>@{{compra.proveedor_nombre}}</td>
                            <td>@{{compra.compra_tipo_factura=='1' ? "Contado" : "Credito"}}</td>
                            <td class="text-right font-weight-bold">@{{new Intl.NumberFormat("de-DE").format(compra.total)}}</td>
                            <td>@{{compra.suc_desc}}</td>
                            <td>
                                <button class="btn btn-link" @click="showDetalle(compra)"><span class="fa fa-file-alt"></span> Detalle</button>
                                <a :href="'pdf/boletacompra/'+compra.compra_cod+'/'" class="btn btn-link"><span class="fa fa-file-pdf"></span> Imprimir</button>
                            </td>
                        </tr>
                    </template>
                </table>
                         
                </div>
        </div>


    <div class="modal fade" id="frmdetalle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title">Detalle Compra</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="pr-3"><span class="fa fa-grip-horizontal text-primary"></span><strong> Nro de Compra: @{{compra.compra_cod}} |</strong></span>
                <span class="pr-3"><span class="fa fa-calendar text-warning"></span><strong> Fecha:@{{compra.compra_fecha}} | </strong></span>
                <span><span class="fa fa-user-circle text-info"></span><strong> Proveedor: @{{compra.proveedor_nombre}}</strong></span>
                <br><br>
                <table class="table table-sm">
                    <tr>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th align="right">Cantidad</th>
                        <th align="right">Precio</th>
                        <th align="right">Importe</th>
                    </tr>
                    <template v-for="d in detalleCompra">
                        <tr>
                            <td>@{{d.producto_c_barra}}</td>
                            <td>@{{d.producto_nombre}}</td>
                            <td align="right">@{{parseInt(d.compra_cantidad)}}</td>
                            <td align="right">@{{new Intl.NumberFormat("de-DE").format(d.compra_precio)}}</td>
                            <td align="right">@{{new Intl.NumberFormat("de-DE").format(d.compra_cantidad * d.compra_precio)}}</td>
                        </tr>
                    </template> 
                </table>
            </div>
            <div class="modal-footer">
                <strong>Total @{{new Intl.NumberFormat("de-DE").format(compra.total)}}</strong>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-times"></span> Cerrar</button>
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
        var app = new Vue({
            el: '#app',
            data: {
                url: 'controller/ComprasController.php',
                meses: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
                anhos: ["2022","2021","2020", "2019", "2018", "2017"],
                fecha: {
                    desde: '2020-01-01',
                    hasta: '2020-01-01'
                },
                articulo:{
                    desde: '2020-01-01',
                    hasta: '2020-01-01'  
                },
                chart: {
                    mes: '1',
                    anho: '2022',
                    byYear: false
                },
                compra:{},
                detalleCompra:{},
                isDataChart: false,
                compras: [],
                articulos: [],
                cantidadCompra:0,
                montoCompra:0,
                error: '',
                datos: [],
                isVisibleChart: false,
                requestSend: false,
                alturaChart: 'alturaChart',
                alturaSinDatos: 'alturaSinDatos',
                idSucursal: 0
            },
            methods: {
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
                            labels: ['Total Monto Compra '],
                            fillOpacity: 0.6,
                            lineColors: ['#0000ff'],
                            hideHover: 'auto'
                        }
                        var line = new Morris.Area(config);
                        config.element = "column_chart_1";
                        new Morris.Bar(config);
                        this.isVisibleChart = true;
                    }

                },
                getFecha: function(flag) {

                    var f = new Date();
                    var dia = flag==1 ? 1 : f.getDate();
                    var mes = (f.getMonth() + 1);
                    if(flag==2){
                        this.chart.mes=mes;
                        return;
                    }
                    return f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString().padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                getCompra: function() {
                    this.requestSend= true;
                    axios.get('infcompra/fecha', {
                            params: {
                                alld: this.fecha.desde,
                                allh: this.fecha.hasta,
                                alls: this.idSucursal
                            }
                        })
                        .then(response => {
                            this.requestSend= false;
                            this.compras = response.data;
                        })
                        .catch(e => {
                            this.requestSend=false;
                            this.error = e.message;
                        })
                },
                getVentaAgrupado: function(show) {
                    this.requestSend= true;
                    document.getElementById("line_chart_1").innerHTML="&nbsp;";
                    document.getElementById("column_chart_1").innerHTML="&nbsp;";
                    this.isDataChart=true;
                    axios.post('infventa/chart', {chart: this.chart})
                        .then(response => {
                            this.requestSend= false;
                            this.datos = response.data;
                            if(this.datos.length==0){
                                this.isDataChart= false;
                            }
                            if(show){
                                this.isVisibleChart=false;
                                this.showChart();
                            }
                        })
                        .catch(e => {
                            this.requestSend= false;
                            this.error = e.message;
                        })
                },
                Procesar: function() {
                    alert("Prueba ");
                },
                showDetalle: function(compra){
                    this.compra= compra;
                    $('#frmdetalle').modal('show');
                    this.getDetalle();
                },
                getDetalle:function(){
                    axios.get('infcompra/detalle/'+this.compra.compra_cod)
                    .then(response=>{
                        this.detalleCompra= response.data;
                    })
                    .catch(error =>{

                    })
                },
                getArticulo: function(){
                    this.requestSend= true;
                    axios.get('infventa/articulo', {
                        params: {
                            artd: this.articulo.desde,
                            arth: this.articulo.hasta,
                            arts: this.idSucursal
                        }
                    })
                    .then(response => {
                        this.requestSend= false;
                        this.articulos = response.data;
                    })
                    .catch(e => {
                        this.requestSend=false;
                        this.error = e.message;
                    })
                }
            },
            computed:{
                totalCompra(){
                    this.cantidadCompra=this.compras.length;
                    return this.cantidadCompra;
                },
                totalGuaranies(){
                    this.montoCompra=0;
                    for(i=0;i<this.compras.length;i++){
                        this.montoCompra +=parseInt(this.compras[i].total);
                    }
                    return this.montoCompra;
                }
            }
            ,mounted() {
                this.getFecha(2);// Configura mes actual
                this.fecha = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.articulo = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.getCompra();
                //this.getVentaAgrupado(false);
            }
        });
       /*  $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
            app.showChart();
        }); */
        activarMenu('m_informe','m_icompra');
    </script>
@endsection