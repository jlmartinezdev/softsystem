<div class="modal fade" id="preciocompra">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <span class="modal-title">Actualizar Precio</span>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <span>
                    <div class="row">
                        <div class="col-md-6 pl-3">
                           <strong>Codigo:</strong> @{{articulo.codigo}}
                        </div>
                        <div class="col-md-6">
                           <strong>Articulo:</strong> @{{articulo.descripcion}}
                        </div>
                    </div>
                </span>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-info p-3 m-1">
                            <fieldset class="form-group">
                                <label for="factura">Costo</label>
                                    <in-number id="costo"  v-model="articulo.costo" placeholder="Precio Costo" :clases="inNumberClass"></in-number>
                            </fieldset>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <fieldset class="form-group">
                                            <label for="factura">Precio 1</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" onfocus="this.select()"  v-on:keyup="setUtilPrecio('M',1)" v-model="articulo.m1" placeholder="%">
                                                <!--input type="text" placeholder="Precio" onfocus="this.select()"  v-on: keyup="setUtilPrecio('P',1)" v-model="articulo.p1" class="form-control" -->
                                                <in-number  v-model="articulo.p1"  :clases="inNumberClass" placeholder="Precio" @change="setUtilPrecio('P',1)"></in-number>
                                            </div>
                                        </fieldset>
                                    
                                        <div class="form-group">
                                            <strong><label for="margen2">Precio 2</label></strong>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" onfocus="this.select()"  v-on:keyup="setUtilPrecio('M',2)" v-model="articulo.m2" placeholder="%">
                                                <input type="text" placeholder="Precio" onfocus="this.select()"  v-on:keyup="setUtilPrecio('P',2)" v-model="articulo.p2" class="form-control">
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <strong><label for="margen3">Precio 3</label></strong>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" onfocus="this.select()"  v-on:keyup="setUtilPrecio('M',3)" v-model="articulo.m3" placeholder="%">
                                                <input type="text" placeholder="Precio" onfocus="this.select()"  v-on:keyup="setUtilPrecio('P',3)" v-model="articulo.p3" class="form-control">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row">
                                        
                                        <div class="form-group">
                                            <strong><label for="margen4">Precio 4</label></strong>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" onfocus="this.select()"  v-on:keyup="setUtilPrecio('M',4)" v-model="articulo.m4" placeholder="%">
                                                <input type="text" placeholder="Precio" onfocus="this.select()"  v-on:keyup="setUtilPrecio('P',4)" v-model="articulo.p4" class="form-control">
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <strong><label for="margen5">Precio 5</label></strong>
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control" onfocus="this.select()"  v-on:keyup="setUtilPrecio('M',5)" v-model="articulo.m5" placeholder="%">
                                                <input type="text" placeholder="Precio" onfocus="this.select()"  v-on:keyup="setUtilPrecio('P',5)" v-model="articulo.p5" class="form-control">
                                            </div>
                                            <!-- input v-model="articulo.m5" onfocus="this.select()" type="number"
                                                v-on:keyup="setUtilPrecio('M',5)" class="form-control form-control-sm"
                                                name="margen5" placeholder="Margen Precio 5" -->
                                        </div>
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button @click="mostrarPrecios" class="btn btn-success btn-block btn-sm">Mas Precios</button>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-hover table-striped">
                            <tr>
                                <th>Proveedor</th>
                                <th>Fecha</th>
                                <th>Precio</th>
                            </tr>
                            <template v-if="articulos.length>0">
                                <template v-for="(item,index) in articulos">
                                    <tr>
                                    <td>@{{item.proveedor_nombre}}</td>
                                    <td>@{{ item.compra_fecha }}</td>
                                    <td>@{{ format(item.compra_precio) }}</td>
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr><td colspan="3">S I N  &nbsp; H I S T O R I A L </td></tr>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn btn-success" @click="update_precio"><span class="fa fa-check"></span> Aceptar</div>
                <div class="btn btn-secondary"  data-dismiss="modal"><span class="fa fa-reply"></span> Cancelar</div>
            </div>
        </div>
    </div>
</div>
