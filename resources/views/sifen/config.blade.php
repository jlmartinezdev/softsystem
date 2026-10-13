@extends('layouts.app')
@section('title', 'Facturación electrónica')
@section('main')
    <div class="container-fluid" id="app">
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4 class="m-0">Facturación electrónica</h4>
                    <p class="text-muted mb-0 small">Emisión por API. El certificado, el timbrado y el CSC se configuran en api_sifen.</p>
                </div>
                <div class="col-sm-4 text-sm-right">
                    <span class="badge badge-pill" :class="config.activo ? 'badge-success' : 'badge-secondary'">
                        @{{ config.activo ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="badge badge-pill badge-primary">API</span>
                    <span class="badge badge-pill" :class="config.ambiente === 'prod' ? 'badge-danger' : 'badge-info'">
                        @{{ config.ambiente === 'prod' ? 'Producción' : 'Prueba' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="alert alert-warning" v-if="faltantes.length && config.activo">
            <strong>Configuración incompleta:</strong>
            <span v-for="(f, i) in faltantes" :key="i">@{{ f }}<span v-if="i < faltantes.length - 1">, </span></span>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <strong>Conexión con la API</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="sifenActivo" v-model="config.activo">
                                <label class="custom-control-label" for="sifenActivo">Habilitar facturación electrónica</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ambiente</label>
                                    <select class="form-control form-control-sm" v-model="config.ambiente">
                                        <option value="test">Prueba (test)</option>
                                        <option value="prod">Producción</option>
                                    </select>
                                    <small class="form-text text-muted">En producción, al emitir se envía la confirmación a SET.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Moneda</label>
                                    <input type="text" class="form-control form-control-sm" v-model="config.moneda" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border small">
                            Microservicio <a href="https://github.com/jlmartinezdev/api_sifen" target="_blank">api_sifen</a>.
                            SoftSystem crea el borrador, lo prepara y lo emite. La venta viaja como <code>VTA-{nro}</code>.
                        </div>
                        <div class="form-group">
                            <label>URL base de la API</label>
                            <input type="text" class="form-control form-control-sm" v-model="config.api_url" placeholder="http://sifen-api.test:8080">
                            <small class="form-text text-muted">Sin barra final. Se agrega <code>/api/v1</code> si falta.</small>
                        </div>
                        <div class="form-group">
                            <label>Token Bearer</label>
                            <textarea class="form-control form-control-sm" rows="2" v-model="config.api_token" placeholder="Token de la API"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="apiEnviarSifen" v-model="config.api_enviar_sifen">
                                    <label class="custom-control-label" for="apiEnviarSifen">Enviar a SIFEN (SET) al emitir</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="apiEnviarCorreo" v-model="config.api_enviar_correo">
                                    <label class="custom-control-label" for="apiEnviarCorreo">Enviar XML/KuDE por correo</label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" @click="probarApi" :disabled="probandoApi">
                                <span class="fa fa-plug"></span> Probar conexión
                            </button>
                        </div>
                        <hr>
                        <h6 class="mb-2">Obtener token</h6>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="email" class="form-control form-control-sm mb-2" v-model="apiLogin.email" placeholder="email de la API">
                            </div>
                            <div class="col-md-4">
                                <input type="password" class="form-control form-control-sm mb-2" v-model="apiLogin.password" placeholder="contraseña" autocomplete="new-password">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-block" @click="obtenerTokenApi" :disabled="obteniendoToken">
                                    <span class="fa fa-key"></span> Obtener
                                </button>
                            </div>
                        </div>
                        <p class="small text-muted">No se guarda la contraseña. Solo el token.</p>
                        <div class="form-group mb-0">
                            <label>Observaciones</label>
                            <textarea class="form-control form-control-sm" rows="2" v-model="config.observaciones" placeholder="Notas internas"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-success btn-sm" @click="guardar" :disabled="guardando">
                            <span class="fa fa-save"></span> Guardar configuración
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <strong>Estado</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <span class="fa fa-check-circle text-success" v-if="puedeEmitir"></span>
                                <span class="fa fa-times-circle text-danger" v-else></span>
                                Lista para emitir por API
                            </li>
                            <li class="mb-2">Documentos registrados: <strong>@{{ documentos.length }}</strong></li>
                            <li v-if="empresa">
                                Empresa: <strong>@{{ empresa.emp_nombre }}</strong><br>
                                RUC empresa: @{{ empresa.emp_ruc }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card card-outline card-info mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Últimos documentos</strong>
                        <button type="button" class="btn btn-link btn-sm p-0" @click="cargarDocumentos">Actualizar</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 420px;">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Venta</th>
                                        <th>Nº</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!documentos.length">
                                        <td colspan="3" class="text-muted text-center">Sin documentos</td>
                                    </tr>
                                    <tr v-for="d in documentos" :key="d.id">
                                        <td>@{{ d.nro_fact_ventas }}</td>
                                        <td>@{{ formatoDoc(d) }}</td>
                                        <td>
                                            <span class="badge badge-pill" :class="badgeEstado(d.estado)">@{{ d.estado }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
                probandoApi: false,
                obteniendoToken: false,
                apiLogin: { email: '', password: '' },
                config: @json($config),
                documentos: @json($documentos),
                faltantes: @json($faltantes),
                empresa: @json($empresa),
            },
            computed: {
                puedeEmitir: function() {
                    return this.config.activo && this.faltantes.length === 0;
                },
            },
            methods: {
                pad: function(val, len) {
                    var s = (val == null ? '' : String(val)).trim();
                    if (!s) s = '0';
                    return s.length >= len ? s : ('0'.repeat(len) + s).slice(-len);
                },
                badgeEstado: function(estado) {
                    var map = {
                        pendiente: 'badge-warning',
                        enviado: 'badge-info',
                        aprobado: 'badge-success',
                        rechazado: 'badge-danger',
                        anulado: 'badge-secondary',
                    };
                    return map[estado] || 'badge-light';
                },
                formatoDoc: function(d) {
                    if (!d.numero) return '—';
                    return this.pad(d.establecimiento, 3) + '-' + this.pad(d.punto_expedicion, 3) + '-' + this.pad(d.numero, 7);
                },
                guardar: function() {
                    var self = this;
                    self.guardando = true;
                    var payload = {
                        activo: self.config.activo ? 1 : 0,
                        ambiente: self.config.ambiente,
                        moneda: self.config.moneda,
                        api_url: self.config.api_url,
                        api_token: self.config.api_token,
                        api_enviar_sifen: self.config.api_enviar_sifen ? 1 : 0,
                        api_enviar_correo: self.config.api_enviar_correo ? 1 : 0,
                        observaciones: self.config.observaciones,
                    };
                    axios.post('{{ route('sifen.update') }}', payload)
                        .then(function() {
                            self.guardando = false;
                            Toast.fire({ icon: 'success', title: 'Configuración guardada' });
                            location.reload();
                        })
                        .catch(function(err) {
                            self.guardando = false;
                            var msg = (err.response && err.response.data && err.response.data.mensaje)
                                ? err.response.data.mensaje
                                : err.message;
                            Swal.fire('Error', msg, 'error');
                        });
                },
                probarApi: function() {
                    var self = this;
                    self.probandoApi = true;
                    axios.post('{{ route('sifen.api.probar') }}')
                        .then(function(res) {
                            self.probandoApi = false;
                            Swal.fire('API OK', res.data.mensaje || 'Conexión correcta', 'success');
                        })
                        .catch(function(err) {
                            self.probandoApi = false;
                            var msg = (err.response && err.response.data && err.response.data.mensaje)
                                ? err.response.data.mensaje
                                : err.message;
                            Swal.fire('Error API', msg, 'error');
                        });
                },
                obtenerTokenApi: function() {
                    var self = this;
                    if (!self.apiLogin.email || !self.apiLogin.password) {
                        Swal.fire('Faltan datos', 'Indicá email y contraseña de la API', 'warning');
                        return;
                    }
                    self.obteniendoToken = true;
                    axios.post('{{ route('sifen.api.token') }}', self.apiLogin)
                        .then(function(res) {
                            self.obteniendoToken = false;
                            self.config.api_token = res.data.token;
                            self.apiLogin.password = '';
                            Toast.fire({ icon: 'success', title: 'Token guardado' });
                        })
                        .catch(function(err) {
                            self.obteniendoToken = false;
                            var msg = (err.response && err.response.data && err.response.data.mensaje)
                                ? err.response.data.mensaje
                                : err.message;
                            Swal.fire('Error', msg, 'error');
                        });
                },
                cargarDocumentos: function() {
                    var self = this;
                    axios.get('{{ route('sifen.documentos') }}')
                        .then(function(res) {
                            self.documentos = res.data;
                        });
                },
            },
            mounted: function() {
                this.config.activo = this.config.activo === true || this.config.activo === 1 || this.config.activo === '1';
                this.config.api_enviar_sifen = this.config.api_enviar_sifen === true || this.config.api_enviar_sifen === 1 || this.config.api_enviar_sifen === '1';
                this.config.api_enviar_correo = this.config.api_enviar_correo === true || this.config.api_enviar_correo === 1 || this.config.api_enviar_correo === '1';
                if (!this.config.moneda) this.config.moneda = 'PYG';
                if (!this.config.ambiente) this.config.ambiente = 'test';
            },
        });

        activarMenu('m_sifen_menu', 'm_sifen');
    </script>
@endsection
