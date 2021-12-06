@extends('layouts.app')
@section('title','Imprimir Ventas')
@section('style')
    <style>
        table{
            font-family: Arial, Helvetica, sans-serif;
            font-size:10pt;
        }
    </style>
@endsection
@section('main')

 <div class="container" id="app">
    <h4 class="text-primary">Imprimir Venta</h4>
    <div class="input-group">
        <input type="text" v-model="txtbuscar" @keyup.enter="showBuscar()" class="form-control" placeholder="Buscar Cliente...." autofocus />
        <div class="input-group-append">
          <button class="btn btn-secondary" @click="showBuscar()">
            <template v-if="requestSend">
                <span class="spinner-border spinner-border-sm" role="status"></span><span class="sr-only">Buscando...</span> Cargando...
            </template>
            <template v-else>
               <span class="fa fa-search"></span> Buscar
            </template>
            </button>
        </div>
    </div>
    <br>
    <table class="table table-sm table-hover table-striped table-responsive-sm ">
        <tr>
            <th>Acciones</th>
            <th>Nro Venta</th>
            <th>Fecha Hora</th>
            <th>Cliente</th>
            <th>Documento</th>
            <th class="text-right">Total</th>
        
        </tr>
        <template v-if="ventas.length==0">
            <tr>
                <td colspan="8">No hay resultado para mostrar</td>
            </tr>
        </template>
        <template v-for="venta in ventas">
            <tr style="font-family: Arial,Helvetica,sans-serif;">
                <td>
                    <button class="btn btn-link" @click="showDetalle(venta)"><span class="fa fa-file-alt"></span> Detalle</button>
                    <a :href="'facturar/'+venta.nro_fact_ventas+''" class="btn btn-link"><span class="fa fa-print"></span> Facturar</a>
                   
                </td>
                <td>@{{venta.nro_fact_ventas}}</td>
                <td>@{{venta.fecha}}</td>
                <td>@{{venta.cliente_nombre}}</td>
              
                <td>@{{venta.documento}}</td>
                <td class="text-right font-weight-bold">@{{new Intl.NumberFormat("de-DE").format(venta.venta_total)}}</td>


            </tr>
        </template>
    </table>
    
 </div>
@endsection
@section('script')
<script>
    var app= new Vue({
        el: '#app',
        data: {
            ventas : [],
            txtbuscar: '',
            requestSend: false,
            idSucursal: 1,
            error:''

        },
        methods: {
            showDetalle: function(){

            },
            showBuscar: function(){

            },
            getVenta: function(){
                this.requestSend= true;
                    axios.get('/infventa/cliente', {
                        params: {
                            cliente: this.txtbuscar,
                            alls: this.idSucursal
                        }
                    })
                    .then(response => {
                        this.requestSend= false;
                        this.ventas = response.data;
                    })
                    .catch(e => {
                        this.requestSend=false;
                        this.error = e.message;
                    })
            },
            getSucursal: function(){
            	var obj= document.getElementById("sucursal");
				if(obj.getAttribute('data-id')!= null)
					this.idSucursal= obj.getAttribute('data-id');
            },
        },
        mounted(){
            this.getSucursal();
            this.getVenta();
        }
    })
</script>
@endsection