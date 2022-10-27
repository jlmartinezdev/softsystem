<template>
<div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label>Monto de Venta</label>
                <p class="font-weight-bold text-primary">{{new Intl.NumberFormat("de-DE").format(total)}}</p>
            </div>
            <div class="form-group">
                <label>Interes %</label>
                <input type="number" class="form-control form-control-sm" v-model="interes" placeholder="Porcentaje ...">
            </div>
            <label><input type="checkbox" v-model="primeracuota"> Entrega primera cuota</label>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label>Saldo</label>
                <p class="font-weight-bold text-danger">{{new Intl.NumberFormat("de-DE").format(saldo)}}</p>
            </div>
            <div class="form-group">
                <label>Cantidad Cuota</label>
                <input type="Number" class="form-control form-control-sm" :disabled="!calcularcuota" v-model="cant_cuota" placeholder="Cant. Cuota ...">
            </div>
            
            
        </div>
        <div class="col-4">
            <div class="form-group">
                <label>Entrega</label>
                <input type="text" class="form-control number-separator form-control-sm" id="entrega" :disabled="primeracuota" placeholder="Monto ..."  style="text-align:right;" @keyup="getSaldo" >
            </div>
            <div class="form-group">
                <label><input type="checkbox" v-model="redondear"> Redondear Monto Cuota</label>
                <button @click="generar" class="btn btn-primary btn-sm btn-block"><span class="fa fa-cog"></span> Generar Cuota</button>
            </div>
        </div>
    </div>


    <hr> 
    <table class="table table-striped table-hover table-sm">
        <tr>
            <th>#</th>
            <th>Cuota</th>
            <th>vencimiento</th>
            <th>Tipo</th>
        </tr>
        <tbody>  
        <template v-for="c in cuotas">
            <tr>
                <td>{{c.nro}}</td>
                <td>{{new Intl.NumberFormat("de-DE").format(c.monto)}}</td>
                <td>{{c.vencimiento}}</td>
                <td>{{c.tipo}}</td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
    
</template>
<script>

export default {
    name: 'generar_cuota',
    props: ['total', 'fecha','datoscuota','calcularcuota'],
    data(){
        return{
            cant_cuota: 1,
            sentrega: '',
            entrega: 0,
            primeracuota: false,
            saldo: 0,
            interes: 0,
            cuota: { nro: 0, interes: 0, vencimiento: 0, monto: 0, tipo: 0 },
            oldDatos: {entrega: 0,saldo: 0},
            cuotas: [],
            redondear: false,
        }
       
    },
    watch: {
        total: function(){
            this.saldo = this.total;
            this.cuotas= [];
            if(!this.datoscuota.calcularcuota && this.datoscuota.iPrecio.includes('CR')){
                this.cant_cuota= Number(this.datoscuota.iPrecio.substr(2)) +2;
            }else{
                this.cant_cuota=0;
            }
        },
        calcularcuota: function(newVal, oldVal){
            
            if(!newVal && this.datoscuota.iPrecio.includes('CR')){
                //Extraer cantidad de cuota Ej:(CR2)
                this.cant_cuota= Number(this.datoscuota.iPrecio.substr(2)) +2;
            }
            else{
                this.cant_cuota= 0;
            }
        },
        primeracuota: function(newVal, oldVal){
            if(newVal){
                this.oldDatos.saldo= this.saldo;
                this.oldDatos.entrega= this.entrega;
                this.saldo= this.total;
                this.entrega= 0;
            }else{
                this.saldo= this.oldDatos.saldo;
                this.entrega= this.oldDatos.entrega;
            }
        }
    },
    methods: {
        generar: function () {
            this.cuotas= [];

            let monto_cuota, Importe, cantidad, restoDivision;
            let Dia;
            let i_plus=0;
            let indexcuota=0;
            
            Importe = this.saldo;
            cantidad = this.cant_cuota;
            
            if (cantidad < 1) {
                Swal.fire('Atención...','Cantidad de cuota es 0!','warning');
                return;
            }
            if (Importe < 1) {
                Swal.fire('Atención...','No se puede generar cuota Saldo es 0!','warning');
                return;
            }
            if(this.calcularcuota){
                monto_cuota = Number.parseInt(Importe / cantidad);
            }else{
                if(this.entrega > 0){
                     monto_cuota = Number.parseInt(Importe / cantidad);
                }else{
                    monto_cuota= this.datoscuota.monto_cuota;
                }
                
            }
           
            restoDivision = Importe % cantidad;

            if (this.interes > 0) {
                monto_cuota = monto_cuota + (monto_cuota * this.interes) / 100;
            }
            const f = this.fecha.split("-");
            
            let d = new Date(f[0],f[1]-1,f[2]);
            if(!this.primeracuota){
                if(this.entrega > 0 ){
                    this._setCuota(1,this._nextMonth(d,false),this.entrega,'Entrega');  //Entrega
                    indexcuota= 1;
                    i_plus++;
                }
            }
            

            if (this.redondear && cantidad > 1 && restoDivision!=0) {

                if (monto_cuota.toString().length > 3) {
                    let ultimo_digito= monto_cuota.toString().substr(monto_cuota.toString().length-1,3);
                    let ultimos_tresdigitos= monto_cuota.toString().substr(monto_cuota.toString().length-3,3);
                    
                    if(ultimos_tresdigitos==ultimo_digito.repeat(3)){
                        let primera_cuota= monto_cuota.toString().substr(0,monto_cuota.toString().length-4);
                        primera_cuota= (Number.parseInt(primera_cuota) + 1) * 10000 
                        this._setCuota(i_plus+1,this._nextMonth(d,this.primeracuota ? false : true),primera_cuota,this.primeracuota ? 'Entrega':'Cuota');
                        cantidad--;
                        i_plus++;
                        monto_cuota= (Importe - primera_cuota)/cantidad;
                    }
                }
            }

            for (var i = 1; i <= cantidad; i++) {
                this._setCuota(i+i_plus,this._nextMonth(d,i==1 && this.primeracuota ? false : true),monto_cuota,i==1 && this.primeracuota ? 'Entrega': 'Cuota');
            }
            this.getCuotas();
        },
        _nextMonth: function(d,next){
            if(next){
                d.setMonth(d.getMonth() + 1);
            }
            return d.getDate().toString().padStart(2,'0') + '-' + (d.getMonth()+1).toString().padStart(2,'0') + '-' + d.getFullYear();
        },
        _setCuota: function(n,vencimiento,monto,tipo){
            let cuota = {
                nro: n,
                interes: this.interes,
                vencimiento: vencimiento,
                monto: monto,
                tipo: tipo,
            };
            this.cuotas.push(cuota);
        },
        getCuotas: function () {
            this.$emit('cuotas',this.cuotas);
        },
        getSaldo: function(){
            let sentrega= $('#entrega').val();
            this.entrega= sentrega.replaceAll(',','');
            if(this.total >= this.entrega){
                this.saldo = this.total - this.entrega ;
            }else{
                Swal.fire('Atención...','Número ingresado es mayor a Monto de Venta!','warning');
                this.saldo = 0; 
            }
        }
           
    },
    mounted(){
        this.saldo= this.total;
    }

};

</script>