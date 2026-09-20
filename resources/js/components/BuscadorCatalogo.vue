<template>
  <div class="buscador-catalogo">
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
            @articulo="$emit('articulo', $event)"
            @peso="$emit('peso', $event)"
          />
        </li>
      </ul>
      <ul class="navbar-nav flex-row">
        <slot name="actions-before"></slot>
        <li class="nav-item">
          <a href="#" class="nav-link" title="Catálogo con imágenes" @click.prevent="abrirCatalogo">
            <i class="fa fa-th"></i>
          </a>
        </li>
        <slot name="actions-after"></slot>
      </ul>
    </nav>

    <div class="modal fade" :id="modalId" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title mb-0">
              <span class="fa fa-th"></span> {{ titulo }}
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body catalogo-modal-body">
            <div class="input-group mb-3">
              <input
                type="text"
                class="form-control"
                v-model.trim="catalogo.buscar"
                :id="buscarInputId"
                placeholder="Buscar por nombre o código de barra..."
                @keyup.enter="buscarCatalogo"
              >
              <div class="input-group-append">
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="buscarCatalogo"
                  :disabled="catalogo.cargando"
                >
                  <span
                    class="fa"
                    :class="catalogo.cargando ? 'fa-spinner fa-spin' : 'fa-search'"
                  ></span>
                  Buscar
                </button>
              </div>
            </div>

            <div v-if="catalogo.cargando" class="text-center text-muted py-5">
              <span class="fa fa-spinner fa-spin fa-2x mb-2 d-block"></span>
              Cargando artículos...
            </div>
            <div v-else-if="!catalogo.items.length" class="text-center text-muted py-5">
              <span class="fa fa-box-open fa-2x mb-2 d-block"></span>
              No se encontraron artículos.
            </div>
            <div v-else class="row">
              <div
                class="col-6 col-md-4 col-lg-3 mb-3"
                v-for="(art, idx) in catalogo.items"
                :key="catalogoId(art) || idx"
              >
                <div
                  class="catalogo-card"
                  :class="{
                    'sin-stock': Number(art.cantidad) <= 0,
                    'seleccionada': estaSeleccionado(art)
                  }"
                  @click="toggleSeleccion(art)"
                  :title="estaSeleccionado(art) ? 'Quitar selección' : 'Seleccionar'"
                >
                  <button
                    type="button"
                    class="catalogo-zoom-btn"
                    title="Ver imagen ampliada"
                    @click.stop="abrirZoom(art)"
                  >
                    <i class="fa fa-search-plus"></i>
                  </button>
                  <span class="catalogo-check">
                    <i class="fa" :class="estaSeleccionado(art) ? 'fa-check' : 'fa-plus'"></i>
                  </span>
                  <div class="catalogo-card-img">
                    <img
                      :src="urlFoto(art.foto)"
                      :alt="art.producto_nombre"
                      @error="onImgError"
                    >
                  </div>
                  <div class="catalogo-card-body">
                    <p class="catalogo-card-title">{{ art.producto_nombre }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="catalogo-card-precio">
                        Gs. {{ formatMoney(precioDe(art)) }}
                      </span>
                      <span
                        class="catalogo-stock"
                        :class="{ agotado: Number(art.cantidad) <= 0 }"
                      >
                        {{ Math.floor(Number(art.cantidad) || 0) }}
                        {{ Number(art.cantidad) == 1 ? 'disponible' : 'disponibles' }}
                      </span>
                    </div>
                    <div class="catalogo-card-meta mt-1">
                      {{ art.producto_c_barra || '—' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <small class="text-muted mr-auto">
              <span v-if="catalogo.items.length">{{ catalogo.items.length }} artículo(s)</span>
              <span v-if="seleccionCount">
                · <strong>{{ seleccionCount }} seleccionado(s)</strong>
              </span>
            </small>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <span class="fa fa-times"></span> Cerrar
            </button>
            <button
              type="button"
              class="btn btn-success"
              :disabled="!seleccionCount"
              @click="confirmarSeleccion"
            >
              <span class="fa fa-cart-plus"></span>
              Agregar al carrito
              <span v-if="seleccionCount">({{ seleccionCount }})</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="catalogo-zoom-overlay"
      v-if="zoom.visible"
      @click.self="cerrarZoom"
    >
      <button
        type="button"
        class="btn btn-light btn-sm catalogo-zoom-close"
        @click="cerrarZoom"
        title="Cerrar"
      >
        <span class="fa fa-times"></span>
      </button>
      <img :src="zoom.src" :alt="zoom.titulo" @error="onImgError">
      <div class="catalogo-zoom-caption" v-if="zoom.titulo">{{ zoom.titulo }}</div>
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
    titulo: { type: String, default: 'Catálogo de artículos' },
    modalId: { type: String, default: 'modalCatalogoArticulos' }
  },
  data() {
    return {
      catalogo: {
        buscar: '',
        cargando: false,
        items: [],
        seleccionados: {}
      },
      zoom: {
        visible: false,
        src: '',
        titulo: ''
      },
      _escZoomHandler: null
    };
  },
  computed: {
    buscarInputId() {
      return this.modalId + 'Buscar';
    },
    seleccionCount() {
      return Object.keys(this.catalogo.seleccionados || {}).length;
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
    $('#' + this.modalId).off('shown.bs.modal hide.bs.modal');
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
        titulo: art.producto_nombre || ''
      };
      this._activarEscZoom();
    },
    cerrarZoom() {
      this.zoom.visible = false;
      this.zoom.src = '';
      this.zoom.titulo = '';
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
  background: #fff;
  border-color: #dee2e6 !important;
}
.buscador-navbar .nav-link {
  color: #495057;
}
.buscador-navbar .nav-link:hover {
  color: #212529;
}
.dark-mode .buscador-navbar {
  background: #343a40;
  border-color: #6c757d !important;
}
.dark-mode .buscador-navbar .nav-link {
  color: #ced4da;
}
.dark-mode .buscador-navbar .nav-link:hover {
  color: #fff;
}
.catalogo-modal-body {
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
  line-height: 1;
}
.catalogo-card.seleccionada .catalogo-check {
  background: #1f2937;
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
</style>
