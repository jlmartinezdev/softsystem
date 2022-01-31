@extends('layouts.app')
@section('title','Ciudades')
@section('main')
	<div class="container" id="app">
		<div class="card">
			<div class="card-header bg-info text-white">Ciudad</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-sm-4">
						<div class="form-group">
							<strong>Departamento</strong>
							<select class="form-control form-control-sm" v-model="departamento">
                                @foreach($departamentos as $d)
								<option value="{{$d->depart_codigo}}">{{$d->depart_nombre}}</option>
                                @endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Ciudad</strong>
							<input type="text" class="form-control form-control-sm" name="descripcion" placeholder="Ciudad" v-model="ciudad">
						</div>
					</div>
					
					<div class="col-sm-4 mt-4">
						<button class="btn btn-success btn-block btn-sm" @click="add"><span class="fa fa-save"></span> Agregar</button>
					</div>
				</div>
			<hr>
			<table class="table table-sm table-striped">
				<tr>
					<th>#</th>
					<th>Departamento</th>
					<th>Ciudad</th>
					<th>Acciones</th>
				</tr>
				@foreach($ciudades as $s)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$s['depart_nombre']}}</td>
					<td>{{$s['ciudad_nombre']}}</td>
					<td>
						<button class="btn btn-outline-danger btn-sm" @click="del({{$s['CIUDAD_cod']}},'{{ trim($s['ciudad_nombre'])}}')" ><span class="fa fa-trash"></span></button>
						<button @click="showEdita({{$s['CIUDAD_cod']}},'{{ trim($s['ciudad_nombre'])}}',{{$s['depart_codigo']}})" class="btn btn-outline-primary btn-sm" ><span class="fa fa-edit"></span></button>
					</td>
				</tr>
				@endforeach
			</table>
			</div>
		</div>

		<div class="modal fade" id="edit">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Editar</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Cerrar</span>
						</button>
						
					</div>
					<div class="modal-body">
						<strong>Departamento</strong>
						<select class="form-control form-control-sm" v-model="e_departamento">
							@foreach($departamentos as $d)
							<option value="{{$d->depart_codigo}}">{{$d->depart_nombre}}</option>
							@endforeach
						</select>
						<div class="form-group">
							<strong>Ciudad</strong>
							<input type="text" class="form-control form-control-sm" placeholder="Ciudad" v-model="e_ciudad">
						</div>
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" @click="update" >Guardar cambio</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
@endsection
@section('script')
<script type="text/javascript">
	function del(id){
		
	}
	var app= new Vue({
		el: '#app',
		data:{
			ciudad: '',
			departamento: 6,
			e_codigo: '',
			e_ciudad: '',
			e_departamento:''
		},
		methods:{
			add: function(){
				if(this.ciudad.length > 0 ){
					axios.post('ciudad',{ciudad: this.ciudad, departamento: this.departamento})
					.then(response => {
						location.reload();
					})
					.catch( error => {
						console.log(error.message);
					})
				}else{
					Swal.fire('Campo vacio!','Complete campo para agregar...','wargning');
				}
			},
			showEdita: function(id,ciudad,depart){
				this.e_codigo= id;
				this.e_ciudad= ciudad;
				this.e_departamento= depart;
				$('#edit').modal('show');
			},
			del: function(id,descripcion){
				Swal.fire({
			        title: '¿Desea eliminar este registro?',
			        text: descripcion,
			        icon: 'question',
			        showCancelButton: true,
			        //confirmButtonColor: 'btn-danger',
			        //cancelButtonColor: 'btn-secondary',
			        cancelButtonText: 'Cancelar',
			        confirmButtonText: 'Si, eliminar!',
			        confirmButtonClass: 'bg-danger'
			      }).then((result) => {
			        if (result.value) {
			          axios.delete('ciudad/'+id)
			          .then(r=>{
			            Swal.fire(
			              'Eliminado!',
			              'El registro ha sido eliminado.',
			              'success'
			            )
			            location.reload();
			          }).catch(e=>{console.log(e.message);});
			        }
			     })
			},
			update: function(){
				if(this.e_ciudad.length > 0){
					axios.post('ciudad/'+this.e_codigo,{'ciudad': this.e_ciudad,'departamento':this.e_departamento})
					.then(response =>{
						location.reload();
					})
					.catch(error =>{
						console.log(error.message);
					})
				}
			}

		}
	})
	activarMenu('m_mantenimiento','m_ciudad');
</script>
@endsection