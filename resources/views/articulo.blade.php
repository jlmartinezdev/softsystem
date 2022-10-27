@extends('layouts.app')
@section('title', 'Articulo')
@section('style')
<style>
    .vgt-table tr{
        font-family: Arial, Helvetica, sans-serif;
    }
    .vgt-table td{
        color: rgb(8, 8, 8);
    }
</style>
@endsection
@section('main')
    <div class="container" id="app">
        <div class="font-weight-bold" style="font-size: 14pt;">Mantimiento de Articulos</div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 col-md-2 pl-2 pb-2">
                        <button class="btn btn-primary btn-block" @click="showMArticulo"><span class="fa fa-plus"></span>
                            Nuevo</button>
                    </div>
                   
                    <div class="col-sm-8 col-md-6">
                        <div class="input-group">
                            <input type="text" v-model="txtbuscar" @keyup.enter="buscar(false)" class="form-control"
                                placeholder="Buscar...." />
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="buscar(false)">
                                    <template v-if="requestSend">
                                        <span class="spinner-border spinner-border-sm" role="status"></span><span
                                            class="sr-only">Cargando...</span> Cargando...
                                    </template>
                                    <template v-else>
                                        <span class="fa fa-sync"></span> Recargar
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" @click="buscar(false)" v-model="filtro.seccion">
                            <option value="0">Todos</option>
                            @foreach ($secciones as $seccion)
                                <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pl-3" >
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false">
                                <span class="fa fa-file-excel"></span> Exportar
                            </button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" @click="exportar('stock')">Con Stock</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item" @click="exportar('precios')">Precios Creditos</button>
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>

        </div><!--  END CARD -->
        <template>
            <div>
                <vue-good-table
                  :columns="columns"
                  :rows="rows"
                  style-class="vgt-table striped"
                  :pagination-options="{
                    enabled: true
                  }"
                  :search-options="{
                    enabled: true,
                    externalQuery: txtbuscar,
                    searchFn: busqueda_tabla
                  }"
                  >
                  <template slot="table-row" slot-scope="props">
                    <span v-if="props.row.stock=='0'">
                      <span style="color: rgb(226, 0, 0);">
                        <span v-if="!props.column.html">
                            @{{props.formattedRow[props.column.field]}}
                        </span>
                        <span v-else v-html="props.row[props.column.field]">
                        </span>
                       
                    </span> 
                    </span>
                    
                  </template>
                </vue-good-table>
                  
              </div>
        </template>
        

        @include('articulo.create')
        @include('articulo.edit')
        @include('articulo.delete')
        @include('articulo.detalle')
        @include('articulo.precio')

    </div>
