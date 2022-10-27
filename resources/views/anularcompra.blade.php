@extends('layouts.app')
@section('title','Anular Compra')
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
            Anular Compra
        </h5>
        <div class="row">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" v-model="txtbuscar" @keyup.enter="buscar()" class="form-control" placeholder="Nro de Compra..." autofocus />
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
                      <input type="text" disabled class="form-control form-control-sm" v-model="compraCabecera.fecha" placeholder="Fecha / Hora" id="input1">
                </div>
                <div class="form-group">
                    <label for="input2">Condicion</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="compraCabecera.condicion" placeholder="Condicion" id="input2">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="input3">Proveedor</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="compraCabecera.proveedorNombre" placeholder="Proveedor" id="input3">
                </div>
                <div class="form-group">
                    <label for="input4">R.U.C.</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="compraCabecera.proveedorId" placeholder="RUC" id="input4">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="input5">Total</label>
                      <input type="text" disabled class="form-control form-control-sm" v-model="totalGuaranies" placeholder="Total" id="input5">
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
                            <td>@{{parseInt(d.compra_cantidad)}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.compra_precio)}}</td>
                            <td>@{{new Intl.NumberFormat("de-DE").format(d.compra_cantidad * d.compra_precio)}}</td>
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
            compraCabecera: {nro: '',fecha: '', condicion: '',proveedorNombre: '', proveedorId: '', total : 0},
            articulos: [],
            total: 0
        },
        methods: {
            buscar: function(){
                if(this.requestSend){
                    return false;
                }
                this.requestSend= true;
                axios.get('compra/cabecera/'+this.txtbuscar)
                .then(response =>{
                    if(response.data.compra.length < 1){
                        this.requestSend= false;
                        Swal.fire('Atencion...', 'No se encontro resultado para '+this.txtbuscar,'info')
                        return false;
                    }
                    this.articulos= response.data.detalle;
                    let compra= response.data.compra[0];
                    this.compraCabecera.nro= this.txtbuscar;
                    this.compraCabecera.fecha= compra.compra_fecha;
                    this.compraCabecera.condicion= compra.compra_tipo_factura=="2" ? "Credito" : "Contado";
                    this.compraCabecera.proveedorId= compra.proveedor_ruc;
                    this.compraCabecera.proveedorNombre= compra.proveedor_nombre;
                    //this.compraCabecera.total= compra.venta_total;
                    this.requestSend= false;
                })
                .catch(error =>{
                    this.requestSend= false;
                    console.log(error.message);
                })
            },
            anular: function(){
                if(this.compraCabecera.proveedorId.length > 0 ){
                    let arts= [];
                    for(i=0;i<this.articulos.length; i++){
                        arts.push({id : this.articulos[i].ARTICULOS_cod, cantidad: parseInt(this.articulos[i].compra_cantidad)});
                    }
                    Swal.fire({
                        title: 'Desea anular?',
                        text: "Anular esta compra!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, anular!',
                        cancelButtonText: 'Cancelar'
                        }).then((result) => {
                        if (result.value) {
                            console.log("Anular")
                            axios.post('anular_compra', {
                                id: this.txtbuscar,
                                articulos: arts
                            }).then(response =>{
                                Swal.fire(
                                    'Anulado!',
                                    'Compra ha sido anulado.',
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
                this.compraCabecera.nro= '';
                this.compraCabecera.fecha= '';
                this.compraCabecera.condicion= '';
                this.compraCabecera.proveedorId= '';
                this.compraCabecera.proveedorNombre= '';
                this.compraCabecera.total= '';
                this.articulos= [];
                this.txtbuscar= '';
            }
        },
        computed:{
            totalGuaranies() {
                this.total = 0;
                for (i = 0; i < this.articulos.length; i++) {
                    this.total += parseInt(this.articulos[i].compra_precio * this.articulos[i].compra_cantidad) ;
                }
                return new Intl.NumberFormat("de-DE").format(this.total);
            }
        }
    })
    activarMenu('m_anular', 'm_acompra');
</script>
@endsection