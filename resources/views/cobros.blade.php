@extends('layouts.app')
@section('title', 'Gestionar Cobros')
@section('style')
    <link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .form-group {
            margin-bottom: 0rem;
        }

        .form-control-sm {
            height: calc(1.6rem + 2px);
        }

        .form-group label {
            margin-bottom: 0rem;
            font-weight: bold;
        }

        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            height: 350px;
            overflow-y: auto;
        }

        .table th {
            vertical-align: middle;
        }

        .tabla td {
            padding: .75rem;
        }

        .input-millares {
            font-size: 14pt;
            font-weight: bold;
        }

    </style>

@endsection
@section('main')
    <div id="app">
        <div class="row">
            <!-- PANEL IZQUIERDO -->
            <div class="col-md-9">
                <div class="card">
                    <div class="p-2 mb-3">
                        <!-- Buscador -->
                        <div class="input-group">
                            <input type="text" v-model="txtbuscar" tabindex="1" @keyup.enter="buscar(false)"
                                class="form-control" id="txtbuscar" placeholder="Buscar...." />
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="buscar(false)">
                                    <template v-if="request.buscar">
                                        <span class="spinner-border spinner-border-sm" role="status"></span><span
                                            class="sr-only">Buscando...</span> Cargando...
                                    </template>
                                    <template v-else>
                                        <span class="fa fa-search"></span> Buscar
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Buscador -->

                    <div class="table-responsive-sm ml-2 mr-2">
                        <table class="table table-sm table-hover table-bordered" style="overflow-x: initial; heigh: 100px">
                            <tr class="bg-light">
                                <th>N de Venta</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Cant. Cuota</th>
                                <th>Cuota Cobrado</th>
                                <th>Saldo</th>
                                <th>Estado</th>
                                <th><span class="fa fa-cog"></span></th>
                            </tr>
                            <template v-if="ctas.length==0">
                                <tr>
                                    <td colspan="8">NO HAY CUENTA PARA MOSTRAR...</td>
                                </tr>
                            </template>
                            <template v-for="(c,index) in ctas">
                                <tr>
                                    <td>@{{ c.nro_fact_ventas }}</td>
                                    <td>@{{ c.venta_fecha }}</td>
                                    <td>@{{ format(c.total) }}</td>
                                    <td>@{{ checkCantidad(c.pagada, c.nro_fact_ventas) + " de " + checkCantidad(c.cuotas, c.nro_fact_ventas) }}</td>
                                    <td>@{{ format(c.cobrado) }}</td>
                                    <td class="text-danger font-weight-bold">@{{ format(c.saldo) }}</td>
                                    <td>
                                        <template v-if="c.saldo==0">
                                            <span class="badge badge-succes"> CANCELADO</span>
                                        </template>
                                        <template>
                                            <span class="badge badge-danger"> PENDIENTE</span>
                                        </template>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <span class="fa fa-bars"></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button class='dropdown-item text-primary' @click="showDetalle(index)"><span
                                                        class="fa fa-edit"></span> Detalle de Venta</button>
                                                <div class="dropdown-divider"></div>
                                                <button class='dropdown-item text-primary'
                                                    @click="showCuotas(c.nro_fact_ventas)"><span
                                                        class="fa fa-list"></span> Seleccionar Cuota</button>

                                            </div>
                                        </div>

                                    </td>
                                </tr>


                            </template>
                        </table>


                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div>
                                    TOTAL COBRADO: &nbsp;<input type="text" :value="format(cobro.cobrado)" disabled
                                        class="form-control form-control-sm text-success font-weight-bold text-monospace">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div>
                                    SALDO: <input type="text" :value="format(cobro.saldo)" disabled
                                class="form-control form-control-sm text-danger font-weight-bold text-monospace">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                
                                <div class="mt-4"><button class="btn btn-primary btn-sm"
                                    @click="modalCobroParcial"><span class="fa fa-edit"></span>&nbsp;Ingresar
                                    Monto</button></div>

                            </div>
                        </div>
                    
                    </div>


                </div>
                <template v-if="cuotasAcobrar.length > 0">
                    <div class="card">
                        <div class="card-header bg-info">Cuotas a Cobrar</div>
                        <div class="card-body">
                            <div class="table-responsive-sm">
                                <table class="table table-sm table-bordered table-hover">
                                    <tr>
                                        <th>N de Cuota</th>
                                        <th>N de Venta</th>
                                        <th>Vencimiento</th>
                                        <th>Mora</th>
                                        <th>Interes</th>
                                        <th>Monto Cuota</th>
                                        <th>Cobrado</th>
                                        <th>Monto a Cobrar</th>
                                    </tr>
                                    <template v-for="(c,index) in cuotasAcobrar">
                                        <tr>
                                            <td>@{{ checkCantidad(c.nro_cuotas, c.nro_fact_ventas) }}</td>
                                            <td>@{{ c.nro_fact_ventas }}</td>
                                            <td>@{{ formatFecha(c.fecha_venc) }}</td>
                                            <td>@{{ diferenciaFecha(c.fecha_venc, c.monto_cobrado) }}</td>
                                            <td>@{{ format(c.interes) }}</td>
                                            <td>@{{ format(c.monto_saldo) }}</td>
                                            <td>@{{ format(c.monto_cobrado) }}</td>
                                            <td>@{{ format(c.acobrar) }}</td>
                                        </tr>


                                    </template>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
            <!-- PANEL DERECHO -->
            <div class="col-md-3">
                <div class="card p-2">

                    <div class="text-secondary text-center">
                        <span class="badge badge-default">
                            <span class="fa fa-cash-register"></span> CAJA
                        </span>
                        <span class="badge badge-pill "
                            :class="[caja.estado == 'ABIERTA' ? 'badge-success pr-2 pl-2' : 'badge-danger']">
                            @{{ caja.estado }} </span>
                        |<span class="badge badge-default">
                            <span class="fa fa-info-circle"></span> #
                        </span>
                        <span class="badge badge-pill "
                            :class="[caja.estado == 'ABIERTA' ? 'badge-success' : 'badge-danger']"> @{{ caja.nrooperacion }}
                        </span>
                    </div>
                    <hr>
                     <label for="interes" @click="setCheckInteres"><input v-model="cobro.cobrarInteres" type="checkbox" id="interes"> Cobrar Interes por Mora</label>
                    <hr>
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" class="form-control form-control-sm" v-model="cobro.fecha"
                            placeholder="Fecha">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 ">
                        <p class="text-muted">
                            <i class="fa fa-id-card"></i> Doc:
                        </p>
                        <p class=" text-right">
                            <template>@{{ cliente.documento }}</template>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center ">
                        <p class="text-muted">
                            <i class="fa fa-user"></i> Cliente:
                        </p>
                        <p class=" text-right">
                            <template>@{{ cliente.nombre }}</template>
                        </p>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center ">
                        <p class="text-warning text-xl">
                            <i class="fa fa-money-check-alt"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <template>@{{ format(cobro.saldonuevo) }}</template>

                            </span>
                            <span class="text-muted">Saldo</span>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center ">
                        <p class="text-success text-xl">
                            <i class="fa fa-dollar-sign"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <template>@{{ totalCobrar }}</template>

                            </span>
                            <span class="text-muted">Total a Cobrar</span>
                        </p>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-app bg-success" @click="showFinalizar">
                            <span class="fa fa-check"></span> FINALIZAR
                        </button>&nbsp;
                        <button class="btn btn-app bg-secondary " @click="cancelar"> <span class="fa fa-times"></span>
                            CANCELAR</button>
                    </div>


                </div>
            </div>
        </div>
        @include('cobro.cuotas')
        @include('venta.detalle')
        @include('cobro.finalizar')
        @include('cobro.cliente')
        @include('cobro.cobro-parcial')
    </div>
