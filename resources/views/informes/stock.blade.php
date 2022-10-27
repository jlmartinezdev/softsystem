@extends('layouts.app')
@section('title', 'Informe stock')
@section('main')
    <div class="container" id="app">
        <div class="font-weight-bold" style="font-size: 14pt;">Informe Stock</div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8 col-md-6">
                        <div class="input-group">
                            <input type="text" v-model="txtbuscar" @keyup.enter="buscar(false)" class="form-control"
                                placeholder="Buscar...." />
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="buscar(false)">
                                    <template v-if="requestSend">
                                        <span class="spinner-border spinner-border-sm" role="status"></span><span
                                            class="sr-only">Buscando...</span> Cargando...
                                    </template>
                                    <template v-else>
                                        <span class="fa fa-search"></span> Buscar
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <registro_mostrado :pagina_actual="paginacion.pagina_actual" :desde="paginacion.desde"
                            :hasta="paginacion.hasta" :total="paginacion.total">
                        </registro_mostrado>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Seccion</label>
                        <select class="form-control form-control-sm" @click="buscar(false)" v-model="filtro.seccion">
                            <option value="0">Todos</option>
                            @foreach ($secciones as $seccion)
                                <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Ordenar Por</label>
                        <select class="form-control form-control-sm" v-model="filtro.columna">
                            <option value="0">Descripcion</option>
                            <option value="1">Codigo</option>
                            <option value="2">Precio</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex" style="margin-top:28pt;">

                            <div class="custom-control custom-radio">
                                <input type="radio" id="asc" name="radio" value="ASC" class="custom-control-input"
                                    v-model="filtro.orden">
                                <label class="custom-control-label" for="asc"><span
                                        class="fa fa-sort-alpha-down"></span></label>
                            </div>
                            <div class="custom-control custom-radio ml-2">
                                <input type="radio" id="desc" name="radio" value="DESC" class="custom-control-input"
                                    v-model="filtro.orden">
                                <label class="custom-control-label" for="desc"><span
                                        class="fa fa-sort-alpha-up"></span></label>
                            </div>
                            <div class="pl-3" style="margin-top: -7pt;">
                                <!-- Example single danger button -->
                              
                                <button type="button" class="btn btn-success"  @click="exportar('stock')">
                                    <span class="fa fa-file-excel"></span> Exportar
                                </button>
                                    
                                    
                                

                            </div>
                            <!--div class="pl-3">
                                <input type="checkbox" id="stockcero"><label for="stockcero"> Exportar solo stock mayor a 0</label>
                            </div -->
                            <!--template>
                                <div class="form-inline ml-2 mt-2">
                                <h6 class="text-muted"><span class="fa fa-calendar-minus"></span> Precio de Compra x Stock <span class="badge badge-pill badge-info" >@{{ separador(totalPrecioCompra) }}</span></h6>
                                
                                </div>
                            </template -->
                        </div>
                        
                    </div>

                </div>
            </div>

        </div><!--  END CARD -->

        <template>
            <div class="table-responsive-sm">
                <table id="tabla" class="table table-striped table-hover table-sm">
                    <thead>
                        <tr class="text-uppercase">
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Seccion</th>
                            <th class="text-right">Precio Costo</th>
                            <th>Stock</th>
                            
                        </tr>
                    </thead>
                    <tbody style="font-family: Arial,Helvetica,sans-serif;">
                        <template v-for="a in articulos">
                            <tr :class="{'text-danger': a.cantidad==0}">
                                <td>@{{ a.producto_c_barra }}</td>
                                <td>@{{ a.producto_nombre }}</td>
                                <td>@{{ a.present_descripcion }}</td>
                                <td class="font-weight-bold text-right">@{{ separador(a.producto_costo_compra) }}</td>
                                <td class="text-center font-weight-bold">@{{ a.cantidad }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <v-pagination v-model="currentPage" :page-count="paginacion.ultima_pagina" :classes="bootstrapPaginationClasses"
                :labels="customLabels" @change="onChange">
            </v-pagination>

            <!--div class="d-flex flex-column">
                          <span>{ a.producto_nombre }}</span>
                          <span class="text-muted small">{ a.present_descripcion }}</span>
                        </div -->
        </template>


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
        
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                currentPage: 0,
                bootstrapPaginationClasses: {
                    ul: 'pagination',
                    li: 'page-item',
                    liActive: 'active',
                    liDisable: 'disabled',
                    button: 'page-link'
                },
                customLabels: {
                    first: 'Primer',
                    prev: 'Ant',
                    next: 'Sig',
                    last: 'Ultimo'
                },
                paginacion: {
                    'total': 0,
                    'pagina_actual': 1,
                    'por_pagina': 0,
                    'ultima_pagina': 0,
                    'desde': 0,
                    'hasta': 0
                },
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
                stock: {...defaultStock},
                stocks: [],
                error: '',
                cantidadStock: 0,
                frmt: {
                    i: -1,
                    t: false,
                    suc: 0,
                    cant: 0
                }
            },
            methods: {
               
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
         
                buscar: function(isPaginate) {
                    this.requestSend = true;
                    let pag = isPaginate ? this.currentPage : 1
                    axios.get('{{env("APP_URL")}}'+'articulo/buscar', {
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
                                this.articulos = response.data.articulos.data;
                                this.paginacion = response.data.paginacion;
                                //this.paginacion.pagina_actual=1;
                                this.currentPage = this.currentPage == 0 ? 1 : this.paginacion
                                    .pagina_actual;
                            }
                            //this.error=response.data;
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        });
                },
                
                separador: function(number) {
                    var n = parseFloat(number);
                    return new Intl.NumberFormat().format(n);
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
                    

                },
                
                getArticulo: function() {
                    axios.get('{{env("APP_URL")}}'+'articulo/buscar')
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
                    axios.get('{{env("APP_URL")}}'+'articulo/precios/' + id).then(response => {
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
                    var url = '{{env("APP_URL")}}'+'sucursal/all';
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
                        window.open('{{env("APP_URL")}}'+'excel/articulos?'+u);
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
                },
                totalPrecioCompra(){
                    let total=0;
                    for(i=0; i< this.articulos.length; i++){
                        total +=  parseInt(this.articulos[i].cantidad) * this.articulos[i].producto_costo_compra
                    }
                    return total;
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
        activarMenu('m_informe', 'm_istock');
    </script>

@endsection
