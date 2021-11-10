<template>
    <div>
        <div class="modal fade" id="busquedaProveedor">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <span class="modal-title">Buscar Proveedor...</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Cerrar</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <input type="text" v-model="txtproveedor" @keyup.enter="buscar()" class="form-control" placeholder="Buscar por Razon Social. ...."/>
                            <div class="input-group-append">
                            <button class="btn btn-secondary" @click="buscar()">
                                <template v-if="requestSend">
                                    <span class="spinner-border spinner-border-sm" role="status"></span><span class="sr-only">Buscando...</span> Cargando...
                                </template>
                                <template v-else>
                                <span class="fa fa-search"></span> Buscar
                                </template>
                                </button>
                            </div>
                        </div>
                    
                        <table class="table table-sm table-striped table-hover mt-2">
                            <tr>
                                <th>Ruc</th>
                                <th>Razon Social</th>
                                <th>Direccion</th>
                                <th>Celular</th>
                                <th>Sel.</th>
                            </tr>
                            <template v-for="p in proveedores">
                                <tr>
                                    <td>{{p.proveedor_ruc}}</td>
                                    <td>{{p.proveedor_nombre}}</td>
                                    <td>{{p.proveedor_direc}}</td>
                                    <td>{{p.proveedor_telef}}</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" @click="selproveedor(p)" title="Seleccionar">
                                            <span class="fa fa-user-check"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-times"></span> Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>
<script>


export default {
    name: 'busquedaproveedor',
    data(){
        return{
            proveedores:[],
            proveedor:{},
            txtproveedor: '',
            requestSend: false,
        }
    },
    methods:{
        buscar: function(){
            if(this.txtproveedor.length> 0){
                this.requestSend= true;
                axios.get('proveedor/buscar',{params:{nombre: this.txtproveedor }})
                .then(response =>{
                    this.requestSend= false;
                    this.proveedores= response.data;
                })
                .catch(error =>{
                    this.requestSend= false;
                    console.log(error.message);
                })
            }
        },
        selproveedor: function(proveedor){
            this.$emit('set_proveedor',proveedor)
        },
        
    },
    mounted(){
        axios.get('proveedor/all',{params:{nombre: this.txtproveedor }})
        .then(response =>{
            this.proveedores= response.data;
        })
        .catch(error =>{
            console.log(error.message);
        })
    }
}
</script>
