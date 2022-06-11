<div class="modal fade" id="selPrecio">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
		        <h5 class="modal-title">Seleccionar Precio</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
			<div class="modal-body">
                <span class="text-muted d-block">Articulo</span>
                <span class="d-block"><strong>@{{preciosContado.articulo}}</strong></span>
                <hr>
                <nav>
					<div class="nav nav-tabs" role="tablist">
						<a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#precio1" aria-controls="frmdescrip" aria-select="true"><strong>Precio Contado</strong></a>
						<a class="nav-item nav-link" data-toggle="tab" role="tab" href="#precio2" aria-controls="frmstock" aria-select="false"><strong>Precio Credito</strong></a>
					</div>
				</nav> 
                <div class="tab-content">
					<div class="tab-pane fade show active p-2" id="precio1" role="tabpanel">
                        <template v-for="n in 5">
                            <div class="row mb-2">
                                <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" :id="'p'+n" :value="'CO'+n" v-model="tmpIndexPrecio.iPrecio" name="radioPrecio"> 
                                            <label class="form-check-label" :for="'p'+n"> Precio @{{n}}:</label>
                                        </div>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-sm" :value="preciosContado['m'+n]" disabled>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control form-control-sm" :value="format(preciosContado['p'+n])" disabled>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="tab-pane fade" id="precio2" role="tabpanel">
                        <table class="mt-1 table table-sm table-striped table-hover">
                            <tr>
                                <th>N Cuota</th>
                                <th>Precio</th>
                                <th>Cuota</th>
                            </tr>
                            <template v-for="(p,index) in preciosCredito">
                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" :value="'CR'+index" :id="'customRadio'+index" v-model="tmpIndexPrecio.iPrecio" name="radioPrecio">
                                            <label :for="'customRadio'+index" class="custom-control-label">@{{index+2}}</label>
                                        </div>
                                    </td>
                                    <td>@{{format(p.p)}}</td>
                                    <td>@{{format(p.c)}}</td>
                                </tr>
                            </template>
                           
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="text-primary align-left">PRECIO SELECCIONADO: </span>
                <button class="btn btn-primary" @click="setPrecio"><span class="fa fa-check"></span> Aceptar</button>
            </div>
        </div>
    </div>
</div>