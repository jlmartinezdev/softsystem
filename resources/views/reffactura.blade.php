@extends('layouts.app')
@section('title','Referencia de Factura')
@section('main')
	<div class="container" id="app">
		<div class="card">
			<div class="card-header bg-info text-white">Referencia de Factura</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Nivel 1</strong>
							<input type="text" class="form-control form-control-sm" name="descripcion" placeholder="Nivel 1" v-model="nivel_1">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Nivel 2</strong>
							<input type="text" class="form-control form-control-sm" name="descripcion" placeholder="Nivel 2" v-model="nivel_2">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<strong>Nro Factura</strong>
							<input type="text" class="form-control form-control-sm" name="descripcion" placeholder="Nro Factura" v-model="nro_factura">
						</div>
					</div>
				</div>
                <div class="form-group">
                    <strong>Timbrado</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Timbrado" v-model="timbrado">
                </div>
                <div class="form-group">
                    <strong>Vigencia desde</strong>
                    <input type="date" class="form-control form-control-sm" placeholder="Fecha desde" v-model="validodesde">
                </div>
                <div class="form-group">
                    <strong>Vigencia hasta</strong>
                    <input type="date" class="form-control form-control-sm" placeholder="Fecha hasta" v-model="validohasta">
                </div>
                <button class="btn btn-success btn-sm" @click="update"><span class="fa fa-save"></span> Guardar</button>
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
			nivel_1: '',
			nivel_2: '',
			nro_factura: '',
			timbrado: '',
			validodesde: '',
			validohasta: ''
		},
		methods:{
			update: function(){
				if(this.nro_factura.length > 0){
					axios.post('reffactura',{
						'nivel1': this.rellenar(this.nivel_1,3),
						'nivel2': this.rellenar(this.nivel_2,3),
						'nrofactura': this.rellenar(this.nro_factura,7),
						'timbrado': this.timbrado,
						'vigenciadesde': this.validodesde,
						'vencimiento':  this.validohasta,
						})
					.then(response =>{
						location.reload();
					})
					.catch(error =>{
						console.log(error.message);
					})
				}
			},
			getAll: function(){
				axios.get('reffactura/all')
				.then(response=>{
					this.nivel_1= this.rellenar(response.data[0].nivel1,3);
					this.nivel_2= this.rellenar(response.data[0].nivel2,3);
					this.nro_factura= this.rellenar(response.data[0].nrofactura,7);
					this.timbrado= response.data[0].timbrado;
					this.validodesde= response.data[0].vigenciadesde;
					this.validohasta= response.data[0].vencimiento;
				})
				.catch(error =>{
						console.log(error.message);
				})
			},
			rellenar: function(value,len){
				let relleno= !isNaN(value)? value.toString() : value;
				if(relleno.length < len){
					return relleno.padStart(len,"0");
				}
				return relleno;
			}
		},
		mounted(){
			this.getAll();
		}
	})
	activarMenu('m_mantenimiento','m_reffactura');
</script>
@endsection