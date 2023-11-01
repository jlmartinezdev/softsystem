<template>
  <div
    id="autocomplete"
    :data-loading="[requestSend ? true : false]"
    class="autocomplete"
    data-position="below"
  >
    <input
      type="text"
      v-model="searchQuery"
      placeholder="Buscar..."
      class="autocomplete-input"
      @keydown.down="selectNextItem"
      @keydown.up="selectPrevItem"
      @keydown.enter="selectItemEnter"
      :aria-expanded="[showResults ? true : false]"
      ref="inputsearch"
    />
    <div class="autocomplete-result-container">
      <ul v-if="showResults" ref="resultList" class="autocomplete-result-list">
        <li
          v-for="(result, index) in results"
          :key="index"
          :class="[result.cantidad == 0 ? 'text-maroon' : '']"
          :aria-selected="[index === activeIndex ? true : false]"
          @click="selectItem(index)"
          :data-result-index="index"
          class="autocomplete-result"
        >
          <span class="left">
            {{ result.producto_nombre }}
          </span>
          <span class="right font-weight-bold">
            <span v-show="result.cantidad > 0" class="badge badge-info">{{
              result.cantidad
            }}</span>
            Gs
            {{ new Intl.NumberFormat("de-DE").format(result.pre_venta1) }}</span
          >
        </li>
        <li v-if="noresult" class="autocomplete-result">
          <span class="left"> No hay resultado para <i>{{ searchQuery }}</i> </span>
          <span class="right"><a :href="routeArticulo" class="btn btn-link btn-sm"><i class="fa fa-plus"></i>  Crear Articulo</a></span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "Searcharticulo",
  data() {
    return {
      searchQuery: "",
      showResults: false,
      results: [],
      activeIndex: null,
      searchTerm: {},
      requestSend: false,
      articulos: [],
      noresult: false,
      timeout: null,
    };
  },
  props: ["url", "idsucursal", "validarLote", "routeArticulo"],
  watch: {
    searchQuery: function () {
      if (this.searchQuery === "") {
        this.showResults = false;
        this.results = [];
        return;
      }
      if (!isNaN(parseFloat(this.searchQuery)) && this.searchQuery.length > 4) {
        return;
      }
      //this.getArticulo(false, this.searchQuery);

      if (this.timeout) 
        clearTimeout(this.timeout); 

      this.timeout = setTimeout(() => {
        this.getArticulo(false, this.searchQuery);
      }, 250); 
    },
  },
  updated() {
    if (!this.$refs.resultList) {
      return;
    }
    this.checkSelectedResultVisible(this.$refs.resultList);
  },
  methods: {
    searchOnEnter() {
      if (this.timeout) 
        clearTimeout(this.timeout); 

      this.timeout = setTimeout(() => {
        if (!isNaN(parseFloat(this.searchQuery)) && this.searchQuery.length > 4) {
            this.getArticulo(true, this.searchQuery);
          }
      }, 250); 
      
    },
    getArticulo(isBarcode, textSearch) {
      var Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
      });
      this.requestSend = true;
      if (isBarcode) {
        axios
          .get(this.url, {
            params: {
              cbarra: textSearch,
              bus_suc: this.idsucursal,
            },
          })
          .then((response) => {
            this.requestSend = false;
            if (response.data == "no") {
              Toast.fire({
                icon: "error",
                title: "Codigo ingresado no existe en la Base de Datos...",
              });
              return;
            }
            if (response.data) {
              if (response.data.length > 1 && this.validarLote == "true") {
                this.checkLote(response.data);
              } else {
                this.searchTerm = response.data[0];
                this.returnData();
              }
            } else {
              Toast.fire({
                icon: "error",
                title: "Codigo ingresado no existe en la Base de Datos...",
              });
            }
          })
          .catch((error) => console.log(error));
      } else {
        axios
          .get(this.url, {
            params: {
              buscar: textSearch,
              bus_suc: this.idsucursal,
            },
          })
          .then((response) => {
            this.requestSend = false;
            if (response.data) {
              this.results = response.data;
              this.showResults = true;
              this.noresult = false;
            } else {
              this.showResults = true;
              this.noresult = true;
              this.results = [];
              this.focusSearchInput();
            }
          })
          .catch((error) => console.log(error));
      }
    },
    returnData() {
      this.searchQuery = "";
      this.$emit("articulo", this.searchTerm);
      this.showResults = false;
      this.results = [];
      //this.noresult = false;
      this.focusSearchInput();
    },
    async checkLote(lotes) {
      var values = {};
      for (var i = 0; i < lotes.length; i++) {
        values[i] = lotes[i].lote_nro;
      }
      const { value: lote } = await Swal.fire({
        title: "Seleccione Lote",
        input: "select",
        inputOptions: values,
        inputPlaceholder: "Seleccione lote",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
      });
      if (lote) {
        this.searchTerm = lotes[value];
        this.returnData();
      }
    },
    selectPrevItem() {
      if (this.activeIndex === null) {
        this.activeIndex = 0;
      } else if (this.activeIndex > 0) {
        this.activeIndex--;
      }
    },
    selectNextItem() {
      if (this.activeIndex === null) {
        this.activeIndex = 0;
      } else if (this.activeIndex < this.results.length - 1) {
        this.activeIndex++;
      }
    },
    selectItem(index) {
      
      this.activeIndex = null;
      if (this.validarLote == "true") {
        this.getArticulo(true, this.results[index].producto_c_barra);
      } else {
        this.searchTerm = this.results[index];
        this.returnData();
      }
      
    },
    selectItemEnter() {
      if (this.results && this.activeIndex !== null) {
        if (this.validarLote == "true") {
          this.getArticulo(
            true,
            this.results[this.activeIndex].producto_c_barra
          );
        } else {
          this.selectItem(this.activeIndex);
        }
      } else {
        this.searchOnEnter();
      }
    },
    checkSelectedResultVisible(resultsElement) {
      const selectedResultElement = resultsElement.querySelector(
        `[data-result-index="${this.activeIndex}"]`
      );
      if (!selectedResultElement) {
        return;
      }

      const resultsPosition = resultsElement.getBoundingClientRect();
      const selectedPosition = selectedResultElement.getBoundingClientRect();

      if (selectedPosition.top < resultsPosition.top) {
        // Element is above viewable area
        resultsElement.scrollTop -= resultsPosition.top - selectedPosition.top;
      } else if (selectedPosition.bottom > resultsPosition.bottom) {
        // Element is below viewable area
        resultsElement.scrollTop +=
          selectedPosition.bottom - resultsPosition.bottom;
      }
    },
    focusSearchInput() {
      this.$refs.inputsearch.focus();
    },
    getAllArticulos() {
      setInterval(() => {
        if (document.visibilityState == "visible") {
          this.getArticulo(false, "");
        }
      }, 1000 * 60 * 5);
    },
  },
  mounted() {
    this.focusSearchInput();
  },
};
</script>
<style scoped>
.autocomplete {
  position: relative;
}
.autocomplete-result-container {
  position: absolute;
  z-index: 100;
  position: absolute;
  width: 100%;
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
  border-color: rgba(0, 0, 0, 0.12);
  background-color: #fff;
  outline: none;
  /* box-shadow: 0 2px 2px rgba(0, 0, 0, .16)*/
}
.dark-mode .autocomplete-input {
  border-color: rgba(0, 0, 0, 0.12);
  color: white;
  background-color: #343a40;
}

