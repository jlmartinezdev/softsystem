<template>
  <div class="buscador-catalogo">
    <!-- Navbar / Barra de búsqueda rápida con atajos -->
    <nav class="navbar navbar-expand buscador-navbar px-2 py-1 border rounded">
      <ul class="navbar-nav w-100">
        <li class="nav-item w-100">
          <Searcharticulo
            ref="search"
            :url="url"
            :idsucursal="idsucursal"
            :validar-lote="validarLote"
            :route-articulo="routeArticulo"
            :is-ready-balance="isReadyBalance"
            :placeholder="scanPlaceholder"
            @articulo="$emit('articulo', $event)"
            @peso="$emit('peso', $event)"
          />
        </li>
      </ul>
      <ul class="navbar-nav flex-row">
        <slot name="actions-before"></slot>
        <!-- Mostrar botón si no hay acciones personalizadas después -->
        <li class="nav-item" v-if="!$slots['actions-after'] && !$scopedSlots['actions-after']">
          <a href="#" class="nav-link" title="Catálogo con imágenes" @click.prevent="abrirCatalogo">
            <i class="fa fa-th"></i>
          </a>
        </li>
        <slot name="actions-after"></slot>
      </ul>
    </nav>

    <!-- Modal Catálogo Moderno -->
    <div
      class="modal fade catalogo-modal"
      :id="modalId"
      tabindex="-1"
      role="dialog"
      :aria-labelledby="modalId + 'Label'"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0 modal-moderno">

          <!-- Modal Header -->
          <div class="modal-header border-bottom">
            <div class="d-flex align-items-center">
              <div class="modal-header-icon mr-2">
                <i class="fa fa-th text-primary"></i>
              </div>
              <div>
                <h5 class="modal-title font-weight-bold mb-0 font-cairo" :id="modalId + 'Label'">
                  {{ titulo }}
                </h5>
                <small class="text-muted d-none d-sm-inline">
                  Explorá con fotos, precios y stock · Clic selecciona · Doble clic agrega directo
                </small>
                <small class="text-muted d-inline d-sm-none">
                  Catálogo con stock y fotos
                </small>
              </div>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body catalogo-modal-body p-3">

            <!-- Barra superior: Input de búsqueda en vivo y Filtros rápidos -->
            <div class="catalogo-toolbar mb-3">
              <div class="row align-items-center">
                <!-- Buscador en tiempo real con debouncing e ícono de limpieza -->
                <div class="col-12 col-md-6 col-lg-5 mb-2 mb-md-0">
                  <div class="input-group catalogo-search-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-transparent border-right-0 text-muted">
                        <i class="fa fa-search"></i>
                      </span>
                    </div>
                    <input
                      type="text"
                      class="form-control border-left-0 border-right-0 catalogo-search-input"
                      v-model="catalogo.buscar"
                      :id="buscarInputId"
                      placeholder="Escribí nombre o pasá código..."
                      @input="onSearchInput"
                      @keyup.enter="buscarInmediato"
                      autocomplete="off"
                    >
                    <div class="input-group-append">
                      <button
                        v-if="catalogo.buscar"
                        type="button"
                        class="btn btn-outline-secondary border-left-0 border-right-0 text-muted"
                        @click="limpiarBusqueda"
                        title="Borrar texto"
                      >
                        <i class="fa fa-times"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-primary font-weight-bold px-3 btn-buscar-action"
                        @click="buscarInmediato"
                        :disabled="catalogo.cargando"
                        title="Buscar en catálogo"
                      >
                        <span class="fa" :class="catalogo.cargando ? 'fa-spinner fa-spin' : 'fa-arrow-right'"></span>
                        <span class="d-none d-sm-inline ml-1">Buscar</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Filtros rápidos por botones pill -->
                <div class="col-12 col-md-6 col-lg-7">
                  <div class="d-flex flex-wrap align-items-center justify-content-md-end catalogo-pills">
                    <button
                      type="button"
                      class="btn btn-sm btn-filter-pill"
                      :class="{ 'active': filtroActivo === 'todos' }"
                      @click="filtroActivo = 'todos'"
                    >
                      Todos
                      <span class="badge badge-pill badge-secondary ml-1">{{ catalogo.items.length }}</span>
                    </button>

                    <button
                      type="button"
                      class="btn btn-sm btn-filter-pill"
                      :class="{ 'active': filtroActivo === 'stock' }"
                      @click="filtroActivo = 'stock'"
                      title="Artículos con stock disponible"
                    >
                      <i class="fa fa-check-circle text-success mr-1"></i> Con stock
                      <span class="badge badge-pill badge-secondary ml-1">{{ countConStock }}</span>
                    </button>

                    <button
                      type="button"
                      class="btn btn-sm btn-filter-pill"
                      :class="{ 'active': filtroActivo === 'sin_stock' }"
                      @click="filtroActivo = 'sin_stock'"
                      title="Artículos sin stock"
                    >
                      <i class="fa fa-times-circle text-danger mr-1"></i> Agotados
                      <span class="badge badge-pill badge-secondary ml-1">{{ countSinStock }}</span>
                    </button>

                    <button
                      v-if="countConOferta > 0"
                      type="button"
                      class="btn btn-sm btn-filter-pill"
                      :class="{ 'active': filtroActivo === 'ofertas' }"
                      @click="filtroActivo = 'ofertas'"
                      title="Artículos con oferta activa"
                    >
                      <i class="fa fa-tag text-warning mr-1"></i> Ofertas
                      <span class="badge badge-pill badge-warning text-dark ml-1">{{ countConOferta }}</span>
                    </button>

                    <button
                      v-if="seleccionCount > 0"
                      type="button"
                      class="btn btn-sm btn-filter-pill btn-pill-selected"
                      :class="{ 'active': filtroActivo === 'seleccionados' }"
                      @click="filtroActivo = 'seleccionados'"
                      title="Ver únicamente los artículos seleccionados"
                    >
                      <i class="fa fa-shopping-bag mr-1"></i> Seleccionados
                      <span class="badge badge-pill badge-primary ml-1 font-weight-bold">{{ seleccionCount }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Estado: Cargando -->
            <div v-if="catalogo.cargando" class="text-center py-5 my-4">
              <div class="spinner-border text-primary mb-3" role="status" style="width: 2.75rem; height: 2.75rem;">
                <span class="sr-only">Cargando artículos...</span>
              </div>
              <h6 class="font-weight-bold text-dark-mode">Buscando artículos en catálogo...</h6>
              <small class="text-muted">Consultando inventario, ofertas y precios actualizados</small>
            </div>

            <!-- Estado: Sin artículos -->
            <div v-else-if="!itemsFiltrados.length" class="text-center py-5 my-4 catalogo-empty-state">
              <div class="catalogo-empty-icon mb-3">
                <i class="fa fa-box-open fa-3x text-muted" style="opacity: 0.45;"></i>
              </div>
              <h5 class="font-weight-bold text-dark-mode mb-1">
                {{ catalogo.buscar ? 'No encontramos coincidencias' : 'No hay artículos para mostrar' }}
              </h5>
              <p class="text-muted small mb-3">
                <span v-if="filtroActivo === 'seleccionados'">
                  Aún no seleccionaste ningún artículo. Hacé clic en cualquier producto para marcarlo.
                </span>
                <span v-else-if="catalogo.buscar">
                  No se encontraron resultados para "<strong>{{ catalogo.buscar }}</strong>". Probá con otro término o código.
                </span>
                <span v-else-if="filtroActivo !== 'todos'">
                  No hay artículos que coincidan con el filtro seleccionado.
                </span>
                <span v-else>
                  No hay artículos disponibles en el catálogo en este momento.
                </span>
              </p>
              <div class="d-flex justify-content-center">
                <button
                  v-if="catalogo.buscar"
                  type="button"
                  class="btn btn-outline-secondary btn-sm mr-2"
                  @click="limpiarBusqueda"
                >
                  <i class="fa fa-undo mr-1"></i> Borrar búsqueda
                </button>
                <button
                  v-if="filtroActivo !== 'todos'"
                  type="button"
                  class="btn btn-outline-primary btn-sm"
                  @click="filtroActivo = 'todos'"
                >
                  <i class="fa fa-th-large mr-1"></i> Ver todos los artículos
                </button>
              </div>
            </div>

            <!-- Grilla de Tarjetas de Artículos -->
            <div v-else class="row catalogo-grid">
              <div
                class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3"
                v-for="(art, idx) in itemsFiltrados"
                :key="catalogoId(art) || idx"
              >
                <div class="catalogo-card-wrap">
                  <div
                    class="catalogo-card"
                    :class="{
                      'sin-stock': Number(art.cantidad) <= 0,
                      'seleccionada': estaSeleccionado(art)
                    }"
                    @click="toggleSeleccion(art)"
                    @dblclick="agregarDirecto(art)"
                    :aria-pressed="estaSeleccionado(art) ? 'true' : 'false'"
                    :title="'Clic: seleccionar · Doble clic: agregar directo'"
                  >
                    <!-- Indicador Check de Selección -->
                    <div class="catalogo-check" :class="{ 'is-checked': estaSeleccionado(art) }">
                      <i class="fa" :class="estaSeleccionado(art) ? 'fa-check' : 'fa-plus'"></i>
                    </div>

                    <!-- Botón para ver foto ampliada -->
                    <button
                      type="button"
                      class="catalogo-zoom-btn"
                      :aria-label="'Ver foto ampliada de ' + art.producto_nombre"
                      title="Ver foto ampliada"
                      @click.stop="abrirZoom(art)"
                    >
                      <i class="fa fa-search-plus" aria-hidden="true"></i>
                    </button>

                    <!-- Badges superiores (Oferta / Combo) -->
                    <div class="catalogo-corner-badges" v-if="art.tiene_oferta > 0 || art.en_combo > 0">
                      <span v-if="art.tiene_oferta > 0" class="badge badge-warning text-dark font-weight-bold">
                        <i class="fa fa-tag"></i> Oferta
                      </span>
                      <span v-if="art.en_combo > 0" class="badge badge-info font-weight-bold">
                        <i class="fa fa-layer-group"></i> Combo
                      </span>
                    </div>

                    <!-- Contenedor Imagen -->
                    <div class="catalogo-card-img">
                      <img
                        :src="urlFoto(art.foto)"
                        :alt="art.producto_nombre"
                        loading="lazy"
                        @error="onImgError"
                      >
                    </div>

                    <!-- Contenido de la Tarjeta -->
                    <div class="catalogo-card-body">
                      <!-- Título con 2 líneas clamp -->
                      <div class="catalogo-card-title" :title="art.producto_nombre">
                        {{ art.producto_nombre }}
                      </div>

                      <!-- Metadatos: Código y Presentación -->
                      <div class="catalogo-card-meta mb-2">
                        <span class="catalogo-codigo" :title="'Código: ' + (art.producto_c_barra || art.ARTICULOS_cod)">
                          {{ art.producto_c_barra || art.ARTICULOS_cod || '—' }}
                        </span>
                        <span v-if="art.uni_abreviatura" class="catalogo-unidad badge badge-light border">
                          {{ art.uni_abreviatura }}
                        </span>
                      </div>

                      <!-- Pie de Tarjeta: Precio y Stock -->
                      <div class="d-flex justify-content-between align-items-center catalogo-card-footer-info">
                        <div class="catalogo-card-precio font-cairo">
                          Gs. {{ formatMoney(precioDe(art)) }}
                        </div>
                        <span
                          class="catalogo-stock"
                          :class="{
                            'agotado': Number(art.cantidad) <= 0,
                            'bajo': Number(art.cantidad) > 0 && Number(art.cantidad) <= 5,
                            'ok': Number(art.cantidad) > 5
                          }"
                        >
                          <i class="fa" :class="Number(art.cantidad) <= 0 ? 'fa-times' : (Number(art.cantidad) <= 5 ? 'fa-exclamation-triangle' : 'fa-check')"></i>
                          {{ Number(art.cantidad) <= 0 ? 'Agotado' : (Math.floor(Number(art.cantidad)) + ' disp.') }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="modal-footer bg-light-panel border-top d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center flex-wrap">
              <span class="text-muted small">
                Mostrando <strong>{{ itemsFiltrados.length }}</strong> de {{ catalogo.items.length }} artículos
              </span>
              <span v-if="seleccionCount > 0" class="badge badge-primary px-2 py-1 ml-2 font-weight-bold">
                <i class="fa fa-check mr-1"></i> {{ seleccionCount }} seleccionado{{ seleccionCount > 1 ? 's' : '' }}
              </span>
              <button
                v-if="seleccionCount > 0"
                type="button"
                class="btn btn-link btn-sm text-danger p-0 ml-2 font-weight-bold"
                @click="limpiarSeleccion"
                title="Desmarcar todos"
              >
                Limpiar selección
              </button>
            </div>

            <div class="d-flex align-items-center">
              <button type="button" class="btn btn-outline-secondary mr-2" data-dismiss="modal">
                <i class="fa fa-times mr-1"></i> Cerrar
              </button>
              <button
                type="button"
                class="btn btn-success btn-cobrar font-weight-bold px-3"
                :disabled="!seleccionCount"
                @click="confirmarSeleccion"
              >
                <i class="fa fa-cart-plus mr-1"></i>
                Agregar al ticket
                <span v-if="seleccionCount" class="badge badge-light text-dark ml-1 font-weight-bold">
                  ({{ seleccionCount }})
                </span>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Lightbox Zoom Modal / Overlay -->
    <div
      class="catalogo-zoom-overlay"
      v-if="zoom.visible"
      @click.self="cerrarZoom"
    >
      <div class="catalogo-zoom-card">
        <button
          type="button"
          class="btn btn-light btn-sm catalogo-zoom-close"
          @click="cerrarZoom"
          title="Cerrar (Esc)"
        >
          <span class="fa fa-times"></span>
        </button>
        <img :src="zoom.src" :alt="zoom.titulo" @error="onImgError">
        <div class="catalogo-zoom-caption" v-if="zoom.titulo">
          <div class="font-weight-bold">{{ zoom.titulo }}</div>
          <small class="text-muted d-block" v-if="zoom.meta">{{ zoom.meta }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Searcharticulo from './Autocomplete.vue';

export default {
  name: 'BuscadorCatalogo',
  components: { Searcharticulo },
  props: {
    url: { type: String, required: true },
    idsucursal: { default: null },
    urlBuscar: { type: String, required: true },
    urlFotoBase: { type: String, required: true },
    imgFallback: { type: String, required: true },
    routeArticulo: { type: String, default: '' },
    validarLote: { type: [String, Boolean], default: 'false' },
    isReadyBalance: { type: [String, Boolean], default: 'false' },
    precioField: { type: String, default: 'pre_venta1' },
    titulo: { type: String, default: 'Catálogo' },
    modalId: { type: String, default: 'modalCatalogoArticulos' },
    scanPlaceholder: { type: String, default: 'Buscá por nombre o código' }
  },
  data() {
    return {
      filtroActivo: 'todos',
      catalogo: {
        buscar: '',
        cargando: false,
        items: [],
        seleccionados: {}
      },
      zoom: {
        visible: false,
        src: '',
        titulo: '',
        meta: ''
      },
      _escZoomHandler: null,
      _searchTimer: null
    };
  },
  computed: {
    buscarInputId() {
      return this.modalId + 'Buscar';
    },
    seleccionCount() {
      return Object.keys(this.catalogo.seleccionados || {}).length;
    },
    countConStock() {
      return (this.catalogo.items || []).filter(function (x) {
        return Number(x.cantidad) > 0;
      }).length;
    },
    countSinStock() {
      return (this.catalogo.items || []).filter(function (x) {
        return Number(x.cantidad) <= 0;
      }).length;
    },
    countConOferta() {
      return (this.catalogo.items || []).filter(function (x) {
        return Number(x.tiene_oferta) > 0;
      }).length;
    },
    itemsFiltrados() {
      var items = this.catalogo.items || [];
      var f = this.filtroActivo;
      var self = this;
      if (f === 'stock') {
        return items.filter(function (x) {
          return Number(x.cantidad) > 0;
        });
      }
      if (f === 'sin_stock') {
        return items.filter(function (x) {
          return Number(x.cantidad) <= 0;
        });
      }
      if (f === 'ofertas') {
        return items.filter(function (x) {
          return Number(x.tiene_oferta) > 0;
        });
      }
      if (f === 'seleccionados') {
        return items.filter(function (x) {
          return self.estaSeleccionado(x);
        });
      }
      return items;
    },
    searchQuery: {
      get() {
        return this.$refs.search ? this.$refs.search.searchQuery : '';
      },
      set(v) {
        if (this.$refs.search) {
          this.$refs.search.searchQuery = v || '';
        }
      }
    }
  },
  mounted() {
    var self = this;
    var modalEl = document.getElementById(this.modalId);
    if (modalEl && modalEl.parentNode !== document.body) {
      document.body.appendChild(modalEl);
    }
    $('#' + this.modalId).on('shown.bs.modal', function () {
      var el = document.getElementById(self.buscarInputId);
      if (el) {
        el.focus();
        el.select();
      }
    });
    $('#' + this.modalId).on('hide.bs.modal', function (e) {
      if (self.zoom.visible) {
        e.preventDefault();
        self.cerrarZoom();
      }
    });
  },
  beforeDestroy() {
    this._desactivarEscZoom();
    if (this._searchTimer) {
      clearTimeout(this._searchTimer);
    }
    $('#' + this.modalId).off('shown.bs.modal hide.bs.modal');
    var modalEl = document.getElementById(this.modalId);
    if (modalEl && modalEl.parentNode === document.body) {
      modalEl.parentNode.removeChild(modalEl);
    }
  },
  methods: {
    focusSearchInput() {
      if (this.$refs.search && this.$refs.search.focusSearchInput) {
        this.$refs.search.focusSearchInput();
      }
    },
    sincronizarBuscador(texto) {
      if (this.$refs.search) {
        this.$refs.search.searchQuery = texto || '';
        this.$refs.search.showResults = false;
        this.$refs.search.results = [];
      }
    },
    abrirCatalogo() {
      var q = '';
      if (this.$refs.search && typeof this.$refs.search.searchQuery !== 'undefined') {
        q = (this.$refs.search.searchQuery || '').trim();
      }
      this.catalogo.buscar = q;
      this.catalogo.seleccionados = {};
      this.filtroActivo = 'todos';
      var modalEl = document.getElementById(this.modalId);
      if (modalEl && modalEl.parentNode !== document.body) {
        document.body.appendChild(modalEl);
      }
      $('#' + this.modalId).modal('show');
      this.buscarCatalogo();
    },
    catalogoId(art) {
      if (!art) return '';
      return String(art.ARTICULOS_cod || art.articulos_cod || '');
    },
    normalizar(art) {
      if (!art) return art;
      if (!art.ARTICULOS_cod && art.articulos_cod) {
        art.ARTICULOS_cod = art.articulos_cod;
      }
      if (typeof art.id_stock === 'undefined' && typeof art.idstock !== 'undefined') {
        art.id_stock = art.idstock;
      }
      return art;
    },
    estaSeleccionado(art) {
      var id = this.catalogoId(art);
      return !!(id && this.catalogo.seleccionados[id]);
    },
    toggleSeleccion(art) {
      if (!art) return;
      art = this.normalizar(art);
      var id = this.catalogoId(art);
      if (!id) return;
      if (this.catalogo.seleccionados[id]) {
        this.$delete(this.catalogo.seleccionados, id);
      } else {
        this.$set(this.catalogo.seleccionados, id, art);
      }
    },
    limpiarSeleccion() {
      this.catalogo.seleccionados = {};
      if (this.filtroActivo === 'seleccionados') {
        this.filtroActivo = 'todos';
      }
    },
    onSearchInput() {
      clearTimeout(this._searchTimer);
      var self = this;
      this._searchTimer = setTimeout(function () {
        self.buscarCatalogo();
      }, 350);
    },
    buscarInmediato() {
      clearTimeout(this._searchTimer);
      this.buscarCatalogo();
    },
    limpiarBusqueda() {
      this.catalogo.buscar = '';
      this.buscarInmediato();
      var el = document.getElementById(this.buscarInputId);
      if (el) {
        el.focus();
      }
    },
    buscarCatalogo() {
      var self = this;
      var texto = (this.catalogo.buscar || '').trim();
      this.catalogo.buscar = texto;
      this.sincronizarBuscador(texto);
      this.catalogo.cargando = true;
      axios
        .get(this.urlBuscar, {
          params: {
            buscar: texto,
            criterio: 0,
            seccion: 0,
            col: 0,
            ord: 'ASC',
            suc: this.idsucursal || null
          }
        })
        .then(function (response) {
          self.catalogo.cargando = false;
          self.catalogo.items = Array.isArray(response.data) ? response.data : [];
        })
        .catch(function (error) {
          self.catalogo.cargando = false;
          self.catalogo.items = [];
          Swal.fire(
            'Error',
            (error.response && error.response.data && error.response.data.message)
              ? error.response.data.message
              : 'No se pudo cargar el catálogo',
            'error'
          );
        });
    },
    precioDe(art) {
      if (!art) return 0;
      var v = art[this.precioField];
      if (v == null || v === '') {
        v = art.pre_venta1;
      }
      return Number(v) || 0;
    },
    formatMoney(n) {
      return new Intl.NumberFormat('de-DE').format(Number(n) || 0);
    },
    urlFoto(foto) {
      if (!foto) return this.imgFallback;
      var f = String(foto);
      if (f.indexOf('http') === 0 || f.indexOf('/') === 0 || f.indexOf('data:') === 0) {
        return f;
      }
      return this.urlFotoBase.replace(/\/$/, '') + '/' + f;
    },
    onImgError(e) {
      if (e && e.target) {
        e.target.src = this.imgFallback;
      }
    },
    abrirZoom(art) {
      if (!art) return;
      this.zoom = {
        visible: true,
        src: this.urlFoto(art.foto),
        titulo: art.producto_nombre || '',
        meta: (art.producto_c_barra || art.ARTICULOS_cod ? 'Código: ' + (art.producto_c_barra || art.ARTICULOS_cod) : '')
      };
      this._activarEscZoom();
    },
    cerrarZoom() {
      this.zoom.visible = false;
      this.zoom.src = '';
      this.zoom.titulo = '';
      this.zoom.meta = '';
      this._desactivarEscZoom();
    },
    _onEscZoom(e) {
      if (!e) return;
      var isEsc = e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27;
      if (!isEsc || !this.zoom.visible) return;
      e.preventDefault();
      e.stopPropagation();
      if (typeof e.stopImmediatePropagation === 'function') {
        e.stopImmediatePropagation();
      }
      this.cerrarZoom();
    },
    _activarEscZoom() {
      if (!this._escZoomHandler) {
        this._escZoomHandler = this._onEscZoom.bind(this);
      }
      document.addEventListener('keydown', this._escZoomHandler, true);
      var modal = $('#' + this.modalId).data('bs.modal');
      if (modal) {
        if (modal._config) modal._config.keyboard = false;
        if (modal.options) modal.options.keyboard = false;
      }
    },
    _desactivarEscZoom() {
      if (this._escZoomHandler) {
        document.removeEventListener('keydown', this._escZoomHandler, true);
      }
      var modal = $('#' + this.modalId).data('bs.modal');
      if (modal) {
        if (modal._config) modal._config.keyboard = true;
        if (modal.options) modal.options.keyboard = true;
      }
    },
    agregarDirecto(art) {
      if (!art) return;
      var a = this.normalizar(art);
      this.$emit('seleccion', [a]);
      $('#' + this.modalId).modal('hide');
      var selfFocus = this;
      $('#' + this.modalId).one('hidden.bs.modal', function () {
        selfFocus.focusSearchInput();
      });
    },
    confirmarSeleccion() {
      var ids = Object.keys(this.catalogo.seleccionados);
      if (!ids.length) {
        Swal.fire('Sin selección', 'Seleccioná al menos un artículo.', 'info');
        return;
      }
      var arts = [];
      var self = this;
      ids.forEach(function (id) {
        arts.push(self.normalizar(self.catalogo.seleccionados[id]));
      });
      this.$emit('seleccion', arts);
      this.catalogo.seleccionados = {};
      this.catalogo.buscar = '';
      this.sincronizarBuscador('');
      $('#' + this.modalId).modal('hide');
      var selfFocus = this;
      $('#' + this.modalId).one('hidden.bs.modal', function () {
        selfFocus.focusSearchInput();
      });
    }
  }
};
</script>

<style scoped>
.buscador-navbar {
  background: var(--dash-card-bg, #fff);
  border-color: var(--dash-border, #dee2e6) !important;
}
.buscador-navbar .nav-link {
  color: var(--dash-text-muted, #495057);
}
.buscador-navbar .nav-link:hover {
  color: var(--dash-primary, #007bff);
}
.dark-mode .buscador-navbar {
  background: #1e293b;
  border-color: #334155 !important;
}
.dark-mode .buscador-navbar .nav-link {
  color: #94a3b8;
}
.dark-mode .buscador-navbar .nav-link:hover {
  color: #f8fafc;
}

/* Modal Content Moderno */
.catalogo-modal .modal-content {
  border-radius: 16px;
  overflow: hidden;
  background: var(--dash-card-bg, #ffffff);
  color: var(--dash-text-main, #1f2937);
  border: 1px solid var(--dash-border, #e5e7eb) !important;
}
.catalogo-modal-body {
  max-height: calc(85vh - 145px);
  min-height: 380px;
  overflow-y: auto;
  background-color: var(--dash-panel-bg, #f8fafc);
}
body.dark-mode .catalogo-modal-body {
  background-color: #0f172a;
}

/* Modal Header Icon */
.modal-header-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: var(--dash-primary-light, rgba(0, 123, 255, 0.1));
  border: 1px solid var(--dash-primary-border, rgba(0, 123, 255, 0.25));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
}

/* Toolbar superior */
.catalogo-toolbar {
  background: var(--dash-card-bg, #ffffff);
  border: 1px solid var(--dash-border, #e2e8f0);
  border-radius: 12px;
  padding: 0.65rem 0.85rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
body.dark-mode .catalogo-toolbar {
  background: #1e293b;
  border-color: #334155;
}

.catalogo-search-group {
  border: 1px solid var(--dash-border, #cbd5e1);
  border-radius: 10px;
  overflow: hidden;
  background: var(--dash-panel-bg, #f8fafc);
  transition: all 0.15s ease;
}
body.dark-mode .catalogo-search-group {
  background: #0f172a;
  border-color: #334155;
}
.catalogo-search-group:focus-within {
  border-color: var(--dash-primary, #007bff);
  box-shadow: 0 0 0 3px var(--dash-primary-light, rgba(0, 123, 255, 0.15));
  background: var(--dash-card-bg, #ffffff);
}
body.dark-mode .catalogo-search-group:focus-within {
  background: #1e293b;
}
.catalogo-search-input {
  background: transparent !important;
  color: var(--dash-text-main, #0f172a) !important;
  border: none !important;
  box-shadow: none !important;
  font-size: 0.92rem;
  padding-left: 0.35rem;
}
body.dark-mode .catalogo-search-input {
  color: #f8fafc !important;
}
.catalogo-search-group .input-group-text {
  border: none !important;
  background: transparent !important;
  padding-left: 0.75rem;
  padding-right: 0.25rem;
}
.btn-buscar-action {
  border-radius: 0 !important;
}

/* Filtros Pills */
.catalogo-pills {
  gap: 0.35rem;
}
.btn-filter-pill {
  border-radius: 999px;
  background: var(--dash-panel-bg, #f1f5f9);
  color: var(--dash-text-muted, #64748b);
  border: 1px solid var(--dash-border, #e2e8f0);
  font-weight: 600;
  font-size: 0.8rem;
  padding: 0.28rem 0.7rem;
  transition: all 0.15s ease;
  display: inline-flex;
  align-items: center;
}
body.dark-mode .btn-filter-pill {
  background: #1e293b;
  border-color: #334155;
  color: #94a3b8;
}
.btn-filter-pill:hover {
  background: var(--dash-card-bg, #ffffff);
  color: var(--dash-text-main, #1e293b);
  border-color: var(--dash-primary, #007bff);
}
body.dark-mode .btn-filter-pill:hover {
  background: #334155;
  color: #f8fafc;
}
.btn-filter-pill.active {
  background: var(--dash-primary, #007bff) !important;
  color: #ffffff !important;
  border-color: var(--dash-primary, #007bff) !important;
  box-shadow: 0 2px 6px rgba(0, 123, 255, 0.25);
}
.btn-filter-pill.active .badge {
  background: rgba(255, 255, 255, 0.25) !important;
  color: #ffffff !important;
}
.btn-pill-selected.active {
  background: #10b981 !important;
  border-color: #10b981 !important;
  box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
}

/* Card Wrap & Grid */
.catalogo-card-wrap {
  position: relative;
  height: 100%;
}
.catalogo-card {
  cursor: pointer;
  border: 1px solid var(--dash-border, #e2e8f0);
  border-radius: 12px;
  overflow: hidden;
  height: 100%;
  width: 100%;
  background: var(--dash-card-bg, #ffffff);
  color: var(--dash-text-main, #1e293b);
  display: flex;
  flex-direction: column;
  position: relative;
  user-select: none;
  transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
body.dark-mode .catalogo-card {
  background: #1e293b;
  border-color: #334155;
  color: #f8fafc;
}
.catalogo-card:hover {
  transform: translateY(-2px);
  border-color: var(--dash-primary, #007bff);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}
body.dark-mode .catalogo-card:hover {
  border-color: #38bdf8;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
}
.catalogo-card.seleccionada {
  border-color: #10b981 !important;
  box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.4), 0 4px 12px rgba(16, 185, 129, 0.15) !important;
}
.catalogo-card.sin-stock {
  opacity: 0.65;
}

/* Check de Selección */
.catalogo-check {
  position: absolute;
  top: 8px;
  right: 8px;
  z-index: 3;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(4px);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  transition: all 0.15s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}
.catalogo-card:hover .catalogo-check {
  background: rgba(15, 23, 42, 0.75);
}
.catalogo-check.is-checked {
  background: #10b981 !important;
  color: #ffffff !important;
  transform: scale(1.08);
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.5);
}

/* Botón Zoom */
.catalogo-zoom-btn {
  position: absolute;
  top: 8px;
  left: 8px;
  z-index: 3;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: none;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(4px);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  cursor: pointer;
  transition: all 0.15s ease;
  padding: 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}
.catalogo-zoom-btn:hover {
  background: var(--dash-primary, #007bff);
  transform: scale(1.1);
}

/* Badges esquina inferior de imagen */
.catalogo-corner-badges {
  position: absolute;
  bottom: 8px;
  left: 8px;
  z-index: 2;
  display: flex;
  gap: 0.25rem;
}
.catalogo-corner-badges .badge {
  font-size: 0.68rem;
  padding: 0.2rem 0.45rem;
  border-radius: 6px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Contenedor de Imagen */
.catalogo-card-img {
  height: 125px;
  background: var(--dash-panel-bg, #f8fafc);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid var(--dash-border, #f1f5f9);
  padding: 0.5rem;
}
body.dark-mode .catalogo-card-img {
  background: #0f172a;
  border-color: #334155;
}
.catalogo-card-img img {
  max-height: 100%;
  max-width: 100%;
  object-fit: contain;
  transition: transform 0.2s ease;
}
.catalogo-card:hover .catalogo-card-img img {
  transform: scale(1.05);
}

/* Cuerpo de la tarjeta */
.catalogo-card-body {
  padding: 0.65rem 0.75rem;
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
}
.catalogo-card-title {
  font-size: 0.84rem;
  font-weight: 700;
  line-height: 1.25;
  color: var(--dash-text-main, #1e293b);
  min-height: 2.5em;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: 0.35rem;
}
body.dark-mode .catalogo-card-title {
  color: #f8fafc;
}
.catalogo-card-meta {
  font-size: 0.72rem;
  color: var(--dash-text-muted, #64748b);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.35rem;
  overflow: hidden;
}
body.dark-mode .catalogo-card-meta {
  color: #94a3b8;
}
.catalogo-codigo {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-family: monospace;
}
.catalogo-unidad {
  font-size: 0.68rem;
  padding: 0.05rem 0.3rem;
  border-radius: 4px;
}
.catalogo-card-footer-info {
  margin-top: auto;
  padding-top: 0.4rem;
  border-top: 1px dashed var(--dash-border, #f1f5f9);
}
body.dark-mode .catalogo-card-footer-info {
  border-top-color: #334155;
}
.catalogo-card-precio {
  font-weight: 800;
  font-size: 0.95rem;
  color: var(--dash-primary-dark, #059669);
  line-height: 1.1;
}
body.dark-mode .catalogo-card-precio {
  color: #34d399;
}
.catalogo-stock {
  font-size: 0.7rem;
  font-weight: 700;
  border-radius: 50px;
  padding: 0.15rem 0.45rem;
  line-height: 1.2;
  white-space: nowrap;
}
.catalogo-stock.ok {
  background: #dcfce7;
  color: #166534;
}
.catalogo-stock.bajo {
  background: #fef3c7;
  color: #92400e;
}
.catalogo-stock.agotado {
  background: #fee2e2;
  color: #991b1b;
}
body.dark-mode .catalogo-stock.ok {
  background: #064e3b;
  color: #a7f3d0;
}
body.dark-mode .catalogo-stock.bajo {
  background: #78350f;
  color: #fde68a;
}
body.dark-mode .catalogo-stock.agotado {
  background: #7f1d1d;
  color: #fecaca;
}

/* Lightbox zoom modal */
.catalogo-zoom-overlay {
  position: fixed;
  inset: 0;
  z-index: 2060;
  background: rgba(0, 0, 0, 0.78);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}
.catalogo-zoom-card {
  position: relative;
  background: var(--dash-card-bg, #ffffff);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
  max-width: 90vw;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}
body.dark-mode .catalogo-zoom-card {
  background: #1e293b;
}
.catalogo-zoom-card img {
  max-width: 80vw;
  max-height: 70vh;
  object-fit: contain;
  padding: 1.5rem;
  background: var(--dash-panel-bg, #f8fafc);
}
body.dark-mode .catalogo-zoom-card img {
  background: #0f172a;
}
.catalogo-zoom-close {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 5;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}
.catalogo-zoom-caption {
  padding: 0.85rem 1.25rem;
  background: var(--dash-card-bg, #ffffff);
  color: var(--dash-text-main, #1e293b);
  width: 100%;
  text-align: center;
  border-top: 1px solid var(--dash-border, #e2e8f0);
}
body.dark-mode .catalogo-zoom-caption {
  background: #1e293b;
  color: #f8fafc;
  border-top-color: #334155;
}

@media (max-width: 768px) {
  .catalogo-modal-body {
    max-height: 65vh;
    min-height: 260px;
    padding: 0.75rem !important;
  }
  .catalogo-card-img {
    height: 105px;
  }
  .catalogo-card-body {
    padding: 0.5rem 0.6rem;
  }
  .catalogo-card-title {
    font-size: 0.78rem;
    min-height: 2.3em;
  }
  .catalogo-card-precio {
    font-size: 0.88rem;
  }
}
</style>
