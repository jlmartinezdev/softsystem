@extends('layouts.app')
@section('title','Proveedores')
@section('main')
	<div class="container" id="app">
		<div class="card">
			<div class="card-header bg-info text-white">Proveedor</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Nombre</strong>
							<input type="text" class="form-control form-control-sm" name="descripcion" id="nombre" placeholder="Nombre proveedor" v-model="proveedor.nombre">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Ciudad</strong>
							<select class="form-control form-control-sm" v-model="proveedor.idciudad">
								@foreach($ciudades as $c)
								<option value="{{$c->CIUDAD_cod}}">{{$c->ciudad_nombre}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Nacionalidad</strong>
							<select class="form-control form-control-sm" v-model="proveedor.idnacionalidad">
								<option value="1">Paraguaya</option>
							</select>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
							<strong>Ruc</strong>
							<input type="text" class="form-control form-control-sm" placeholder="R.U.C." v-model="proveedor.ruc">
						</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
							<strong>Direccion</strong>
							<input type="text" class="form-control form-control-sm" placeholder="Direccion" v-model="proveedor.direccion">
						</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
							<strong>Telefono</strong>
							<input type="text" class="form-control form-control-sm" placeholder="Telefono" v-model="proveedor.telefono">
						</div>
                    </div>
                </div>
				<template v-if="band==0">
					<button class="btn btn-success btn-sm" @click="add"><span class="fa fa-save"></span> Agregar</button>
				</template>
				<template v-else>
					<button class="btn btn-success btn-sm" @click="update"><span class="fa fa-save"></span> Actualizar</button>
				</template>
                
				<button class="btn btn-secondary btn-sm" @click="cancelar"><span class="fa fa-times"></span> Cancelar</button>

			<hr>
			<table class="table table-sm table-striped">
				<tr>
					<th>#</th>
					<th>Descripcion</th>
					<th>Impuesto</th>
					<th>Acciones</th>
				</tr>
				@foreach($proveedores as $s)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{trim($s['proveedor_nombre'])}}</td>
					<td>{{$s['proveedor_ruc']}}</td>
					<td>
						<button class="btn btn-outline-danger btn-sm" @click="del({{$s['PROVEEDOR_cod']}},'{{ trim($s['proveedor_nombre'])}}')" ><span class="fa fa-trash"></span></button>
						<button @click="showEdita({{$s}})" class="btn btn-outline-primary btn-sm" ><span class="fa fa-edit"></span></button>
					</td>
				</tr>
				@endforeach
			</table>
			</div>
		</div>
	</div>
@endsection
@section('script')
<script type="text/javascript">
	function del(id){
		
	}
	var app= new Vue({
		el: '#app',
		data:{
			proveedor: {id : 0,idnacionalidad : 1,idciudad : 1,nombre:'',direccion:'',telefono: '' ,ruc: ''},
			band: 0
		},
		methods:{
			add: function(){
				if(this.proveedor.nombre.length > 0 ){
					axios.post('proveedor',this.proveedor)
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
			cancelar : function(){
				this.band=0;
				this.proveedor=  {id : 0,idnacionalidad : 1,idciudad : 1,nombre:'',direccion:'',telefono: '' ,ruc: ''};
			},
			showEdita: function(p){
				this.band= 1;
				this.proveedor=  {id : p.PROVEEDOR_cod,idnacionalidad : p.nacio_cod,idciudad : p.CIUDAD_cod, nombre: p.proveedor_nombre ,direccion : p.proveedor_direc,telefono: p.proveedor_telef ,ruc: p.proveedor_ruc};
				document.getElementById('nombre').focus();
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
			          axios.delete('proveedor/'+id)
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
				if(this.proveedor.nombre.length > 0){
					axios.post('proveedor/'+this.proveedor.id,this.proveedor)
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
	activarMenu('m_mantenimiento','m_proveedor');
</script>
@endsection