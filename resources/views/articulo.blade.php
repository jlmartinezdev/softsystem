@extends('layouts.app')
@section('title', 'Articulo')
@section('style')
<link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
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
    .vgt-table tr{
        font-family: Arial, Helvetica, sans-serif;
    }
    .vgt-table td{
        color: rgb(8, 8, 8);
    }
    .modal-dialog-rigth {
    position: fixed;
    margin: auto;
    width: 360px;
    height: 100%;
    right: 0px;
}
.modal-content {
    height: 100%;
}
.autocomplete-input {
  border: 1px solid #eee;
  border-radius: 8px;
  width: 100%;
  padding: 7px 7px 7px 48px;
  box-sizing: border-box;
  position: relative;
  font-size: 16px;
  line-height: 1.5;
  flex: 1;
  background-color: #eee;
  background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNjY2IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PGNpcmNsZSBjeD0iMTEiIGN5PSIxMSIgcj0iOCIvPjxwYXRoIGQ9Ik0yMSAyMWwtNC00Ii8+PC9zdmc+");
  background-repeat: no-repeat;
  background-position: 12px;
}
.autocomplete-input:focus,
.autocomplete-input[aria-expanded="true"] {
  border-color: #17a2b8;
  background-color: #fff;
  outline: none;
  /* box-shadow: 0 2px 2px rgba(0, 0, 0, .16)*/
}
.dark-mode .autocomplete-input {
  border-color: rgba(0, 0, 0, 0.12);
  color: white;
  background-color: #343a40;
}
.articulo-toolbar > .card-body {
  padding-top: 0.85rem !important;
  padding-bottom: 0.45rem !important;
}
.articulo-toolbar .articulo-acciones {
  line-height: 1.2;
}
.articulo-toolbar .articulo-acciones .btn {
  padding: 0 0.4rem;
  line-height: 1.2;
  vertical-align: middle;
}
#modalPromo .modal-content {
  height: auto;
}
#modalPromo .promo-item {
  border: 1px solid #dee2e6;
  border-radius: 0.35rem;
  padding: 0.65rem 0.75rem;
  margin-bottom: 0.5rem;
}
#modalPromo .promo-item-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
}
#modalPromo .promo-item-title {
  flex: 1 1 auto;
  min-width: 0;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.35rem;
}
#modalPromo .promo-item-title strong {
  line-height: 1.2;
}
#modalPromo .promo-item-actions {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  white-space: nowrap;
}
#modalPromo .promo-item-actions .badge {
  margin: 0;
  vertical-align: middle;
}
#modalPromo .promo-item-actions .btn {
  padding: 0.15rem 0.4rem;
  line-height: 1;
}
#modalPromo .promo-item-body {
  margin-top: 0.4rem;
}
#modalPromo .promo-item-body ul {
  margin-bottom: 0;
  padding-left: 1.1rem;
}
</style>
@endsection
@section('main')
    <div class="container" id="app">
        <div class="py-2"  ><span class="font-weight-bold" style="font-size: 18pt;">Productos</span><template><span class="pl-3">@{{articulos.length}} articulos registrados</span></template></div>
        <div class="card shadow-sm articulo-toolbar">
            <div class="card-body px-3">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-8">
                        <input
                        type="text"
                        v-model="txtbuscar"
                        placeholder="Articulo o Codigo"
                        @keyup.enter="buscar(false)"
                        class="autocomplete-input"
                      />
                    </div>
                    <div class="col-sm-12 col-md-4 mt-2 mt-md-0">
                        <a href="{{ route('articulo.cm')}}" class="btn btn-info btn-block" ><span class="fa-regular fa-plus"></span>
                            Producto</a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 articulo-acciones">
                        <button :class="[filtro.seccion=='0' ? 'btn btn-link text-secondary': 'btn btn-outline-light text-primary']" data-toggle="modal" data-target="#modalfiltro"><i :class="[filtro.seccion=='0' ? 'fa-regular fa-filter':'fa-solid fa-filter text-primary']"></i> Filtro</button>
                        <button class="btn btn-link text-secondary"><i class="fa-regular fa-folder"></i> Seccion</button>
                        <button class="btn btn-link text-secondary" data-toggle="modal" data-target="#modalexportar"><i class="fa-regular fa-upload"></i> Exportar</button>
                        <a href="{{ route('combo.index') }}" class="btn btn-link text-secondary"><i class="fa fa-layer-group"></i> Combos</a>
                        <a href="{{ route('oferta.index') }}" class="btn btn-link text-secondary"><i class="fa fa-tags"></i> Ofertas</a>
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
                      <span  style="color: rgb(226, 0, 0);">
                        <span v-if="!props.column.html">
                            @{{props.formattedRow[props.column.field]}}
                        </span>
                        <span  v-else v-html="props.row[props.column.field]">
                        </span>
                       
                    </span> 
                    </span>
                    
                  </template>
                </vue-good-table>
                  
              </div>

        </template>
        

        
        
        @include('articulo.delete')
        @include('articulo.detalle')
        @include('articulo.precio')
        <div class="modal fade" id="modalfiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-rigth" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  Filtrar
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <div class="modal-body">
                  
                  <table class="table table-hover table-borderless">
                    <tr>
                        <td>
                            <div class="icheck-primary d-inline">
                            <input type="radio" id="r1" name="r1"  v-model="filtro.seccion" value="0">
                            <label for="r1">
                                TODAS LAS SECCIONES
                            </label>
                            </div>
                        </td>
                    </tr>
                     
                    @foreach ($secciones as $seccion)
                        <tr>
                            <td>
                                <div class="icheck-primary d-inline">
                                <input type="radio" id="{{ $seccion['present_cod'] }}"  v-model="filtro.seccion" name="r1" value="{{ $seccion['present_cod'] }}">
                                <label for="{{ $seccion['present_cod'] }}">
                                    {{ $seccion['present_descripcion'] }}
                                </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                       
                    

                  </table>
                  
                </div>
                <div class="modal-footer">
                  <button class="btn btn-info"  data-dismiss="modal" @click="buscar(false)">Filtrar</button>
              </div>
              </div>

            </div>
        </div>
        <div class="modal fade" id="modalseccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  Filtrar
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <div class="modal-body">
                   <!--select class="form-control" @@click="buscar(false)" v-model="filtro.seccion">
                            <option value="0">Todos</option>
                            @@foreach ($secciones as $seccion)
                                <option value="{ $seccion['present_cod'] }}">{ $seccion['present_descripcion'] }}
                                </option>
                            @@endforeach
                        </select -->
                </div>
                <div class="modal-footer">
                  <button class="btn btn-info">Filtrar</button>
              </div>
              </div>

            </div>
        </div>
        <div class="modal fade" id="modalexportar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-dialog-rigth" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  Exportar
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action" @click="exportar('stock')"><i class="fa-solid fa-file-excel text-success"></i> Con stock</button>
                    <button type="button" class="list-group-item list-group-item-action" @click="exportar('precios')"><i class="fa-solid fa-file-excel text-success"></i> Precio Credito</button>
                </div>
                
              </div>

            </div>
        </div>

        <div class="modal fade" id="modalPromo" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="height:auto;">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0">
                            Promo — @{{ promoDetalle.articulo.nombre || '…' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="promoDetalle.cargando" class="text-center py-4 text-muted">
                            <i class="fa fa-spinner fa-spin"></i> Cargando…
                        </div>
                        <template v-else>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-muted small mb-0" v-if="promoDetalle.articulo.precio">
                                    Precio lista: <strong>@{{ separador(promoDetalle.articulo.precio) }}</strong>
                                    <span v-if="promoDetalle.articulo.c_barra" class="ml-2">· Cod: @{{ promoDetalle.articulo.c_barra }}</span>
                                </p>
                                <button type="button" class="btn btn-sm btn-outline-dark"
                                    @click="imprimirEtiqueta('articulo')"
                                    title="Imprimir etiqueta del artículo">
                                    <i class="fa fa-print"></i> Etiqueta
                                </button>
                            </div>

                            <h6 id="promo-ofertas" class="text-danger"><i class="fa fa-tag"></i> Ofertas</h6>
                            <div v-if="!promoDetalle.ofertas.length" class="text-muted small mb-3">Sin ofertas.</div>
                            <div v-for="o in promoDetalle.ofertas" :key="'of-'+o.id" class="promo-item">
                                <div class="promo-item-head">
                                    <div class="promo-item-title">
                                        <strong>@{{ o.nombre }}</strong>
                                        <span class="badge" :class="o.activo ? 'badge-success' : 'badge-secondary'">
                                            @{{ o.activo ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                    <div class="promo-item-actions">
                                        <span class="badge badge-danger">@{{ labelDescOferta(o) }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-dark"
                                            @click="imprimirEtiqueta('oferta', o)" title="Imprimir etiqueta oferta">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="promo-item-body">
                                    <div class="small text-muted">
                                        Tipo: @{{ labelTipoOferta(o.tipo) }}
                                        <span v-if="o.cantidad_min"> · Desde @{{ o.cantidad_min }} unid.</span>
                                        <span v-if="o.fecha_desde || o.fecha_hasta">
                                            · @{{ o.fecha_desde || '…' }} → @{{ o.fecha_hasta || '…' }}
                                        </span>
                                    </div>
                                    <div class="small mt-1">
                                        Precio con oferta: <strong>@{{ separador(o.precio_final) }}</strong>
                                    </div>
                                    <div class="small text-muted" v-if="o.observacion">@{{ o.observacion }}</div>
                                </div>
                            </div>

                            <h6 id="promo-combos" class="text-info mt-3"><i class="fa fa-layer-group"></i> Combos</h6>
                            <div v-if="!promoDetalle.combos.length" class="text-muted small">Sin combos.</div>
                            <div v-for="c in promoDetalle.combos" :key="'cb-'+c.id" class="promo-item">
                                <div class="promo-item-head">
                                    <div class="promo-item-title">
                                        <strong>@{{ c.nombre }}</strong>
                                        <span class="badge" :class="c.activo ? 'badge-success' : 'badge-secondary'">
                                            @{{ c.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <span class="text-muted small" v-if="c.codigo">(@{{ c.codigo }})</span>
                                    </div>
                                    <div class="promo-item-actions">
                                        <span class="badge badge-info">@{{ separador(c.precio) }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-dark"
                                            @click="imprimirEtiqueta('combo', c)" title="Imprimir etiqueta combo">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="promo-item-body">
                                    <div class="small text-muted" v-if="c.precio_lista">
                                        Precio lista: @{{ separador(c.precio_lista) }}
                                    </div>
                                    <ul class="small mt-1">
                                        <li v-for="(it, idx) in c.items" :key="'it-'+c.id+'-'+idx">
                                            @{{ it.cantidad }} × @{{ it.nombre || ('#' + it.articulos_cod) }}
                                        </li>
                                    </ul>
                                    <div class="small text-muted mt-1" v-if="c.observacion">@{{ c.observacion }}</div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark"
                            :disabled="promoDetalle.cargando"
                            @click="imprimirEtiqueta('articulo')">
                            <i class="fa fa-print"></i> Imprimir etiqueta
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/separator.js') }}"></script>
    <script>
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
                articulo: {},
                stock: {},
                stocks: [],
                error: '',
                cantidadStock: 0,
                frmt: {
                    i: -1,
                    t: false,
                    suc: 0,
                    cant: 0
                },
                promoDetalle: {
                    cargando: false,
                    articulo: {},
                    ofertas: [],
                    combos: [],
                    foco: null
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
                    label: 'Promo',
                    field: 'promo',
                    html: true,
                    sortable: false,
                    width: '130px'
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

            methods: {
                busqueda_tabla: function(row, col, cellValue, searchTerm){
                    
                    if(!isNaN(searchTerm) && searchTerm.length>5){
                        
                        if(row.codigo.includes(searchTerm)){
                            return cellValue;
                        } 
                    }else{
                        if(row.descripcion.toUpperCase().includes(searchTerm.toUpperCase())){
                            return cellValue;
                        } 
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
                verPromo: function(cod, foco) {
                    this.promoDetalle = {
                        cargando: true,
                        articulo: {},
                        ofertas: [],
                        combos: [],
                        foco: foco || null
                    };
                    $('#modalPromo').modal('show');
                    axios.get('articulo/promo/' + cod)
                        .then(response => {
                            const d = response.data || {};
                            this.promoDetalle = {
                                cargando: false,
                                articulo: d.articulo || {},
                                ofertas: d.ofertas || [],
                                combos: d.combos || [],
                                foco: foco || null
                            };
                            this.$nextTick(function () {
                                if (foco === 'combo') {
                                    var el = document.getElementById('promo-combos');
                                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                } else if (foco === 'oferta') {
                                    var el2 = document.getElementById('promo-ofertas');
                                    if (el2) el2.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            });
                        })
                        .catch(e => {
                            this.promoDetalle.cargando = false;
                            $('#modalPromo').modal('hide');
                            Swal.fire('Error', e.response && e.response.data && e.response.data.message
                                ? e.response.data.message
                                : (e.message || 'No se pudo cargar'), 'error');
                        });
                },
                labelTipoOferta: function(tipo) {
                    if (tipo === 'cantidad') return 'Por cantidad';
                    if (tipo === 'fecha') return 'Por fecha';
                    if (tipo === 'ambos') return 'Cantidad + fecha';
                    return tipo || '—';
                },
                labelDescOferta: function(o) {
                    if (!o) return '';
                    const v = o.descuento_valor;
                    if (o.descuento_tipo === 'porcentaje') return v + '% off';
                    if (o.descuento_tipo === 'monto') return '-' + this.separador(v);
                    if (o.descuento_tipo === 'precio_fijo') return 'Precio ' + this.separador(v);
                    return String(v);
                },
                imprimirEtiqueta: function(tipo, item) {
                    var art = this.promoDetalle.articulo || {};
                    var titulo = art.nombre || '';
                    var codigo = art.c_barra || '';
                    var precioLista = art.precio || 0;
                    var precio = precioLista;
                    var badge = '';
                    var extra = '';

                    if (tipo === 'oferta' && item) {
                        precio = item.precio_final;
                        badge = 'OFERTA';
                        extra = item.nombre || '';
                        if (item.codigo) {
                            codigo = item.codigo;
                        }
                        if (item.cantidad_min) {
                            extra += (extra ? ' · ' : '') + 'Desde ' + item.cantidad_min + ' unid.';
                        }
                    } else if (tipo === 'combo' && item) {
                        titulo = item.nombre || titulo;
                        codigo = item.codigo || codigo || ('COMBO-' + item.id);
                        precio = item.precio;
                        precioLista = item.precio_lista || precioLista;
                        badge = 'COMBO';
                        if (item.items && item.items.length) {
                            extra = item.items.map(function (it) {
                                return (it.cantidad || 1) + '× ' + (it.nombre || ('#' + it.articulos_cod));
                            }).join(' · ');
                        }
                    } else {
                        var ofertaActiva = (this.promoDetalle.ofertas || []).find(function (o) { return parseInt(o.activo) === 1; });
                        if (ofertaActiva) {
                            precio = ofertaActiva.precio_final;
                            badge = 'OFERTA';
                            extra = ofertaActiva.nombre || '';
                            if (ofertaActiva.codigo) {
                                codigo = ofertaActiva.codigo;
                            }
                        }
                    }

                    var html = this._htmlEtiqueta({
                        titulo: titulo,
                        codigo: codigo,
                        precio: precio,
                        precioLista: precioLista,
                        badge: badge,
                        extra: extra,
                        barcodeSvg: this._barcodeSvg(codigo)
                    });

                    var w = window.open('', '_blank', 'width=480,height=580');
                    if (!w) {
                        Swal.fire('Atención', 'Permití ventanas emergentes para imprimir la etiqueta', 'info');
                        return;
                    }
                    w.document.open();
                    w.document.write(html);
                    w.document.close();
                },
                _barcodeSvg: function(text) {
                    text = String(text || '').trim();
                    if (!text) return '';

                    // CODE128B patterns (bar/space widths)
                    var patterns = [
                        '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213',
                        '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132',
                        '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211',
                        '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313',
                        '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331',
                        '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111',
                        '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214',
                        '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111',
                        '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141',
                        '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141',
                        '114131','311141','411131','211412','211214','211232','2331112'
                    ];

                    var start = 104; // Code B
                    var stop = 106;
                    var codes = [start];
                    var checksum = start;

                    for (var i = 0; i < text.length; i++) {
                        var v = text.charCodeAt(i) - 32;
                        if (v < 0 || v > 95) {
                            v = ('?').charCodeAt(0) - 32;
                        }
                        codes.push(v);
                        checksum += v * (i + 1);
                    }
                    codes.push(checksum % 103);
                    codes.push(stop);

                    var module = 1.6;
                    var height = 36;
                    var x = 0;
                    var bars = '';
                    for (var c = 0; c < codes.length; c++) {
                        var pat = patterns[codes[c]];
                        if (!pat) continue;
                        for (var p = 0; p < pat.length; p++) {
                            var w = parseInt(pat.charAt(p), 10) * module;
                            if (p % 2 === 0) {
                                bars += '<rect x="' + x.toFixed(2) + '" y="0" width="' + w.toFixed(2) + '" height="' + height + '" fill="#000"/>';
                            }
                            x += w;
                        }
                    }

                    var width = Math.ceil(x);
                    return '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height +
                        '" viewBox="0 0 ' + width + ' ' + height + '" role="img" aria-label="barcode">' +
                        bars + '</svg>';
                },
                _htmlEtiqueta: function(d) {
                    var formatMiles = function (n) {
                        var x = Math.round(Number(n) || 0).toString();
                        return x.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    };
                    var precioFmt = formatMiles(d.precio);
                    var listaFmt = formatMiles(d.precioLista);
                    var showTachado = d.badge && d.precioLista && Number(d.precioLista) > Number(d.precio);
                    var titulo = String(d.titulo || '').trim().replace(/</g, '&lt;');
                    var codigoRaw = String(d.codigo || '').trim();
                    var codigo = codigoRaw.replace(/</g, '&lt;');
                    var extra = String(d.extra || '').replace(/</g, '&lt;');
                    var badge = String(d.badge || '').toUpperCase();
                    var badgeClass = badge === 'COMBO' ? 'badge-combo' : (badge === 'OFERTA' ? 'badge-oferta' : 'badge-plain');
                    var badgeHtml = badge
                        ? '<span class="badge ' + badgeClass + '">' + badge + '</span>'
                        : '<span class="badge badge-plain">PRODUCTO</span>';
                    var listaHtml = showTachado
                        ? '<div class="precio-antes"><span class="lbl">Antes</span> <span class="val">Gs. ' + listaFmt + '</span></div>'
                        : '';
                    var extraHtml = extra
                        ? '<div class="extra">' + extra + '</div>'
                        : '';
                    var barcodeSvg = d.barcodeSvg || '';

                    return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Etiqueta</title>' +
                        '<style>' +
                        '@@page{size:70mm 50mm;margin:0}' +
                        '*{box-sizing:border-box}' +
                        'html,body{margin:0;padding:0}' +
                        'body{font-family:"Segoe UI",Arial,Helvetica,sans-serif;color:#111;background:#f3f4f6;' +
                        'min-height:100vh;padding:12px;display:flex;flex-direction:column;align-items:center;gap:12px}' +
                        '.toolbar{display:flex;gap:8px;align-items:center}' +
                        '.toolbar button{border:0;border-radius:6px;padding:8px 14px;font-size:14px;font-weight:600;cursor:pointer}' +
                        '.btn-print{background:#111;color:#fff}' +
                        '.btn-close{background:#e5e7eb;color:#111}' +
                        '.etiqueta{width:64mm;min-height:42mm;background:#fff;border:1.5px solid #222;border-radius:2mm;' +
                        'padding:2.5mm 3mm;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 8px rgba(0,0,0,.08)}' +
                        '.head{display:flex;align-items:center;justify-content:space-between;gap:2mm;margin-bottom:1.5mm}' +
                        '.badge{display:inline-block;font-size:8px;font-weight:800;letter-spacing:.6px;' +
                        'padding:1.5px 5px;border-radius:2px;line-height:1.2;text-transform:uppercase}' +
                        '.badge-oferta{background:#c62828;color:#fff}' +
                        '.badge-combo{background:#1565c0;color:#fff}' +
                        '.badge-plain{background:#333;color:#fff}' +
                        '.codigo{font-size:8px;color:#555;font-weight:600;letter-spacing:.3px}' +
                        '.titulo{font-size:12px;font-weight:700;line-height:1.25;text-transform:capitalize;' +
                        'max-height:2.6em;overflow:hidden;margin:0 0 1.5mm}' +
                        '.precios{margin:1mm 0}' +
                        '.precio-antes{font-size:9px;color:#777;margin-bottom:0.5mm;display:flex;align-items:center;gap:1.5mm}' +
                        '.precio-antes .lbl{font-weight:600;color:#888}' +
                        '.precio-antes .val{text-decoration:line-through}' +
                        '.precio-ahora{display:flex;align-items:baseline;gap:1.5mm;line-height:1}' +
                        '.precio-ahora .moneda{font-size:11px;font-weight:700;color:#333}' +
                        '.precio-ahora .monto{font-size:22px;font-weight:800;letter-spacing:-0.6px}' +
                        '.extra{font-size:8px;color:#555;line-height:1.25;margin-top:1.5mm;' +
                        'max-height:2.5em;overflow:hidden;border-top:1px dashed #bbb;padding-top:1.2mm}' +
                        '.bc-wrap{text-align:center;margin-top:1.5mm;padding-top:1.2mm;border-top:1px solid #ddd}' +
                        '.bc-wrap svg{max-width:100%;height:36px}' +
                        '.bc-text{font-size:9px;letter-spacing:1.2px;color:#222;margin-top:0.8mm;font-weight:600}' +
                        '.bc-missing{font-size:9px;color:#999;padding:2mm 0}' +
                        '@@media print{' +
                        'body{background:#fff;padding:0;min-height:auto;display:block}' +
                        '.toolbar{display:none!important}' +
                        '.etiqueta{width:100%;min-height:45mm;border-radius:0;box-shadow:none;margin:0}' +
                        '}' +
                        '</style></head><body>' +
                        '<div class="toolbar">' +
                        '<button type="button" class="btn-print" onclick="window.print()">Imprimir etiqueta</button>' +
                        '<button type="button" class="btn-close" onclick="window.close()">Cerrar</button>' +
                        '</div>' +
                        '<div class="etiqueta">' +
                        '<div>' +
                        '<div class="head">' + badgeHtml + (codigo ? '<span class="codigo">' + codigo + '</span>' : '') + '</div>' +
                        '<div class="titulo">' + titulo + '</div>' +
                        '<div class="precios">' + listaHtml +
                        '<div class="precio-ahora"><span class="moneda">Gs.</span><span class="monto">' + precioFmt + '</span></div>' +
                        '</div>' +
                        extraHtml +
                        '</div>' +
                        (codigo && barcodeSvg
                            ? '<div class="bc-wrap">' + barcodeSvg + '<div class="bc-text">' + codigo + '</div></div>'
                            : '<div class="bc-wrap"><div class="bc-missing">Sin código de barras</div></div>') +
                        '</div>' +
                        '</body></html>';
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
                                    let badges = '';
                                    const codArt = this.articulos[i].ARTICULOS_cod;
                                    if (parseInt(this.articulos[i].tiene_oferta || 0) > 0) {
                                        badges += `<span class="badge badge-danger mr-1 badge-promo" style="cursor:pointer" onclick="app.verPromo(${codArt},'oferta')" title="Ver ofertas">OFERTA</span>`;
                                    }
                                    if (parseInt(this.articulos[i].en_combo || 0) > 0) {
                                        badges += `<span class="badge badge-info mr-1 badge-promo" style="cursor:pointer" onclick="app.verPromo(${codArt},'combo')" title="Ver combos">COMBO</span>`;
                                    }
                                    const item = {
                                        codigo : this.articulos[i].producto_c_barra== null ? '' : this.articulos[i].producto_c_barra,
                                        descripcion: this.articulos[i].producto_nombre,
                                        seccion: this.articulos[i].present_descripcion,
                                        promo: badges || '<span class="text-muted">—</span>',
                                        precio : this.separador(this.articulos[i].pre_venta1),
                                        stock : this.articulos[i].cantidad,
                                        opciones : `<div class="btn-group">
                                        <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="fa fa-bars"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class='dropdown-item' href='{{env("APP_URL")}}articulo/cm/${this.articulos[i].ARTICULOS_cod}'><span
                                                    class="fa fa-edit text-primary"></span> Editar</a>
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
                },
                validar_codigo_de_barra: function(){
                    if(this.articulo.c_barra.length > 0){
                        axios.get('articulo/validar/cbarra/'+this.articulo.c_barra)
                        .then(response => {
                            if(response.data == '1'){
                                Swal.fire('Atención','El codigo de barra ya esta registrado en la base de datos','warning');
                                this.articulo.c_barra = '';
                            }
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
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
