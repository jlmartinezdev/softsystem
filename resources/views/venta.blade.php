@extends('layouts.app')
@section('title', 'Gestionar Venta')
@section('style')
    <style type="text/css">
        @font-face {
            font-family: "Sofia";
            font-style: normal;
            font-weight: 400;
            font-display: auto;
            src: url({{ asset('webfonts/SofiaSans-Regular.ttf') }}) format("truetype");
        }

        #main {
            font-family: 'Sofia';
        }

        .form-group {
            margin-bottom: 7px;
        }

        .form-group label {
            margin-bottom: 0.2rem;
            font-weight: bold;
        }

        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            height: 350px;
            overflow-y: auto;
        }
        .dark-mode .bg-light {
            background-color: #454d55 !important;
            color: #fff !important;
        }
        .input-number-precio{
            width: 100px; 
            border: none; 
            background-color: transparent;
        }
    </style>
@endsection
@section('main')
    <div id="app">

        <div>
            <div class="row">
                <!-- PANEL IZQUIERDA -->
                <div class="col-md-8">
                   
                    <div class="content-header">
                        <div class="row">
                            <div class="col-6">
                                
                                <template v-for="(cr, idx) in carritos">
                                    <div class="btn-group mr-1 mb-1" role="group">
                                        <button type="button" class="btn btn-sm" :class="indiceCarroActivo === idx ? 'btn-primary' : 'btn-outline-secondary'" @click="cambiarCarro(idx)" :title="'Carro ' + (idx + 1) + (cr.carro.length ? ' (' + cr.carro.length + ' ítem(s))' : '')">
                                            Venta @{{ idx + 1 }}
                                            <span v-if="cr.carro.length" class="badge badge-light ml-1">@{{ cr.carro.length }}</span>
                                        </button>
                                        <button v-if="carritos.length > 1" type="button" class="btn btn-sm" :class="indiceCarroActivo === idx ? 'btn-primary' : 'btn-outline-secondary'" @click.stop="eliminarCarro(idx)" title="Eliminar carro">
                                            <span class="fa fa-times"></span>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" class="btn btn-sm btn-outline-success mb-1" @click="nuevoCarro()" title="Nuevo carro de venta">
                                    <span class="fa fa-plus"></span>
                                </button>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary float-sm-right">
                                    <span class="badge badge-default"><span class="fa fa-cash-register"></span> CAJA
                                    </span><span class="badge badge-pill "
                                        :class="[caja == 'ABIERTA' ? 'badge-success pr-2 pl-2' : 'badge-danger']">
                                        @{{ caja }} - @{{ nrooperacion }}
                                    </span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        
                            <div class="row">
                                
                                <div class="col-sm-12 col-md-12">
                                    <nav class="navbar navbar-expand navbar-light bg-white">
                                        <ul class="navbar-nav w-100">
                                            <li class="nav-item w-100">
                                                <Searcharticulo url="{{ env('APP_APIDB') }}" :idsucursal="ventaCabecera.idSucursal" @peso="setPeso"
                                    @articulo="addCarrito" validar-lote="false" is-ready-balance="true"  ref="Searcharticulo" route-articulo="{{ route('articulo.cm')}}">
                                </Searcharticulo>
                                            </li>
                                        </ul>
                                        <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link"><i class="fa-regular fa-filter-list"></i></a>
                                            
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" title="Ítem libre (sin catálogo)"
                                                data-toggle="modal" data-target="#modalItemLibre"
                                                @click.prevent="abrirItemLibre">
                                                <i class="fa fa-bolt text-warning"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link"><i class="fa-regular fa-list-ul"></i></a>
                                        </li>
                                    </ul>
                                    </nav>
                                    
                                </div>
                            </div>
                        
                    </div>
                    <!-- TABLA ......................... -->
                    <div class="card mt-2">
                        
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <tr>

                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Importe</th>
                                    <th colspan="2">Opciones</th>

                                </tr>
                                <template v-if="carro.length>0">
                                    <template v-for="(item,index) in carroOrdenado">
                                        <tr :key="item.linea_uid || (item.codigo + '-' + item.idstock + '-' + index)">

                                            <td>
                                                <span v-if="item.es_libre" class="badge badge-warning">LIBRE</span>
                                                <span v-else>@{{ item.codigo }}</span>
                                            </td>
                                            <td>@{{ item.descripcion }}</td>
                                            <td> <input type="number"
                                                    style="width: 57px; border: none; background-color: transparent;"
                                                    min="1" :max="item.es_libre ? 999999 : item.stock" v-model="item.cantidad"
                                                    @change="saveDatos"> </td>
                                            <td><in-number v-model="item.precio" :clases="inputNumberClassesPrecio" placeholder="Precio"></in-number></td>
                                            <td>@{{ new Intl.NumberFormat("de-DE").format(item.precio * item.cantidad) }}</td>


                                            <td><button class="btn btn-link btn-sm" title="Quitar de la lista" @click="delArticulo(item)">
                                                    <span class="fa fa-trash-alt text-secondary"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-bars"></span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button class="dropdown-item"
                                                            @click="setCantidad(item)">
                                                            <span class="fa fa-cubes text-primary"
                                                                style="width: 13pt"></span>
                                                            Cantidad
                                                        </button>
                                                        <div class="dropdown-divider"></div>
                                                        <button class="dropdown-item" @click="showModalPrecio(index,item)">
                                                            <span class="fa fa-dollar-sign  text-info"
                                                                style="width: 13pt"></span> Precio
                                                        </button>
                                                      
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6">S I N &nbsp; A R T I C U L O . . .</td>
                                    </tr>
                                </template>

                            </table>
                        </div>


                    </div>

                </div>
                <!-- PANEL DERECHA  -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            
                            <fieldset class="form-group">
                                <label>Documento</label>
                                <select class="form-control form-control-sm" v-model="ventaCabecera.documento">
                                    <option value="Ticket">Ticket</option>
                                    <option value="Comprobante">Comprobante de Venta</option>
                                    <option value="Factura">Factura</option>
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control form-control-sm" v-model="ventaCabecera.fecha"
                                    placeholder="Fecha">
                            </fieldset>

                            <fieldset class="form-group">
                                <label>Cliente</label>
                                <div class="input-group">
                                    <input type="text" disabled class="form-control form-control-sm"
                                        placeholder="Nombre Cliente" v-model="ventaCabecera.clienteNombre">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary btn-sm" @click="showBuscarCliente()">
                                            <span class="fa fa-user"></span> Buscar
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group">
                                <label>Descuento</label>
                                <input type="number" @keyup="saveDatos" class="form-control form-control-sm"
                                    v-model="ventaCabecera.descuento" placeholder="Descuento...">
                            </fieldset>
                            <div class="description-block border-right">
                                <div class="descripcion-percentage text-muted">
                                    <i class="fa fa-money-bill"></i> TOTAL
                                </div>
                                <div class="description-header">
                                    <h3><template>Gs. @{{ totalVenta }}</template></h3>
                                </div>
                            </div>

                         

                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" @click="showFinalizar">
                                <span class="fa fa-check"></span>
                                <strong>FINALIZAR</strong>
                            </button>
                            <button class="btn btn-secondary float-right" @click="cancelar"> <span class="fa fa-times"></span>
                                CANCELAR</button>

                        </div>
                    </div>
                </div>
            </div> <!-- end row -->
        </div>
        <!--end container -->

        @include('venta.finalizar')
        @include('cliente.buscar')
        @include('venta.selprecio')

        <!-- Modal Vuelto -->
        <div class="modal fade" id="modalVuelto" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Calcular Vuelto</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <div class="d-flex justify-content-center">
                                <i class="fas fa-money-bill text-success" style="font-size: 2rem;"></i>
                            </div>
                            <label>Total a Pagar</label>
                            <h1 class="display-5">Gs. @{{ format(ventaCabecera.total) }}</h1>
                        </div>
                        <div class="form-group">

                            <label> <i class="fas fa-arrow-right text-success"></i> Monto Recibido</label>
                            <in-number  v-model="efectivoRecibido" :clases="inputNumberClasses" placeholder="Ingrese el monto recibido" @change="calcularVuelto"></in-number>
                            
                        </div>
                        <div class="form-group text-center" v-if="vuelto > 0">
                            <div class="d-flex justify-content-center">
                                <i class="fas fa-arrow-left text-danger" style="font-size: 2rem;"></i>
                            </div>
                            <label>Vuelto</label>
                            <h1 class="display-5 text-danger">Gs. @{{ format(vuelto) }}</h1>
                        </div>
                        <div class="alert alert-danger" v-if="vuelto < 0">
                            <i class="fas fa-exclamation-triangle"></i> Monto insuficiente
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <span class="fa fa-times"></span> Cerrar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-if="vuelto >= 0">
                            <span class="fa fa-check"></span> Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Ítem libre -->
        <div class="modal fade" id="modalItemLibre" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title mb-0">
                            <span class="fa fa-bolt"></span> Ítem libre (sin catálogo)
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            Para vender algo no registrado: cargá descripción y precio. No descuenta stock.
                        </p>
                        <div class="form-group">
                            <label for="fastItemDescripcion">Descripción</label>
                            <input type="text" class="form-control" v-model.trim="fastItem.descripcion"
                                id="fastItemDescripcion" placeholder="Ej: Servicio técnico, repuesto varios..."
                                maxlength="255" @keyup.enter="focusFastPrecio">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="fastItemCantidad">Cantidad</label>
                                    <input type="number" class="form-control" v-model.number="fastItem.cantidad"
                                        id="fastItemCantidad" min="1" step="1">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="fastItemPrecio">Precio unitario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Gs.</span>
                                        </div>
                                        <input type="number" class="form-control" v-model.number="fastItem.precio"
                                            id="fastItemPrecio" placeholder="0" min="1" step="1"
                                            @keyup.enter="addFastItem">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="fa fa-times"></span> Cerrar
                        </button>
                        <button type="button" class="btn btn-warning" @click="addFastItem">
                            <span class="fa fa-plus"></span> Agregar al carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end app -->
