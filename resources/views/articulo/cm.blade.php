@extends('layouts.app')
@section('title', 'ABM Articulo')
@section('style')
    <link href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet">
    <style>
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


        /* The switch - the box around the slider */
        .switch-sm {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
            top: 12px;

        }

        /* Hide default HTML checkbox */
        .switch-sm input {
            display: none;
        }

        /* The slider */
        .slider-sm {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider-sm:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input.default:checked+.slider-sm {
            background-color: #444;
        }

        input.primary:checked+.slider-sm {
            background-color: #2196F3;
        }

        input.success:checked+.slider-sm {
            background-color: #8bc34a;
        }

        input.info:checked+.slider-sm {
            background-color: #17a2b8;
        }

        input.warning:checked+.slider-sm {
            background-color: #FFC107;
        }

        input.danger:checked+.slider-sm {
            background-color: #f44336;
        }

        input:focus+.slider-sm {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider-sm:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Rounded sliders */
        .slider-sm.round {
            border-radius: 20px;
        }

        .slider-sm.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('main')
    <div class="container pt-2" id="app">
        <div class="row ">
            <div class="col-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <a href="{{ url()->previous() }}" class="btn btn-light"><i
                                    class="fa-solid fa-chevron-left"></i></a>
                            <span class="font-weight-bold" style="font-size: 18pt;">@if(isset($id)) Modificar Producto @else Registrar Producto @endif</span>
                        </div>
                        <div class="col-sm-12 col-md-2 text-nowrap">
                            <div class="input-group">
                                <span class="text-secondary mx-2"><i class="fa-solid fa-list-ol"></i> Varios
                                    Precios <label class="switch-sm">
                                        <input type="checkbox" v-model="isMultiprecio" @change="_saveSettingPreferences()"
                                            class="info">
                                        <span class="slider-sm round"></span>
                                    </label></span>
                            </div>
                        </div>
                        <div class="col-md-2 text-nowrap">

                            <div class="input-group">
                                <span class="text-secondary mx-2"><i class="fa-regular fa-calendar"></i> Vencimiento <label
                                        class="switch-sm">
                                        <input type="checkbox" v-model="isVencimiento" @change="_saveSettingPreferences()"
                                            class="info">
                                        <span class="slider-sm round"></span>
                                    </label></span>
                            </div>
                        </div>
                        <!--div class="col-md-2 text-nowrap">
                            <div class="input-group">
                                <span class="text-secondary mx-2"><i class="fa-regular fa-user-tie"></i> Mayorista <label
                                        class="switch-sm">
                                        <input type="checkbox" v-model="isMayorista" @change="_saveSettingPreferences()"
                                            class="info">
                                        <span class="slider-sm round"></span>   
                                    </label></span>
                            </div>
                        </div -->


                        <div class="col-sm-12 col-md-2 ml-auto">
                            <button class="btn btn-success" @click="saveArticulo"><i
                                    class="fa-regular fa-floppy-disk"></i></span>
                                Guardar</button>
                        </div>


                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <!-- Panel descripcion  -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="d-flex justify-content-center bg-light">
                        <img src="{{ asset('img/sinimagen.png') }}" height="130" alt="...">
                    </div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="codigo">Codigo</label></strong>
                                    <input type="text" v-model="articulo.codigo" class="form-control form-control-sm"
                                        disabled name="codigo" placeholder="Auto">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="cbarra">Codigo de Barra</label></strong>
                                    <input type="text" v-model="articulo.c_barra" v-on:blur="validar_codigo_de_barra"
                                        class="form-control form-control-sm" name="cbarraN" id="cbarraN"
                                        placeholder="Codigo de Barra">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="descripcion">Descripcion *</label></strong>
                                    <input type="text" v-model="articulo.descripcion"
                                        class="form-control form-control-sm" name="descripcion"
                                        placeholder="Descripcion de Articulo">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="seccion">Seccion *</label></strong>
                                    <select name="seccion" v-model="articulo.seccion" class="form-control form-control-sm">
                                        <option value="0">Seleccionar</option>
                                        @foreach ($secciones as $seccion)
                                            <option value="{{ $seccion['present_cod'] }}">
                                                {{ $seccion['present_descripcion'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="unidad">Unidad *</label></strong>
                                    <select name="unidad" v-model="articulo.unidad" class="form-control form-control-sm">
                                        <option value="0">Seleccionar</option>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad['uni_codigo'] }}">{{ $unidad['uni_nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong><label for="factor">Factor</label></strong>
                                    <input type="text" v-model="articulo.factor" class="form-control form-control-sm"
                                        name="factor" placeholder="Factor">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <template v-if="isVencimiento">
                <!-- STOCK CON VICIMIEMTO -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-3 ">
                                    <div class="form-group">
                                        <strong><label for="stock">Stock *</label></strong>
                                        <input type="number" onfocus="this.select()" v-model.number="stock.cantidad"
                                            class="form-control form-control-sm" name="stock" placeholder="Stock">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <strong><label for="lote">Lote</label></strong>
                                        <input type="text" v-model="stock.lotenew"
                                            class="form-control form-control-sm" name="lote" placeholder="Lote">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <strong><label for="vencimiento">Vencimiento</label></strong>
                                        <input type="date" v-model="stock.vencimiento"
                                            class="form-control form-control-sm" name="vencimiento"
                                            placeholder="Vencimiento">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <strong><label for="sucursal">Sucursal</label></strong>
                                        <select v-model="stock.sucursal" class="form-control form-control-sm">
                                            <option value="0">Seleccionar</option>
                                            <template v-for="sucursal in sucursales">
                                                <option :value="sucursal.suc_cod">@{{ sucursal.suc_desc }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    <template v-if="bandstock==0">
                                        <button class="btn btn-block btn-info" v-on:click="addStock()"><i
                                                class="fa-regular fa-box-check"></i> Agregar Stock</button>
                                    </template>
                                    <template v-else>
                                        <button class="btn btn-info btn-block" v-on:click="limpiarCamposStock()"><span
                                                class="fa fa-save"></span> Guardar Cambio</button>
                                    </template>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <strong><label>Ubicacion</label></strong>
                                    <input type="text" v-model="articulo.ubicacion"
                                        class="form-control form-control-sm" name="ubicacion" placeholder="Ubicacion">
                                </div>
                            </div>
                            <div class="form-row py-3">
                                <table class="table table-sm table-striped table-bordered table-hover">
                                    <tr>
                                        <th>Sucursal</th>
                                        <th>Stock</th>
                                        <th>Lote</th>
                                        <th>Vencimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <template v-if="stocks">
                                        <template v-for="stock in stocks">
                                            <tr>
                                                <td>@{{ getByIdSucursal(stock.sucursal) }}</td>
                                                <td>@{{ stock.cantidad }}</td>
                                                <td>@{{ stock.lotenew }}</td>
                                                <td>@{{ stock.vencimiento }}</td>
                                                <td>
                                                    <button v-on:click="editStockA(stock)"
                                                        class="btn btn-outline-success btn-sm" title="Modificar"><span
                                                            class="fa fa-edit"></span>
                                                    </button>
                                                    <button v-on:Click="delStockA(stock.id)"
                                                        class="btn btn-outline-danger btn-sm" title="Eliminar"><span
                                                            class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                    <tr>
                                        <td colspan="5">Total Stock: @{{ totalStock }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 ">
                                    <div class="form-group">
                                        <strong><label for="stock">Stock *</label></strong>
                                        <input type="number" onfocus="this.select()" v-model.number="stock.cantidad"
                                            class="form-control form-control-sm" name="stock" placeholder="Stock">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong><label for="sucursal">Sucursal</label></strong>
                                        <select v-model="stock.sucursal" class="form-control form-control-sm">
                                            <option value="0">Seleccionar</option>
                                            <template v-for="sucursal in sucursales">
                                                <option :value="sucursal.suc_cod">@{{ sucursal.suc_desc }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    <template v-if="bandstock==0">
                                        <button class="btn btn-block btn-info" v-on:click="addStock()"><i
                                                class="fa-regular fa-box-check"></i> Agregar Stock</button>
                                    </template>
                                    <template v-else>
                                        <button class="btn btn-info btn-block" v-on:click="limpiarCamposStock()"><span
                                                class="fa fa-save"></span> Guardar Cambio</button>
                                    </template>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <strong><label>Ubicacion</label></strong>
                                    <input type="text" v-model="articulo.ubicacion"
                                        class="form-control form-control-sm" name="ubicacion" placeholder="Ubicacion">
                                </div>
                            </div>
                            <div class="form-row py-3">
                                <table class="table table-sm table-striped table-bordered table-hover">
                                    <tr>
                                        <th>Sucursal</th>
                                        <th>Stock</th>

                                        <th>Acciones</th>
                                    </tr>
                                    <template v-if="stocks">
                                        <template v-for="stock in stocks">
                                            <tr>
                                                <td>@{{ getByIdSucursal(stock.sucursal) }}</td>
                                                <td>@{{ stock.cantidad }}</td>
                                                <td>
                                                    <button v-on:click="editStockA(stock)"
                                                        class="btn btn-outline-success btn-sm" title="Modificar"><span
                                                            class="fa fa-edit"></span>
                                                    </button>
                                                    <button v-on:Click="delStockA(stock.id)"
                                                        class="btn btn-outline-danger btn-sm" title="Eliminar"><span
                                                            class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                    <tr>
                                        <td colspan="5">Total Stock: @{{ totalStock }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="row">

            <template v-if="isMultiprecio">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <strong><label for="costo">Precio Compra *</label></strong>
                                        <in-number v-model="articulo.costo" placeholder="Precio Compra"
                                            @change="setPrecioVenta"></in-number>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <strong>Precio de Venta</strong>
                            <div class="form-row">
                                <div class="col border-right">
                                    <div class="form-group">
                                        <strong><label for="marge1">Utilidad % </label></strong>
                                        <input v-model="articulo.m1" onfocus="this.select()"
                                            v-on:keyup="setUtilPrecio('M',1)" type="number"
                                            class="form-control form-control-sm" name="margen1"
                                            placeholder="Margen Precio 1">
                                        <strong><label for="venta1">Precio 1 *</label></strong>
                                        <in-number v-model="articulo.p1" placeholder="Precio venta 1"
                                            @change="setUtilPrecio('P',1)"></in-number>

                                    </div>
                                </div>
                                <div class="col border-right">
                                    <div class="form-group">
                                        <strong><label for="margen2">Utilidad %</label></strong>
                                        <input v-model="articulo.m2" onfocus="this.select()"
                                            v-on:keyup="setUtilPrecio('M',2)" type="number"
                                            class="form-control form-control-sm" name="margen2"
                                            placeholder="Margen Precio 2">
                                        <strong><label for="venta2">Precio 2</label></strong>
                                        <in-number v-model="articulo.p2" placeholder="Precio venta 2"
                                            @change="setUtilPrecio('P',2)"></in-number>
                                    </div>
                                </div>
                                <div class="col border-right">
                                    <div class="form-group">
                                        <strong><label for="margen3">Utiliad %</label></strong>
                                        <input v-model="articulo.m3" onfocus="this.select()"
                                            v-on:keyup="setUtilPrecio('M',3)" type="number"
                                            class="form-control form-control-sm" name="margen3"
                                            placeholder="Margen Precio 3">
                                        <strong><label for="venta3">Precio 3</label></strong>
                                        <in-number v-model="articulo.p3" placeholder="Precio venta 3"
                                            @change="setUtilPrecio('P',3)"></in-number>
                                    </div>
                                </div>
                                <div class="col border-right">
                                    <div class="form-group">
                                        <strong><label for="margen4">Utilidad %</label></strong>
                                        <input v-model="articulo.m4" onfocus="this.select()" type="number"
                                            v-on:keyup="setUtilPrecio('M',4)" class="form-control form-control-sm"
                                            name="margen4" placeholder="Margen Precio 4">
                                        <strong><label for="venta4">Precio 4</label></strong>
                                        <in-number v-model="articulo.p4" placeholder="Precio venta 4"
                                            @change="setUtilPrecio('P',4)"></in-number>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong><label for="margen5">Utilidad %</label></strong>
                                        <input v-model="articulo.m5" onfocus="this.select()" type="number"
                                            v-on:keyup="setUtilPrecio('M',5)" class="form-control form-control-sm"
                                            name="margen5" placeholder="Margen Precio 5">
                                        <strong><label for="venta5">Precio 5</label></strong>
                                        <in-number v-model="articulo.p5" placeholder="Precio venta 5"
                                            @change="setUtilPrecio('P',5)"></in-number>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-info" @click="collapsePrecio" role="button"><i
                                    class="fa-regular fa-list-ol"></i>
                                Precios Creditos</button>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <strong><label for="costo">Precio Compra *</label></strong>
                                <in-number v-model="articulo.costo" placeholder="Precio Compra"
                                    @change="setPrecioVenta"></in-number>
                            </div>
                            <hr>
                            <strong>Precio de Venta</strong>
                            <div class="form-group">
                                <strong><label for="marge1">Margen de Utilidad % </label></strong>
                                <input v-model="articulo.m1" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',1)"
                                    type="number" class="form-control form-control-sm" name="margen1"
                                    placeholder="Margen Precio 1">
                                <strong><label for="venta1">Precio *</label></strong>
                                <in-number v-model="articulo.p1" placeholder="Precio venta 1"
                                    @change="setUtilPrecio('P',1)"></in-number>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-info" @click="collapsePrecio" role="button"><i
                                    class="fa-regular fa-list-ol"></i>
                                Precios Creditos</button>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <div class="collapse" id="collapsePrecioCredito">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <nav>
                                <div class="nav nav-tabs card-header-tabs" role="tablist">
                                    <a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#group1"
                                        aria-controls="group1" aria-select="true">PRECIO 2 - 7</a>
                                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#group2"
                                        aria-controls="group2" aria-select="false">PRECIO 8 - 13</a>
                                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#group3"
                                        aria-controls="group3" aria-select="false">PRECIO 14 - 18</a>
                                </div>
                            </nav>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!--         GRUPO 1  -->
                                <div class="tab-pane fade show active" id="group1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 2  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 2</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[0].m"
                                                    type="number" tabindex="22" v-on:keyup="setPrecio(0);setCuota(0)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="2" v-model="precios[0].p" placeholder="Precio"
                                                    @change="setMargen(0)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[0].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 3  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 3</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[1].m"
                                                    type="number" tabindex="23" v-on:keyup="setPrecio(1);setCuota(1)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="3" v-model="precios[1].p" placeholder="Precio"
                                                    @change="setMargen(1)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[1].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 4  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 4</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[2].m"
                                                    type="number" tabindex="24" v-on:keyup="setPrecio(2);setCuota(2)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="4" v-model="precios[2].p" placeholder="Precio"
                                                    @change="setMargen(2)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[2].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 5  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 5</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[3].m"
                                                    type="number" tabindex="25" v-on:keyup="setPrecio(3);setCuota(3)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="5" v-model="precios[3].p" placeholder="Precio"
                                                    @change="setMargen(3)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[3].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 6  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 6</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[4].m"
                                                    type="number" tabindex="26" v-on:keyup="setPrecio(4);setCuota(4)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="6" v-model="precios[4].p" placeholder="Precio"
                                                    @change="setMargen(4)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[4].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 7  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 7</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[5].m"
                                                    type="number" tabindex="27" v-on:keyup="setPrecio(5);setCuota(5)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="7" v-model="precios[5].p" placeholder="Precio"
                                                    @change="setMargen(5)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[5].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--         GRUPO 2  -->
                                <div class="tab-pane fade" id="group2" role="tabpanel">
                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 8  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 8</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[6].m"
                                                    type="number" tabindex="28" v-on:keyup="setPrecio(6);setCuota(6)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="8" v-model="precios[6].p" placeholder="Precio"
                                                    @change="setMargen(6)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[6].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 9  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 9</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[7].m"
                                                    type="number" tabindex="29" v-on:keyup="setPrecio(7);setCuota(7)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="9" v-model="precios[7].p" placeholder="Precio"
                                                    @change="setMargen(7)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[7].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 10  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 10</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[8].m"
                                                    type="number" tabindex="30" v-on:keyup="setPrecio(8);setCuota(8)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="10" v-model="precios[8].p" placeholder="Precio"
                                                    @change="setMargen(8)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[8].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 11  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 11</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[9].m"
                                                    type="number" tabindex="31" v-on:keyup="setPrecio(9);setCuota(9)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="11" v-model="precios[9].p" placeholder="Precio"
                                                    @change="setMargen(9)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[9].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 12  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 12</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[10].m"
                                                    type="number" tabindex="32" v-on:keyup="setPrecio(10);setCuota(10)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="12" v-model="precios[10].p" placeholder="Precio"
                                                    @change="setMargen(10)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[10].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 13  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 13</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[11].m"
                                                    type="number" tabindex="33" v-on:keyup="setPrecio(11);setCuota(11)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="13" v-model="precios[11].p" placeholder="Precio"
                                                    @change="setMargen(11)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[11].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--         GRUPO 3  -->
                                <div class="tab-pane fade" id="group3" role="tabpanel">
                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 14  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 14</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[12].m"
                                                    type="number" tabindex="34" v-on:keyup="setPrecio(12);setCuota(12)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="14" v-model="precios[12].p" placeholder="Precio"
                                                    @change="setMargen(12)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[12].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 15  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 15</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[13].m"
                                                    type="number" tabindex="35" v-on:keyup="setPrecio(13);setCuota(13)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="15" v-model="precios[13].p" placeholder="Precio"
                                                    @change="setMargen(13)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[13].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 16  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 16</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[14].m"
                                                    type="number" tabindex="36" v-on:keyup="setPrecio(14);setCuota(14)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="16" v-model="precios[14].p" placeholder="Precio"
                                                    @change="setMargen(14)"></in-number>
                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[14].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <span class="d-block mb-2">&nbsp;</span>
                                            <span class="d-block mb-3 text-right">Margen %</span>
                                            <span class="d-block mb-3 text-right">Precio</span>
                                            <span class="d-block mb-3 text-right">Cuota</span>
                                        </div>
                                        <!--  PRECIO 17  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 17</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[15].m"
                                                    type="number" tabindex="37" v-on:keyup="setPrecio(15);setCuota(15)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="17" v-model="precios[15].p" placeholder="Precio"
                                                    @change="setMargen(15)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[15].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                        <!--  PRECIO 18  -->
                                        <div class="col-3">
                                            <span class="m-2"><strong>PRECIO 18</strong></span>
                                            <div class="m-2">
                                                <input class="form-control form-control-sm" v-model="precios[16].m"
                                                    type="number" tabindex="38" v-on:keyup="setPrecio(16);setCuota(16)"
                                                    placeholder="Margen">
                                            </div>
                                            <div class="m-2">
                                                <in-number tabindex="18" v-model="precios[16].p" placeholder="Precio"
                                                    @change="setMargen(16)"></in-number>

                                            </div>
                                            <div class="m-2">
                                                <in-number v-model="precios[16].c" placeholder="Cuota"></in-number>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <label><input type="checkbox" v-model="chprecio" name="precio"> Redondear Precio</label>
                            <label><input type="checkbox" v-model="chcuota" name="cuota"> Redondear Cuota</label>
                            <button @click="cerrarPrecios" class="btn btn-success"><span class="fa fa-save"></span>
                                Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/separator.js') }}"></script>
    <script src="{{ asset('js/bootstrap4-toggle.min.js') }}"></script>
    <script>
        const defaultStock = {
            'id': 0,
            'cantidad': 0,
            'loteold': '',
            'lotenew': '',
            'vencimiento': '',
            'sucursal': 1
        };
        const defaultPrecio = [{
            p: 50,
            m: 5,
            c: 2
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }];
        const defaultArticulo = {
            'codigo': '',
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
                isVencimiento: true,
                isMultiprecio: true,
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

                articulo: {
                    ...defaultArticulo
                },
                stock: {
                    ...defaultStock
                },
                stocks: [],
                error: '',
                cantidadStock: 0,
                frmt: {
                    i: -1,
                    t: false,
                    suc: 0,
                    cant: 0
                },
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

                setMargen: function(index) {
                    if (this.viewPrecio) {
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
                    if (this.viewPrecio) {
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

                    var retornar = parseInt((this.articulo.costo * parseInt(this.precios[index].m)) / 100 + this
                        .articulo.costo)
                    if (this.chprecio)
                        this.precios[index].p = this.redondear(retornar);
                    else
                        this.precios[index].p = retornar; //parseInt(retornar) 

                },
                setCuota: function(index) {
                    if (this.viewPrecio) {
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

                verPreciosCredito: function(cod, costo) {
                    this.viewPrecio = true;
                    this.articulo.costo = costo;
                    this.getPrecios(cod);


                },
                collapsePrecio: function() {
                    if (this.articulo.costo > 0) {
                        $('#collapsePrecioCredito').collapse('toggle');
                    } else {
                        Swal.fire('Atención...', 'Agregue precio de compra', 'info');
                    }

                },
                cerrarPrecios: function() {
                    if (!this.viewPrecio) {
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

                getArticuloById: function(id) {
                    let a = ''
                    axios.put('{{ env('APP_URL') }}' + 'articulo/id', {
                            codigo: id
                        })
                        .then(response => {
                            a = response.data;
                        
                            this.setArticulo(a);
                            this.getStock(a.ARTICULOS_cod);
                            this.getPrecios(a.ARTICULOS_cod);
                            this.reservarC = false;
                            
                            this.viewPrecio = false;

                            setTimeout(function() {
                                $('input[name="descripcion"]').focus();

                            }, 500);
                        })
                        .catch(e => {

                            this.error = e.message;
                            return false;
                        })
                    //const a = this.articulos[this.articulos.findIndex(e => e.ARTICULOS_cod == id)];

                },
                setArticulo: function(a) {
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
                duplicar: function(id) {
                    const articulo = this.articulos[this.articulos.findIndex(e => e.ARTICULOS_cod == id)];
                    this.isnew = true;
                    this.viewPrecio = false;
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
                    this.stock = {
                        ...defaultStock
                    };
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
                            axios.delete('{{ env('APP_URL') }}' + 'articulo/res/' + id)
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
                        axios.delete('{{ env('APP_URL') }}' + 'articulo/res/' + this.articulo.codigo)
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
                        axios.post('{{ env('APP_URL') }}' + 'stock/' + this.articulo.codigo, {
                            stock: this.stocks
                        }).then(r => {
                            this.cancelTrans();
                        }).catch(e => {
                            this.error = e.message;
                        })
                    }
                },
                saveArticulo: function() {
                    if (this.articulo.descripcion && this.articulo.costo && this.articulo.p1) {
                        // this.validar_Cbarra();
                        if (this.stocks.length < 1) {
                            this.stocks.push(defaultStock);
                        }
                        this.error = "";
                        if (this.isnew) {
                            axios.post('{{ env('APP_URL') }}' + 'articulo', {
                                    articulo: this.articulo,
                                    stock: this.stocks,
                                    precios: this.precios
                                })
                                .then(r => {

                                    // RETORNAR PAGINA ANTERIOR
                                    window.location.assign('{{ url()->previous() }}');

                                })
                                .catch(e => {
                                    this.error = e.message;
                                })
                        } else {
                            axios.put('{{ env('APP_URL') }}' + 'articulo/' + this.articulo.codigo, {
                                    articulo: this.articulo,
                                    stock: this.stocks,
                                    precios: this.precios
                                })
                                .then(r => {
                                    window.location.assign('{{ url()->previous() }}');

                                    //RETORNAR PAGINA ANTERIOR
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
                    axios.get('{{ env('APP_URL') }}' + 'articulo/buscar')
                        .then(response => {
                            this.articulos = response.data.articulos.data;
                            this.datos = 'T';
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getStock: function(id) {
                    axios.get('{{ env('APP_URL') }}' + 'stock/' + id).then(r => {
                        this.stocks = r.data;
                    }).catch(e => {
                        this.error = e.message;
                    })
                },
                getPrecios: function(id) {
                    axios.get('{{ env('APP_URL') }}' + 'articulo/precios/' + id).then(response => {
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
                    axios.post('{{ env('APP_URL') }}' + 'articulo/res', {
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
                    axios.get('{{ env('APP_URL') }}' + 'articulo/ultimo').then(r => {
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
                    var url = '{{ env('APP_URL') }}' + 'sucursal/all';
                    axios.get(url)
                        .then(response => {
                            this.sucursales = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                validar_codigo_de_barra: function() {
                    if (this.articulo.c_barra.length > 0 && this.isnew) {
                        axios.get('{{ env('APP_URL') }}' + 'articulo/validar/cbarra/' + this.articulo.c_barra)
                            .then(response => {
                                if (response.data == '1') {
                                    Swal.fire('Atención',
                                        'El codigo de barra ya esta registrado en la base de datos',
                                        'warning');
                                    this.articulo.c_barra = '';
                                }
                            })
                            .catch(e => {
                                this.error = e.message;
                            })
                    }
                },
                _saveSettingPreferences: function() {
                    localStorage.setItem('is_vencimiento', this.isVencimiento);
                    localStorage.setItem('is_multiprecio', this.isMultiprecio);

                },
                _loadSettingPreferences: function() {

                    this.isVencimiento = localStorage.getItem('is_vencimiento') === 'true' ?? true;
                    this.isMultiprecio = localStorage.getItem('is_multiprecio') === 'true' ?? true;

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
                this._loadSettingPreferences();
                this.getSucursal();
                @if (isset($id))
                    this.getArticuloById({{ $id }});
                    this.isnew = false;
                @else
                setTimeout(function() {
                    $('input[name="cbarraN"]').focus();

                }, 500);
                @endif
                
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
