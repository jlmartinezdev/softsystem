@extends('layouts.app')
@section('title', 'Configuracion de Empresa')
@section('main')
<h2>Configuracion de Empresa</h2>
<div class="card" id="app">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Nombre de la empresa *</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Nombre de empresa"
                        v-model="empresa.nombre">
                </div>
                <div class="form-group">
                    <strong>Sucursal</strong>
                    <select class="form-control form-control-sm" v-model="empresa.sucursal">
                        @foreach ($sucursales as $s)
                        <option value="{{ $s->suc_cod }}">{{ trim($s->suc_desc) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <strong>Ciudad</strong>
                    <select class="form-control form-control-sm" v-model="empresa.ciudad">
                        @foreach ($ciudades as $c)
                        <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                        @endforeach
                    </select>
                    
                </div>
                <div class="form-group">
                    <strong>Direccion</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Direccion"
                        v-model="empresa.direccion">
                </div>
                <div class="form-group">
                    <strong>Ruc</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="R.U.C."
                        v-model="empresa.ruc">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Celular</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Celular"
                        v-model="empresa.celular">
                </div>
                <div class="form-group">
                    <strong>Telefono</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Telefono"
                        v-model="empresa.telefono">
                </div>
                <div class="form-group">
                    <strong>Correo</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Correo"
                        v-model="empresa.correo">
                </div>
                <div class="form-group">
                    <strong>Página Web</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Pagina Web"
                        v-model="empresa.web">
                </div>
                <div class="form-group">
                    <strong>Descripcion</strong>
                    <input type="text" class="form-control form-control-sm" placeholder="Descripcion"
                        v-model="empresa.descripcion">
                </div>
            </div>
        </div>
        
    </div>
    <div class="card-footer">
        <button class="btn btn-success" @click="update"><span class="fa fa-save"></span> Actualizar</button>
    <button class="btn btn-secondary" @click="cancelar"><span class="fa fa-times"></span> Cancelar</button>
    </div>
</div>
@endsection
@section('script')
    <script>
        var app= new Vue({
            el: '#app',
            data: {
                empresa: {nombre: '{{trim($empresa->emp_nombre)}}',sucursal: '{{$empresa->suc_cod}}', ciudad: '{{$empresa->CIUDAD_cod}}',direccion: '{{ trim($empresa->emp_direccion)}}', ruc: '{{$empresa->emp_ruc}}',celular: '{{$empresa->emp_celular}}', telefono: '{{ $empresa->emp_telefono}}', correo: '{{trim($empresa->emp_correo)}}',web: '{{trim($empresa->emp_web)}}', descripcion: '{{ trim($empresa->emp_descripcion)}}'}
            },
            methods: {
                update: function(){
                    if(this.empresa.nombre.length > 0){
                       axios.post('empresa',this.empresa)
                    .then(response =>{
                        location.reload();
                    })
                    .catch(error => {
                        console.log(error.message);
                    })
 
                    }else{
                        Swal.fire('Campos vacios!', 'Complete campo para agregar...', 'wargning');
                    }
                    
                },
                cancelar : function(){
                    location.reload();
                }
            }
        });
        activarMenu('m_mantenimiento', 'm_empresa');
    </script>
@endsection
