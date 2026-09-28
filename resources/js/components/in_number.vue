<template>
    <input :id="id" :tabindex="tabindex" @keyup="keyup" @keypress="isNumber($event)" v-model="text" onfocus="this.select()" :placeholder="placeholder" :class="inputClass" type="text" />
</template>
<script>
const defaultClasses = {
    input: "form-control form-control-sm"
};

export default {
    name: "inNumber",
    props: {
        value: {},
        placeholder: {},
        clases: {},
        id: {},
        tabindex: {},
        enabled: {},
    },
    data() {
        return {
            text: ""
        };
    },
    computed: {
        inputClass() {
            if (typeof this.clases === 'string' && this.clases) {
                return this.clases;
            }
            if (this.clases && typeof this.clases === 'object' && this.clases.input) {
                return this.clases.input;
            }
            return defaultClasses.input;
        }
    },
    watch: {
        value: function (newVal) {
            if (newVal === null || newVal === undefined || newVal === '') {
                this.text = '';
                return;
            }
            this.text = this.addCommas(newVal.toString().replace(/,/g, '').replace(/\./g, ''));
        }
    },
    methods: {
        addCommas(nStr) {
            if (nStr === null || nStr === undefined) return '';
            let x, x1, x2;
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        },
        isNumber: function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        keyup() {
            if (this.text === "" || this.text === null) {
                this.$emit("input", 0);
                this.$emit("change", true);
                return true;
            }
            var clean = this.text.toString().replace(/,/g, '').replace(/\./g, '');
            if (clean.length > 1 && clean.substr(0, 1) === "0") {
                clean = clean.substr(1);
            }
            var parsed = parseInt(clean, 10);
            if (isNaN(parsed)) parsed = 0;
            this.$emit("input", parsed);
            this.$emit("change", true);
        }
    },
    mounted() {
        if (this.value !== null && this.value !== undefined && this.value !== '') {
            this.text = this.addCommas(this.value.toString().replace(/,/g, '').replace(/\./g, ''));
        } else {
            this.text = '';
        }
    }
};
</script>
