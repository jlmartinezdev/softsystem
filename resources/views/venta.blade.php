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

        #modalCatalogoArticulos .modal-body,
        #modalCombos .modal-body {
            height: auto;
            max-height: 70vh;
            overflow-y: auto;
        }
        .catalogo-card {
            cursor: pointer;
            border: 1px solid #dee2e6;
            border-radius: 0.35rem;
            overflow: hidden;
            height: 100%;
            transition: box-shadow .15s, border-color .15s, transform .15s;
            background: #fff;
            position: relative;
        }
        .catalogo-card:hover {
            border-color: #007bff;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.08);
            transform: translateY(-1px);
        }
        .catalogo-card.seleccionada {
            border-color: #28a745;
            box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.25);
        }
        .catalogo-card.sin-stock {
            opacity: 0.65;
        }
        .catalogo-check {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            border: 0;
            background: rgba(33, 37, 41, 0.45);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            box-shadow: none;
            line-height: 1;
        }
        .catalogo-card.seleccionada .catalogo-check {
            background: #1f2937;
            color: #fff;
        }
        .catalogo-zoom-btn {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: rgba(0, 123, 255, 0.85);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
            padding: 0;
            line-height: 1;
        }
        .catalogo-zoom-btn:hover {
            background: #007bff;
            color: #fff;
        }
        .catalogo-zoom-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .catalogo-zoom-overlay img {
            max-width: 95vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.4);
            background: #fff;
        }
        .catalogo-zoom-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2001;
        }
        .catalogo-zoom-caption {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: 0.95rem;
            text-align: center;
            max-width: 90vw;
        }
        .catalogo-card-img {
            height: 120px;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .catalogo-card-img img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .catalogo-card-body {
            padding: 0.55rem 0.65rem 0.7rem;
        }
        .catalogo-card-title {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.2;
            margin: 0 0 0.35rem;
            min-height: 2.1em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .catalogo-card-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }
        .catalogo-card-precio {
            font-weight: 700;
            color: #28a745;
            font-size: 0.95rem;
        }
        .catalogo-stock {
            display: inline-block;
            border-radius: 50px;
            padding: 0.15rem 0.55rem;
            font-size: 0.72rem;
            font-weight: 600;
            line-height: 1.3;
            white-space: nowrap;
            background: #e8f5e9;
            color: #2d5a4c;
        }
        .catalogo-stock.agotado {
            background: #fdecea;
            color: #9b2c2c;
        }
        .autocomplete-result .catalogo-stock {
            margin-right: 0.35rem;
            vertical-align: middle;
        }
        .dark-mode .buscador-catalogo .navbar,
        .dark-mode .buscador-catalogo .buscador-navbar {
            background-color: #343a40 !important;
            border-color: #6c757d !important;
        }
        .dark-mode .buscador-catalogo .navbar-light .navbar-nav .nav-link,
        .dark-mode .buscador-catalogo .buscador-navbar .nav-link {
            color: #ced4da;
        }
        .dark-mode .buscador-catalogo .navbar-light .navbar-nav .nav-link:hover,
        .dark-mode .buscador-catalogo .buscador-navbar .nav-link:hover {
            color: #fff;
        }
        .dark-mode .autocomplete-result-list {
            background: #343a40;
            color: #fff;
            border-color: #6c757d;
        }
        .dark-mode .autocomplete-result,
        .dark-mode .autocomplete-result .left,
        .dark-mode .autocomplete-result .precio {
            color: #fff;
        }
        .dark-mode .autocomplete-result.text-maroon,
        .dark-mode .autocomplete-result.text-maroon .left,
        .dark-mode .autocomplete-result.text-maroon .precio {
            color: #ff8a80;
        }
        .dark-mode .autocomplete-result:hover,
        .dark-mode .autocomplete-result[aria-selected="true"] {
            background-color: #454d55;
        }
        .dark-mode .autocomplete-result .btn-link {
            color: #9ec5fe;
        }
        .cliente-picker {
            position: relative;
        }
        .cliente-picker-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            border: 1px solid #343a40;
            border-radius: 0.5rem;
            background: #fff;
            padding: 0.45rem 0.7rem;
            font-size: 0.875rem;
            color: #212529;
            text-align: left;
            line-height: 1.3;
            cursor: pointer;
        }
        .cliente-picker-trigger:hover,
        .cliente-picker-trigger.open {
            border-color: #111;
        }
        .cliente-picker-trigger .placeholder {
            color: #6c757d;
        }
        .cliente-picker-trigger .fa-chevron-up,
        .cliente-picker-trigger .fa-chevron-down {
            color: #495057;
            font-size: 0.75rem;
        }
        .cliente-picker-panel {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border: 1px solid #343a40;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.12);
        }
        .cliente-picker-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.7rem;
            border-bottom: 1px solid #dee2e6;
        }
        .cliente-picker-search .fa-search {
            color: #6c757d;
        }
        .cliente-picker-search input {
            border: 0;
            outline: none;
            flex: 1;
            font-size: 0.875rem;
            background: transparent;
            min-width: 0;
        }
        .cliente-picker-search .btn-clear {
            border: 0;
            background: transparent;
            color: #6c757d;
            padding: 0;
            line-height: 1;
            cursor: pointer;
        }
        .cliente-picker-section-title {
            padding: 0.45rem 0.7rem 0.2rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #6c757d;
            text-transform: uppercase;
        }
        .cliente-picker-list {
            max-height: 220px;
            overflow-y: auto;
        }
        .cliente-picker-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            border: 0;
            background: transparent;
            padding: 0.55rem 0.7rem;
            text-align: left;
            cursor: pointer;
            font-size: 0.875rem;
            color: #212529;
        }
        .cliente-picker-item:hover,
        .cliente-picker-item.active {
            background: #f1f3f5;
        }
        .cliente-picker-item .fa-user {
            color: #495057;
            width: 1rem;
            text-align: center;
        }
        .cliente-picker-item .meta {
            display: block;
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 400;
        }
        .cliente-picker-empty {
            padding: 0.75rem 0.7rem;
            color: #6c757d;
            font-size: 0.85rem;
        }
        .cliente-picker-create {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            border: 0;
            border-top: 1px solid #dee2e6;
            background: transparent;
            padding: 0.65rem 0.7rem;
            text-align: left;
            cursor: pointer;
            font-size: 0.875rem;
            color: #212529;
        }
        .cliente-picker-create:hover {
            background: #f8f9fa;
        }
        .cliente-picker-create .fa-plus-circle {
            color: #212529;
        }
        .cliente-picker-form {
            padding: 0.7rem;
            border-top: 1px solid #dee2e6;
        }
        .cliente-picker-form .form-control {
            font-size: 0.85rem;
        }
        .dark-mode .cliente-picker-trigger {
            background: #343a40;
            border-color: #6c757d;
            color: #fff;
        }
        .dark-mode .cliente-picker-trigger:hover,
        .dark-mode .cliente-picker-trigger.open {
            border-color: #adb5bd;
            background: #3d444b;
        }
        .dark-mode .cliente-picker-trigger .placeholder {
            color: #adb5bd;
        }
        .dark-mode .cliente-picker-trigger .fa-chevron-up,
        .dark-mode .cliente-picker-trigger .fa-chevron-down {
            color: #ced4da;
        }
        .dark-mode .cliente-picker-panel {
            background: #343a40;
            border-color: #6c757d;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.45);
            color: #fff;
        }
        .dark-mode .cliente-picker-search {
            border-bottom-color: #6c757d;
        }
        .dark-mode .cliente-picker-search .fa-search,
        .dark-mode .cliente-picker-search .btn-clear {
            color: #adb5bd;
        }
        .dark-mode .cliente-picker-search input {
            color: #fff;
            background: transparent;
        }
        .dark-mode .cliente-picker-search input::placeholder {
            color: #adb5bd;
        }
        .dark-mode .cliente-picker-section-title,
        .dark-mode .cliente-picker-empty,
        .dark-mode .cliente-picker-item .meta {
            color: #adb5bd;
        }
        .dark-mode .cliente-picker-item {
            color: #fff;
        }
        .dark-mode .cliente-picker-item:hover,
        .dark-mode .cliente-picker-item.active {
            background: #454d55;
        }
        .dark-mode .cliente-picker-item .fa-user {
            color: #ced4da;
        }
        .dark-mode .cliente-picker-create {
            border-top-color: #6c757d;
            color: #fff;
        }
        .dark-mode .cliente-picker-create:hover {
            background: #454d55;
        }
        .dark-mode .cliente-picker-create .fa-plus-circle {
            color: #ced4da;
        }
        .dark-mode .cliente-picker-form {
            border-top-color: #6c757d;
        }
        .pago-fila {
            display: flex;
            align-items: stretch;
            gap: 0.5rem;
        }
        .pago-metodos,
        .pago-condiciones {
            display: grid;
            gap: 0.4rem;
        }
        .pago-metodos {
            flex: 1 1 auto;
            grid-template-columns: repeat(4, 1fr);
            min-width: 0;
        }
        .pago-condiciones {
            flex: 0 0 auto;
            grid-template-columns: repeat(2, minmax(72px, 88px));
        }
        .pago-separador {
            width: 1px;
            align-self: stretch;
            background: #ced4da;
            margin: 0.15rem 0.15rem;
            flex: 0 0 1px;
        }
        .pago-metodo {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            min-height: 68px;
            padding: 0.55rem 0.3rem 0.45rem;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            background: #fff;
            color: #343a40;
            cursor: pointer;
            transition: border-color .15s, box-shadow .15s;
        }
        .pago-metodo:hover {
            border-color: #adb5bd;
        }
        .pago-metodo.selected {
            border-color: #28a745;
            box-shadow: 0 0 0 1px rgba(40, 167, 69, 0.15);
        }
        .pago-metodo .pago-icon {
            font-size: 1.1rem;
            line-height: 1;
            color: #212529;
        }
        .pago-metodo .pago-label {
            font-size: 0.72rem;
            font-weight: 500;
            text-align: center;
            line-height: 1.15;
        }
        .pago-metodo .pago-check {
            position: absolute;
            top: 0;
            right: 0;
            width: 1.25rem;
            height: 1.25rem;
            background: #28a745;
            border-radius: 0 0.4rem 0 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.6rem;
        }
        .pago-metodo .pago-check .fa-check {
            background: #fff;
            color: #28a745;
            border-radius: 50%;
            width: 0.8rem;
            height: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.45rem;
        }
        @media (max-width: 767.98px) {
            .pago-fila {
                flex-direction: column;
            }
            .pago-separador {
                width: 100%;
                height: 1px;
                margin: 0.25rem 0;
            }
            .pago-metodos {
                grid-template-columns: repeat(4, 1fr);
            }
            .pago-condiciones {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 575.98px) {
            .pago-metodos {
                grid-template-columns: 1fr 1fr;
            }
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
                        <buscador-catalogo
                            ref="buscador"
                            url="{{ env('APP_APIDB') }}"
                            :idsucursal="ventaCabecera.idSucursal"
                            url-buscar="{{ url('articulo/buscar') }}"
                            url-foto-base="{{ asset('storage/articulos') }}"
                            img-fallback="{{ asset('img/sinimagen.png') }}"
                            route-articulo="{{ route('articulo.cm') }}"
                            validar-lote="false"
                            is-ready-balance="true"
                            precio-field="pre_venta1"
                            modal-id="modalCatalogoArticulos"
                            @articulo="addCarrito"
                            @peso="setPeso"
                            @seleccion="agregarDesdeCatalogo"
                        >
                            <template slot="actions-before">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" title="Ítem libre (sin catálogo)"
                                        @click.prevent="abrirItemLibre">
                                        <i class="fa fa-bolt text-warning"></i>
                                    </a>
                                </li>
                            </template>
                            <template slot="actions-after">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" title="Combos"
                                        @click.prevent="abrirModalCombos">
                                        <i class="fa fa-layer-group text-info"></i>
                                    </a>
                                </li>
                            </template>
                        </buscador-catalogo>
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
                                                <span v-else-if="item.es_combo" class="badge badge-info">COMBO</span>
                                                <span v-else>@{{ item.codigo }}</span>
                                                <div v-if="item.oferta_nombre">
                                                    <span class="badge badge-danger">OFERTA</span>
                                                    <small class="text-danger">@{{ item.oferta_nombre }}</small>
                                                </div>
                                            </td>
                                            <td>@{{ item.descripcion }}</td>
                                            <td> <input type="number"
                                                    style="width: 57px; border: none; background-color: transparent;"
                                                    min="1" :max="(item.es_libre || item.es_combo) ? 999999 : item.stock" v-model="item.cantidad"
                                                    @change="onCantidadCarritoChange(item)"> </td>
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
                                <div class="cliente-picker" ref="clientePicker">
                                    <button type="button" class="cliente-picker-trigger"
                                        :class="{ open: clientePickerOpen }"
                                        @click="toggleClientePicker">
                                        <span :class="{ placeholder: !ventaCabecera.clienteNombre }">
                                            @{{ ventaCabecera.clienteNombre || 'Selecciona un cliente' }}
                                        </span>
                                        <i class="fa" :class="clientePickerOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                    </button>

                                    <div class="cliente-picker-panel" v-show="clientePickerOpen" @click.stop>
                                        <div class="cliente-picker-search">
                                            <i class="fa fa-search"></i>
                                            <input type="text"
                                                id="txtclienteVenta"
                                                v-model="txtcliente"
                                                @input="onBuscarClienteInput"
                                                @keydown.enter.prevent="seleccionarPrimerCliente"
                                                @keydown.down.prevent="moverSeleccionCliente(1)"
                                                @keydown.up.prevent="moverSeleccionCliente(-1)"
                                                @keydown.esc.prevent="cerrarClientePicker"
                                                placeholder="Buscar cliente"
                                                autocomplete="off">
                                            <button type="button" class="btn-clear" v-if="txtcliente"
                                                @click="limpiarBusquedaCliente" title="Limpiar">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="cliente-picker-section-title">Buscar cliente</div>

                                        <div class="cliente-picker-empty" v-if="clienteBuscando">
                                            <i class="fa fa-spinner fa-spin"></i> Buscando...
                                        </div>
                                        <div class="cliente-picker-empty" v-else-if="!clientes.length">
                                            No hay clientes para mostrar.
                                        </div>
                                        <div class="cliente-picker-list" v-else>
                                            <button type="button" class="cliente-picker-item"
                                                v-for="(cliente, index) in clientes"
                                                :key="cliente.clientes_cod"
                                                :class="{ active: index === clienteIndexActivo }"
                                                @click="selectCliente(cliente.clientes_cod, cliente.cliente_nombre)"
                                                @mouseenter="clienteIndexActivo = index">
                                                <i class="fa fa-user"></i>
                                                <span>
                                                    <strong>@{{ cliente.cliente_nombre }}</strong>
                                                    <span class="meta" v-if="cliente.cliente_ci || cliente.cliente_ruc">
                                                        @{{ cliente.cliente_ci || cliente.cliente_ruc }}
                                                    </span>
                                                </span>
                                            </button>
                                        </div>

                                        <button type="button" class="cliente-picker-create"
                                            v-if="!mostrarFormClienteNuevo"
                                            @click="mostrarFormClienteNuevo = true">
                                            <i class="fa fa-plus-circle"></i>
                                            Crear nuevo Cliente
                                        </button>

                                        <div class="cliente-picker-form" v-else>
                                            <div class="form-group mb-2">
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model.trim="clienteNuevo.nombre"
                                                    placeholder="Nombre del cliente *"
                                                    @keydown.enter.prevent="guardarClienteRapido">
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model.trim="clienteNuevo.doc"
                                                    placeholder="CI / RUC (opcional)">
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-link btn-sm px-0"
                                                    @click="cancelarClienteNuevo">Cancelar</button>
                                                <button type="button" class="btn btn-dark btn-sm"
                                                    :disabled="clienteCreando"
                                                    @click="guardarClienteRapido">
                                                    <i class="fa" :class="clienteCreando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
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

        <!-- Modal combos -->
        <div class="modal fade" id="modalCombos" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title mb-0">
                            <span class="fa fa-layer-group"></span> Combos
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="comboModal.cargando" class="text-center text-muted py-4">
                            <span class="fa fa-spinner fa-spin"></span> Cargando...
                        </div>
                        <div v-else-if="!comboModal.items.length" class="text-center text-muted py-4">
                            No hay combos activos. Creá uno en <strong>Combos</strong>.
                        </div>
                        <div v-else class="list-group">
                            <button type="button" class="list-group-item list-group-item-action"
                                v-for="c in comboModal.items" :key="c.id"
                                @click="agregarComboAlCarrito(c)">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">@{{ c.nombre }}</h6>
                                    <strong class="text-success">Gs. @{{ format(c.precio) }}</strong>
                                </div>
                                <small class="text-muted d-block mb-1">
                                    <span v-for="(it, i) in c.items" :key="i">
                                        @{{ it.cantidad }}× @{{ it.nombre }}<span v-if="i < c.items.length - 1"> · </span>
                                    </span>
                                </small>
                                <span class="badge" :class="c.stock_ok ? 'badge-success' : 'badge-warning'">
                                    @{{ c.stock_ok ? 'Stock OK' : 'Stock insuficiente' }}
                                </span>
                                <span class="badge badge-light" v-if="c.precio_lista > c.precio">
                                    Lista Gs. @{{ format(c.precio_lista) }}
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('combo.index') }}" class="btn btn-outline-secondary btn-sm mr-auto">
                            Administrar combos
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                clientePickerOpen: false,
                mostrarFormClienteNuevo: false,
                clienteCreando: false,
                clienteNuevo: {
                    nombre: '',
                    doc: ''
                },
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
                },
                comboModal: {
                    cargando: false,
                    items: []
                },
                ofertasActivas: []
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
                            precio_lista: parseInt(a.pre_venta1, 10) || Number(a.pre_venta1) || 0,
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
                            oferta_id: null,
                            oferta_nombre: null
                        }
                        

                        this.carro.push(art);
                        this.aplicarOfertaItem(art);
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
                        this.aplicarOfertaItem(this.carro[i]);
                        //Actualizar cantidad
                    }
                    this.saveDatos();
                },
                cargarOfertasActivas: function () {
                    var self = this;
                    var fecha = (this.ventaCabecera && this.ventaCabecera.fecha) ? this.ventaCabecera.fecha : '';
                    axios.get('{{ url('oferta/activas') }}', { params: { fecha: fecha } })
                        .then(function (r) {
                            self.ofertasActivas = Array.isArray(r.data) ? r.data : [];
                            self.aplicarOfertasCarrito();
                        })
                        .catch(function () {
                            self.ofertasActivas = [];
                        });
                },
                ofertaAplica: function (o, cantidad, fecha) {
                    if (!o) return false;
                    cantidad = Number(cantidad) || 0;
                    var tipo = o.tipo;
                    var okCant = true;
                    var okFecha = true;

                    if (tipo === 'cantidad' || tipo === 'ambos') {
                        var min = Number(o.cantidad_min) || 0;
                        okCant = min > 0 && cantidad >= min;
                    }
                    if (tipo === 'fecha' || tipo === 'ambos') {
                        okFecha = true;
                        if (o.fecha_desde && fecha && fecha < o.fecha_desde) okFecha = false;
                        if (o.fecha_hasta && fecha && fecha > o.fecha_hasta) okFecha = false;
                        if (!o.fecha_desde && !o.fecha_hasta) okFecha = false;
                        if (!fecha && (o.fecha_desde || o.fecha_hasta)) {
                            // sin fecha de venta, usar hoy
                            var hoy = new Date();
                            fecha = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-' + String(hoy.getDate()).padStart(2, '0');
                            okFecha = true;
                            if (o.fecha_desde && fecha < o.fecha_desde) okFecha = false;
                            if (o.fecha_hasta && fecha > o.fecha_hasta) okFecha = false;
                        }
                    }
                    if (tipo === 'cantidad') return okCant;
                    if (tipo === 'fecha') return okFecha;
                    return okCant && okFecha;
                },
                calcularPrecioOferta: function (o, precioBase) {
                    var base = Number(precioBase) || 0;
                    var v = Number(o.descuento_valor) || 0;
                    if (o.descuento_tipo === 'monto') return Math.max(0, base - v);
                    if (o.descuento_tipo === 'precio_fijo') return Math.max(0, v);
                    return Math.max(0, Math.round(base * (1 - v / 100)));
                },
                aplicarOfertaItem: function (item) {
                    if (!item || item.es_libre || item.es_combo) return;
                    var base = Number(item.precio_lista);
                    if (!base) {
                        base = Number(item.p1) || Number(item.precio) || 0;
                        item.precio_lista = base;
                    }
                    var fecha = (this.ventaCabecera && this.ventaCabecera.fecha) ? this.ventaCabecera.fecha : '';
                    var best = null;
                    var bestPrecio = base;
                    var self = this;
                    (this.ofertasActivas || []).forEach(function (o) {
                        if (String(o.articulos_cod) !== String(item.codigo)) return;
                        if (!self.ofertaAplica(o, item.cantidad, fecha)) return;
                        var p = self.calcularPrecioOferta(o, base);
                        if (p < bestPrecio) {
                            bestPrecio = p;
                            best = o;
                        }
                    });
                    if (best) {
                        item.precio = bestPrecio;
                        item.oferta_id = best.id;
                        item.oferta_nombre = best.nombre;
                    } else {
                        if (!item.iPrecio || item.iPrecio === 'CO1') {
                            item.precio = base;
                        }
                        item.oferta_id = null;
                        item.oferta_nombre = null;
                    }
                },
                aplicarOfertasCarrito: function () {
                    var self = this;
                    (this.carro || []).forEach(function (item) {
                        self.aplicarOfertaItem(item);
                    });
                },
                onCantidadCarritoChange: function (item) {
                    this.aplicarOfertaItem(item);
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
                            this.aplicarOfertaItem(this.carro[realIndex]);
                        }
                        this.saveDatos();
                    }
                    this.$refs.buscador.focusSearchInput();
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
                    this.$refs.buscador.focusSearchInput();
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
                    this.$refs.buscador.focusSearchInput();
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
                    this.toggleClientePicker(true);
                },
                toggleClientePicker: function (forceOpen) {
                    var open = typeof forceOpen === 'boolean' ? forceOpen : !this.clientePickerOpen;
                    if (!open) {
                        this.cerrarClientePicker();
                        return;
                    }
                    this.clientePickerOpen = true;
                    this.mostrarFormClienteNuevo = false;
                    this.clienteIndexActivo = 0;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.buscarCliente();
                    this.$nextTick(function () {
                        var el = document.getElementById('txtclienteVenta');
                        if (el) el.focus();
                    });
                },
                cerrarClientePicker: function () {
                    this.clientePickerOpen = false;
                    this.mostrarFormClienteNuevo = false;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                },
                onClickOutsideClientePicker: function (e) {
                    if (!this.clientePickerOpen) return;
                    var root = this.$refs.clientePicker;
                    if (root && !root.contains(e.target)) {
                        this.cerrarClientePicker();
                    }
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
                            this.clientes = [];
                            console.log(error.message);
                        });
                },
                cancelarClienteNuevo: function () {
                    this.mostrarFormClienteNuevo = false;
                    this.clienteNuevo = { nombre: '', doc: '' };
                },
                guardarClienteRapido: function () {
                    var self = this;
                    var nombre = (this.clienteNuevo.nombre || '').trim();
                    if (!nombre) {
                        Swal.fire('Falta nombre', 'Indicá el nombre del cliente.', 'warning');
                        return;
                    }
                    this.clienteCreando = true;
                    axios.post('{{ url('cliente') }}', {
                        cliente: {
                            idciudad: 1,
                            doc: (this.clienteNuevo.doc || '').trim() || '0',
                            nombre: nombre,
                            direccion: '',
                            telefono: '',
                            celular: '',
                            correo: '',
                            celfamiliar: '',
                            ocupacion: '',
                            reflaboral: ''
                        }
                    }).then(function () {
                        return axios.get('{{ url('cliente/buscar') }}', {
                            params: { q: nombre, limit: 5 }
                        });
                    }).then(function (r) {
                        self.clienteCreando = false;
                        var list = r.data || [];
                        var found = list.find(function (c) {
                            return String(c.cliente_nombre).toUpperCase() === nombre.toUpperCase();
                        }) || list[0];
                        if (found) {
                            self.selectCliente(found.clientes_cod, found.cliente_nombre);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                icon: 'success',
                                title: 'Cliente creado'
                            });
                        } else {
                            Swal.fire('Creado', 'Cliente guardado. Buscalo para seleccionarlo.', 'success');
                            self.cancelarClienteNuevo();
                            self.txtcliente = nombre;
                            self.buscarCliente();
                        }
                    }).catch(function () {
                        self.clienteCreando = false;
                        Swal.fire('Error', 'No se pudo crear el cliente', 'error');
                    });
                },
                selectCliente: function(id, cliente) {
                    this.ventaCabecera.clienteId = id;
                    this.ventaCabecera.clienteNombre = cliente;
                    this.txtcliente = '';
                    this.clientes = [];
                    this.clienteIndexActivo = 0;
                    this.cancelarClienteNuevo();
                    this.cerrarClientePicker();
                    this.saveDatos && this.saveDatos();
                },
                seleccionarFormaPago: function (valor) {
                    this.ventaCabecera.formacobro = valor;
                    this.saveDatos && this.saveDatos();
                },
                seleccionarCondicionVenta: function (valor) {
                    this.ventaCabecera.condicionventa = valor;
                    if (String(valor) === '1') {
                        this.ventaCabecera.generarcuota = true;
                    }
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
                abrirModalCombos: function () {
                    var self = this;
                    this.comboModal.cargando = true;
                    this.comboModal.items = [];
                    $('#modalCombos').modal('show');
                    axios.get('{{ url('combo/activos') }}', {
                        params: { suc: this.ventaCabecera.idSucursal || null }
                    }).then(function (r) {
                        self.comboModal.cargando = false;
                        self.comboModal.items = Array.isArray(r.data) ? r.data : [];
                    }).catch(function () {
                        self.comboModal.cargando = false;
                        Swal.fire('Error', 'No se pudieron cargar los combos', 'error');
                    });
                },
                agregarComboAlCarrito: function (combo) {
                    if (!combo) return;
                    if (!combo.stock_ok && Number(this.ventaCabecera.vender_sin_stock) == 0) {
                        Swal.fire('Stock insuficiente', 'Uno o más artículos del combo no tienen stock.', 'warning');
                        return;
                    }
                    this.ensureVentaCabecera();
                    var art = {
                        codigo: 'COMBO-' + combo.id,
                        combo_id: combo.id,
                        idstock: 0,
                        descripcion: combo.nombre,
                        descripcion_libre: combo.nombre,
                        es_combo: true,
                        componentes: (combo.items || []).map(function (it) {
                            return {
                                articulos_cod: it.articulos_cod,
                                cantidad: it.cantidad,
                                id_stock: it.id_stock,
                                nombre: it.nombre
                            };
                        }),
                        linea_uid: 'combo-' + combo.id + '-' + Date.now(),
                        cantidad: 1,
                        stock: 999999,
                        precio: Number(combo.precio) || 0,
                        p1: Number(combo.precio) || 0,
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
                    $('#modalCombos').modal('hide');
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    Toast.fire({ icon: 'success', title: combo.nombre + ' agregado' });
                    var self = this;
                    $('#modalCombos').one('hidden.bs.modal', function () {
                        if (self.$refs.buscador && self.$refs.buscador.focusSearchInput) {
                            self.$refs.buscador.focusSearchInput();
                        }
                    });
                },
                agregarDesdeCatalogo: function (arts) {
                    var agregados = 0;
                    var self = this;
                    (arts || []).forEach(function (art) {
                        if (!art) return;
                        if (!art.ARTICULOS_cod && art.articulos_cod) {
                            art.ARTICULOS_cod = art.articulos_cod;
                        }
                        if (typeof art.id_stock === 'undefined' && typeof art.idstock !== 'undefined') {
                            art.id_stock = art.idstock;
                        }
                        var antes = self.carro.length;
                        var cantAntes = 0;
                        var idx = self.carro.findIndex(function (x) {
                            return String(x.codigo) === String(art.ARTICULOS_cod) && x.idstock == art.id_stock;
                        });
                        if (idx !== -1) {
                            cantAntes = self.carro[idx].cantidad;
                        }
                        self.addCarrito(art);
                        idx = self.carro.findIndex(function (x) {
                            return String(x.codigo) === String(art.ARTICULOS_cod) && x.idstock == art.id_stock;
                        });
                        if (idx !== -1 && (self.carro.length > antes || self.carro[idx].cantidad > cantAntes)) {
                            agregados++;
                        }
                    });
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    Toast.fire({
                        icon: agregados ? 'success' : 'warning',
                        title: agregados
                            ? (agregados + ' artículo(s) agregado(s) al carrito')
                            : 'No se pudo agregar (revisá stock)'
                    });
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
                this.cargarOfertasActivas();
                document.addEventListener('click', this.onClickOutsideClientePicker);
            },
            beforeDestroy() {
                document.removeEventListener('click', this.onClickOutsideClientePicker);
            }
        });
        window.ventaApp = app;
        activarMenu('m_venta', '');
    </script>
@endsection
