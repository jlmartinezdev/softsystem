<template>
    <input :id="id" :tabindex="tabindex" @keyup="keyup" @keypress="isNumber($event)" v-model="text" onfocus="this.select()" :placeholder="placeholder" :class="Classes.input" type="text" />
</template>
<script>
const defaultClasses ={
    input: "form-control form-control-sm"
}
export default {
    name: "inNumber",
    props: ["value","placeholder","clases","id","tabindex","enabled","moneda"],
    data() {
        return {
            text: "",
            Classes: {
            ...defaultClasses,
            ...this.clases
            },
            monedas: ['GS ','USD ','$a ','R$ ','€ ']
        }
    },
    watch: {
      value: function (newVal, oldVal) {
        this.text= this.addCommas(newVal.toString().replace(/,/g, ''));
      }
    },
    methods: {
        addCommas(nStr) {
            let x,x1,x2;
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return this.monedas[this.moneda]+x1 + x2;
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
            if(this.text==""){
                //this.text= 0;
                this.$emit("input",0);
                return true;
            }
            if(this.text.substr(0,1)=="0"){
                this.text= this.text.substr(1,this.text.length);
            }
            //this.text = this.addCommas(this.text.replace(/,/g, ''));
            this.$emit("input", parseInt(this.text.replace(/,/g, '')));
            this.$emit("change",true);
            
            
        }
    },
    mounted(){
        this.text= this.value;
    }
}
</script>

