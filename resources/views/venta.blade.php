@extends('layouts.app')
@section('title','Gestionar Venta')
@section('style')
<style type="text/css">
	.form-group{
		margin-bottom: 7px;
	}
	.form-group label{
		margin-bottom: 0.2rem;
		font-weight: bold;
	}
	.modal-dialog{
		overflow-y: initial !important
	}
	.modal-body{
		height: 350px;
		overflow-y: auto;
	}
</style>
@endsection
@section('main')
	<div id="app">
		
		<div >
			<div class="row" >
			<!-- PANEL IZQUIERDA -->
			<div class="col-md-8">
				<h4>Formulario de Venta</h4>
						<div class="input-group">
			                <input type="text" v-model="txtbuscar" @keyup.enter="showBuscar()" class="form-control" placeholder="Buscar...." autofocus />
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
				
					<!-- TABLA ......................... -->
				<div class="card mt-2 table-responsive-sm">
					<table class="table table-striped table-sm ">
						<tr>
							<th>Codigo</th>
							<th>Descripcion</th>
							<th>Cantidad</th>
							<th>Precio</th>
							<th>Importe</th>
							<th>Opciones</th>
						</tr>
						<template v-if="carro.length>0">
							<template v-for="(item,index) in carro">
								<tr>
								<td>@{{item.codigo}}</td>
								<td>@{{ item.descripcion }}</td>
								<td>@{{item.cantidad}}</td>
								<td>@{{new Intl.NumberFormat("de-DE").format(item.precio)}}</td>
								<td>@{{new Intl.NumberFormat("de-DE").format(item.precio * item.cantidad)}}</td>
								<td>
									<div class="btn-group">
										<button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="fa fa-bars"></span>
										</button>
										<div class="dropdown-menu dropdown-menu-right">
										<button class="dropdown-item" @click="setCantidad(index,item.cantidad,item.stock)">
											<span class="fa fa-cubes text-primary" style="width: 13pt"></span> Cantidad	
										</button>
										<button class="dropdown-item" @click="showModalPrecio(index,item)">
											<span class="fa fa-dollar-sign  text-info" style="width: 13pt"></span> Precio
										</button>
										<button class="dropdown-item" @click="delArticulo(item)">
											<span class="fa fa-times-circle text-danger" style="width: 13pt"></span> Quitar
										</button>
										</div>
									</div>
									
								</td>
								</tr>
							</template>
						</template>
						<template v-else>
							<tr><td colspan="6">S I N  &nbsp; A R T I C U L O  .  .  .</td></tr>
						</template>
						
					</table>
					
				</div>
				
			</div>
			<!-- PANEL DERECHA  -->
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="text-secondary text-center">
								<span class="badge badge-default"><span class="fa fa-cash-register"></span> CAJA </span><span class="badge badge-pill " :class="[ caja=='ABIERTA' ? 'badge-success pr-2 pl-2' : 'badge-danger' ]">  @{{caja}} </span> |<span class="badge badge-default"><span class="fa fa-info-circle"></span> NRO OPERACION </span><span class="badge badge-pill " :class="[ caja=='ABIERTA' ? 'badge-success' : 'badge-danger' ]">  @{{nrooperacion}} </span>
							
						</div>
						<hr>
						<fieldset class="form-group">
							<label>Documento</label>
							<select class="form-control form-control-sm" v-model="ventaCabecera.documento">
								<option value="Ticket">Ticket</option>
								<option value="Comprobante">Comprobante de Venta</option>
								<option value="Factura">Factura</option>
							</select>
						</fieldset>	
						<fieldset class="form-group">
							<label>Fecha</label>
							<input type="date" class="form-control form-control-sm" v-model="ventaCabecera.fecha" placeholder="Fecha">
						</fieldset>
					
						<fieldset class="form-group">
							<label>Cliente</label>
							<div class="input-group">
								<input type="text" disabled class="form-control form-control-sm" placeholder="Nombre Cliente" v-model="ventaCabecera.clienteNombre">
				                <div class="input-group-append">
				                  <button class="btn btn-secondary btn-sm" @click="showBuscarCliente()">	
				                       <span class="fa fa-user"></span> Buscar
				                    </button>
				                </div>
				            </div>
						</fieldset>

						<fieldset class="form-group">
							<label>Descuento</label>
							<input type="number" @keyup="saveDatos" class="form-control form-control-sm" v-model="ventaCabecera.descuento" placeholder="Descuento...">
						</fieldset>
						<hr>
						
						<h3><template>TOTAL: @{{totalVenta}}</template></h3>
						<hr>
						<button class="btn btn-success" @click="showFinalizar">
							<span class="fa fa-check"></span>
							<strong>FINALIZAR</strong> 
						</button>
						<button class="btn btn-secondary" @click="cancelar"> <span class="fa fa-times"></span> CANCELAR</button>
						<hr>
						<a href="venta/imprimir" class="btn btn-outline-success"> <span class="fa fa-print"></span> Imprimir</a>
						<button class="btn btn-outline-success"><span class="fa fa-file-alt"></span> Informe</button>
				</div>
			</div>
			</div>
		</div> <!-- end row -->
		</div> <!--end container -->
		@include('articulo.buscar')
		@include('venta.finalizar')
		@include('cliente.buscar') 
		@include('venta.selprecio')
	</div><!-- end app -->
