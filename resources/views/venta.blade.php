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
                                <div class="py-2"  ><span class="font-weight-bold" style="font-size: 18pt;">Vender</span></div>
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
                                                <Searcharticulo url="{{ env('APP_APIDB') }}" :idsucursal="ventaCabecera.idSucursal"
                                    @articulo="addCarrito" validar-lote="false" ref="Searcharticulo" route-articulo="{{ route('articulo.cm')}}">
                                </Searcharticulo>
                                            </li>
                                        </ul>
                                        <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link"><i class="fa-regular fa-filter-list"></i></a>
                                            
                                        </li>
                                        <li class="nav-item dropdown" >
                                            <a href="#" class="nav-link" title="Articulo Rapido" data-toggle="dropdown"><i class="fa-regular fa-bolt"></i></a>
                                            
                                            <!-- div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"  id="fastDropdown">
                                                <h3 class="dropdown-header">Agregar item rapido</h3>
                                                <form class="px-4 py-3">
                                                    <div class="form-group">
                                                      <label for="fastItemPrecio">Precio</label>
                                                      <input type="number" class="form-control"v-model="fastItem.precio" id="fastItemPrecio" placeholder="Gs.">
                                                    </div>
                                                    <div class="form-group">
                                                      <label for="fastItemDescripcion">Descripcion</label>
                                                      <input type="text" class="form-control" v-model="fastItem.descripcion" id="fastItemDescripcion" placeholder="Descripcion">
                                                    </div>
                                                    <hr>
                                                  <button type="button" class="btn btn-info btn-block" @click="addFastItem" ><i class="fa-regular fa-plus"></i> Agregar</button>
                                                    
                                                  </form>
                                                  
                                            </div -->
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
                                    <template v-for="(item,index) in carro">
                                        <tr>

                                            <td>@{{ item.codigo }}</td>
                                            <td>@{{ item.descripcion }}</td>
                                            <td> <input type="number"
                                                    style="width: 50px; border: none; background-color: transparent;"
                                                    min="1" :max="item.stock" v-model="item.cantidad"> </td>
                                            <td>@{{ new Intl.NumberFormat("de-DE").format(item.precio) }}</td>
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
                                                            @click="setCantidad(index,item.cantidad,item.stock)">
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
    </div><!-- end app -->
@endsection
@section('footer')
    <span class="fa fa-user"></span> Usuario: {{ Auth::user()->nom_usuarios }}
    <span class="ml-3">{{ date('d-m-Y') }}</span>