[data-position="below"] .autocomplete-input[aria-expanded="true"] {
  border-bottom-color: transparent;
  border-radius: 8px 8px 0 0;
}

[data-position="above"] .autocomplete-input[aria-expanded="true"] {
  border-top-color: transparent;
  border-radius: 0 0 8px 8px;
  z-index: 2;
}

.autocomplete[data-loading="true"]:after {
  content: "";
  border: 3px solid rgba(0, 0, 0, 0.12);
  border-right-color: rgba(0, 0, 0, 0.48);
  border-radius: 100%;
  width: 20px;
  height: 20px;
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  animation: rotate 1s linear infinite;
}

.autocomplete-result-list {
  margin: 0;
  border: 1px solid rgba(0, 0, 0, 0.12);
  padding: 0;
  box-sizing: border-box;
  max-height: 370px;
  overflow-y: auto;
  background: #fff;
  list-style: none;
  box-shadow: 0 2px 2px rgba(0, 0, 0, 0.16);
}
.dark-mode .autocomplete-result-list {
  background: #343a40;
}

[data-position="below"] .autocomplete-result-list {
  margin-top: -1px;
  border-top-color: transparent;
  border-radius: 0 0 8px 8px;
  padding-bottom: 8px;
}

[data-position="above"] .autocomplete-result-list {
  margin-bottom: -1px;
  border-bottom-color: transparent;
  border-radius: 8px 8px 0 0;
  padding-top: 8px;
}

.autocomplete-result {
  cursor: default;
  padding: 7px 7px 7px 48px;
  background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjY2NjIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PGNpcmNsZSBjeD0iMTEiIGN5PSIxMSIgcj0iOCIvPjxwYXRoIGQ9Ik0yMSAyMWwtNC00Ii8+PC9zdmc+");
  background-repeat: no-repeat;
  background-position: 12px;
  overflow: hidden;
}
.autocomplete-result .left {
  float: left;
  width: 80%;
}
.autocomplete-result .right {
  float: right;
}
.autocomplete-result:hover,
.autocomplete-result[aria-selected="true"] {
  background-color: rgba(0, 0, 0, 0.12);
}

@keyframes rotate {
  0% {
    transform: translateY(-50%) rotate(0deg);
  }

  to {
    transform: translateY(-50%) rotate(359deg);
  }
}
</style>
