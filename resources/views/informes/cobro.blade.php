@extends('layouts.app')
@section('title','Informe de Cobros')
@section('style')
<style type="text/css">
	
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
        <h4>Informe de Cobros</h4>
        <div class="card">

            <div class="card-body">
                <div class="form-inline">    
                    <strong><label class="mr-sm-2" for="desde">Desde: </label></strong>
                    <input type="date" class="form-control form-control-sm my-1 mr-sm-2" v-model="fecha.desde" name="desde" placeholder="Desde Fecha">

                    <strong><label class=" mr-sm-2" for="hasta">Hasta: </label></strong>
                    <input type="date" class="form-control form-control-sm  my-1 mr-sm-2" v-model="fecha.hasta" name="hasta" placeholder="Hasta Fecha">

                    <strong><label class="mr-sm-2" for="sucursal">Sucursal: </label></strong>
                    <select class="form-control form-control-sm my-1 mr-sm-2" id="sucursal" v-model="idSucursal">
                        <option value="0">Todas</option>
                        @foreach($sucursales as $s)
                            <option value="{{$s['suc_cod']}}">{{ $s['suc_desc']}}</option>
                        @endforeach
                    </select>      
                </div>
                <div class="row">
                    <div class="col-md-9 my-1">
                        <input type="text" v-model="txtbuscar" @keyup.enter="getCobro" class="form-control form-control" placeholder="Buscar cliente...">
                    </div>
                    <div class="col-md-3 my-1">
                        <button @click="getCobro" class="btn btn-primary btn-block">
                            <template v-if="requestSend">
                                <span class="spinner-border spinner-border-sm" role="status"></span><span class="sr-only">Cargando...</span> Cargando...
                            </template>
                            <template v-else>
                                <span class="fa fa-search"></span> Buscar
                            </template>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    
                    <template>
                        <div class="d-flex ml-2">
                            <h6 class="text-muted"><span class="fa fa-calendar-minus"></span> Total de Cobros <span class="badge badge-pill badge-info" >@{{ totalCobro }}</span></h6>
                            <h6 class="ml-4 text-muted"><span class="fa fa-money-bill"></span> Monto de Gs <span class="badge badge-pill badge-info">@{{ new Intl.NumberFormat("de-DE").format(totalGuaranies) }}</span></h6>
                        </div>
                    </template>
                </div>
                    
                
                
                <table class="table table-sm table-hover table-striped table-responsive-sm ">
                    <tr>
                        <th>Nro Cobro</th>
                        <th>Fecha</th>
                        <th>Nro Recibo</th>
                        <th>Cliente</th>
                        <th class="text-right">Importe</th>
                        <th><span class="fa fa-list"></span> Detalles</th>
                        <th><span class="fa fa-print"></span> Imprimir</th>
                    </tr>
                    <template v-if="cobros.length==0">
                        <tr>
                            <td colspan="7">No hay resultado para fecha!👆📆</td>
                        </tr>
                    </template>
                    <template v-for="cobro in cobros">
                        <tr>
                            <td>@{{ cobro.cc_numero }}</td>
                            <td>@{{ formatFecha(cobro.cob_fecha) }}</td>
                            <td>@{{ numeroRecibo(cobro.recibon1,cobro.recibon2,cobro.nro_recibo) }}</td>
                            <td>@{{ cobro.cliente_nombre }}</td>
                            <td>@{{ format(cobro.cob_importe) }}</td>
                            <td><button class="btn btn-link" @click="showDetalle(cobro)"> Detalle </button></td>
                            <td><a class="btn btn-link" :href="'documento/recibocobro/'+cobro.cc_numero"> Imprimir</a></td>
                        </tr>
                    </template>
                </table>
                         
                </div>
        </div>


    <div class="modal fade" id="frmdetalle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title">Detalle Cobro</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-3 border-right">
                        <span><span class="fa fa-grip-horizontal text-primary"></span><strong> Nro de Cobro </strong></span>
                        <span class="d-block">@{{cobro.cc_numero}}</span>
                    </div>
                    <div class="col-3 border-right">
                        <span><span class="fa fa-calendar text-warning"></span><strong> Fecha </strong></span>
                        <span class="d-block">@{{formatFecha(cobro.cob_fecha)}}</span>
                    </div>
                    <div class="col-4">
                        <span><span class="fa fa-file text-info"></span><strong> Recibo </strong></span>
                        <span class="d-block">@{{numeroRecibo(cobro.recibon1,cobro.recibon2,cobro.nro_recibo)}}</span>
                    </div>
                </div>
                <div class="row p-2">
                    <span>Cliente: <strong>@{{ cobro.cliente_nombre }}</strong></span>
                </div>
                <span class="badge bg-primary">Cuotas cobrados</span>
                <div class="border border-primary">
                    <table class="table table-sm table-striped">
                    <tr>
                        <th>Nro Venta</th>
                        <th>Nro Cuota</th>
                        <th>Importe</th>
                        <th>Cobrado</th>
                    </tr>
                    <template v-for="d in detalleCobro">
                        <tr>
                            <td>@{{d.nro_fact_ventas}}</td>
                            <td>@{{d.nro_cuotas}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.importe)}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.cobrado)}}</td>
                        </tr>
                    </template> 
                </table> 
                </div>
               

                <span class="badge bg-success">Detalle Cuotas</span>
                <div class="border border-success">
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
                                <td>@{{c.nro_cuotas}}</td>
                                <td>@{{formatFecha(c.fecha_venc)}}</td>
                                <td>@{{new Intl.NumberFormat("de-DE").format(c.monto_cuota)}}</td>
                                <td>@{{new Intl.NumberFormat("de-DE").format(c.monto_cobrado)}}</td>
                                <td>@{{new Intl.NumberFormat("de-DE").format(c.monto_saldo)}}</td>
                            </tr>
                        </template>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-4">
                        Monto Cobrado: <strong>@{{ new Intl.NumberFormat("de-DE").format(Cuenta.montoCobrado)}}</strong> 
                    </div>
                    <div class="col-4">
                        Saldo: <strong>@{{ new Intl.NumberFormat("de-DE").format(Cuenta.saldo)}}</strong> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <strong>Total @{{new Intl.NumberFormat("de-DE").format(cobro.cob_importe)}}</strong>
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
                cobro:{},
                detalleCobro:{},
                isDataChart: false,
                cobros: [],
                cantidadCobro:0,
                montoCobro:0,
                error: '',
                requestSend: false,
                cuotas: [],
                Cuenta: {cantitad: 0, montoCuota: 0, saldo: 0, cobrado : 0, montoCobrado: 0},
                idSucursal: 0,
                txtbuscar: ''
            },
            methods: {
               
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
                getCobro: function() {
                    this.requestSend= true;
                    axios.get('infcobro/fecha', {
                            params: {
                                alld: this.fecha.desde,
                                allh: this.fecha.hasta,
                                alls: this.idSucursal,
                                search: this.txtbuscar
                            }
                        })
                        .then(response => {
                            this.requestSend= false;
                            this.cobros = response.data;
                        })
                        .catch(e => {
                            this.requestSend=false;
                            this.error = e.message;
                        })
                },
                showDetalle: function(cobro){
                    this.cobro= cobro;
                    $('#frmdetalle').modal('show');
                    this.getDetalle();
                },
                numeroRecibo: function(n1,n2,n3){
                    if(n1 && n2 && n3)
				        return n1.toString().padStart(3,"0") + "-" + n2.toString().padStart(3,"0") + "-" + n3.toString().padStart(7,"0");
			    },
                getDetalle:function(){
                    axios.get('infcobro/detalle/'+this.cobro.cc_numero)
                    .then(response=>{
                        this.detalleCobro= response.data;
                    })
                    .catch(error =>{
                        console.log(error.message);
                    })
                    this.getCuotas();
                },
                getCuotas: function(){
                    axios.get('cuotas/' + this.cobro.nro_fact_ventas)
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
                format: function(numero){
            	    return new Intl.NumberFormat("de-DE").format(numero);
                },
                formatFecha: function(fecha){
                   if (typeof fecha !== 'undefined'){
                    const f = fecha.split("-");
                    return f[2]+"/"+f[1]+"/"+f[0];
                   }
                    
                },
            },
            computed:{
                totalCobro(){
                    this.cantidadCobro=this.cobros.length;
                    return this.cantidadCobro;
                },
                totalGuaranies(){
                    this.montoCobro=0;
                    for(i=0;i<this.cobros.length;i++){
                        this.montoCobro +=parseInt(this.cobros[i].cob_importe);
                    }
                    return this.montoCobro;
                }
            },
            mounted() {
                //this.getFecha(2);// Configura mes actual
                this.fecha = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                this.getCobro();
                //this.getVentaAgrupado(false);
            }
        });
       /*  $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
            app.showChart();
        }); */
        activarMenu('m_informe','m_ictacobro');
    </script>
@endsection