@endsection
@section('script')
    <script src="{{ asset(mix('js/venta.js')) }}"></script>
    <script src="{{ asset('js/separator.js') }}"></script>
    <script type="text/javascript">
    $(document).on('show.bs.dropdown', function (event) {
        // Check if the dropdown being shown is the one for "Agregar item rapido"
        const dropdown = $(event.target).find('#fastItemPrecio');
        if (dropdown.length) {
            setTimeout(() => {
                dropdown.focus(); 
            }, 200); 
        }
    });
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                requestFinalizar: false,
                currentPage: 1,

                ventaCabecera: {
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
                    generarcuota: true
                },
                carro: [],
                caja: '...',
                nrooperacion: '...',
                tmpIndexPrecio: {
                    iPrecio: 'CO1',
                    iArticulo: 0,
                    monto_cuota: 0
                },
                txtbuscar: '',
                txtcliente: '',
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
                preciosCredito: [],
                articulo: null,
                clientes: [],
                requestLote: false,
                cuotas: [],
                enfocar: false,
                fastItem: {
                    precio: 0,
                    descripcion: ''
                }
            },
            methods: {
                search: function(input) {
                    console.log(input);
                },

                setCuotas: function(cuotas) {
                    this.cuotas = cuotas;
                },

                addCarrito: function(a) {

                    var Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    //Buscar articulo si no esta en la lista
                    if (a.cantidad == 0) {
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
                            cantidad: 1,
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
                    } else {
                        if ((this.carro[i].cantidad + 1) <= a.cantidad) {
                            this.carro[i].cantidad = parseInt(this.carro[i].cantidad) + 1;
                        } else {
                            Toast.fire({
                                title: `Cantidad supera stock disponible: ${a.cantidad} ...`,
                                icon: 'error'
                            });
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
                setCantidad: async function(index, cantidad, stock) {
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
                        inputValue: cantidad,
                        inputAttributes: {
                            min: 0,
                            max: stock
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar'
                    })
                    if (cant) {
                        this.carro[index].cantidad = cant;
                        this.saveDatos();
                    }
                    this.$refs.Searcharticulo.focusSearchInput();
                },
                showModalPrecio: function(index, articulo) {

                    this.articulo = articulo;
                    this.tmpIndexPrecio.iArticulo = index;
                    for (i = 1; i < 6; i++) {
                        this.preciosContado['m' + i] = parseInt(articulo['m' + i]);
                        this.preciosContado['p' + i] = parseInt(articulo['p' + i]);
                    }
                    this.preciosContado.articulo = articulo.descripcion;
                    $('#selPrecio').modal('show');
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
                        }

                    }
                    this.$refs.Searcharticulo.focusSearchInput();
                    this.saveDatos();
                },
                delArticulo: function(a) {
                    this.$refs.Searcharticulo.focusSearchInput();
                    let validar = this.carro.findIndex(x => x.codigo == a.codigo)
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
                    if (this.ventaCabecera.condicionventa == 2 && this.cuotas.length < 1) {
                        Swal.fire('Error', 'Por favor genere las cuotas', 'error');
                        return false;
                    }
                    this.requestFinalizar = true;
                    axios.post('venta', {
                            ventaCabecera: this.ventaCabecera,
                            detalle: this.carro,
                            cuotas: this.cuotas
                        })
                        .then(response => {
                            this.requestFinalizar = false;
                            this.carro = [];
                            localStorage.removeItem('carro_venta');
                            localStorage.removeItem('ventaCabecera');
                            if (print) {
                                if (this.ventaCabecera.documento == 'Ticket') {

                                    window.location.assign('{{ env('APP_URL') }}' + 'ticket/venta/' +
                                        response.data);
                                } else {
                                    window.location.assign('{{ env('APP_URL') }}' + 'pdf/boletaventa/' +
                                        response.data);
                                }

                            } else {
                                $('#finalizarventa').modal('hide');
                                window.location.reload();
                            }

                        })
                        .catch(error => {
                            this.requestFinalizar = false;
                            Swal.fire('Error', error.message, 'error');
                        })
                },
                numeroaletra: function(n) {
                    return NumeroALetras.NumeroALetras(parseInt(n));
                },
                saveDatos: function() {
                    localStorage.setItem('carro_venta', JSON.stringify(this.carro));
                    localStorage.setItem('ventaCabecera', JSON.stringify(this.ventaCabecera));
                },
                recuperarDatos: function() {
                    var carro = localStorage.getItem('carro_venta');
                    if (carro != null) {
                        this.carro = JSON.parse(carro);
                    }
                    var cab = localStorage.getItem('ventaCabecera');
                    if (cab != null) {
                        this.ventaCabecera = JSON.parse(cab);
                    }
                    this.ventaCabecera.condicionventa = 1;
                    this.ventaCabecera.generarcuota = true;

                },
                showBuscarCliente: function() {
                    $('#busquedaCliente').modal('show');
                },
                buscarCliente: function() {
                    if (this.txtcliente.length > 0) {
                        var doc = '';
                        var nom = '';
                        if (isNaN(parseFloat(this.txtcliente))) {
                            nom = this.txtcliente;
                        } else {
                            doc = this.txtcliente;
                        }
                        axios.get('cliente/buscar', {
                                params: {
                                    documento: doc,
                                    nombre: nom
                                }
                            })
                            .then(response => {
                                this.clientes = response.data;
                            })
                            .catch(error => {
                                console.log(error.message);
                            })
                    }
                },

                selectCliente: function(id, cliente) {
                    this.ventaCabecera.clienteId = id;
                    this.ventaCabecera.clienteNombre = cliente;
                    $('#busquedaCliente').modal('hide');
                },
                getSucursal: function() {
                    var obj = document.getElementById("sucursal");
                    if (obj.getAttribute('data-id') != null)
                        this.ventaCabecera.idSucursal = obj.getAttribute('data-id');
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
                    this.carro = [];
                    localStorage.removeItem('carro_venta');
                    localStorage.removeItem('ventaCabecera');
                    this.getFecha();
                    window.location.reload();
                },
                getConfigVenta() {
                    var config = localStorage.getItem('config_venta');
                    if (config != null) {
                        config = JSON.parse(config);
                        this.ventaCabecera.documento = config.tipo_comprobante;
                    }

                },
                addFastItem: function(){
                    if(this.fastItem.precio>0 && this.fastItem.descripcion.length>0){
                        let art = {
                            codigo: 1,
                            idstock: 0,
                            descripcion: this.fastItem.descripcion,
                            cantidad: 1,
                            stock: 1,
                            precio: this.fastItem.precio,
                            p1: parseInt(this.fastItem.precio),
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
                            iPrecio: 'CO1',
                        }
                        this.carro.push(art);
                        this.saveDatos();
                        this.fastItem.precio = 0;
                        this.fastItem.descripcion = '';
                        

                    }
                    //ocultar dropdown
                    
                }
            },
            computed: {
                totalVenta: function() {
                    this.ventaCabecera.total = 0;
                    for (var i = 0; i < this.carro.length; i++) {
                        this.ventaCabecera.total += (this.carro[i].precio * this.carro[i].cantidad);
                    }
                    if (this.ventaCabecera.descuento > 0 && this.ventaCabecera.total > 0) {
                        this.ventaCabecera.total -= this.ventaCabecera.descuento;
                    }
                    return this.format(this.ventaCabecera.total);
                }
            },
            mounted() {
                // this.getFecha();
                this.getApertura();
                this.recuperarDatos();
                this.getFecha();
                this.getSucursal();
                this.getConfigVenta();
            }
        });
        activarMenu('m_venta', '');
    </script>
@endsection
