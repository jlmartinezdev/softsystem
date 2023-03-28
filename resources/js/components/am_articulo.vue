<template>

<div>
<div class="modal fade" id="addArticulo">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
		        <strong class="modal-title">Nuevo Articulo</strong>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
			<div class="modal-body">
				<nav>
					<div class="nav nav-tabs" id="tabadd" role="tablist">
						<a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#frmdescrip" aria-controls="frmdescrip" aria-select="true"><strong>Descripcion</strong></a>
						<a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmstock" aria-controls="frmstock" aria-select="false">Stock</a>
						<a class="nav-item nav-link" data-toggle="tab" role="tab" href="#frmprecio" aria-controls="frmprecio" aria-select="false">Precio</a>
					</div>
				</nav> 
				<div class="tab-content">
					<div class="tab-pane fade show active" id="frmdescrip" role="tabpanel">
						<div class="form-row">
	    					<div class="col">
	    						<div class="form-group">
							      <strong><label for="codigo">Codigo</label></strong>
							      <input type="text" v-model="articulo.codigo" class="form-control form-control-sm" disabled name="codigo" placeholder="Auto">
							    </div>
							</div>
							<div class="col">
								<div class="form-group">
							      <strong><label for="cbarra">Codigo de Barra</label></strong>
							      <input type="text" v-model="articulo.c_barra" class="form-control form-control-sm" name="cbarraN" id="cbarraN" placeholder="Codigo de Barra">
							    </div> 
							</div>
						</div>
						<div class="form-row">
	    					<div class="col">
	    						<div class="form-group">
							      <strong><label for="descripcion">Descripcion *</label></strong>
							      <input type="text" v-model="articulo.descripcion" class="form-control form-control-sm" name="descripcionN" placeholder="Descripcion de Articulo">
							    </div>
							</div>
						</div>
						
						<div class="form-row">
	    					<div class="col">
	    						<div class="form-group">
							      <strong><label for="seccion">Seccion *</label></strong>
							      <select name="seccion" v-model="articulo.seccion" class="form-control form-control-sm">
							      	<option value="0">Seleccionar</option>
							      	<!--
                                    foreach($secciones as $seccion)
							      		<option value="{{$seccion['present_cod']}}"> {{ $seccion['present_descripcion'] }}</option>
							      	endforeach -->
							      </select>
							    </div>
							</div>
							<div class="col">
								<div class="form-group">
							      <strong><label for="unidad">Unidad *</label></strong>
							      <select name="unidad" v-model="articulo.unidad" class="form-control form-control-sm">
							      	<option value="0">Seleccionar</option>
							      	<!-- foreach($unidades as $unidad)
							      		<option value="{{$unidad['uni_codigo']}}">{{ $unidad['uni_nombre'] }}</option>
							      	endforeach -->
							      </select>
							    </div>
							</div>
							<div class="col">
								<div class="form-group">
							      <strong><label for="factor">Factor</label></strong>
							      <input type="text" v-model="articulo.factor" class="form-control form-control-sm" name="factor" placeholder="Factor">
							    </div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="frmstock" role="tabpanel">
						<div class="row">
	    					<div class="col-sm-6 col-md-3 ">
	    						<div class="form-group">
							      <strong><label for="stock">Stock *</label></strong>
							      <input type="number" onfocus="this.select()" v-model.number="stock.cantidad" class="form-control form-control-sm" name="stock" placeholder="Stock">
							    </div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="form-group">
							      <strong><label for="lote">Lote</label></strong>
							      <input type="text" v-model="stock.lotenew" class="form-control form-control-sm" name="lote" placeholder="Lote">
							    </div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="form-group">
							      <strong><label for="vencimiento">Vencimiento</label></strong>
							      <input type="date" v-model="stock.vencimiento" class="form-control form-control-sm" name="vencimiento" placeholder="Vencimiento">
							    </div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="form-group">
							      <strong><label for="sucursal">Sucursal</label></strong>
							      <select v-model="stock.sucursal" class="form-control form-control-sm">
							      	<option value="0">Seleccionar</option>
							      	<template >
							      		<option v-for="(sucursal,index) in sucursales" :key="index"  :value="sucursal.suc_cod">@{{ sucursal.suc_desc }}</option>
							      	</template>
							      </select>


							    </div>
							</div>
							
						</div>
						<div class="row">
							<div class="col">
								<template v-if="bandstock==0">
									<button class="btn btn-outline-info" v-on:click="addStock()"><span class="fa fa-plus"></span> Agregar</button>
								</template>
								<template v-else>
									<button class="btn btn-outline-info" v-on:click="limpiarCamposStock()"><span class="fa fa-save"></span> Listo</button>
								</template>
								
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col">
								<strong><label>Ubicacion</label></strong>								
							    <input type="text" v-model="articulo.ubicacion" class="form-control form-control-sm" name="ubicacion" placeholder="Ubicacion">
							</div>
						</div>
						<div class="form-row py-3">
							<table class="table table-sm table-striped table-bordered table-hover">
								<tr>
									<th>Sucursal</th>
									<th>Stock</th>
									<th>Lote</th>
									<th>Vencimiento</th>
									<th>Acciones</th>
								</tr>
								<template v-if="stocks">
									<template>
										<tr  v-for="(stock,index) in stocks" :key="index">
											<td>@{{getByIdSucursal(stock.sucursal)}}</td>
											<td>@{{stock.cantidad}}</td>
											<td>@{{stock.lotenew}}</td>
											<td>@{{stock.vencimiento}}</td>
											<td>
												<button v-on:click="editStockA(stock)" class="btn btn-outline-info btn-sm"><span class="fa fa-edit"></span> Modificar</button>
										<button v-on:Click="delStockA(stock.id)" class="btn btn-outline-info btn-sm"><span class="fa fa-trash"></span> Eliminar</button>
											</td>
										</tr>
									</template>
								</template>
								<tr>
									<td colspan="5">Total Stock: @{{ totalStock }}</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="frmprecio" role="tabpanel">
						<div class="form-row">

	    					<div class="col">
	    						<div class="form-group">
							      <strong><label for="costo">Precio Compra *</label></strong>
								  <in-number  v-model="articulo.costo" placeholder="Precio Compra" @change="setPrecioVenta"></in-number>
							    </div>
								<button class="btn btn-primary" @click="mostrarPrecios"><span class="fa fa-plus"></span> Precios</button>
							</div>
						</div>
						<div class="card border-warning py-2 px-2 mt-2">
							<strong>Margen de Utilidad %</strong>
							<div class="form-row">
								<div class="col">
									<div class="form-group">
								      <strong><label for="marge1">Precio 1</label></strong>
								      <input v-model="articulo.m1" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',1)" type="number" class="form-control form-control-sm" name="margen1" placeholder="Margen Precio 1">
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="margen2">Precio 2</label></strong>
								      <input v-model="articulo.m2" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',2)" type="number"  class="form-control form-control-sm" name="margen2" placeholder="Margen Precio 2">
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="margen3">Precio 3</label></strong>
								      <input v-model="articulo.m3" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',3)" type="number" class="form-control form-control-sm" name="margen3" placeholder="Margen Precio 3">
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="margen4">Precio 4</label></strong>
								      <input v-model="articulo.m4" onfocus="this.select()" type="number" v-on:keyup="setUtilPrecio('M',4)" class="form-control form-control-sm" name="margen4" placeholder="Margen Precio 4">
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="margen5">Precio 5</label></strong>
								      <input v-model="articulo.m5" onfocus="this.select()" type="number" v-on:keyup="setUtilPrecio('M',5)" class="form-control form-control-sm" name="margen5" placeholder="Margen Precio 5">
								    </div>
								</div>
							</div>
						</div>
						<div class="card border-warning py-2 px-2 mt-1">
							<strong>Precio de Venta</strong>
							<div class="form-row">
								<div class="col">
									<div class="form-group">
								      <strong><label for="venta1">Precio Venta 1 *</label></strong>
									  <in-number  v-model="articulo.p1" placeholder="Precio venta 1" @change="setUtilPrecio('P',1)"></in-number>
						
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="venta2">Precio Venta 2</label></strong>
								      <in-number  v-model="articulo.p2" placeholder="Precio venta 1" @change="setUtilPrecio('P',2)"></in-number>
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="venta3">Precio Venta 3</label></strong>
								      <in-number  v-model="articulo.p3" placeholder="Precio venta 3" @change="setUtilPrecio('P',3)"></in-number>
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="venta4">Precio Venta 4</label></strong>
									  <in-number  v-model="articulo.p4" placeholder="Precio venta 4" @change="setUtilPrecio('P',4)"></in-number>
								    </div>
								</div>
								<div class="col">
								    <div class="form-group">
								      <strong><label for="venta5">Precio Venta 5</label></strong>
								      <in-number  v-model="articulo.p5" placeholder="Precio venta 5" @change="setUtilPrecio('P',5)"></in-number>
								    </div>
								</div>
							</div>
						</div>
							
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<template v-if="error">
			      <div class="alert alert-danger" role="alert">
			        @{{ error }}
			      </div>
			    </template>
                
				
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-times"></span> Cerrar</button>
				<button type="button" class="btn btn-primary" @click="saveArticulo"><span class="fa fa-save"></span> Guardar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
