@extends('layouts.app')
@section('title','Gestionar Compra')
@section('main')
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                            <div>
                                <h5 class="mb-1">Buscar articulos</h5>
                                <small class="text-muted">Agrega productos y gestiona cantidades desde el carrito.</small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm mt-2 mt-sm-0" @click="showBuscar">
                                <span class="fa fa-search mr-2"></span>
                                Abrir buscador
                            </button>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <Searcharticulo url="{{ env('APP_APIDB') }}" :idsucursal="compraCabecera.idSucursal" @articulo="addCarrito"
                            validar-lote="false">
                        </Searcharticulo>
                        <p class="text-muted small mb-0 mt-3">
                            Tambien podes presionar <strong>Enter</strong> desde el campo de busqueda para agregar rapidamente.
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <h5 class="mb-0">Carrito de compra</h5>
                        <span class="badge badge-pill badge-primary mt-2 mt-md-0">@{{ carro.length }} articulos</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm mb-0 align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-uppercase small">Codigo</th>
                                        <th class="text-uppercase small">Descripcion</th>
                                        <th class="text-uppercase small text-center">Cantidad</th>
                                        <th class="text-uppercase small text-right">Costo</th>
                                        <th class="text-uppercase small text-right">Importe</th>
                                        <th class="text-uppercase small text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody v-if="carro.length">
                                    <tr v-for="(item,index) in carro" :key="item.codigo + '-' + item.idstock">
                                        <td>@{{ item.codigo }}</td>
                                        <td>@{{ item.descripcion }}</td>
                                        <td class="text-center">@{{ item.cantidad }}</td>
                                        <td class="text-right">@{{ new Intl.NumberFormat('de-DE').format(item.costo) }}</td>
                                        <td class="text-right">@{{ new Intl.NumberFormat('de-DE').format(item.costo * item.cantidad) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-link text-secondary dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span class="fa fa-bars"></span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button class="dropdown-item" @click="setCantidad(index,item.cantidad,item.stock)">
                                                        <span class="fa fa-cubes text-primary" style="width: 13pt"></span> Cantidad
                                                    </button>
                                                    <button class="dropdown-item" @click="showSetPrecio(index,item)">
                                                        <span class="fa fa-dollar-sign text-info" style="width: 13pt"></span> Precio
                                                    </button>
                                                    <button class="dropdown-item" @click="delArticulo(item)">
                                                        <span class="fa fa-times-circle text-danger" style="width: 13pt"></span> Quitar
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <span class="d-block mb-2"><span class="fa fa-box-open fa-lg"></span></span>
                                            Todavia no agregaste articulos. Usa el buscador para comenzar.
                                        </td>
                                    </tr>
                                </tbody>
                              
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm sticky-top" style="top: 1.5rem;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumen de compra</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted text-uppercase small">Estado de caja</span>
                                <span class="badge badge-pill" :class="[ caja=='ABIERTA' ? 'badge-success' : 'badge-danger' ]">@{{ caja }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted text-uppercase small">Nro. Operacion</span>
                                <span class="badge badge-pill" :class="[ caja=='ABIERTA' ? 'badge-success' : 'badge-danger' ]">@{{ nrooperacion }}</span>
                            </div>
                        </div>

                        <div class="bg-light border rounded p-3 mb-4">
                            <span class="text-muted text-uppercase small d-block">Total estimado</span>
                            <h3 class="mb-0 text-success">@{{ totalCompra }}</h3>
                        </div>

                        <fieldset class="form-group">
                            <label>Fecha</label>
                            <input type="date" v-model="compraCabecera.fecha" class="form-control form-control-sm" placeholder="Fecha">
                        </fieldset>

                        <fieldset class="form-group">
                            <label>Proveedor</label>
                            <div class="input-group input-group-sm">
                                <input type="text" v-model="compraCabecera.proveedor" disabled class="form-control" placeholder="Nombre Proveedor">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" @click="showBuscarProveedor()">
                                        <span class="fa fa-user mr-1"></span>Buscar
                                    </button>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="factura">Nro. Factura</label>
                            <div class="input-group input-group-sm">
                                <input type="text" placeholder="001" v-model="compraCabecera.factura_n1" v-on:blur="rellenarCero('factura_n1',3)" class="form-control">
                                <input type="text" placeholder="001" v-model="compraCabecera.factura_n2" v-on:blur="rellenarCero('factura_n2',3)" class="form-control">
                                <input type="text" placeholder="0000001" v-model="compraCabecera.factura_n3" v-on:blur="rellenarCero('factura_n3',7)" class="form-control">
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <label>Descuento</label>
                            <input type="number" v-model="compraCabecera.descuento" class="form-control form-control-sm" placeholder="Descuento...">
                        </fieldset>

                        <button class="btn btn-success btn-block" @click="showFinalizar">
                            <span class="fa fa-check mr-2"></span>
                            <strong>Finalizar compra</strong>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <busqueda @articulo_sel="validarArticulo" url="{{env('APP_APIDB')}}" :txt_buscar="txtbuscar" ref="busqueda"></busqueda>
    <busquedaproveedor @set_proveedor="setProveedor"></busquedaproveedor>
    @include('compra.finalizar')
    @include('compra.precio')
    @include('articulo.precio')
</div>
@endsection
@section('script')
<script src="{{ asset(mix('js/busqueda.js'))}}"></script>
<script src="{{ asset(mix('js/component/proveedor.js'))}}"></script>
<script type="text/javascript">
    const defaulPrecio = [{p: 50,m: 5,c: 2}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}];
    var app=new Vue({
    el: '#app',
    data: {
        txtbuscar: '',
        inNumberClass: {
            input: 'form-control form-control-sm'
        },
        requestSend: false,
        requestLote: false,
        carro: [],
        articulos: [],
        preciosCreditos: [],
        precioCredito : [],
        chcuota: false,
        chprecio: false,
        precios: defaulPrecio,
        articulo: {},
        pos_edit: 0,
        stocks: [],
        requestLote: false,
        compraCabecera: {fecha: '2021-01-01',idproveedor:1,proveedor:'',pro:'aa',idSucursal: 1,factura_n1:'',factura_n2:'',factura_n3:'',total:0,descuento:0,nro_operacion:0,condicioncompra:1,formacobro:1},
        caja : '...',
        nrooperacion: '...',
        articulo_selecionado: {}
    },
    watch: {
        chprecio: function(newVal, oldVal) {
            for (i = 0; i < this.precios.length; i++) {
                this.setPrecio(i);
            }
        },
        chcuota: function(newVal, oldVal) {
            for (i = 0; i < this.precios.length; i++) {
                this.setCuota(i);
            }
        }

    },
    methods:{
        setMargen: function(index) {
            if (typeof(this.articulo.costo === 'string')) {
                this.articulo.costo = parseInt(this.articulo.costo);
            }
            if (this.articulo.costo > 0 && this.precios[index].p > 0) {
                if (this.precios[index].p > this.articulo.costo) {
                    var res = this.precios[index].p - this.articulo.costo;
                    this.precios[index].m = Math.round(res * 100 / this.articulo.costo);
                } else {
                    this.precios[index].m = 0;
                }
                this.setCuota(index)
            }
        },
        setPrecio: function(index) {
            if (typeof(this.articulo.costo === 'string')) {
                this.articulo.costo = parseInt(this.articulo.costo);
            }
            if (this.articulo.costo < 1) {
                this.precios[index].p = 0;
                return;
            }
            if (parseInt(this.precios[index].m) < 1 || this.precios[index].m.length == 0) {
                this.precios[index].p = 0;
                return;
            }

            var retornar = parseInt((this.articulo.costo * parseInt(this.precios[index].m)) / 100 + this.articulo
                .costo)
            if (this.chprecio)
                this.precios[index].p = this.redondear(retornar);
            else
                this.precios[index].p = retornar; //parseInt(retornar)

        },
        setCuota: function(index) {
            if (this.precios[index].p > 0) {
                if (this.chcuota) {
                    this.precios[index].c = this.precios[index].p / (index + 2);
                    this.precios[index].c = this.redondear(parseInt(this.precios[index].c));
                } else {
                    this.precios[index].c = parseInt(this.precios[index].p / (index + 2));
                }
            } else {
                this.precios[index].c = 0;
            }
        },
        mostrarPrecios: function() {
            if(this.articulo.costo > 0 ){
                $('#preciocompra').modal('hide');
                $('#precioArticulo').modal('show');
            }else{
                Swal.fire('Atencion...','Agregue precio de compra','info');
            }

        },
        cerrarPrecios: function() {
            $('#preciocompra').modal('show');
            $('#precioArticulo').modal('hide');
        },
        format: function(numero){
            return new Intl.NumberFormat("de-DE").format(numero);
        },
        showBuscar: function(){
            if( this.txtbuscar.length>0) {
                this.$refs.busqueda.setBuscar();
            }
            $('#busquedaArticulo').modal('show');
            //this.buscar(false);
        },
        showBuscarProveedor: function(){
            $('#busquedaProveedor').modal('show');
        },
        setProveedor: function(p){
            this.compraCabecera.idproveedor= p.PROVEEDOR_cod;
            this.compraCabecera.proveedor= p.proveedor_nombre;
            $('#busquedaProveedor').modal('hide');
            this.saveDatos();
        },
        validarArticulo: function(a){
              this.requestLote= true;
                if(this.compraCabecera.idSucursal=== undefined){
                    Swal.fire('Por favor seleccione una sucursal!','warning');
                }
              //Traer lotes
              axios.get('{{env("APP_APIDB")}}',{params:{ lotecompra : a.ARTICULOS_cod, bus_suc : this.compraCabecera.idSucursal}})
                .then(response =>{
                    const stocks= response.data;
                    this.requestLote= false;
                    if(stocks.length>1){ //Si hay mas de un lote
                        $('#busquedaArticulo').modal('hide');
                        this.validarLote(a,stocks);
                    }else{
                        this.addCarrito(a,stocks[0].id_stock);
                        $('#busquedaArticulo').modal('hide');
                        this.txtbuscar= "";
                    }
                })

        },
        validarLote:async function(articulo,lotes){
            var values= {};
            for (var i = 0; i < lotes.length; i++) {
                values[i]=lotes[i].lote_nro;
            }
            const { value: lote } = await Swal.fire({
                title: 'Seleccione Lote',
                input: 'select',
                inputOptions: values,
                inputPlaceholder: 'Seleccione lote',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                })
            if(lote){
                this.addCarrito(articulo,lotes[lote].id_stock);
            }
        },
        addCarrito: function(a){
            let i=this.carro.findIndex(x=> x.codigo == a.ARTICULOS_cod &&  x.idstock==a.id_stock);
            if(i == -1){
                let art= {
                    codigo: a.ARTICULOS_cod,
                    idstock: a.id_stock,
                    descripcion: a.producto_nombre,
                    cantidad: 1,
                    stock: a.cantidad,
                    costo: a.producto_costo_compra,
                    precio: a.pre_venta1,
                    p1: parseInt(a.pre_venta1,10),
                    p2: parseInt(a.pre_venta2,10),
                    p3: parseInt(a.pre_venta3,10),
                    p4: parseInt(a.pre_venta4,10),
                    p5: parseInt(a.pre_venta5,10),
                    m1: parseInt(a.pre_margen1,10),
                    m2: parseInt(a.pre_margen2,10),
                    m3: parseInt(a.pre_margen3,10),
                    m4: parseInt(a.pre_margen4,10),
                    m5: parseInt(a.pre_margen5,10)}
                this.carro.push(art);
                this.getPreciosCredito(a.ARTICULOS_cod);
            }else{
                this.carro[i].cantidad= parseInt(this.carro[i].cantidad) + 1;//Actualizar cantidad
            }
            //this.saveDatos(); // Llamar despues del request
        },
        setCantidad: async function(index,cantidad,stock){
            const swalBootstrap = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-primary mr-2',
                cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            const { value: cant } = await swalBootstrap.fire({
                title: 'Escriba cantidad a Comprar...',
                input: 'number',
                inputValue: cantidad,
                inputAttributes: { min :0 , max : 1000},
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            })
            if (cant) {
                this.carro[index].cantidad= cant;
                this.saveDatos();
            }
        },

        showSetPrecio:function(index,a){
            this.pos_edit= index;
            this.articulo= this.carro[index];
            let indexPrecio = this.preciosCreditos.findIndex( x => x.id== this.articulo.codigo);
            if(index != -1){
                this.precios = this.preciosCreditos[indexPrecio].precios;
            }else{
                this.precios = defaulPrecio;
            }

            $('#preciocompra').modal('show');
            this.get_historial();

        },
        update_precio: function(){
            this.carro[this.pos_edit]= this.articulo;
            this.preciosCreditos[this.preciosCreditos.findIndex( x => x.id== this.articulo.codigo)].precios= this.precios;
            $('#preciocompra').modal('hide');
            this.saveDatos();
        },
        getPreciosCredito: function(id){
            axios.get('articulo/precios/'+id).then(response =>{
                if(response.data.length> 0){
                    //this.preciosCreditos=[];
                    let tmpPrecios= [];
                    for(i=0;i<response.data.length; i++){
                        let precios={p:response.data[i].p,c: response.data[i].c, m:response.data[i].m}
                        tmpPrecios.push(precios);
                    }
                    this.preciosCreditos.push({'id':id,'precios':tmpPrecios});
                    this.saveDatos();
                }else{
                    this.preciosCreditos.push({'id':id,'precios':defaulPrecio});
                    this.saveDatos();
                }
            }).catch( error => {
                this.error= error.message;
            })
        },
        setUtilPrecio: function(tipo,i){
            if(tipo=='M'){
                if(this.articulo.costo > 0 && this.articulo['m'+i]  > 0){
                    this.articulo['p'+i]= ((this.articulo.costo * this.articulo['m'+i])/100) + parseFloat(this.articulo.costo);
                }

            }else{
            if(this.articulo.costo >0 && this.articulo['p'+i]  > 0){
                var res= this.articulo['p'+i] - this.articulo.costo;
                this.articulo['m'+i]= Math.round(res*100/this.articulo.costo);
            }
            }

        },
        delArticulo: function(a){
            let validar = this.carro.findIndex(x=> x.codigo == a.codigo)
            if(validar > -1 ) {
                this.carro.splice(validar,1);
                this.preciosCreditos.splice(this.preciosCreditos.findIndex(x => x.id == a.codigo));
            }
            this.saveDatos();
        },
        showFinalizar: function(){
            if(this.caja=='ABIERTA'){
                if(this.compraCabecera.total > 0 && this.compraCabecera.proveedor.length > 0){
                    $('#finalizarcompra').modal('show');
                }else{
                    Swal.fire('Atencion...','Seleccione los articulos y un proveedor!','warning');
                }
            }else{
                Swal.fire('Atencion...','Caja no esta abierta!','warning');
            }
        },
        finalizar: function(){
            axios.post('compra',{compraCabecera: this.compraCabecera, detalle: this.carro, precios: this.preciosCreditos})
            .then(response =>{
                this.carro= [];
                this.precios= defaulPrecio;
                this.preciosCreditos= [];
                localStorage.removeItem('carro_compra');
                localStorage.removeItem('compraCabecera');
                localStorage.removeItem('compraPreciosCredito');
                $('#finalizarcompra').modal('hide');
                this.compraCabecera.factura_n1="";
                this.compraCabecera.factura_n2="";
                this.compraCabecera.factura_n3="";
                this.compraCabecera.proveedor="";
                this.compraCabecera.idproveedor= 1;
            })
            .catch(error =>{
                Swal.fire('Error',error.message,'error');
            })

        },
        get_historial: function(){
            axios.get('compra/historial',{params:{ ARTICULOS_cod : this.articulo.codigo}})
                .then(response =>{
                    this.articulos= response.data;
                })
        },
        saveDatos: function(){
            localStorage.setItem('carro_compra',JSON.stringify(this.carro));
            localStorage.setItem('compraCabecera',JSON.stringify(this.compraCabecera));
            localStorage.setItem('compraPreciosCredito',JSON.stringify(this.preciosCreditos));

        },
        recuperarDatos: function(){
            var carro= localStorage.getItem('carro_compra');
            if(carro != null){
                this.carro= JSON.parse(carro);
            }
            var cab = localStorage.getItem('compraCabecera');
            if(cab != null){
                this.compraCabecera= JSON.parse(cab);
            }
            let prec = localStorage.getItem('compraPreciosCredito');
            if(prec != null){
                this.preciosCreditos= JSON.parse(prec);
            }
        },
        getSucursal: function(){
            var obj= document.getElementById("sucursal");
            if(obj.getAttribute('data-id')!= null)
                this.compraCabecera.idSucursal= obj.getAttribute('data-id');
        },
        getApertura: function(){
            let idSucursal= $('#sucursal').attr('data-id');
            this.compraCabecera.idSucursal=idSucursal;
            if(idSucursal != null){
                axios.get('aperturacierre/'+idSucursal)
                .then(response =>{
                    if(response.data){
                        this.nrooperacion= response.data.nro_operacion;
                        this.compraCabecera.nro_operacion= response.data.nro_operacion;
                        this.caja= 'ABIERTA';
                    }else{
                        this.caja= 'CERRADA';
                    }
                })
                .catch(error =>{
                    console.log(error);
                })
            }
        },
        validarNroFactura: function(n,flag){
            if(flag== 3){

            }else{
                if(n.length < 3 ){
                    n.toString().padStart(2, "0")
                }
            }
        },
        numeroaletra: function(n){
            return NumeroALetras.NumeroALetras(n);
        },
        getFecha: function() {
            var f = new Date();
            this.compraCabecera.fecha= f.format("yyyy-mm-dd");
            //var dia =  f.getDate();
            //var mes = (f.getMonth() + 1);
            //this.compraCabecera.fecha= f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString().padStart(2, "0");
            //this.filtrovalue= this.meses[mes];

        },
        rellenarCero: function(obj,cantidad){

            if(cantidad==3){
                this.compraCabecera[obj]= this.compraCabecera[obj].toString().padStart(3,"0");
            }else{
                this.compraCabecera[obj]= this.compraCabecera[obj].toString().padStart(7,"0");
            }
        }
    },
    computed: {
        totalCompra: function(){
            this.compraCabecera.total=0;
            for (var i = 0; i < this.carro.length; i++) {
                this.compraCabecera.total += (this.carro[i].costo * this.carro[i].cantidad);
            }
            if(this.compraCabecera.descuento > 0 && this.compraCabecera.total > 0){
                this.compraCabecera.total -=  this.compraCabecera.descuento;
            }
            return this.format(this.compraCabecera.total);

        }

    },
    mounted(){
        this.getApertura();
        this.recuperarDatos();
        this.getSucursal();
        this.getFecha();
    }
})
activarMenu('m_compra','');
</script>
@endsection