@endsection
@section('footer')
		<span class="fa fa-user"></span> Usuario: {{ Auth::user()->nom_usuarios }}
		<span class="ml-3">{{date('d-m-Y')}}</span>
@endsection
@section('script')
<script src="{{ asset(mix('js/venta.js'))}}"></script>
<script src="{{ asset('js/separator.js')}}"></script>
<script type="text/javascript">
	var app= new Vue({
		el: '#app',
		data: {
			requestSend:false,
			requestFinalizar: false,
			currentPage: 1,
		    bootstrapPaginationClasses: { ul: 'pagination',li: 'page-item',liActive: 'active',liDisable: 'disabled', button: 'page-link'},
		    customLabels: { first: 'Primer', prev: 'Ant',next: 'Sig', last: 'Ultimo' },
		    paginacion: {'total': 0,'pagina_actual': 1, 'por_pagina': 0,'ultima_pagina': 0,'desde': 0,'hasta': 0 },
			ventaCabecera: {fecha: '2020-01-01', clienteId: '1', clienteNombre:'Cliente Ocasional', documento: 'Ticket',idSucursal: 1,formacobro:1,condicionventa: 1, total: 0,descuento: 0,nro_operacion:0, generarcuota: true},
			carro:[],
			caja: '...',
			nrooperacion: '...',
			tmpIndexPrecio: {iPrecio:'CO1',iArticulo:0,monto_cuota: 0},
			txtbuscar: '',
			txtcliente: '',
			filtro: {seccion: 0, columna: 0, orden: 'ASC'},
			error:'',
			articulos:[],
			preciosContado: {p1:0,m1:10,p2:0,m2:20,p3:0,m3:30,p4:0,m4:40,p5:0,m5:0,articulo:''},
			preciosCredito:[],
			articulo: null,
			clientes:[],
			requestLote: false,
			cuotas: []
		},
		methods:{
			onChange: function () {//Al cambiar pagina
		      if(this.paginacion.ultima_pagina > 1){
		         this.buscar(true);
		      }
		    },
			setCuotas: function(cuotas){
				this.cuotas= cuotas;
			},
			buscar: function(isPaginate){
		        this.requestSend= true;
		        if(this.txtbuscar.length == 0) {
		        	let pag= isPaginate? this.currentPage: 1
			  		axios.get('articulo/buscar',{
			          params:{page:pag,buscar:this.txtbuscar,criterio:0,seccion:this.filtro.seccion,col:this.filtro.columna,ord:this.filtro.orden,suc:this.ventaCabecera.idSucursal}
			        })
				  	.then(response=>{
			          this.requestSend= false;
			          if(response.data=='NO'){
			            Swal.fire('No se encontrado resultado!','Para:  '+this.txtbuscar, 'info' );
			          }else{
			            this.articulos= response.data.articulos.data;
			            this.paginacion= response.data.paginacion;
			            //this.paginacion.pagina_actual=1;
			          }
				  			//this.error=response.data;
				  	})
			  		.catch(e=>{
		          		this.requestSend= false;
			  			this.error= e.message;
			  		});
		        }else{
		        	axios.get('{{env("APP_APIDB")}}',{
		        		params: {buscar: this.txtbuscar,bus_suc:this.ventaCabecera.idSucursal}
		        	})
		        	.then( response => {
		        		this.requestSend= false;
		        		if(response.data!="no"){
		        			this.articulos= response.data;
		        		}else{
		        			this.articulos= [];
		        		}
		        		
		        	})
		        	.catch(error => {
		        		Swal.fire('Error',error.message,'error');
		        	});
		        }
			        
		  	},
		  	showBuscar: function(){
				var Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
	
		  		var t=parseFloat(this.txtbuscar);
		  		if(isNaN(t)){
		  			$('#busquedaArticulo').modal('show');
		  			this.buscar(0);
		  		}else{ 
		  			axios.get('{{env("APP_APIDB")}}',{params:{cbarra:this.txtbuscar,bus_suc: this.ventaCabecera.idSucursal}})
		  			.then(response => {
		  				const articulo= response.data;
		  				if(articulo !="no"){
		  					if(articulo.length > 1)
		  						this.validarLote(articulo[0],articulo);
		  					else
		  						this.addCarrito(articulo[0],articulo[0].id_stock);
		  				}
		  				else{
							Toast.fire({
								icon: 'error',
								title: 'Codigo ingresado no existe en la Base de Datos...'
							})
						}
		  			})
		  			.catch(error =>{
		  				console.log(error.message)
		  			})
		  		}
		  		
		  	},
		  	validarArticulo: function(a){
		  		if(a.cantidad == 0) {
		  			Swal.fire('Articulo en stock 0','No se puede agregar este articulo!','error');
		  			return;
		  		}
		  		this.requestLote= true;
		  		//Traer lotes
		  		axios.get('{{env("APP_APIDB")}}',{params:{ lote : a.ARTICULOS_cod, bus_suc : this.ventaCabecera.idSucursal}})
        		.then(response =>{
        			const stocks= response.data;
        			this.requestLote= false;
        			if(stocks.length>1){ //Si hay mas de un lote
        				$('#busquedaArticulo').modal('hide');
        				this.validarLote(a,stocks); 
        			}else{
        				this.addCarrito(a,stocks[0].id_stock);
						$('#busquedaArticulo').modal('hide');
        			}
        		})
		  		this.txtbuscar='';
		  		
		  	},
		  	addCarrito: function(a,idStock){
		  		//Buscar articulo si no esta en la lista
				let i=this.carro.findIndex(x=> x.codigo == a.ARTICULOS_cod &&  x.idstock==idStock);
				if(i == -1){
					let art= {
			  			codigo: a.ARTICULOS_cod, 
	  					idstock: idStock,
	  					descripcion: a.producto_nombre,
	  					cantidad: 1,
	  					stock: a.cantidad,
	  					precio: a.pre_venta1,
	  					p1: parseInt(a.pre_venta1),
	  					p2: a.pre_venta2,
	  					p3: a.pre_venta3,
	  					p4: a.pre_venta4,
						p5: a.pre_venta5,
						m1: a.pre_margen1,
						m2: a.pre_margen2,
						m3: a.pre_margen3,
						m4: a.pre_margen4,
						m5: a.pre_margen5,
						costo: a.producto_costo_compra,
						iPrecio: 'CO1',
					}
			  		this.carro.push(art);
				}else{
					this.carro[i].cantidad= parseInt(this.carro[i].cantidad) + 1;//Actualizar cantidad
				}
			  	this.saveDatos();
		  	},
            getFecha: function() {

                var f = new Date();
                var dia =  f.getDate();
                var mes = (f.getMonth() + 1);
                this.ventaCabecera.fecha= f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString().padStart(2, "0");
                //this.filtrovalue= this.meses[mes];
            },
            setCantidad: async function(index,cantidad,stock){
            	const swalBootstrap = Swal.mixin({
				  customClass: {
				    confirmButton: 'btn btn-primary mr-2',
				    cancelButton: 'btn btn-danger'
				  },
				  buttonsStyling: false
				})
            	const { value: cant } = await swalBootstrap.fire({
				  title: 'Escriba cantidad a Vender...',
				  input: 'number',
				  inputValue: cantidad,
				  inputAttributes: { min :0 , max : stock},
				  showCancelButton: true,
				  confirmButtonText: 'Aceptar',
				  cancelButtonText: 'Cancelar'
				})
				if (cant) {
				  this.carro[index].cantidad= cant;
				  this.saveDatos();
				}
            },
			showModalPrecio: function(index,articulo){

				this.articulo= articulo;
				this.tmpIndexPrecio.iArticulo= index;
				for(i=1;i<6;i++){
					this.preciosContado['m'+i]=parseInt(articulo['m'+i]);
					this.preciosContado['p'+i]=parseInt(articulo['p'+i]);
				}
				this.preciosContado.articulo= articulo.descripcion;
				$('#selPrecio').modal('show');
				axios.get('articulo/precios/'+articulo.codigo).then(response =>{
					if(response.data.length> 0)
						this.preciosCredito=[];
						for(i=0;i<response.data.length; i++){
							let precios={p:response.data[i].p,c: response.data[i].c, m:response.data[i].m}
							this.preciosCredito.push(precios);
						}
					
				}).catch( error => {
					this.error= error.message;
				})
			},
            setPrecio: function(){
				$('#selPrecio').modal('hide');
				let iPrecio= this.tmpIndexPrecio.iPrecio;
				let x= iPrecio.substr(2);
				let index= this.tmpIndexPrecio.iArticulo;
				if(iPrecio.includes('CO')){
					this.ventaCabecera.condicionventa= 1;
					this.ventaCabecera.generarcuota= true;
					let newPrecio= this.articulo['p' + x];
					if(newPrecio > 0 )
						this.carro[index].precio= newPrecio;
				}else{
					this.ventaCabecera.condicionventa= 2
					this.ventaCabecera.generarcuota= false;
					let newPrecio= this.preciosCredito[x].p;
					if( newPrecio > 0){
						this.carro[index].precio= newPrecio;
						this.tmpIndexPrecio.monto_cuota= this.preciosCredito[x].c;
					}
						
				} 
				
            	this.saveDatos(); 
            },
            delArticulo: function(a){
				let validar = this.carro.findIndex(x=> x.codigo == a.codigo)
				if(validar > -1 ) {
                    this.carro.splice(validar,1);
                   }
            	this.saveDatos();
            },
            format: function(numero){
            	return new Intl.NumberFormat("de-DE").format(numero);
            },
            getApertura: function(){
            	let idSucursal= $('#sucursal').attr('data-id');
            	this.ventaCabecera.idSucursal=idSucursal;
            	if(idSucursal != null){
            		axios.get('aperturacierre/'+idSucursal)
            		.then(response =>{
            			if(response.data){
            				this.nrooperacion= response.data.nro_operacion;
            				this.ventaCabecera.nro_operacion= response.data.nro_operacion;
            				this.caja= 'ABIERTA';
            			}else{
            				this.caja= 'CERRADA';
            			}
            		})
            		.catch(error =>{
            			console.log(error);
            		})
            	}
            },
            showFinalizar:function(){
            	if(this.caja=='ABIERTA'){
            		if(this.ventaCabecera.total > 0){
            			$('#finalizarventa').modal('show');
            		}
            	}else{
            		Swal.fire('Atención...','Caja no esta abierta!','warning');
            	}
            	
            },
            finalizar: function(print){
				if(this.requestFinalizar){
					return false;
				}
            	if(this.ventaCabecera.condicionventa==2 && this.cuotas.length < 1){
					Swal.fire('Error','Por favor genere las cuotas','error');
					return false;
				}
				this.requestFinalizar= true;
            	axios.post('venta',{ventaCabecera: this.ventaCabecera, detalle: this.carro, cuotas: this.cuotas})
            	.then(response =>{
					this.requestFinalizar= false;
            		this.carro= [];
            		localStorage.removeItem('carro_venta');
            		localStorage.removeItem('ventaCabecera');
					if(print){
						window.location.assign('{{ env('APP_URL') }}'+'pdf/boletaventa/' + response.data);
					}else{
						$('#finalizarventa').modal('hide');
						window.location.reload();
					}
            		
            	})
            	.catch(error =>{
					this.requestFinalizar= false;
            		Swal.fire('Error',error.message,'error');
            	})
            },
            numeroaletra: function(n){
            	return NumeroALetras.NumeroALetras(parseInt(n));
            },
            saveDatos: function(){
            	localStorage.setItem('carro_venta',JSON.stringify(this.carro));
            	localStorage.setItem('ventaCabecera',JSON.stringify(this.ventaCabecera));
            },
            recuperarDatos: function(){
            	var carro= localStorage.getItem('carro_venta');
            	if(carro != null){
            		this.carro= JSON.parse(carro);
            	}
            	var cab = localStorage.getItem('ventaCabecera');
            	if(cab != null){
            		this.ventaCabecera= JSON.parse(cab);
            	}
				this.ventaCabecera.condicionventa= 1;
				this.ventaCabecera.generarcuota= true;

            },
            showBuscarCliente: function(){
            	$('#busquedaCliente').modal('show');
            },
            buscarCliente: function(){
            	if(this.txtcliente.length > 0){
            		var doc= '';
            		var nom= '';
            		if(isNaN(parseFloat(this.txtcliente))){
            			nom= this.txtcliente;
            		}else{
            			doc= this.txtcliente;
            		}
            		axios.get('cliente/buscar',{params:{documento: doc,nombre: nom }})
            		.then(response =>{
            			this.clientes= response.data;
            		})
            		.catch(error =>{
            			console.log(error.message);
            		})
            	}
            },
			
            selectCliente: function(id,cliente){
            	this.ventaCabecera.clienteId=id;
            	this.ventaCabecera.clienteNombre= cliente;
            	$('#busquedaCliente').modal('hide');
            },
            getSucursal: function(){
            	var obj= document.getElementById("sucursal");
				if(obj.getAttribute('data-id')!= null)
					this.ventaCabecera.idSucursal= obj.getAttribute('data-id');
            },
            validarLote:async function(articulo,lotes){
            	var values= {};
            	for (var i = 0; i < lotes.length; i++) {
            		values[i]=lotes[i].lote_nro;
            	}
            	const { value: lote } = await Swal.fire({
					title: 'Seleccione Lote',
					input: 'select',
					inputOptions: values,
					inputPlaceholder: 'Seleccione lote',
					showCancelButton: true,
					confirmButtonText: 'Aceptar',
				  	cancelButtonText: 'Cancelar'
					})
            	if(lote){
            		this.addCarrito(articulo,lotes[lote].id_stock);
            	}
            },
			cancelar: function(){
				this.carro= [];
            	localStorage.removeItem('carro_venta');
            	localStorage.removeItem('ventaCabecera');
				this.getFecha();
				window.location.reload();
			}
		},
		computed: {
			totalVenta: function(){
				this.ventaCabecera.total=0;
				for (var i = 0; i < this.carro.length; i++) {
					this.ventaCabecera.total += (this.carro[i].precio * this.carro[i].cantidad);
				}
				if(this.ventaCabecera.descuento > 0 && this.ventaCabecera.total > 0){
					this.ventaCabecera.total -=  this.ventaCabecera.descuento;
				}
				return this.format(this.ventaCabecera.total);	
			}
		},
		mounted(){
			this.getFecha();
			this.getApertura();
			this.recuperarDatos();
			this.getFecha();
			this.getSucursal();
		}
	});
	activarMenu('m_venta','');
</script>
@endsection