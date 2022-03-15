@extends('layouts.app')
@section('title','Anular Venta')
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
        <h5>
            Anular Venta
        </h5>
        <div class="row">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" v-model="txtbuscar" @keyup.enter="buscar()" class="form-control" placeholder="Nro de Venta..." autofocus />
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
                <button class="btn btn-primary " @click="anular"><span class="fa fa-check"></span> ANULAR</button>
                <button class="btn btn-secondary" @click="cancelar"><span class="fa fa-times"></span> CANCELAR</button>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
            <div class="col-4">
                <div class="form-group">
                    <label for="input1">Fecha/Hora</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="ventaCabecera.fecha" placeholder="Fecha / Hora" id="input1">
                </div>
                <div class="form-group">
                    <label for="input2">Condicion</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="ventaCabecera.condicion" placeholder="Condicion" id="input2">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="input3">Cliente</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="ventaCabecera.clienteNombre" placeholder="Cliente" id="input3">
                </div>
                <div class="form-group">
                    <label for="input4">C.I. / R.U.C.</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="ventaCabecera.clienteId" placeholder="Documento" id="input4">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="input5">Total</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="ventaCabecera.total" placeholder="Total" id="input5">
                </div>
                
            </div>
        </div>
        <div class="container bg-white">
            <table class="table table-hover table-striped table-sm">
                <tr>
                    <th>#</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Importe</th>
                </tr>
                <template v-if="articulos.length > 0">
                    <template v-for="d in articulos">
                        <tr>
                            <td>@{{d.producto_c_barra}}</td>
                            <td>@{{d.producto_nombre}}</td>
                            <td>@{{parseInt(d.venta_cantidad)}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.venta_precio)}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.venta_cantidad * d.venta_precio)}}</td>
                        </tr>
                    </template>
                </template>
                <template v-else>
                    <tr>
                        <td colspan="5">
                            No hay articulo para mostrar
                        </td>
                    </tr>
                </template>
            </table>
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
            ventaCabecera: {nro: '',fecha: '', condicion: '',clienteNombre: '', clienteId: '', total : 0},
            articulos: []
        },
        methods: {
            buscar: function(){
                if(this.requestSend){
                    return false;
                }
                this.requestSend= true;
                axios.get('venta/cabecera/'+this.txtbuscar)
                .then(response =>{
                    if(response.data.venta.length < 1){
                        this.requestSend= false;
                        Swal.fire('Atencion...', 'No se encontro resultado para '+this.txtbuscar,'info')
                        return false;
                    }
                    this.articulos= response.data.detalle;
                    let venta= response.data.venta[0];
                    this.ventaCabecera.nro= this.txtbuscar;
                    this.ventaCabecera.fecha= venta.venta_fecha;
                    this.ventaCabecera.condicion= venta.tipo_factura=="2" ? "Credito" : "Contado";
                    this.ventaCabecera.clienteId= venta.cliente_ci;
                    this.ventaCabecera.clienteNombre= venta.cliente_nombre;
                    this.ventaCabecera.total= venta.venta_total;
                    this.requestSend= false;
                })
                .catch(error =>{
                    this.requestSend= false;
                    console.log(error.message);
                })
            },
            anular: function(){
                if(this.articulos.length > 0 ){
                    Swal.fire({
                        title: 'Desea anular?',
                        text: "Anular esta venta!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, anular!',
                        cancelButtonText: 'Cancelar'
                        }).then((result) => {
                        if (result.value) {
                            console.log("Anular")
                            axios.delete('venta/'+this.txtbuscar).then(response =>{
                                Swal.fire(
                                    'Anulado!',
                                    'Venta ha sido anulado.',
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
                this.ventaCabecera.nro= '';
                this.ventaCabecera.fecha= '';
                this.ventaCabecera.condicion= '';
                this.ventaCabecera.clienteId= '';
                this.ventaCabecera.clienteNombre= '';
                this.ventaCabecera.total= '';
                this.articulos= [];
                this.txtbuscar= '';
            }
        }
    })
</script>
@endsection