</template>
<script>
    const defaultStock= {
        'id': 0,
        'cantidad': 0,
        'loteold': '',
        'lotenew': '',
        'vencimiento': '',
        'sucursal': 1
    };
    const defaultArticulo= { 'codigo': '',
        'c_barra': '',
        'descripcion': '',
        'indicaciones': '',
        'modouso': '',
        'seccion': 1,
        'unidad': 1,
        'factor': 1,
        'ubicacion': '',
        'costo': 0,
        'p1': 0,
        'p2': 0,
        'p3': 0,
        'p4': 0,
        'p5': 0,
        'm1': 0,
        'm2': 0,
        'm3': 0,
        'm4': 0,
        'm5': 0,
        'svenc': '0',
        existePrecios: false
    }
    export default {
        name : 'am_articulo',
        props:['articulo','url','stock'],
        data() {
            return {
                requestSend: false,
                bandstock: 0,
                isnew: true,
                articulos : [],
                requestLote: false,
                filtro: {seccion: 0, columna: 0, orden: 'ASC'},
                requestLote: false,
                articulo: {...defaultArticulo},
                stock: {...defaultStock},
                error: '',
                unidades: [],
                secciones: []
            }
        },
        components:{
            vPagination
        },
        methods: {
            setUtilPrecio: function(tipo, i) {
                if (tipo == 'M') {
                    this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                        parseFloat(this.articulo.costo);
                } else {
                    if (this.articulo.costo > 0 && this.articulo['p' + i] > 0) {
                        var res = this.articulo['p' + i] - this.articulo.costo;
                        this.articulo['m' + i] = Math.round(res * 100 / this.articulo.costo);
                    }
                }
            },
            separador: function(number) {
                var n = parseFloat(number);
                return new Intl.NumberFormat().format(n);
            },
            setArticulo: function(a){
                this.articulo = {
                    'codigo': a.ARTICULOS_cod,
                    'c_barra': a.producto_c_barra,
                    'descripcion': a.producto_nombre,
                    'indicaciones': a.producto_indicaciones == null ? a.producto_indicaciones : a
                        .producto_indicaciones /*.trim()*/ ,
                    'modouso': a.producto_dosis == null ? a.producto_dosis : a
                        .producto_dosis /*.trim()*/ ,
                    'seccion': a.present_cod,
                    'unidad': a.uni_codigo,
                    'factor': a.producto_factor,
                    'ubicacion': a.producto_ubicacion,
                    'costo': a.producto_costo_compra,
                    'p1': a.pre_venta1,
                    'p2': a.pre_venta2,
                    'p3': a.pre_venta3,
                    'p4': a.pre_venta4,
                    'p5': a.pre_venta5,
                    'm1': parseInt(a.pre_margen1, 10),
                    'm2': parseInt(a.pre_margen2, 10),
                    'm3': parseInt(a.pre_margen3, 10),
                    'm4': parseInt(a.pre_margen4, 10),
                    'm5': parseInt(a.pre_margen5, 10),
                    'svenc': '0',
                    existePrecios: false
                }
            },
            setPrecioVenta: function() {
                if (this.articulo.costo > 0) {
                    for (var i = 1; i < 6; i++) {
                        this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                            parseFloat(this.articulo.costo);
                    }
                }
            },
            getD: function() {
                return {
                    'id': this.idstock,
                    'cantidad': this.stock.cantidad,
                    'loteold': this.reservarC ? this.stock.lotenew : this.stock.loteold,
                    'lotenew': this.stock.lotenew,
                    'vencimiento': this.validarVenc(this.stock.vencimiento),
                    'sucursal': this.stock.sucursal
                };
            },
            validarVenc: function(fecha) {
                if (fecha.length < 1) {
                    return "Sin vencimiento";
                }
                this.articulo.svenc = '1'
                return fecha;
            },
            addStock: function() {
                if (this.stock.cantidad > 0) {
                    var x = this.stocks.findIndex(x => x.lotenew == this.stock.lotenew && x.sucursal == this
                        .stock.sucursal);
                    if (x == -1) {
                        this.idstock = this.stocks.length + 1;
                        this.stock.loteold = this.stock.lotenew;
                        this.stocks.push(this.getD());

                        this.limpiarCamposStock();
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Existe un lote igual a esta, se actualiza cantidad...'
                        })
                        this.stocks[x].cantidad = parseInt(this.stocks[x].cantidad) + parseInt(this.stock
                            .cantidad);
                        if (this.stock.vencimiento.length > 0) {
                            this.stocks[x].vencimiento = this.stock.vencimiento;
                        }
                    }
                }
            },
            setStock: function(s, index) {

                if (this.frmt.t) {
                    this.frmt.i = -1;
                    this.frmt.t = false;
                } else {
                    this.frmt.t = true;
                    this.frmt.i = index;
                }
                this.stock = {
                    'id': s.id,
                    'cantidad': 0,
                    'loteold': s.loteold,
                    'lotenew': s.lotenew,
                    'vencimiento': s.vencimiento,
                    'sucursal': s.sucursal
                };
            },
            transladarStock: function() {
                const i = this.stocks.findIndex(stock => stock.id == this.stock.id);

                if (this.frmt.cant > 0 && this.frmt.suc > 0) {
                    if (this.stocks[i].sucursal == this.frmt.suc) {
                        Swal.fire('Atencion!', 'Seleccione otra sucursal!', 'warning');
                        return false;
                    }
                    if (this.frmt.cant > this.stocks[i].cantidad) {
                        Swal.fire('Atencion!', 'Cantidad ingresada es Mayor!', 'warning');
                        return false;
                    }
                    this.stocks[i].cantidad = parseInt(this.stocks[i].cantidad) - this.frmt.cant;
                    this.stocks.push({
                        'id': this.stocks.length + 1,
                        'cantidad': this.frmt.cant,
                        'loteold': this.stock.loteold,
                        'lotenew': this.stock.lotenew,
                        'vencimiento': this.stock.vencimiento,
                        'sucursal': this.frmt.suc
                    });
                    this.updateStock();

                } else {
                    Swal.fire('Atencion!', 'Seleccione Destino e ingrese cantidad!', 'error');
                }
            },
            setBuscar: function(){
                this.txtbuscar= this.txt_buscar;
                this.buscar(false);
            },
            limpiarCamposStock: function() {
                this.bandstock = 0;
                this.stock = {...defaultStock};
            },
            cleanAll: function() {
                this.stocks = [];
                this.limpiarCamposStock();
                for (i = 0; i < 17; i++) {
                    this.precios[i].p = 0;
                    this.precios[i].m = 0;
                    this.precios[i].c = 0;
                }
                this.articulo = {...defaultArticulo};

            },
            delStockA: function(id) {
                const s = this.stocks.find(stock => stock.id == id);
                if (s.id > 20) {
                    const cant = parseInt(s.cantidad);
                    var index = this.articulos.findIndex(x => x.ARTICULOS_cod == this.articulo.codigo);
                    this.articulos[index].cantidad = parseInt(this.articulos[index].cantidad) - cant;
                    if (!this.reservarC) {
                        axios.delete('stock/' + s.id)
                            .then(response => {
                                console.log(response.data)
                            })
                            .catch(e => {
                                console.log(e.message);
                            });
                    }
                }
                this.stocks.pop(s);
                this.limpiarCamposStock();
            },
            editStockA: function(stock) {
                this.stock = stock;
                this.bandstock = 1;
            },
            updateStock() {
                if (this.stocks.length > 0) {
                    axios.post('stock/' + this.articulo.codigo, {
                        stock: this.stocks
                    }).then(r => {
                        this.cancelTrans();
                        this.buscar();
                    }).catch(e => {
                        this.error = e.message;
                    })
                }
            },
            saveArticulo: function() {
                if (this.articulo.descripcion && this.articulo.costo && this.articulo.p1) {
                    // this.validar_Cbarra();
                    if(this.stocks.length < 1){
                        this.stocks.push(defaultStock);
                    }
                    this.error = "";
                    if (this.isnew) {
                        axios.post('articulo', {
                            articulo: this.articulo,
                            stock: this.stocks,
                            precios: this.precios
                        })
                        .then(r => {
                            this.cleanAll();
                            $('#addArticulo').modal('hide');
                            this.buscar();
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                    } else {
                        axios.put('articulo/' + this.articulo.codigo, {
                            articulo: this.articulo,
                            stock: this.stocks,
                            precios: this.precios
                        })
                        .then(r => {
                            this.cleanAll();
                            $('#editArticulo').modal('hide');
                            this.recargarArticulo();
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                    }
                    
                } else {
                    Swal.fire('Atencion', 'Hay campos obligatorios (*) vacios!', 'warning');
                }
            },
            recargarArticulo: function(){
                this.$emit('success');
            },
            getSeccion: function() {
                var url = 'seccion/all';
                axios.get(url)
                    .then(response => {
                        this.secciones = response.data;
                    })
                    .catch(e => {
                        this.error = e.message;
                    })
            },
            getUnidad: function() {
                var url = 'unidad/all';
                axios.get(url)
                    .then(response => {
                        this.unidades = response.data;
                    })
                    .catch(e => {
                        this.error = e.message;
                    })
            },
        },
        mounted(){
           
        }
    }
</script>