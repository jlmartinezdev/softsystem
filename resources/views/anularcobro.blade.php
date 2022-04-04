@extends('layouts.app')
@section('title','Anular Cobro')
@section('style')
<style type="text/css">
	.form-group{
		margin-bottom: 7px;
	}
	.form-group label{
		margin-bottom: 0.2rem;
		font-weight: bold;
	}
</style>
@section('main')
	<div id="app" class="mb-2">
        <div class="card">
            <div class="card-body">
                <h5>
                    Anular Cobro
                </h5>
                <div class="row">
                    <div class="col-4">
                        <div class="input-group">
                            <input type="text" v-model="txtbuscar" @keyup.enter="buscar()" class="form-control" placeholder="Nro de Cobro..." autofocus />
                            <div class="input-group-append">
                              <button class="btn btn-secondary" @click="buscar()">
                                <template v-if="requestSend">
                                    <span class="spinner-border spinner-border-sm" role="status"></span><span class="sr-only">Buscando...</span> Cargando...
                                </template>
                                <template v-else>
                                   <span class="fa fa-search"></span> Buscar
                                </template>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        
                    </div>
                    <div class="col-4">
                        <button class="btn btn-danger " @click="anular"><span class="fa fa-check"></span> ANULAR</button>
                        <button class="btn btn-secondary" @click="cancelar"><span class="fa fa-times"></span> CANCELAR</button>
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="input1">Fecha</label>
                              <input type="text" disabled class="form-control form-control-sm" v-model="cobroCabecera.fecha" placeholder="Fecha" id="input1">
                        </div>
                        <div class="form-group">
                            <label for="input4">C.I. / R.U.C.</label>
                              <input type="text" disabled class="form-control form-control-sm" v-model="cobroCabecera.clienteId" placeholder="Documento" id="input4">
                        </div>
                       
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="input3">Cliente</label>
                              <input type="text" disabled class="form-control form-control-sm" v-model="cobroCabecera.clienteNombre" placeholder="Cliente" id="input3">
                        </div>
                        <div class="form-group">
                            <label for="input2">Direccion</label>
                              <input type="text" disabled class="form-control form-control-sm" v-model="cobroCabecera.direccion" placeholder="Condicion" id="input2">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="input5">Total</label>
                              <input type="text" disabled class="form-control form-control-sm" v-model="cobroCabecera.total" placeholder="Total" id="input5">
                        </div>
                        
                    </div>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-hover table-striped table-sm">
                        <tr>
                            <th>Nro. Venta</th>
                            <th>Nro. Cuota</th>
                            <th>Monto</th>
                            <th>Cobrado</th>
                            <th>Saldo</th>
                        </tr>
                        <template v-if="cuotas.length > 0">
                            <template v-for="d in cuotas">
                                <tr>
                                    <td>@{{d.nro_fact_ventas}}</td>
                                    <td>@{{d.nro_cuotas}}</td>
                                    <td>@{{new Intl.NumberFormat("de-DE").format(d.importe)}}</td>
                                    <td>@{{new Intl.NumberFormat("de-DE").format(d.cobrado)}}</td>
                                    <td>@{{new Intl.NumberFormat("de-DE").format(d.importe - d.cobrado)}}</td>
                                </tr>
                            </template>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="5" class="text-monospace">
                                    No hay cuotas para mostrar...
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@section('script')
<script>
    var app= new Vue({
        el : "#app",
        data: {
            txtbuscar: '',
            requestSend: false,
            cobroCabecera: {nro: '',fecha: '', direccion: '',clienteNombre: '', clienteId: '', total : 0},
            cuotas: [],
            idSucursal: 0,
            nrooperacion: 0
        },
        methods: {
            buscar: function(){
                if(this.requestSend){
                    return false;
                }
                this.requestSend= true;
                axios.get('cobro/'+this.txtbuscar)
                .then(response =>{
                    if(response.data.cobro.length < 1){
                        this.requestSend= false;
                        Swal.fire('Atencion...', 'No se encontro resultado para '+this.txtbuscar,'info')
                        this.cancelar();
                        return false;
                    }
                    this.cuotas= response.data.detalle;
                    let cobro= response.data.cobro[0];
                    this.cobroCabecera.nro= this.txtbuscar;
                    this.cobroCabecera.fecha= this.formatFecha(cobro.cob_fecha);
                    this.cobroCabecera.direccion= cobro.cliente_direccion;
                    this.cobroCabecera.clienteId= cobro.cliente_ci;
                    this.cobroCabecera.clienteNombre= cobro.cliente_nombre;
                    this.cobroCabecera.total= cobro.cob_importe;
                    this.requestSend= false;
                })
                .catch(error =>{
                    this.requestSend= false;
                    console.log(error.message);
                })
            },
            anular: function(){
                if(this.cobroCabecera.total > 0 ){
                    
                    Swal.fire({
                        title: 'Desea anular?',
                        text: "Anular este cobro!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, anular!',
                        cancelButtonText: 'Cancelar'
                        }).then((result) => {
                        if (result.value) {
                            console.log("Anular")
                            axios.post('anular_cobro', {
                                id: this.txtbuscar,
                                monto: this.cobroCabecera.total,
                                idSucursal: this.idSucursal,
                                nrooperacion: this.nrooperacion,
                                cuotas: this.cuotas
                            }).then(response =>{
                                Swal.fire(
                                    'Anulado!',
                                    'Cobro ha sido anulado.',
                                    'success'
                                )
                                window.location.reload();
                            }).catch(error => {
                                console.log(error.message);
                            })
                        }
                        })
                   
                }
            },
            cancelar: function(){
                this.cobroCabecera.nro= '';
                this.cobroCabecera.fecha= '';
                this.cobroCabecera.direccion= '';
                this.cobroCabecera.clienteId= '';
                this.cobroCabecera.clienteNombre= '';
                this.cobroCabecera.total= '';
                this.cuotas= [];
                this.txtbuscar= '';
            },
            formatFecha: function(fecha){
                const f = fecha.split("-");
                return f[2]+"/"+f[1]+"/"+f[0];
            },
            getApertura: function(){
            	let idSucursal= $('#sucursal').attr('data-id');
            	this.idSucursal=idSucursal;
            	if(idSucursal != null){
            		axios.get('aperturacierre/'+idSucursal)
            		.then(response =>{
            			if(response.data){
            				this.nrooperacion= response.data.nro_operacion;
            			}
            		})
            		.catch(error =>{
            			console.log(error);
            		})
            	}
            }
        },
        mounted(){
            this.getApertura();
        }
    })
    activarMenu('m_anular', 'm_acobro');
</script>
@endsection