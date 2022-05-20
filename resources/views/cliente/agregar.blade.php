<div class="modal fade" id="agregarCliente">
	<div class="modal-dialog modal-xl " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Agregar Cliente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Cerrar</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-3">
						<div class="form-group">
							<label class="font-weight-bold">Nro Documento</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.doc" placeholder="C.I. o R.U.C">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Nombre y Apellido</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.nombre" placeholder="Nombre y Apellido...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Sexo</label>
							<select class="form-control form-control-sm">
								<option value="M">Masculino</option>
								<option value="F">Femenino</option>
							</select>
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Direccion</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.direccion" placeholder="Direccion...">
						</div>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-3">
						<div class="form-group">
							<label class="font-weight-bold">Celular</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.celular" placeholder="Celular...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Correo</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.correo" placeholder="Correo...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Telefono</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.direccion" placeholder="Direccion...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Cuidad</label>
							<select class="form-control form-control-sm" v-model="cliente.idciudad">
								@foreach($ciudades as $ciudad)
								<option value="{{$ciudad['CIUDAD_cod']}}">{{$ciudad['ciudad_nombre']}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-3">
						<div class="form-group">
							<label class="font-weight-bold">Cel. Familiar</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.direccion" placeholder="Direccion...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Profesion/ Ocupacion</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.direccion" placeholder="Direccion...">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Ref. Laboral</label>
							<input type="text" class="form-control form-control-sm" v-model="cliente.direccion" placeholder="Direccion...">
						</div>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-3">

					</div>
				</div>
					
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" @click="save"><span class="fa fa-save"></span> Guardar</button>
			</div>
		</div>
	</div>
</div>

