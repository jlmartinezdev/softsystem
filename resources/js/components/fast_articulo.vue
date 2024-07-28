<template>
    <div class="modal fade" id="fastArticulo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">Articulo Rapido</strong>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong><label for="codigo">Codigo</label></strong>
                        <input type="text" name="codigo" id="codigo" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong><label for="descripcion">Descripcion</label></strong>
                        <input type="text" name="descripcion" id="descripcion" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong><label for="precio">Precio</label></strong>
                        <input type="text" name="precio" id="precio" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary"><span class="fa fa-save"> Agregar</span></button>
                </div>
            </div>
        </div>
    </div>
    
</template>
<script>
export default{
    name: 'fast_articulo',
    props: ['descripcion'],
    data(){
        return {
            codigo: '',
            descripcion: '',
            precion: ''
        }
    },
    methods: {
        saveArticulo: function(){
            
        }
    }
}
</script>