@endsection
@section('script')
    <script src="{{ asset(mix('js/venta.js')) }}"></script>
    <script src="{{ asset('js/separator.js') }}"></script>
    <script type="text/javascript">

    $('#modalItemLibre').on('shown.bs.modal', function () {
        var el = document.getElementById('fastItemDescripcion');
        if (el) el.focus();
    });
        var app = new Vue({
            el: '#app',
            data: {
                articuloLibreId: {{ (int) ($articuloLibreId ?? 0) }},
                inputNumberClasses: {
                    input: "form-control form-control-lg text-success"
                },
                inputNumberClassesPrecio: {
                    input: "input-number-precio"
                },
                requestSend: false,
                requestFinalizar: false,
                currentPage: 1,
                opcionesEfectivo: [],
                carritos: [],
                indiceCarroActivo: 0,
                nextCarroId: 1,
                caja: '...',
                nrooperacion: '...',
                tmpIndexPrecio: {
                    iPrecio: 'CO1',
                    iArticulo: 0,
                    monto_cuota: 0,
                    is_multiple: false
                },
                txtbuscar: '',
                txtcliente: '',
                clienteBuscando: false,
                clienteBusquedaTimer: null,
                clienteBusquedaSeq: 0,
                clienteIndexActivo: 0,
                filtro: {
                    seccion: 0,
                    columna: 0,
                    orden: 'ASC'
                },
                error: '',
                articulos: [],
                preciosContado: {
                    p1: 0,
                    m1: 10,
                    p2: 0,
                    m2: 20,
                    p3: 0,
                    m3: 30,
                    p4: 0,
                    m4: 40,
                    p5: 0,
                    m5: 0,
                    articulo: ''
                },
                peso: "",
                cantidad: 0,
                preciosCredito: [],
                articulo: null,
                clientes: [],
                requestLote: false,
                enfocar: false,
                defaultVentaCabecera: {
                    fecha: '2020-01-01',
                    clienteId: '1',
                    clienteNombre: 'Cliente Ocasional',
                    documento: 'Ticket',
                    idSucursal: 1,
                    formacobro: 1,
                    condicionventa: 1,
                    total: 0,
                    descuento: 0,
                    nro_operacion: 0,
                    generarcuota: true,
                    vender_sin_stock: 0,
                    descontar_stock: 1
                },
                fastItem: {
                    precio: 0,
                    descripcion: '',
                    cantidad: 1
                }
            },
            methods: {
                search: function(input) {
                    console.log(input);
                },

                setCuotas: function(cuotas) {
                    this.cuotas = cuotas;
                },
                setPeso: function(peso){
                    this.peso = peso;
                    const parteEntera = parseInt(peso.slice(0, 2), 10); 
                    const parteDecimal = parseInt(peso.slice(2, 4), 10);
                    const resultado = parteEntera + parteDecimal / 100;
                    this.cantidad = resultado;
                   
                },
                addCarrito: function(a) {

                    var Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    //Buscar articulo si no esta en la lista
                    if (a.cantidad == 0 && this.ventaCabecera.vender_sin_stock == 0) {
                        console.log("No se puede agregar")
                        Toast.fire({
                            title: 'No se puede agregar articulo con stock 0!',
                            icon: 'error'
                        });
                        return;
                    }
                    let i = this.carro.findIndex(x => x.codigo == a.ARTICULOS_cod && x.idstock == a.id_stock);
                    if (i == -1) {
                        let art = {
                            codigo: a.ARTICULOS_cod,
                            idstock: a.id_stock,
                            descripcion: a.producto_nombre,
                            cantidad: this.peso.length > 0 ? this.cantidad : 1,
                            stock: a.cantidad,
                            precio: a.pre_venta1,
                            p1: parseInt(a.pre_venta1),
                            p2: a.pre_venta2,
                            p3: a.pre_venta3,
                            p4: a.pre_venta4,
                            p5: a.pre_venta5,
                            m1: a.pre_margen1,
                            m2: a.pre_margen2,
                            m3: a.pre_margen3,
                            m4: a.pre_margen4,
                            m5: a.pre_margen5,
                            costo: a.producto_costo_compra,
                            iPrecio: 'CO1',
                            
                        }
                        

                        this.carro.push(art);
                        this.peso = '';
                        this.cantidad = 0;
                    } else {
                        //vender sin stock
                        if(this.ventaCabecera.vender_sin_stock==1){
                            this.carro[i].cantidad = this.peso.length > 0 ? +(this.carro[i].cantidad+ this.cantidad).toFixed(2) : parseInt(this.carro[i].cantidad) + 1;
                        }else{
                            if ((this.carro[i].cantidad + 1) <= a.cantidad) {
                                this.carro[i].cantidad = this.peso.length > 0 ? +(this.carro[i].cantidad+ this.cantidad).toFixed(2)  : parseInt(this.carro[i].cantidad) + 1;
                            } else {
                                Toast.fire({
                                    title: `Cantidad supera stock disponible: ${a.cantidad} ...`,
                                    icon: 'error'
                                });
                            }
                        }
                        //Actualizar cantidad
                    }
                    this.saveDatos();
                },
                getFecha: function() {

                    var f = new Date();
                    var dia = f.getDate();
                    var mes = (f.getMonth() + 1);
                    this.ventaCabecera.fecha = f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" +
                        dia.toString().padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                setCantidad: async function(articulo) {
                    const swalBootstrap = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-primary mr-2',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    const {
                        value: cant
                    } = await swalBootstrap.fire({
                        title: 'Escriba cantidad a Vender...',
                        input: 'number',
                        inputValue: articulo.cantidad,
                        inputAttributes: {
                            min: 0,
                            max: articulo.stock
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar'
                    })
                    if (cant) {
                        let realIndex = this.findCarroIndex(articulo);
                        if (realIndex !== -1) {
                            this.carro[realIndex].cantidad = cant;
                        }
                        this.saveDatos();
                    }
                    this.$refs.Searcharticulo.focusSearchInput();
                },
                showModalPrecio: function(index, articulo) {
                    
                    this.articulo = articulo;
                    let realIndex = this.findCarroIndex(articulo);
                    this.tmpIndexPrecio.iArticulo = realIndex;
                    for (i = 1; i < 6; i++) {
                        this.preciosContado['m' + i] = parseInt(articulo['m' + i]);
                        this.preciosContado['p' + i] = parseInt(articulo['p' + i]);
                    }
                    this.preciosContado.articulo = articulo.descripcion;
                    $('#selPrecio').modal('show');
                    this.preciosCredito = [];
                    axios.get('articulo/precios/' + articulo.codigo).then(response => {
                        if (response.data.length > 0)
                            this.preciosCredito = [];
                        for (i = 0; i < response.data.length; i++) {
                            let precios = {
                                p: response.data[i].p,
                                c: response.data[i].c,
                                m: response.data[i].m
                            }
                            this.preciosCredito.push(precios);
                        }

                    }).catch(error => {
                        this.error = error.message;
                    })
                },
                setPrecio: function() {
                    $('#selPrecio').modal('hide');
                    let iPrecio = this.tmpIndexPrecio.iPrecio;
                    let x = iPrecio.substr(2);
                    let index = this.tmpIndexPrecio.iArticulo;
                    if (iPrecio.includes('CO')) {
                        this.ventaCabecera.condicionventa = 1;
                        this.ventaCabecera.generarcuota = true;
                        let newPrecio = this.articulo['p' + x];
                        if (newPrecio > 0)
                            this.carro[index].precio = newPrecio;
                    } else {
                        this.ventaCabecera.condicionventa = 2
                        this.ventaCabecera.generarcuota = false;
                        let newPrecio = this.preciosCredito[x].p;
                        if (newPrecio > 0) {
                            
                            this.carro[index].precio = newPrecio;
                            this.tmpIndexPrecio.monto_cuota = this.preciosCredito[x].c;
                            this.tmpIndexPrecio.is_multiple = this.carro.length > 1;
                        }

                    }
                    this.$refs.Searcharticulo.focusSearchInput();
                    this.saveDatos();
                },
                findCarroIndex: function(a) {
                    if (a && a.linea_uid) {
                        return this.carro.findIndex(x => x.linea_uid === a.linea_uid);
                    }
                    if (a && a.es_libre) {
                        return this.carro.findIndex(x => x.es_libre
                            && x.descripcion === a.descripcion
                            && x.precio == a.precio
                            && x.cantidad == a.cantidad);
                    }
                    return this.carro.findIndex(x => x.codigo == a.codigo && x.idstock == a.idstock);
                },
                delArticulo: function(a) {
                    this.$refs.Searcharticulo.focusSearchInput();
                    let validar = this.findCarroIndex(a);
                    if (validar > -1) {
                        this.carro.splice(validar, 1);
                    }
                    this.saveDatos();
                    
                },
                format: function(numero) {
                    return new Intl.NumberFormat("de-DE").format(numero);
                },
                getApertura: function() {
                    let idSucursal = $('#sucursal').attr('data-id');
                    this.ventaCabecera.idSucursal = idSucursal;
                    if (idSucursal != null) {
                        axios.get('aperturacierre/' + idSucursal)
                            .then(response => {
                                if (response.data) {
                                    this.nrooperacion = response.data.nro_operacion;
                                    this.ventaCabecera.nro_operacion = response.data.nro_operacion;
                                    this.caja = 'ABIERTA';
                                } else {
                                    this.caja = 'CERRADA';
                                }
                            })
                            .catch(error => {
                                console.log(error);
                            })
                    }
                },
                showFinalizar: function() {
                    if (this.caja == 'ABIERTA') {
                        if (this.ventaCabecera.total > 0) {
                            this.sugerirEfectivoRecibido();
                            var self = this;
                            $('#finalizarventa').one('shown.bs.modal', function() {
                                self.$nextTick(function() {
                                    var el = document.getElementById('efectivo-recibido-modal');
                                    if (el) {
                                        el.focus();
                                        if (typeof el.select === 'function') {
                                            el.select();
                                        }
                                    }
                                });
                            });
                            $('#finalizarventa').modal('show');
                        }
                    } else {
                        Swal.fire('Atención...', 'Caja no esta abierta!', 'warning');
                    }

                },
                finalizar: function(print) {
                    if (this.requestFinalizar) {
                        return false;
                    }
                    var cabecera = this.ensureVentaCabecera();
                    if (!cabecera.idSucursal) {
                        Swal.fire('Sucursal requerida', 'Seleccioná una sucursal antes de finalizar la venta.', 'warning');
                        return false;
                    }
                    if (cabecera.condicionventa == 2 && this.cuotas.length < 1) {
                        Swal.fire('Error', 'Por favor genere las cuotas', 'error');
                        return false;
                    }
                    this.calcularVuelto();
                    this.requestFinalizar = true;
                    axios.post('venta', {
                            ventaCabecera: cabecera,
                            detalle: this.carro,
                            cuotas: this.cuotas,
                            venta_recibido: Number(this.efectivoRecibido) || 0,
                            venta_vuelto: Number(this.vuelto) || 0
                        })
                        .then(response => {
                            this.requestFinalizar = false;
                            var docParaPrint = cabecera.documento;
                            this.carritos.splice(this.indiceCarroActivo, 1);
                            if (this.carritos.length === 0) {
                                this.carritos.push(this.getDefaultCarrito());
                            }
                            if (this.indiceCarroActivo >= this.carritos.length) {
                                this.indiceCarroActivo = this.carritos.length - 1;
                            }
                            this.saveDatos();
                            if (print) {
                                if (docParaPrint == 'Ticket') {
                                    window.location.assign('{{ env('APP_URL') }}' + 'ticket/venta/' +
                                        response.data);
                                } else {
                                    window.location.assign('{{ env('APP_URL') }}' + 'pdf/boletaventa/' +
                                        response.data);
                                }
                            } else {
                                $('#finalizarventa').modal('hide');
                            }
                        })
                        .catch(error => {
                            this.requestFinalizar = false;
                            var msg = 'No se pudo guardar la venta.';
                            if (error.response && error.response.data && error.response.data.message) {
                                msg = error.response.data.message;
                            } else if (error.message) {
                                msg = error.message;
                            }
                            Swal.fire('Error', msg, 'error');
                        })
                },
                numeroaletra: function(n) {
                    return NumeroALetras.NumeroALetras(parseInt(n));
                },
                getDefaultCarrito: function() {
                    var id = this.nextCarroId++;
                    var def = this.defaultVentaCabecera;
                    var vc = (def && typeof def === 'object') ? JSON.parse(JSON.stringify(def)) : {
                        fecha: '2020-01-01', clienteId: '1', clienteNombre: 'Cliente Ocasional', documento: 'Ticket',
                        idSucursal: this.ventaCabecera.idSucursal, formacobro: 1, condicionventa: 1, total: 0, descuento: 0, nro_operacion: this.nrooperacion,
                        generarcuota: true, vender_sin_stock: 0, descontar_stock: 1
                    };
                    return { id: id, carro: [], ventaCabecera: vc, efectivoRecibido: 0, vuelto: 0, cuotas: [] };
                },
                saveDatos: function() {
                    localStorage.setItem('carritos_venta', JSON.stringify(this.carritos));
                    localStorage.setItem('indice_carro_activo', String(this.indiceCarroActivo));
                },
                recuperarDatos: function() {
                    var saved = localStorage.getItem('carritos_venta');
                    var idx = localStorage.getItem('indice_carro_activo');
                    if (saved != null && saved !== '' && saved !== 'undefined') {
                        try {
                            var arr = JSON.parse(saved);
                            if (Array.isArray(arr) && arr.length > 0) {
                arr.forEach(function(c) {
                                    if (!c.cuotas) c.cuotas = [];
                                    if (typeof c.efectivoRecibido === 'undefined') c.efectivoRecibido = 0;
                                    if (typeof c.vuelto === 'undefined') c.vuelto = 0;
                                    if (!c.ventaCabecera || typeof c.ventaCabecera !== 'object') {
                                        c.ventaCabecera = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                    } else {
                                        var base = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                        c.ventaCabecera = Object.assign(base, c.ventaCabecera);
                                    }
                                    if (Array.isArray(c.carro)) {
                                        c.carro.forEach(function(item, i) {
                                            if (item && item.es_libre && !item.linea_uid) {
                                                item.linea_uid = 'libre-rec-' + (c.id || 0) + '-' + i + '-' + Date.now();
                                            }
                                        });
                                    }
                                }.bind(this));
                                this.carritos = arr;
                                this.indiceCarroActivo = idx != null ? Math.min(parseInt(idx, 10) || 0, arr.length - 1) : 0;
                                if (this.nextCarroId <= Math.max.apply(null, this.carritos.map(function(c) { return c.id || 0; }))) {
                                    this.nextCarroId = Math.max.apply(null, this.carritos.map(function(c) { return c.id || 0; })) + 1;
                                }
                                return;
                            }
                        } catch (e) {}
                    }
                    var carroAntiguo = localStorage.getItem('carro_venta');
                    var cabAntigua = localStorage.getItem('ventaCabecera');
                    var carroValido = carroAntiguo != null && carroAntiguo !== '' && carroAntiguo !== 'undefined';
                    var cabValida = cabAntigua != null && cabAntigua !== '' && cabAntigua !== 'undefined';
                    if (carroValido || cabValida) {
                        var c = this.getDefaultCarrito();
                        if (carroValido) {
                            try { c.carro = JSON.parse(carroAntiguo); } catch (e) {}
                        }
                        if (cabValida) {
                            try {
                                var cab = JSON.parse(cabAntigua);
                                if (cab && typeof cab.total !== 'undefined') {
                                    var base = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                    c.ventaCabecera = Object.assign(base, cab);
                                }
                            } catch (e) {}
                        }
                        c.ventaCabecera.condicionventa = 1;
                        c.ventaCabecera.generarcuota = true;
                        this.carritos = [c];
                        this.saveDatos();
                    } else {
                        this.carritos = [this.getDefaultCarrito()];
                    }
                    this.indiceCarroActivo = 0;
                },
                showBuscarCliente: function() {
                    this.txtcliente = '';
                    this.clienteIndexActivo = 0;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    $('#busquedaCliente').modal('show');
                    this.buscarCliente();
                    this.$nextTick(function () {
                        var el = document.getElementById('txtclienteVenta');
                        if (el) {
                            el.focus();
                        }
                    });
                },
                onBuscarClienteInput: function () {
                    var self = this;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.clienteIndexActivo = 0;
                    var q = (this.txtcliente || '').trim();
                    var delay = q.length >= 2 ? 300 : 150;
                    this.clienteBusquedaTimer = setTimeout(function () {
                        self.buscarCliente();
                    }, delay);
                },
                limpiarBusquedaCliente: function () {
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.txtcliente = '';
                    this.clienteIndexActivo = 0;
                    this.buscarCliente();
                    this.$nextTick(function () {
                        var el = document.getElementById('txtclienteVenta');
                        if (el) el.focus();
                    });
                },
                moverSeleccionCliente: function (dir) {
                    if (!this.clientes.length) return;
                    var next = this.clienteIndexActivo + dir;
                    if (next < 0) next = this.clientes.length - 1;
                    if (next >= this.clientes.length) next = 0;
                    this.clienteIndexActivo = next;
                },
                seleccionarPrimerCliente: function () {
                    if (!this.clientes.length) {
                        this.buscarCliente();
                        return;
                    }
                    var idx = this.clienteIndexActivo >= 0 ? this.clienteIndexActivo : 0;
                    var c = this.clientes[idx];
                    if (c) {
                        this.selectCliente(c.clientes_cod, c.cliente_nombre);
                    }
                },
                buscarCliente: function() {
                    var q = (this.txtcliente || '').trim();
                    var params = q.length >= 2
                        ? { q: q, limit: 50 }
                        : { limit: 10 };

                    var seq = ++this.clienteBusquedaSeq;
                    this.clienteBuscando = true;

                    axios.get('{{ url('cliente/buscar') }}', { params: params })
                        .then(response => {
                            if (seq !== this.clienteBusquedaSeq) return;
                            this.clientes = response.data || [];
                            this.clienteIndexActivo = 0;
                            this.clienteBuscando = false;
                        })
                        .catch(error => {
                            if (seq !== this.clienteBusquedaSeq) return;
                            this.clienteBuscando = false;
                            console.log(error.message);
                        });
                },

                selectCliente: function(id, cliente) {
                    this.ventaCabecera.clienteId = id;
                    this.ventaCabecera.clienteNombre = cliente;
                    this.txtcliente = '';
                    this.clientes = [];
                    this.clienteIndexActivo = 0;
                    $('#busquedaCliente').modal('hide');
                    this.saveDatos && this.saveDatos();
                },
                getSucursal: function() {
                    var obj = document.getElementById("sucursal");
                    var id = (obj && obj.getAttribute('data-id') != null) ? obj.getAttribute('data-id') : null;
                    if (!this.carritos.length) {
                        this.carritos.push(this.getDefaultCarrito());
                    }
                    if (!this.carritos[this.indiceCarroActivo].ventaCabecera) {
                        this.$set(this.carritos[this.indiceCarroActivo], 'ventaCabecera', JSON.parse(JSON.stringify(this.defaultVentaCabecera)));
                    }
                    if (id != null) {
                        this.$set(this.carritos[this.indiceCarroActivo].ventaCabecera, 'idSucursal', id);
                    } else if (!this.carritos[this.indiceCarroActivo].ventaCabecera.idSucursal) {
                        this.$set(this.carritos[this.indiceCarroActivo].ventaCabecera, 'idSucursal', this.defaultVentaCabecera.idSucursal);
                    }
                },
                ensureVentaCabecera: function() {
                    if (!this.carritos.length) {
                        this.carritos.push(this.getDefaultCarrito());
                    }
                    var act = this.carritos[this.indiceCarroActivo];
                    var defaults = this.defaultVentaCabecera;
                    if (!defaults || typeof defaults !== 'object') {
                        defaults = {
                            fecha: '2020-01-01',
                            clienteId: '1',
                            clienteNombre: 'Cliente Ocasional',
                            documento: 'Ticket',
                            idSucursal: 1,
                            formacobro: 1,
                            condicionventa: 1,
                            total: 0,
                            descuento: 0,
                            nro_operacion: 0,
                            generarcuota: true,
                            vender_sin_stock: 0,
                            descontar_stock: 1
                        };
                    }
                    if (!act.ventaCabecera || typeof act.ventaCabecera !== 'object') {
                        this.$set(act, 'ventaCabecera', JSON.parse(JSON.stringify(defaults)));
                    }
                    var vc = act.ventaCabecera;
                    var sid = $('#sucursal').attr('data-id');
                    Object.keys(defaults).forEach(function (k) {
                        if (typeof vc[k] === 'undefined' || vc[k] === null || vc[k] === '') {
                            if (k === 'idSucursal' && sid != null && sid !== '') {
                                vc[k] = sid;
                            } else if (k === 'nro_operacion' && this.nrooperacion && this.nrooperacion !== '...') {
                                vc[k] = this.nrooperacion;
                            } else {
                                vc[k] = defaults[k];
                            }
                        }
                    }.bind(this));
                    if (sid != null && sid !== '') {
                        vc.idSucursal = sid;
                    }
                    if (this.nrooperacion && this.nrooperacion !== '...') {
                        vc.nro_operacion = this.nrooperacion;
                    }
                    return vc;
                },
                validarLote: async function(articulo, lotes) {
                    var values = {};
                    for (var i = 0; i < lotes.length; i++) {
                        values[i] = lotes[i].lote_nro;
                    }
                    const {
                        value: lote
                    } = await Swal.fire({
                        title: 'Seleccione Lote',
                        input: 'select',
                        inputOptions: values,
                        inputPlaceholder: 'Seleccione lote',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar'
                    })
                    if (lote) {
                        this.addCarrito(articulo, lotes[lote].id_stock);
                    }
                },
                cancelar: function() {
                    var act = this.carritos[this.indiceCarroActivo];
                    if (act) {
                        act.carro = [];
                        act.ventaCabecera.total = 0;
                        act.ventaCabecera.descuento = 0;
                        act.ventaCabecera.condicionventa = 1;
                        act.ventaCabecera.generarcuota = true;
                        act.efectivoRecibido = 0;
                        act.vuelto = 0;
                        act.cuotas = [];
                    }
                    this.getFecha();
                    this.saveDatos();
                },
                irAVentaPrincipal: function() {
                    this.cambiarCarro(0);
                    var el = document.getElementById('main') || document.getElementById('app');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                },
                nuevoCarro: function() {
                    this.carritos.push(this.getDefaultCarrito());
                    this.indiceCarroActivo = this.carritos.length - 1;
                    this.getFecha();
                    this.getConfigVenta();
                    this.saveDatos();
                },
                cambiarCarro: function(index) {
                    if (index >= 0 && index < this.carritos.length) {
                        this.indiceCarroActivo = index;
                        this.saveDatos();
                    }
                },
                eliminarCarro: function(index) {
                    if (this.carritos.length <= 1) return;
                    this.carritos.splice(index, 1);
                    if (this.indiceCarroActivo >= this.carritos.length) {
                        this.indiceCarroActivo = this.carritos.length - 1;
                    } else if (index < this.indiceCarroActivo) {
                        this.indiceCarroActivo--;
                    }
                    this.saveDatos();
                },
                getConfigVenta() {
                    this.ensureVentaCabecera();
                    var config = localStorage.getItem('config_venta');
                    if (config != null && config !== '' && config !== 'undefined') {
                        try {
                            config = JSON.parse(config);
                            if (config && typeof config.tipo_comprobante !== 'undefined') {
                                var vc = this.carritos[this.indiceCarroActivo].ventaCabecera;
                                this.$set(vc, 'documento', config.tipo_comprobante);
                                this.$set(vc, 'vender_sin_stock', config.vender_sin_stock);
                                this.$set(vc, 'descontar_stock', config.descontar_stock);
                            }
                        } catch (e) {}
                    }
                },
                abrirItemLibre: function () {
                    this.fastItem = { precio: '', descripcion: '', cantidad: 1 };
                    $('#modalItemLibre').modal('show');
                },
                focusFastPrecio: function () {
                    var el = document.getElementById('fastItemPrecio');
                    if (el) el.focus();
                },
                addFastItem: function () {
                    var desc = (this.fastItem.descripcion || '').trim();
                    var precio = parseFloat(this.fastItem.precio);
                    var cantidad = parseFloat(this.fastItem.cantidad) || 1;

                    if (!desc) {
                        Swal.fire('Falta descripción', 'Ingresá la descripción del ítem.', 'warning');
                        return;
                    }
                    if (!(precio > 0)) {
                        Swal.fire('Falta precio', 'Ingresá un precio mayor a cero.', 'warning');
                        return;
                    }
                    if (!(cantidad > 0)) {
                        cantidad = 1;
                    }

                    this.ensureVentaCabecera();

                    var art = {
                        codigo: this.articuloLibreId || 'VARIOS',
                        idstock: 0,
                        descripcion: desc,
                        descripcion_libre: desc,
                        es_libre: true,
                        linea_uid: 'libre-' + Date.now() + '-' + Math.floor(Math.random() * 100000),
                        cantidad: cantidad,
                        stock: 999999,
                        precio: precio,
                        p1: parseInt(precio, 10),
                        p2: 0,
                        p3: 0,
                        p4: 0,
                        p5: 0,
                        m1: 0,
                        m2: 0,
                        m3: 0,
                        m4: 0,
                        m5: 0,
                        costo: 0,
                        iPrecio: 'CO1'
                    };

                    var lista = this.carro.slice();
                    lista.push(art);
                    this.carro = lista;
                    this.saveDatos();
                    this.fastItem = { precio: '', descripcion: '', cantidad: 1 };
                    $('#modalItemLibre').modal('hide');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Ítem libre agregado',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                calcularVuelto: function() {
                    if (this.efectivoRecibido > 0 && this.ventaCabecera.total > 0) {
                        this.vuelto = this.efectivoRecibido - this.ventaCabecera.total;
                    } else {
                        this.vuelto = 0;
                    }
                },
                sugerirEfectivoRecibido: function() {
                    var billetes = [5000, 10000, 20000, 50000, 100000];
                    var total = this.ventaCabecera.total;
                    var opciones = [];
                    var minCombinacion = this.minimoConBilletes(total, billetes);
                    if (minCombinacion.monto > 0) {
                        opciones.push({ monto: minCombinacion.monto, label: this.format(minCombinacion.monto) });
                    }
                    if (50000 > total && opciones.every(function(o) { return o.monto !== 50000; })) {
                        opciones.push({ monto: 50000, label: this.format(50000) });
                    }
                    if (100000 > total && opciones.every(function(o) { return o.monto !== 100000; })) {
                        opciones.push({ monto: 100000, label: this.format(100000) });
                    }
                    this.opcionesEfectivo = opciones.slice(0, 3);
                },
                minimoConBilletes: function(total, billetes) {
                    var maxBill = Math.max.apply(null, billetes);
                    var maxAmount = total + maxBill;
                    var canMake = { 0: true };
                    for (var a = 1; a <= maxAmount; a++) {
                        canMake[a] = false;
                        for (var i = 0; i < billetes.length; i++) {
                            if (a >= billetes[i] && canMake[a - billetes[i]]) {
                                canMake[a] = true;
                                break;
                            }
                        }
                    }
                    var monto = 0;
                    for (var j = total + 1; j <= maxAmount; j++) {
                        if (canMake[j]) {
                            monto = j;
                            break;
                        }
                    }
                    var label = monto ? this.formarLabelBilletes(monto, billetes) : '';
                    return { monto: monto, label: label };
                },
                formarLabelBilletes: function(monto, billetes) {
                    var ordenados = billetes.slice().sort(function(a, b) { return b - a; });
                    var usados = [];
                    var restante = monto;
                    for (var i = 0; i < ordenados.length && restante > 0; i++) {
                        while (restante >= ordenados[i]) {
                            usados.push(ordenados[i]);
                            restante -= ordenados[i];
                        }
                    }
                    return usados.map(function(u) { return u.toLocaleString('es-PY'); }).join(' + ');
                },
                aplicarOpcionEfectivo: function(monto) {
                    this.efectivoRecibido = monto;
                    this.calcularVuelto();
                }
            },
            computed: {
                carro: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].carro : [];
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'carro', v);
                        }
                    }
                },
                ventaCabecera: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].ventaCabecera : {};
                    }
                },
                efectivoRecibido: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].efectivoRecibido : 0;
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'efectivoRecibido', v);
                        }
                    }
                },
                vuelto: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].vuelto : 0;
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'vuelto', v);
                        }
                    }
                },
                cuotas: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].cuotas : [];
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'cuotas', v);
                        }
                    }
                },
                carroOrdenado: function() {
                    var c = this.carro;
                    return c.slice().sort((a, b) => {
                        return c.indexOf(b) - c.indexOf(a);
                    });
                },
                totalVenta: function() {
                    var vc = this.ventaCabecera;
                    var c = this.carro;
                    if (!vc || typeof vc.total === 'undefined') return '0';
                    vc.total = 0;
                    for (var i = 0; i < c.length; i++) {
                        vc.total += (c[i].precio * c[i].cantidad);
                    }
                    if (vc.descuento > 0 && vc.total > 0) {
                        vc.total -= vc.descuento;
                    }
                    return this.format(vc.total);
                }
            },
            mounted() {
                // this.getFecha();
                this.recuperarDatos();
                this.getSucursal();
                this.ensureVentaCabecera();
                this.getApertura();
                this.getFecha();
                this.getConfigVenta();
            }
        });
        window.ventaApp = app;
        activarMenu('m_venta', '');
    </script>
@endsection
