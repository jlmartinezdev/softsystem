@extends('layouts.app')
@section('title', 'Articulo')
@section('style')
    <style>
        .vgt-table tr {
            font-family: Arial, Helvetica, sans-serif;
        }

        .vgt-table td {
            color: rgb(8, 8, 8);
        }
    </style>
@endsection
@section('main')
    <div class="container" id="app">
        <div class="font-weight-bold" style="font-size: 14pt;">Inventario</div>
        <div class="card">
            <div class="card-body">
                <div class="row">


                    <div class="col-md-3 col-sm-6 pr-2">
                        <strong><label for="desde">Desde: </label></strong>
                        <input type="date" class="form-control form-control-sm " v-model="fecha.desde" name="desde"
                            placeholder="Desde Fecha">
                    </div>
                    <div class="col-md-3 col-sm-6 pr-2">
                        <strong><label for="hasta">Hasta: </label></strong>
                        <input type="date" class="form-control form-control-sm" v-model="fecha.hasta" name="hasta"
                            placeholder="Hasta Fecha">
                    </div>
                    <div class="col-md-2 col-sm-6 pr-2">
                        <label for="hasta">&nbsp;</label>
                        <select class="form-control form-control-sm" @click="buscar()" v-model="seccion">
                            <option value="0">Todos</option>
                            @foreach ($secciones as $seccion)
                                <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-3 pr-2">
                        <label for="hasta">&nbsp;</label>
                        <button class="btn btn-primary btn-sm btn-block" @click="buscar()">
                            <span class="fa fa-search"></span> Buscar
                    </div>
                    <div class="col-12 col-md-2 col-sm-3 d-flex align-items-end pt-2">
                        <div class="btn-group btn-group-sm btn-block">
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
                <vue-good-table :columns="columns" :rows="rows" style-class="vgt-table striped">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.row.stock=='0'">
                            <span style="color: rgb(226, 0, 0);">
                                <span v-if="!props.column.html">
                                    @{{ props.formattedRow[props.column.field] }}
                                </span>
                                <span v-else v-html="props.row[props.column.field]">
                                </span>

                            </span>
                        </span>

                    </template>
                </vue-good-table>
            </div>
        </template>

    </div>
@endsection
@section('script')
    <script src="{{ asset('js/separator.js') }}"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                fecha: {
                    desde: '2020-01-01',
                    hasta: '2020-01-01'
                },
                seccion: 0,
                columns: [{
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
                        label: 'Entrada',
                        field: 'entrada',
                        type: 'number'
                    },
                    {
                        label: 'Salida',
                        field: 'salida',
                        type: 'number',
                    }

                ],
                rows: [],
                articulos: [],
            },

            methods: {
                buscar: function() {
                    this.requestSend = true;

                    axios.get('inventario/fecha', {
                            params: {
                                desde: this.fecha.desde,
                                hasta: this.fecha.hasta,
                                seccion: this.seccion,
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            if (response.data == 'NO') {
                                Swal.fire('No se encontrado resultado!', 'Para:  ' + this.txtbuscar,
                                    'info');
                            } else {
                                this.rows = [];
                                this.articulos = response.data;
                                for (let i = 0; i < response.data.length; i++) {
                                    const item = {
                                        codigo: this.articulos[i].producto_c_barra,
                                        descripcion: this.articulos[i].producto_nombre,
                                        seccion: this.articulos[i].present_descripcion,
                                        precio: this.separador(this.articulos[i].pre_venta1),
                                        stock: this.articulos[i].cantidad,
                                        entrada: this.articulos[i].entrada == null ? 0 : parseInt(this.articulos[i].entrada),
                                        salida: this.articulos[i].salida == null ? 0 : parseInt(this.articulos[i].salida),
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

                separador: function(number) {
                    var n = parseFloat(number);
                    return new Intl.NumberFormat().format(n);
                },

                getByIdSucursal: function(id) {
                    const suc = this.sucursales.find(sucursal => sucursal.suc_cod == id);
                    return suc.suc_desc;
                },
                exportar: function(tipo) {
                    if (this.articulos.length < 1) {
                        return false;
                    }
                    if (tipo == "stock") {
                        let params = {
                            page: 0,
                            buscar: this.txtbuscar,
                            criterio: 0,
                            seccion: this.filtro.seccion,
                            col: this.filtro.columna,
                            ord: this.filtro.orden,
                            suc: ''
                        }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulos?' + u);
                    }
                    if (tipo == "precios") {
                        let params = {
                            page: 0,
                            buscar: this.txtbuscar,
                            criterio: 0,
                            seccion: this.filtro.seccion,
                            col: this.filtro.columna,
                            ord: this.filtro.orden,
                            suc: ''
                        }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulosprecios?' + u);
                    }
                },
                getFecha: function(flag) {

                    var f = new Date();
                    var dia = flag == 1 ? 1 : f.getDate();
                    var mes = (f.getMonth() + 1);
                    return f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" + dia.toString()
                        .padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
            },
            mounted() {
                this.fecha = {
                    desde: this.getFecha(1),
                    hasta: this.getFecha(0)
                };
                //this.buscar();
                //this.getSucursal();
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
