@extends('layouts.app')
@section('title', 'Ajuste Sistema')
@section('main')
<div class="container-fluid" id="app">
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-md-8">
                <h4 class="m-0">Ajustes del sistema</h4>
                <p class="text-muted mb-0 small">Caja, venta y servidor de correo.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-outline card-info">
                <div class="card-header"><strong>Caja</strong></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Validez apertura</label>
                        <select v-model="caja[0].value" class="form-control form-control-sm">
                            <option value="1">Día a día por fecha</option>
                            <option value="2">Cada 24 horas</option>
                            <option value="3">Indefinido</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Usuario</label>
                        <select v-model="caja[1].value" class="form-control form-control-sm">
                            <option value="1">Una apertura multiusuario</option>
                            <option value="2">Una apertura por cada usuario</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-header"><strong>Venta</strong></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Comprobante predeterminado</label>
                        <select v-model="venta.tipo_comprobante" class="form-control form-control-sm">
                            <option value="Ticket">Ticket</option>
                            <option value="Comprobante">Comprobante</option>
                            <option value="Factura">Factura</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tamaño ticket</label>
                        <select v-model="venta.tamano_ticket" class="form-control form-control-sm">
                            <option value="80mm">80mm</option>
                            <option value="56mm">56mm</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descontar stock</label>
                        <select v-model="venta.descontar_stock" class="form-control form-control-sm">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Vender sin stock</label>
                        <select v-model="venta.vender_sin_stock" class="form-control form-control-sm">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-outline card-success">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><span class="fa fa-envelope"></span> Servidor de correo (SMTP)</strong>
                    <span class="badge" :class="mail.activo ? 'badge-success' : 'badge-secondary'">
                        @{{ mail.activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="mailActivo" v-model="mail.activo">
                        <label class="custom-control-label" for="mailActivo">Habilitar envío de correos</label>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Host SMTP</label>
                            <input type="text" class="form-control form-control-sm" v-model.trim="mail.host"
                                placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Puerto</label>
                            <input type="number" class="form-control form-control-sm" v-model="mail.port"
                                placeholder="587">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Cifrado</label>
                            <select class="form-control form-control-sm" v-model="mail.encryption">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="null">Ninguno</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Usuario</label>
                            <input type="text" class="form-control form-control-sm" v-model.trim="mail.username"
                                placeholder="correo@dominio.com" autocomplete="off">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Contraseña</label>
                            <input type="password" class="form-control form-control-sm" v-model="mail.password"
                                placeholder="Dejar vacío para no cambiar" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Remitente (From)</label>
                            <input type="email" class="form-control form-control-sm" v-model.trim="mail.from_address"
                                placeholder="noreply@dominio.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nombre remitente</label>
                            <input type="text" class="form-control form-control-sm" v-model.trim="mail.from_name"
                                placeholder="SoftSystem">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Enviar a (destinatarios)</label>
                        <input type="text" class="form-control form-control-sm" v-model.trim="mail.to"
                            placeholder="admin@empresa.com, contabilidad@empresa.com">
                        <small class="text-muted">Separá varios correos con coma.</small>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="mailCierre" v-model="mail.cierre_caja">
                        <label class="custom-control-label" for="mailCierre">
                            Enviar resumen automático al cerrar caja
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-outline-info btn-sm" @click="testMail" :disabled="mailTesting">
                        <span class="fa" :class="mailTesting ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></span>
                        @{{ mailTesting ? 'Enviando...' : 'Probar correo' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-success" @click="update" :disabled="guardando">
            <span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
            Guardar ajustes
        </button>
    </div>
</div>
@endsection
@section('script')
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
    });

    var app = new Vue({
        el: '#app',
        data: {
            guardando: false,
            mailTesting: false,
            caja: [{
                name: 'validez',
                value: '{{ $ajuste[0]->value ?? 1 }}',
            }, {
                name: 'usuario',
                value: '{{ $ajuste[1]->value ?? 1 }}'
            }],
            venta: {
                tipo_comprobante: 'Ticket',
                descontar_stock: 1,
                vender_sin_stock: 1,
                tamano_ticket: '80mm',
            },
            mail: {
                activo: {{ !empty($mail['activo']) ? 'true' : 'false' }},
                host: @json($mail['host'] ?? ''),
                port: @json($mail['port'] ?? '587'),
                username: @json($mail['username'] ?? ''),
                password: '',
                encryption: @json($mail['encryption'] ?? 'tls'),
                from_address: @json($mail['from_address'] ?? ''),
                from_name: @json($mail['from_name'] ?? 'SoftSystem'),
                to: @json($mail['to'] ?? ''),
                cierre_caja: {{ !empty($mail['cierre_caja']) ? 'true' : 'false' }}
            }
        },
        methods: {
            mailPayload: function () {
                var payload = Object.assign({}, this.mail);
                if (!payload.password) {
                    delete payload.password;
                }
                return payload;
            },
            updateCaja: function () {
                return axios.post('{{ url('ajustes') }}', {
                    caja: this.caja,
                    mail: this.mailPayload()
                });
            },
            updateVenta: function () {
                localStorage.setItem('config_venta', JSON.stringify(this.venta));
            },
            getConfigVenta: function () {
                var venta = localStorage.getItem('config_venta');
                if (venta != null) {
                    try {
                        this.venta = JSON.parse(venta);
                        if (!this.venta.tamano_ticket) {
                            this.venta.tamano_ticket = '80mm';
                        }
                    } catch (e) {}
                }
            },
            update: function () {
                var self = this;
                this.guardando = true;
                this.updateVenta();
                this.updateCaja()
                    .then(function () {
                        self.guardando = false;
                        self.mail.password = '';
                        Toast.fire({ title: 'Ajustes actualizados', icon: 'success' });
                    })
                    .catch(function (error) {
                        self.guardando = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo guardar';
                        Swal.fire('Error', msg, 'error');
                    });
            },
            testMail: function () {
                var self = this;
                this.mailTesting = true;
                axios.post('{{ route('ajuste.mail.test') }}', { mail: this.mailPayload() })
                    .then(function (response) {
                        self.mailTesting = false;
                        self.mail.password = '';
                        Swal.fire('Listo', response.data.message || 'Correo enviado', 'success');
                    })
                    .catch(function (error) {
                        self.mailTesting = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo enviar el correo de prueba';
                        Swal.fire('Error SMTP', msg, 'error');
                    });
            }
        },
        mounted: function () {
            this.getConfigVenta();
            activarMenu('m_ajuste');
        }
    });
</script>
@endsection
