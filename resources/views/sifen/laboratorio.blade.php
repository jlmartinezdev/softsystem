@extends('layouts.app')
@section('title', 'Laboratorio SIFEN')
@section('main')
    <div class="container-fluid" id="app">
        <div class="content-header">
            <div class="row mb-2 align-items-center">
                <div class="col-md-8">
                    <h4 class="m-0">Laboratorio SIFEN</h4>
                    <p class="text-muted mb-0 small">Pruebe XML, firma, QR y envío SET sin afectar numeración real (usa próximo número en preview).</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('sifen.index') }}" class="btn btn-outline-info btn-sm">
                        <span class="fa fa-cog"></span> Configuración
                    </a>
                    <span class="badge badge-pill ml-1" :class="config.ambiente === 'prod' ? 'badge-danger' : 'badge-info'">
                        @{{ config.ambiente === 'prod' ? 'Producción' : 'Prueba' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="alert alert-warning" v-if="faltantes.length">
            <strong>Faltantes de configuración:</strong> @{{ faltantes.join(', ') }}
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Prueba</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Venta de prueba</label>
                            <select class="form-control form-control-sm" v-model.number="nroVenta">
                                <option :value="null">— Seleccionar venta —</option>
                                <option v-for="v in ventas" :key="v.nro_fact_ventas" :value="v.nro_fact_ventas">
                                    Nº @{{ v.nro_fact_ventas }} — @{{ v.cliente_nombre }} (@{{ v.estado_sifen }})
                                </option>
                            </select>
                        </div>
                        <div class="btn-group-vertical btn-block">
                            <button type="button" class="btn btn-outline-secondary btn-sm text-left" @click="probar('validar')" :disabled="cargando">
                                <span class="fa fa-check-circle"></span> 1. Validar configuración
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm text-left" @click="probar('xml')" :disabled="cargando || !nroVenta">
                                <span class="fa fa-code"></span> 2. Generar XML DE
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm text-left" @click="probar('firmar')" :disabled="cargando || !nroVenta">
                                <span class="fa fa-key"></span> 3. Firmar XML
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm text-left" @click="probar('qr')" :disabled="cargando || !nroVenta">
                                <span class="fa fa-qrcode"></span> 4. Generar QR KuDE
                            </button>
                            <button type="button" class="btn btn-warning btn-sm text-left" @click="probar('enviar')" :disabled="cargando || !nroVenta">
                                <span class="fa fa-paper-plane"></span> 5. Enviar a SET
                            </button>
                            <button type="button" class="btn btn-success btn-sm text-left" @click="probar('completo')" :disabled="cargando || !nroVenta">
                                <span class="fa fa-flask"></span> Flujo completo (local)
                            </button>
                        </div>
                        <div class="custom-control custom-checkbox mt-3">
                            <input type="checkbox" class="custom-control-input" id="enviarSet" v-model="enviarSet">
                            <label class="custom-control-label" for="enviarSet">Incluir envío SET en flujo completo</label>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            El laboratorio <strong>no reserva</strong> numeración ni guarda documentos. Solo el paso 5 envía a SET.
                        </p>
                    </div>
                </div>

                <div class="card card-outline card-info mt-3" v-if="resultado">
                    <div class="card-header"><strong>Resumen</strong></div>
                    <div class="card-body small">
                        <p class="mb-1"><strong>Paso:</strong> @{{ resultado.paso }}</p>
                        <p class="mb-1 text-success" v-if="resultado.mensaje">@{{ resultado.mensaje }}</p>
                        <p class="mb-1" v-if="resultado.numeracion"><strong>Nº preview:</strong> @{{ resultado.numeracion }}</p>
                        <p class="mb-1" v-if="resultado.cdc"><strong>CDC:</strong><br><code>@{{ resultado.cdc }}</code></p>
                        <p class="mb-1" v-if="resultado.digest_value"><strong>Digest:</strong><br><code>@{{ resultado.digest_value }}</code></p>
                        <p class="mb-1" v-if="resultado.certificado"><strong>Certificado:</strong> @{{ resultado.certificado }}</p>
                        <div v-if="resultado.cert_diag" class="mt-2 small">
                            <strong>Datos del certificado</strong>
                            <ul class="mb-0 pl-3">
                                <li>Titular: @{{ resultado.cert_diag.titular }}</li>
                                <li>Tipo: @{{ resultado.cert_diag.tipo_certificado }} · @{{ resultado.cert_diag.identificacion_cert }}</li>
                                <li>Vigente hasta: @{{ resultado.cert_diag.valido_hasta }}</li>
                                <li>RUC configurado: @{{ resultado.cert_diag.ruc_config }} (@{{ resultado.cert_diag.ruc_coincide ? 'coincide' : 'revisar' }})</li>
                            </ul>
                        </div>
                        <div v-if="resultado.set" class="mt-2">
                            <strong v-if="resultado.set.http_code !== undefined">Conexión SET</strong>
                            <strong v-else>Respuesta SET</strong>
                            <p class="mb-0" v-if="resultado.set.http_code !== undefined" :class="resultado.set.ok ? 'text-success' : 'text-danger'">
                                HTTP @{{ resultado.set.http_code }} — @{{ resultado.set.mensaje }}
                            </p>
                            <p class="mb-0" v-else>[@{{ resultado.set.codigo }}] @{{ resultado.set.mensaje }}</p>
                            <p class="mb-0 text-muted" v-if="resultado.set.location"><small>Redirect: @{{ resultado.set.location }}</small></p>
                        </div>
                        <div v-if="resultado.qr_image" class="text-center mt-2">
                            <img :src="'data:image/png;base64,' + resultado.qr_image" alt="QR" style="max-width:140px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-outline card-secondary">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <strong>Salida</strong>
                        <div class="d-flex align-items-center flex-wrap mt-1 mt-md-0">
                            <button type="button" class="btn btn-outline-success btn-sm mr-1 mb-1" @click="descargarXml" :disabled="!xmlSalida">
                                <span class="fa fa-download"></span> Descargar XML
                            </button>
                            <a href="{{ \App\Support\SifenMt150::PREVALIDADOR_URL }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm mr-1 mb-1" :class="{ disabled: !xmlSalida }" @click.prevent="abrirPrevalidador">
                                <span class="fa fa-external-link"></span> Prevalidador DNIT
                            </a>
                            <span class="badge badge-light mb-1" v-if="ultimoPaso">@{{ ultimoPaso }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs px-3 pt-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-xml">XML</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-set">Respuesta SET</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-qr">QR URL</a>
                            </li>
                        </ul>
                        <div class="tab-content p-3">
                            <div class="tab-pane fade show active" id="tab-xml">
                                <p class="small text-muted mb-2" v-if="xmlSalida">
                                    Para el prevalidador DNIT use el XML del paso <strong>3 Firmar</strong> o <strong>4 QR</strong> (debe incluir <code>Signature</code>).
                                    Descargue el archivo y súbalo en el
                                    <a href="{{ \App\Support\SifenMt150::PREVALIDADOR_URL }}" target="_blank" rel="noopener">Prevalidador SIFEN</a>.
                                </p>
                                <textarea class="form-control font-monospace small" rows="22" readonly v-model="xmlSalida" placeholder="Ejecute una prueba para ver el XML..."></textarea>
                            </div>
                            <div class="tab-pane fade" id="tab-set">
                                <textarea class="form-control font-monospace small" rows="22" readonly v-model="respuestaSet" placeholder="Sin respuesta SET..."></textarea>
                            </div>
                            <div class="tab-pane fade" id="tab-qr">
                                <textarea class="form-control font-monospace small" rows="6" readonly v-model="qrUrl" placeholder="Sin URL QR..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });

        var app = new Vue({
            el: '#app',
            data: {
                cargando: false,
                nroVenta: null,
                enviarSet: false,
                config: @json($config),
                faltantes: @json($faltantes),
                ventas: @json($ventas),
                resultado: null,
                xmlSalida: '',
                respuestaSet: '',
                qrUrl: '',
                ultimoPaso: '',
            },
            methods: {
                probar: function(paso) {
                    var self = this;
                    if (paso !== 'validar' && !self.nroVenta) {
                        Swal.fire('Atención', 'Seleccione una venta.', 'warning');
                        return;
                    }
                    if (paso === 'enviar' || (paso === 'completo' && self.enviarSet)) {
                        if (!confirm('¿Enviar documento de prueba al servicio SET?')) return;
                    }
                    self.cargando = true;
                    axios.post('{{ route('sifen.laboratorio.ejecutar') }}', {
                        paso: paso,
                        nro_venta: self.nroVenta,
                        enviar_set: self.enviarSet ? 1 : 0,
                    }).then(function(res) {
                        self.cargando = false;
                        self.resultado = res.data.resultado;
                        self.ultimoPaso = paso;
                        if (res.data.resultado.faltantes) {
                            self.faltantes = res.data.resultado.faltantes;
                        }
                        if (res.data.resultado.xml) {
                            self.xmlSalida = res.data.resultado.xml;
                        }
                        if (res.data.resultado.respuesta_set) {
                            self.respuestaSet = res.data.resultado.respuesta_set;
                        }
                        if (res.data.resultado.qr_url) {
                            self.qrUrl = res.data.resultado.qr_url;
                        }
                        var icon = res.data.resultado.ok === false ? 'warning' : 'success';
                        Toast.fire({ icon: icon, title: res.data.resultado.mensaje || 'Listo' });
                    }).catch(function(err) {
                        self.cargando = false;
                        var msg = (err.response && err.response.data && err.response.data.mensaje) ? err.response.data.mensaje : err.message;
                        if (err.response && err.response.data && err.response.data.resultado) {
                            self.resultado = err.response.data.resultado;
                        }
                        Swal.fire('Error', msg, 'error');
                    });
                },
                descargarXml: function() {
                    if (!this.xmlSalida) {
                        Swal.fire('Atención', 'No hay XML para descargar. Ejecute una prueba primero.', 'warning');
                        return;
                    }
                    var nombre = 'DE-preview';
                    if (this.resultado && this.resultado.cdc) {
                        nombre = this.resultado.cdc;
                    } else if (this.nroVenta) {
                        nombre = 'DE-venta-' + this.nroVenta;
                    }
                    var blob = new Blob([this.xmlSalida], { type: 'application/xml;charset=utf-8' });
                    var url = URL.createObjectURL(blob);
                    var enlace = document.createElement('a');
                    enlace.href = url;
                    enlace.download = nombre + '.xml';
                    document.body.appendChild(enlace);
                    enlace.click();
                    document.body.removeChild(enlace);
                    URL.revokeObjectURL(url);
                    Toast.fire({ icon: 'success', title: 'XML descargado' });
                },
                abrirPrevalidador: function() {
                    if (!this.xmlSalida) {
                        Swal.fire('Atención', 'Genere el XML antes de usar el prevalidador.', 'warning');
                        return;
                    }
                    window.open('{{ \App\Support\SifenMt150::PREVALIDADOR_URL }}', '_blank');
                },
            },
        });

        activarMenu('m_mantenimiento', 'm_sifen_lab');
    </script>
@endsection