@endsection
@section('script')
    <script src="{{ asset('js/separator.js') }}"></script>
    <script>
        const defaultStock= {
                        'id': 0,
                        'cantidad': 0,
                        'loteold': '',
                        'lotenew': '',
                        'vencimiento': '',
                        'sucursal': 1
                    };
        const defaultPrecio= [{p: 50,m: 5,c: 2}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,
c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {
p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,
c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}, {p: 0,m: 0,c: 0}];
        const defaultArticulo= { 'codigo': '',
                        'c_barra': '',
                        'descripcion': '',
                        'indicaciones': '',
                        'modouso': '',
                        'seccion': 1,
                        'unidad': 1,
                        'factor': 1,
                        'ubicacion': '',
                        'costo': 0,
                        'p1': 0,
                        'p2': 0,
                        'p3': 0,
                        'p4': 0,
                        'p5': 0,
                        'm1': 0,
                        'm2': 0,
                        'm3': 0,
                        'm4': 0,
                        'm5': 0,
                        'svenc': '0',
                        existePrecios: false
                    }
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                
                precios: [...defaultPrecio],
                chcuota: false,
                chprecio: false,
                url: 'controller/ArticulosController.php',
                reservarC: false,
                bandstock: 0,
                isnew: true,
                viewPrecio: false,
                txtbuscar: '',
                datos: 'F',
                idstock: 1,
                articulos: [],
                secciones: [],
                sucursales: [],
                unidades: [],
                filtro: {
                    seccion: 0,
                    columna: 0,
                    orden: 'ASC'
                },
                articulo: {...defaultArticulo},
                stock: {...defaultStock},
                stocks: [],
                error: '',
                cantidadStock: 0,
                frmt: {
                    i: -1,
                    t: false,
                    suc: 0,
                    cant: 0
                },
                columns: [
                    {
                    label: 'Codigo',
                    field: 'codigo',
                    },
                    {
                    label: 'Descripcion',
                    field: 'descripcion',
                    },
                    {
                    label: 'Seccion',
                    field: 'seccion',
                    },
                    {
                    label: 'Precio',
                    field: 'precio',
                    type: 'number'
                    },
                    {
                    label: 'Stock',
                    field: 'stock',
                    type: 'number',
                    },
                    {
                    label: 'Opciones',
                    field: 'opciones',
                    html: true
                    },
                
                ],
                rows: []
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
            methods: {
                busqueda_tabla: function(row, col, cellValue, searchTerm){
                    if(row.descripcion.toUpperCase().includes(searchTerm.toUpperCase())){
                        return cellValue;
                    }
                },
                setMargen: function(index) {
                    if(this.viewPrecio){
                        return false;
                    }
                    if (typeof(this.articulo.costo === 'string')) {
                        this.articulo.costo = this.articulo.costo * 1;
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
                    if(this.viewPrecio){
                        return false;
                    }
                    if (typeof(this.articulo.costo === 'string')) {
                        this.articulo.costo = parseInt(this.articulo.costo)
                    }
                    if (this.articulo.costo < 1) {
                        this.precios[index].p = 0;
                        return;
                    }
                    if (parseInt(this.precios[index].m) < 1 || this.precios[index].m.length == 0) {
                        this.precios[index].p = 0;
                        return;
                    }

                    var retornar = parseInt((this.articulo.costo * parseInt(this.precios[index].m)) / 100 + this.articulo.costo)
                    if (this.chprecio)
                        this.precios[index].p = this.redondear(retornar);
                    else
                        this.precios[index].p = retornar; //parseInt(retornar) 

                },
                setCuota: function(index) {
                    if(this.viewPrecio){
                        return false;
                    }
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
                redondear: function(monto) {
                    var longitud = 0,
                        x = "",
                        b = "",
                        PFinal = 0;
                    if (monto > 1000) {
                        longitud = monto.toString().length;
                        x = monto.toString().substr(-3);
                        if (parseInt(x) > 500) {
                            x = "500"
                        } else {
                            x = "000"
                        }
                        b = monto.toString().substr(0, longitud - 3);
                        PFinal = parseInt(b + x);
                    } else {
                        if (monto => 500) {
                            PFinal = 500;
                        }
                    }
                    return PFinal;
                },
                validar_Cbarra: function() {
                    if (this.articulo.c_barra.length == 0) {
                        this.articulo.c_barra = this.articulo.codigo.toString().padStart(7, '0')
                    }
                },
                onChange: function() { //Al cambiar pagina
                    if (this.paginacion.ultima_pagina > 1) {
                        this.buscar(true);
                    }

                },
                verPreciosCredito: function(cod,costo){
                    this.viewPrecio= true;
                    this.articulo.costo= costo;
                    this.getPrecios(cod);
                    this.mostrarPrecios();

                },
                mostrarPrecios: function() {
                    if(this.articulo.costo > 0 ){
                        if(!this.viewPrecio){
                            if (this.isnew) {
                                $('#addArticulo').modal('hide');
                            } else {
                                $('#editArticulo').modal('hide');
                            }
                        }

                        $('#precioArticulo').modal('show');
                    }else{
                        Swal.fire('Atención...','Agregue precio de compra','info');
                    }
                    
                },
                cerrarPrecios: function() {
                    if(!this.viewPrecio){
                        if (this.isnew) {
                            $('#addArticulo').modal('show');
                        } else {
                            $('#editArticulo').modal('show');
                        }
                    }
                    $('#precioArticulo').modal('hide');
                },
                color: function(id) {
                    return this.frmt.i == id ? true : false;
                },
                cancelTrans: function() {
                    $('#accordiontransferir').collapse('hide');
                    this.frmt = {
                        i: -1,
                        t: false,
                        suc: 0,
                        cant: 0
                    }
                },
                buscar: function(isPaginate) {
                    this.requestSend = true;
                    let pag = isPaginate ? this.currentPage : 1
                    axios.get('articulo/buscar', {
                            params: {
                                page: pag,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: null
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            if (response.data == 'NO') {
                                Swal.fire('No se encontrado resultado!', 'Para:  ' + this.txtbuscar,
                                    'info');
                            } else {
                                this.rows= [];
                                this.articulos = response.data;
                                for (let i = 0; i < response.data.length; i++) {
                                    const item = {
                                        codigo : this.articulos[i].producto_c_barra,
                                        descripcion: this.articulos[i].producto_nombre,
                                        seccion: this.articulos[i].present_descripcion,
                                        precio : this.separador(this.articulos[i].pre_venta1),
                                        stock : this.articulos[i].cantidad,
                                        opciones : `<div class="btn-group">
                                        <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="fa fa-bars"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button class='dropdown-item' onclick="app.showEArticulo('${this.articulos[i].ARTICULOS_cod}')"><span
                                                    class="fa fa-edit text-primary"></span> Editar</button>
                                            <button class='dropdown-item' onclick="app.verPreciosCredito( '${this.articulos[i].ARTICULOS_cod}','${this.articulos[i].producto_costo_compra}')"><span
                                                class="fa fa-edit text-primary"></span> Ver Precios Credito</button>
                                            <button class='dropdown-item'
                                                onclick="app.modalDelete( '${this.articulos[i].ARTICULOS_cod}', '${this.articulos[i].producto_nombre}')"><span
                                                    class="fa fa-trash text-primary"></span> Eliminar</button>
                                            <button class='dropdown-item'
                                                onclick="app.showDetalle( '${this.articulos[i].ARTICULOS_cod}','${this.articulos[i].producto_nombre}' )"><span
                                                    class="fa fa-retweet text-primary"></span> Transferir</button>
                                            <button class="dropdown-item" onclick="app.duplicar('${this.articulos[i].ARTICULOS_cod}')">
                                                <span class="fa fa-copy text-primary"></span> Duplicar
                                            </button>
                                        </div>
                                    </div>`
                                    }
                                    this.rows.push(item);
                                    
                                }
                               
                            }
                            //this.error=response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        });
                },
                setUtilPrecio: function(tipo, i) {
                    if (tipo == 'M') {
                        this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                            parseFloat(this.articulo.costo);
                    } else {
                        if (this.articulo.costo > 0 && this.articulo['p' + i] > 0) {
                            var res = this.articulo['p' + i] - this.articulo.costo;
                            this.articulo['m' + i] = Math.round(res * 100 / this.articulo.costo);
                        }
                    }
                },
                separador: function(number) {
                    var n = parseFloat(number);
                    return new Intl.NumberFormat().format(n);
                },
                showMArticulo: function() {
                    this.isnew = true;
                    this.viewPrecio= false;
                    this.cleanAll();
                    $('#addArticulo').modal('show');
                    $('#tabadd a:first-child').tab('show')
                    //this.getUltimo();
                    setTimeout(function() {
                        $('input[name="cbarraN"]').focus();
                        
                    }, 500); 

                    //document.getElementById("cbarraN").focus()
                },
                showEArticulo: function(id) {
                    const a = this.articulos[this.articulos.findIndex(e => e.ARTICULOS_cod== id)];
                    $('#editArticulo').modal('show');
                    this.setArticulo(a);
                    this.getStock(a.ARTICULOS_cod);
                    this.getPrecios(a.ARTICULOS_cod);
                    this.reservarC = false;
                    this.isnew = false;
                    this.viewPrecio= false;
                    $('#tabedit a:first-child').tab('show')
                    //this.getUltimo();
                    setTimeout(function() {
                        $('input[name="descripcionE"]').focus();
                        
                    }, 500);
                },
                setArticulo: function(a){
                    this.articulo = {
                        'codigo': a.ARTICULOS_cod,
                        'c_barra': a.producto_c_barra,
                        'descripcion': a.producto_nombre,
                        'indicaciones': a.producto_indicaciones == null ? a.producto_indicaciones : a
                            .producto_indicaciones /*.trim()*/ ,
                        'modouso': a.producto_dosis == null ? a.producto_dosis : a
                            .producto_dosis /*.trim()*/ ,
                        'seccion': a.present_cod,
                        'unidad': a.uni_codigo,
                        'factor': a.producto_factor,
                        'ubicacion': a.producto_ubicacion,
                        'costo': a.producto_costo_compra,
                        'p1': a.pre_venta1,
                        'p2': a.pre_venta2,
                        'p3': a.pre_venta3,
                        'p4': a.pre_venta4,
                        'p5': a.pre_venta5,
                        'm1': parseInt(a.pre_margen1, 10),
                        'm2': parseInt(a.pre_margen2, 10),
                        'm3': parseInt(a.pre_margen3, 10),
                        'm4': parseInt(a.pre_margen4, 10),
                        'm5': parseInt(a.pre_margen5, 10),
                        'svenc': '0',
                        existePrecios: false
                    }
                },
                duplicar: function(id){
                    const articulo = this.articulos[this.articulos.findIndex(e => e.ARTICULOS_cod== id)];
                    this.isnew = true;
                    this.viewPrecio= false;
                    this.setArticulo(articulo);
                    this.articulo.codigo = '';
                    this.articulo.c_barra = '';
                    $('#addArticulo').modal('show');
                    $('#tabadd a:first-child').tab('show')
                    //this.getUltimo();
                    setTimeout(function() {
                        $('input[name="cbarraN"]').focus();
                        
                    }, 500);

                },
                setPrecioVenta: function() {
                    if (this.articulo.costo > 0) {
                        for (var i = 1; i < 6; i++) {
                            this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                                parseFloat(this.articulo.costo);

                        }
                    }
                },
                getD: function() {
                    return {
                        'id': this.idstock,
                        'cantidad': this.stock.cantidad,
                        'loteold': this.reservarC ? this.stock.lotenew : this.stock.loteold,
                        'lotenew': this.stock.lotenew,
                        'vencimiento': this.validarVenc(this.stock.vencimiento),
                        'sucursal': this.stock.sucursal
                    };
                },
                validarVenc: function(fecha) {
                    if (fecha.length < 1) {
                        return "Sin vencimiento";
                    }
                    this.articulo.svenc = '1'
                    return fecha;
                },
                addStock: function() {
                    if (this.stock.cantidad > 0) {
                        var x = this.stocks.findIndex(x => x.lotenew == this.stock.lotenew && x.sucursal == this
                            .stock.sucursal);
                        if (x == -1) {
                            this.idstock = this.stocks.length + 1;
                            this.stock.loteold = this.stock.lotenew;
                            this.stocks.push(this.getD());

                            this.limpiarCamposStock();
                        } else {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'Existe un lote igual a esta, se actualiza cantidad...'
                            })
                            this.stocks[x].cantidad = parseInt(this.stocks[x].cantidad) + parseInt(this.stock
                                .cantidad);
                            if (this.stock.vencimiento.length > 0) {
                                this.stocks[x].vencimiento = this.stock.vencimiento;
                            }
                        }
                    }
                },
                setStock: function(s, index) {

                    if (this.frmt.t) {
                        this.frmt.i = -1;
                        this.frmt.t = false;
                    } else {
                        this.frmt.t = true;
                        this.frmt.i = index;
                    }
                    this.stock = {
                        'id': s.id,
                        'cantidad': 0,
                        'loteold': s.loteold,
                        'lotenew': s.lotenew,
                        'vencimiento': s.vencimiento,
                        'sucursal': s.sucursal
                    };
                },
                transladarStock: function() {
                    const i = this.stocks.findIndex(stock => stock.id == this.stock.id);

                    if (this.frmt.cant > 0 && this.frmt.suc > 0) {
                        if (this.stocks[i].sucursal == this.frmt.suc) {
                            Swal.fire('Atencion!', 'Seleccione otra sucursal!', 'warning');
                            return false;
                        }
                        if (this.frmt.cant > this.stocks[i].cantidad) {
                            Swal.fire('Atencion!', 'Cantidad ingresada es Mayor!', 'warning');
                            return false;
                        }
                        this.stocks[i].cantidad = parseInt(this.stocks[i].cantidad) - this.frmt.cant;
                        this.stocks.push({
                            'id': this.stocks.length + 1,
                            'cantidad': this.frmt.cant,
                            'loteold': this.stock.loteold,
                            'lotenew': this.stock.lotenew,
                            'vencimiento': this.stock.vencimiento,
                            'sucursal': this.frmt.suc
                        });
                        this.updateStock();

                    } else {
                        Swal.fire('Atencion!', 'Seleccione Destino e ingrese cantidad!', 'error');
                    }
                },
                getByIdSucursal: function(id) {
                    const suc = this.sucursales.find(sucursal => sucursal.suc_cod == id);
                    return suc.suc_desc;
                },
                limpiarCamposStock: function() {
                    this.bandstock = 0;
                    this.stock = {...defaultStock};
                },
                cleanAll: function() {
                    this.stocks = [];
                    this.limpiarCamposStock();
                    for (i = 0; i < 17; i++) {
                        this.precios[i].p = 0;
                        this.precios[i].m = 0;
                        this.precios[i].c = 0;
                    }
                    this.articulo = {...defaultArticulo};

                },
                delStockA: function(id) {
                    const s = this.stocks.find(stock => stock.id == id);
                    if (s.id > 20) {
                        const cant = parseInt(s.cantidad);
                        var index = this.articulos.findIndex(x => x.ARTICULOS_cod == this.articulo.codigo);
                        this.articulos[index].cantidad = parseInt(this.articulos[index].cantidad) - cant;
                        if (!this.reservarC) {
                            axios.delete('stock/' + s.id)
                                .then(response => {
                                    console.log(response.data)
                                })
                                .catch(e => {
                                    console.log(e.message);
                                });
                        }
                    }
                    this.stocks.pop(s);
                    this.limpiarCamposStock();
                },
                editStockA: function(stock) {
                    this.stock = stock;
                    this.bandstock = 1;
                },
                modalDelete: function(id, descripcion) {
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
                            axios.delete('articulo/res/' + id)
                                .then(r => {
                                    Swal.fire(
                                        'Eliminado!',
                                        'El registro ha sido eliminado.',
                                        'success'
                                    )
                                    location.reload();
                                }).catch(e => {
                                    console.log(e.message);
                                });
                        }
                    })
                },
                showDetalle: function(id, desc) {
                    this.articulo.descripcion = desc;
                    this.articulo.codigo = id;
                    this.getStock(id);
                    $('#detalleArticulo').modal('show');
                },
                delArticulo: function() {
                    if (this.reservarC) {
                        this.reservarC = false;
                        axios.delete('articulo/res/' + this.articulo.codigo)
                            .then(response => {
                                console.log(response.data)
                            })
                            .catch(e => {
                                console.log(e.message);
                            });
                    }
                },
                updateStock() {
                    if (this.stocks.length > 0) {
                        axios.post('stock/' + this.articulo.codigo, {
                            stock: this.stocks
                        }).then(r => {
                            this.cancelTrans();
                            this.buscar();
                        }).catch(e => {
                            this.error = e.message;
                        })
                    }
                },
                saveArticulo: function() {
                    if (this.articulo.descripcion && this.articulo.costo && this.articulo.p1) {
                        // this.validar_Cbarra();
                        if(this.stocks.length < 1){
                            this.stocks.push(defaultStock);
                        }
                        this.error = "";
                        if (this.isnew) {
                            axios.post('articulo', {
                                articulo: this.articulo,
                                stock: this.stocks,
                                precios: this.precios
                            })
                            .then(r => {
                                this.cleanAll();
                                $('#addArticulo').modal('hide');
                                this.buscar();
                            })
                            .catch(e => {
                                this.error = e.message;
                            })
                        } else {
                            axios.put('articulo/' + this.articulo.codigo, {
                                articulo: this.articulo,
                                stock: this.stocks,
                                precios: this.precios
                            })
                            .then(r => {
                                this.cleanAll();
                                $('#editArticulo').modal('hide');
                                this.buscar();
                            })
                            .catch(e => {
                                this.error = e.message;
                            })
                        }
                        
                    } else {
                        Swal.fire('Atencion', 'Hay campos obligatorios (*) vacios!', 'warning');
                    }
                },
                getArticulo: function() {
                    axios.get('articulo/buscar')
                        .then(response => {
                            this.articulos = response.data.articulos.data;
                            this.datos = 'T';
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getStock: function(id) {
                    axios.get('stock/' + id).then(r => {
                        this.stocks = r.data;
                    }).catch(e => {
                        this.error = e.message;
                    })
                },
                getPrecios: function(id) {
                    axios.get('articulo/precios/' + id).then(response => {
                        if (response.data.length > 0)
                            for (i = 0; i < response.data.length; i++) {
                                this.articulo.existePrecios = true;
                                this.precios[i].p = parseInt(response.data[i].p);
                                this.precios[i].m = parseInt(response.data[i].m);
                                this.precios[i].c = response.data[i].c;
                            }
                        else
                            for (i = 0; i < 17; i++) {
                                this.articulo.existePrecios = false;
                                this.precios[i].p = 0;
                                this.precios[i].m = 0;
                                this.precios[i].c = 0;
                            }
                    }).catch(error => {
                        this.error = error.message;
                    })
                },
                reservarCodigo: function() {
                    axios.post('articulo/res', {
                            "codigo": this.articulo.codigo
                        })
                        .then(r => {
                            this.reservarC = true;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getUltimo: function() {
                    axios.get('articulo/ultimo').then(r => {
                        this.articulo.codigo = (r.data) + 1;
                        this.reservarCodigo();
                    }).catch(e => {
                        Console.log(e.message)
                    })
                },
                getSeccion: function() {
                    var url = 'seccion/all';
                    axios.get(url)
                        .then(response => {
                            this.secciones = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getUnidad: function() {
                    var url = 'unidad/all';
                    axios.get(url)
                        .then(response => {
                            this.unidades = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getSucursal: function() {
                    var url = 'sucursal/all';
                    axios.get(url)
                        .then(response => {
                            this.sucursales = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                exportar: function(tipo){
                    if(this.articulos.length < 1){
                        return false;
                    }
                    if(tipo=="stock"){
                       let params= {
                                page: 0,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: ''
                            }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulos?'+u);
                    }
                    if(tipo=="precios"){
                        let params= {
                                page: 0,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: ''
                            }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulosprecios?'+u);
                    }
                }
            },
            computed: {
                totalStock() {
                    this.cantidadStock = 0;
                    for (i = 0; i < this.stocks.length; i++) {
                        this.cantidadStock += parseInt(this.stocks[i].cantidad);
                    }
                    return this.cantidadStock;
                }
            },
            mounted() {
                this.buscar();
                this.getSucursal();
            }
        })
        /* $('#addArticulo').on('hidden.bs.modal',function(e){
        	app.delArticulo();
        }); */
        /*  $('#editArticulo').on('hidden.bs.modal', function(e) {
             app.cleanAll();
         }); */
        activarMenu('m_articulo', '');
    </script>

@endsection