@endsection
@section('script')
<script>
    var app = new Vue({
        el: "#app",
        data: {
            request: { buscar: false,cuota: false,cliente: false },
            txtbuscar: '',
            inNumberClass: {
                input: 'form-control input-millares'
            },
            montoParcial: 0,
            caja: {
                estado: 'CERRADO',
                id: 0,
                nrooperacion: 0
            },
            filtro: {
                orden: 'ASC',
                busquedapor: 'cliente',
                ordenarpor: '0',
                tipo: ''
            },
            cobro: {
                total: 0,
                saldo: 0,
                saldonuevo: 0,
                cobrado: 0,
                entrega: 0,
                fecha: '',
                idSucursal: 0,
                totalInteres: 0,
                cobrarInteres: true
            },
            cliente: {
                id: 0,
                documento: 0,
                nombre: '.-'
            },
            descontarCantidad: 0,
            txtcliente: '',
            ctas: [],
            articulos: [],
            cuotas: [],
            cuotasAcobrar: [],
            clientes: [],
            allCuota: [],
            venta: {}
        },
        methods: {
            buscar: function(parm) {
                if (this.txtbuscar.length < 1)
                    return

                var t = parseFloat(this.txtbuscar);
                if (isNaN(t)) {
                    this.txtcliente = this.txtbuscar
                    $('#busquedaCliente').modal('show');
                    this.buscarCliente();
                    setTimeout(() => {
                        document.getElementById('txtcliente').focus();
                    }, 500);

                } else {
                    this.request.buscar = true;
                    this.filtro.busquedapor = "ci";
                    this.getCta(this.txtbuscar);
                }

            },
            checkPrimeraCuota: function(cantidad, nroventa) {
                let primeracuota = this.allCuota.find(cuota => cuota.nro_fact_ventas == nroventa && cuota.nro_cuotas == 1);
                
                if(primeracuota==undefined){
                    return cantidad -1;
                }
                if (primeracuota.monto_cuota == 0 && primeracuota.monto_saldo == 0) {
                    return cantidad - 1;
                } else {
                    return cantidad;
                }
            },
            checkCantidad: function(cantidad, nroventa) {
                let primeracuota = this.allCuota.find(cuota => cuota.nro_fact_ventas == nroventa && cuota.nro_cuotas == 1);
                
                if(primeracuota==undefined){
                    return cantidad;
                }
                if (primeracuota.monto_cuota == 0 && primeracuota.monto_saldo == 0) {
                    return cantidad - 1;
                } else {
                    return cantidad;
                }
            },
            getCta: function(abuscar) {
                axios.get('ctas_cobrar/buscar', {
                        params: {
                            buscar: abuscar,
                            buscarpor: this.filtro.busquedapor,
                            ordenarpor: this.filtro.ordenarpor,
                            ord: this.filtro.orden,
                            tipo: "cliente",
                            from: "cobro"
                        }
                    })
                    .then(response => {
                        this.request.buscar = false;
                        if (response.data.ctas.length == 0) {
                            Swal.fire('No posee cuenta a Cobrar!', this.filtro.ci ? 'Documento Nro: ' +
                                abuscar : 'Cliente: ' + abuscar, 'info');
                        } else {
                            this.cobro.cobrado = 0;
                            this.cobro.saldo = 0;
                            this.ctas = response.data.ctas;
                            this.articulos = response.data.articulos;
                            this.allCuota = response.data.cuotas;
                            if (this.ctas.length > 0) {
                                this.cliente.id = this.ctas[0].cliente_ruc;
                                this.cliente.nombre = this.ctas[0].cliente_nombre;
                                this.cliente.documento = this.ctas[0].cliente_ruc;
                            }
                            for (i = 0; i < this.ctas.length; i++) {
                                this.cobro.saldo += parseInt(this.ctas[i].saldo);
                                this.cobro.cobrado += parseInt(this.ctas[i].cobrado);
                            }

                            // this.paginacion= response.data.paginacion;
                            //this.paginacion.pagina_actual=1;
                        }
                        this.request.buscar = false;
                        //this.error=response.data;
                    })
                    .catch(e => {
                        this.request.buscar = false;
                        this.error = e.message;
                    });
            },
            buscarCliente: function() {
                if (this.txtcliente.length > 0) {
                    var doc = '';
                    var nom = '';
                    if (isNaN(parseFloat(this.txtcliente))) {
                        nom = this.txtcliente;
                    } else {
                        doc = this.txtcliente;
                    }
                    this.request.cliente = true;
                    axios.get('cliente/buscar', {
                        params: {
                            documento: doc,
                            nombre: nom
                        }
                    })
                    .then(response => {
                        this.clientes = response.data;
                        this.request.cliente = false;
                    })
                    .catch(error => {
                        this.request.cliente = false;
                        console.log(error.message);
                    })
                }
            },
            selectCliente: function(ci) {
                this.cuotasAcobrar= [];
                this.filtro.busquedapor = "ci";
                $('#busquedaCliente').modal('hide');
                this.getCta(ci);
            },
            getCuotas: function(nroventa) {
                this.cuotas = this.allCuota.filter(function(cuota) {
                    return cuota.nro_fact_ventas == nroventa
                })
            },
            cancelar: function() {
                this.cliente = {
                    id: 0,
                    documento: 0,
                    nombre: '.-'
                };
                this.ctas = [];
                this.cobro = {
                    total: 0,
                    saldo: 0,
                    saldonuevo: 0,
                    cobrado: 0,
                    entrega: 0,
                    fecha: '',
                    idSucursal: 0,
                    totalInteres: 0,
                    cobrarInteres: true
                };
                this.descontarCantidad = 0;
                this.articulos = [];
                this.txtbuscar = '';
                this.cuotas = [];
                this.cuotasAcobrar = [];
                this.getFecha();
                this.getApertura();
            },
            finalizar: function() {
                axios.post('cobro', {
                        cuotas: this.cuotasAcobrar,
                        cobro: this.cobro
                    })
                    .then(response => {
                        if (response.data > 0) {
                            window.location.assign('/documento/recibocobro/' + response.data);

                        }
                    })
                    .catch(error => {
                        console.log(error.message);
                    })

            },
            addCuota: function() {
                for (i = 0; i < this.cuotas.length; i++) {
                    if (this.cuotas[i].check) {
                        let validar = this.cuotasAcobrar.findIndex(x => x.nro_cuotas == this.cuotas[i]
                            .nro_cuotas && x.nro_fact_ventas == this.cuotas[i].nro_fact_ventas);
                        if (validar == -1) {
                            this.pushCuota(this.cuotas[i]);
                        }
                    }
                }
                $('#selCuotas').modal('hide');

            },
            pushCuota: function(cuota){
                let c = {
                        estado: cuota.estado,
                        fecha_venc: cuota.fecha_venc,
                        interes: this.cobro.cobrarInteres ?  this.setMontoInteres(cuota.fecha_venc, cuota.monto_cuota) : 0,
                        monto_cobrado: cuota.monto_cobrado,
                        monto_cuota: cuota.monto_cuota,
                        monto_saldo: cuota.monto_saldo,
                        nro_cuotas: cuota.nro_cuotas,
                        nro_fact_ventas: cuota.nro_fact_ventas,
                        acobrar: cuota.monto_saldo
                    }
                    this.cuotasAcobrar.push(c);
            },
            cobroParcial: function() {
                $('#cobroParcial').modal('hide');
                $('#txtbuscar').focus();
                if (this.montoParcial > 0) {
                    if (this.montoParcial > this.cobro.saldo) {
                        this.montoParcial=0;
                        Swal.fire('Datos incorrecto...','Monto ingresado es mayor al saldo!','error');
                        return false;
                    }
                    
                    let iCuotasAcobrar = [];
                    let sumatoria = 0;

                    for (i = 0; i < this.allCuota.length; i++) { //mientras cuotas seleccionada sea menor a monto a cobrar

                        if (sumatoria < this.montoParcial) {
                            if (this.allCuota[i].monto_saldo > 0) { //Verifica si cuota ya se cobro
                                iCuotasAcobrar[i] = true // Marcar posicion en array como agregado (TRUE)
                                sumatoria += parseInt(this.allCuota[i].monto_saldo)
                            } else {
                                iCuotasAcobrar[i] = false
                            }
                        } else {
                            iCuotasAcobrar[i] = false;
                        }
                    }

                    for(i=0;i< iCuotasAcobrar.length; i++){
                        if(iCuotasAcobrar[i]){
                            this.pushCuota(this.allCuota[i]);
                        }
                    } 

                    for (i = 0; i < this.cuotasAcobrar.length; i++) {
                        sumatoria += this.cuotasAcobrar[i].interes;
                    }
                    if(sumatoria >= this.montoParcial){
                        let lastIndex= this.cuotasAcobrar.length -1;
                        let lastAcobrar= this.cuotasAcobrar[lastIndex].acobrar;
                        let monto_resto = sumatoria - this.montoParcial;
                        
                        this.cuotasAcobrar[lastIndex].acobrar= lastAcobrar - monto_resto;
                    }

                }
            },
            modalCobroParcial: function() {
                if (this.cobro.saldo > 0 && this.cuotasAcobrar.length==0) {
                    $('#cobroParcial').modal('show');
                    $('#txtparcial').focus();
                }

            },
            detalleVenta: function(nroventa) {
                return this.articulos.filter(function(venta) {
                    return venta.nro_fact_ventas == nroventa
                })
            },
            format: function(numero) {
                return new Intl.NumberFormat("de-DE").format(numero);
            },
            formatFecha: function(fecha) {
                const f = fecha.split("-");
                return f[2] + "/" + f[1] + "/" + f[0];
            },
            subFecha: function(startFecha) {
                const fechaInicio = new Date(startFecha).getTime();
                const fechaFin = new Date().getTime(); 
                if (fechaInicio > fechaFin) {
                    return 0;
                }
                const diff = fechaFin - fechaInicio;
                return parseInt(diff / (1000 * 60 * 60 * 24));
            },
            diferenciaFecha: function(fecha_vent, pagada) {
                //2016-07-12
                const dia = this.subFecha(fecha_vent)
                
                //let diferenciaFecha = 0;
                if (pagada == 0) {
                    /* if ((dia - 30) > 0) {
                        return dia - 30;
                    } else {
                        return "-";
                    } */
                    return dia
                } else {
                    return "-"
                }
            },
            validarContador(cantidad, index, monto) {
                if (index == 0 && parseInt(monto) > 0) {
                    this.descontarCantidad = 1;
                }
                return cantidad - this.descontarCantidad;
            },
            getFecha: function() {

                var f = new Date();
                var dia = f.getDate();
                var mes = (f.getMonth() + 1);
                this.cobro.fecha = f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" +
                    dia.toString().padStart(2, "0");
                //this.filtrovalue= this.meses[mes];
            },
            checkCuota: function(index) {
                const check = document.getElementById('check' + index).checked;
                if (!check) {
                    let validar = this.cuotasAcobrar.findIndex(x => x.nro_cuotas == this.cuotas[
                            index].nro_cuotas && x.nro_fact_ventas == this.cuotas[index]
                        .nro_fact_ventas);
                    if (validar > -1) {
                        this.cuotasAcobrar.splice(validar, 1);
                    }

                }
                this.cuotas[index].check = check;
            },
            showCuotas: function(id) {
                $('#selCuotas').modal('show');
                this.getCuotas(id);
            },
            showDetalle: function(i) {
                this.venta = this.ctas[i];

                $('#frmdetalle').modal('show');
            },
            showFinalizar: function() {
                if (this.cuotasAcobrar.length > 0)
                    $('#saveCuotas').modal('show');
            },
            getApertura: function() {
                let idSucursal = $('#sucursal').attr('data-id');
                this.cobro.idSucursal = idSucursal;
                if (idSucursal != null) {
                    axios.get('aperturacierre/' + idSucursal)
                        .then(response => {
                            if (response.data) {
                                this.caja.nrooperacion = response.data.nro_operacion;
                                this.cobro.nro_operacion = response.data.nro_operacion;
                                this.caja.estado = 'ABIERTA';
                            } else {
                                this.caja.estado = 'CERRADA';
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
            },
            numeroaletra: function(n) {
                return NumeroALetras.NumeroALetras(n);
            },
            setMontoInteres: function(vencimiento, monto) {
                let montoInteres = 0;
                const interes_mora = 100;
                const tmp_vencimiento = this.subFecha(vencimiento);

                if (interes_mora > 0 && tmp_vencimiento > 5) {
                    montoInteres = (monto * interes_mora) / 100;
                    montoInteres = montoInteres / 360;
                    montoInteres = montoInteres * tmp_vencimiento;
                }
                return parseInt(montoInteres);
            },
            setCheckInteres: function(){
                for (let i = 0; i < this.cuotasAcobrar.length; i++) {
                    this.cuotasAcobrar[i].interes=  !this.cobro.cobrarInteres ?  this.setMontoInteres(this.cuotasAcobrar[i].fecha_venc, this.cuotasAcobrar[i].monto_cuota) : 0 ;
                }
            }
        },
        computed: {
            totalCobrar: function() {
                this.cobro.total = 0;
                this.cobro.totalInteres = 0;
                this.cobro.saldonuevo = 0;
                if (this.cuotasAcobrar.length > 0) {
                    for (i = 0; i < this.cuotasAcobrar.length; i++) {
                        this.cobro.total += parseInt(this.cuotasAcobrar[i].acobrar) + parseInt(this.cuotasAcobrar[i].interes);
                        this.cobro.totalInteres += this.cuotasAcobrar[i].interes;
                    }
                    this.cobro.saldonuevo = this.cobro.saldo - (this.cobro.total - this.cobro.totalInteres);
                }

                return this.format(this.cobro.total);
            }
        },
        mounted() {
            this.getFecha();
            this.getApertura();
        }
    });
    activarMenu('m_cobro', '');
</script>
@endsection
