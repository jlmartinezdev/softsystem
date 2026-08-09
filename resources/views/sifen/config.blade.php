@extends('layouts.app')
@section('title', 'Configuración SIFEN')
@section('main')
    <div class="container-fluid" id="app">
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4 class="m-0">Facturación electrónica SIFEN</h4>
                    <p class="text-muted mb-0 small">Manual Técnico v150 · Notas técnicas DNIT (NT23)</p>
                </div>
                <div class="col-sm-4 text-sm-right">
                    <a href="{{ route('sifen.laboratorio') }}" class="btn btn-outline-warning btn-sm mr-1">
                        <span class="fa fa-flask"></span> Laboratorio
                    </a>
                    <span class="badge badge-dark mr-1">MT v@{{ config.version_formato || 150 }}</span>
                    <span class="badge badge-secondary mr-1">@{{ config.nota_tecnica || 'NT23' }}</span>
                    <span class="badge badge-pill" :class="config.activo ? 'badge-success' : 'badge-secondary'">
                        @{{ config.activo ? 'Activo' : 'Inactivo' }}
                    </span>
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
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-general">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-mt150">MT v150 / DNIT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-emisor">Emisor / Receptor</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-timbrado">Timbrado</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-cert">Certificado / API</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-csc">CSC / QR</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane fade show active" id="tab-general">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sifenActivo" v-model="config.activo">
                                    <label class="custom-control-label" for="sifenActivo">Habilitar facturación electrónica SIFEN</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Ambiente</label>
                                <select class="form-control form-control-sm" v-model="config.ambiente">
                                    <option value="test">Prueba (test)</option>
                                    <option value="prod">Producción</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>RUC</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.ruc">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Razón social</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.razon_social">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tipo contribuyente emisor</label>
                                <select class="form-control form-control-sm" v-model.number="config.tipo_contribuyente">
                                    <option v-for="op in catalogos.tipo_contribuyente" :key="'tc-'+op.value" :value="op.value">@{{ op.label }}</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="sincronizarEmpresa">
                                <span class="fa fa-sync"></span> Copiar RUC, nombre y dirección desde Empresa
                            </button>
                            <div class="form-group mt-3">
                                <label>Observaciones</label>
                                <textarea class="form-control form-control-sm" rows="2" v-model="config.observaciones" placeholder="Notas internas"></textarea>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-mt150">
                            <div class="alert alert-light border small mb-3">
                                <strong>Referencia normativa:</strong>
                                <a href="{{ \App\Support\SifenMt150::DOC_DNIT }}" target="_blank">Documentación DNIT e-Kuatia</a> ·
                                <a href="{{ \App\Support\SifenMt150::MANUAL_PDF }}" target="_blank">Manual Técnico v150</a> ·
                                <a href="{{ \App\Support\SifenMt150::NOTA_NT23_PDF }}" target="_blank">Nota Técnica N° 23</a>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Versión formato</label>
                                        <input type="number" class="form-control form-control-sm" v-model.number="config.version_formato" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nota técnica aplicada</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.nota_tecnica" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo emisión</label>
                                        <select class="form-control form-control-sm" v-model.number="config.tipo_emision">
                                            <option v-for="op in catalogos.tipo_emision" :key="'te-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo documento</label>
                                        <select class="form-control form-control-sm" v-model.number="config.tipo_documento">
                                            <option v-for="op in catalogos.tipo_documento" :key="'td-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo transacción</label>
                                        <select class="form-control form-control-sm" v-model.number="config.tipo_transaccion">
                                            <option v-for="op in catalogos.tipo_transaccion" :key="'tt-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo impuesto</label>
                                        <select class="form-control form-control-sm" v-model.number="config.tipo_impuesto">
                                            <option v-for="op in catalogos.tipo_impuesto" :key="'ti-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Moneda</label>
                                        <input type="text" maxlength="3" class="form-control form-control-sm text-uppercase" v-model="config.moneda">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Indicador presencia</label>
                                        <select class="form-control form-control-sm" v-model.number="config.indicador_presencia">
                                            <option v-for="op in catalogos.indicador_presencia" :key="'ip-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Condición operación por defecto</label>
                                        <select class="form-control form-control-sm" v-model.number="config.condicion_operacion_defecto">
                                            <option v-for="op in catalogos.condicion_operacion" :key="'co-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light border-0 mt-2">
                                <div class="card-body py-2 small">
                                    <strong>Nota Técnica N° 23 — puntos clave</strong>
                                    <ul class="mb-0 pl-3">
                                        @foreach ($notasTecnicas[0]['puntos'] as $punto)
                                            <li>{{ $punto }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-emisor">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Dirección emisor</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.direccion_emisor">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nº casa</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.numero_casa" maxlength="6">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departamento</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.cod_departamento" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Descripción departamento</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.desc_departamento" placeholder="Ej. CENTRAL">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Distrito</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.cod_distrito" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Descripción distrito</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.desc_distrito" placeholder="Ej. ASUNCIÓN">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Ciudad</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.cod_ciudad" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Descripción ciudad</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.desc_ciudad" placeholder="Ej. ASUNCIÓN">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono emisor</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.telefono_emisor" placeholder="021123456">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Correo emisor</label>
                                        <input type="email" class="form-control form-control-sm" v-model="config.email_emisor">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Actividad económica</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.cod_actividad_economica" placeholder="Según Tabla 3 MT v150">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Descripción actividad</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.desc_actividad_economica" placeholder="Según RUC / Tabla 3">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tipo régimen</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.tipo_regimen" placeholder="Opcional · Tabla 1 MT v150">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p class="small text-muted mb-2"><strong>Receptor por defecto</strong></p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Naturaleza</label>
                                        <select class="form-control form-control-sm" v-model.number="config.natura_receptor_defecto">
                                            <option v-for="op in catalogos.natura_receptor" :key="'nr-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo operación</label>
                                        <select class="form-control form-control-sm" v-model.number="config.tipo_operacion_defecto">
                                            <option v-for="op in catalogos.tipo_operacion" :key="'to-'+op.value" :value="op.value">@{{ op.label }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>País receptor</label>
                                        <input type="text" maxlength="3" class="form-control form-control-sm text-uppercase" v-model="config.pais_receptor_defecto">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info small mb-0">@{{ reglaReceptorNt23 }}</div>
                        </div>

                        <div class="tab-pane fade" id="tab-timbrado">
                            <div class="form-group">
                                <label>Timbrado</label>
                                <input type="text" class="form-control form-control-sm" v-model="config.timbrado">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vigencia desde</label>
                                        <input type="date" class="form-control form-control-sm" v-model="config.vigencia_desde">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vigencia hasta <span class="text-muted">— opcional</span></label>
                                        <input type="date" class="form-control form-control-sm" v-model="config.vigencia_hasta">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Establecimiento</label>
                                        <input type="text" class="form-control form-control-sm" maxlength="3" v-model="config.establecimiento">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Punto expedición</label>
                                        <input type="text" class="form-control form-control-sm" maxlength="3" v-model="config.punto_expedicion">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Último número</label>
                                        <input type="number" min="0" class="form-control form-control-sm" v-model.number="config.ultimo_numero">
                                    </div>
                                </div>
                            </div>
                            <p class="small text-muted mb-0">
                                Próximo número: <strong>@{{ proximoNumero }}</strong> (@{{ formatoNumeracion }}) · Formato timbrado MT v150
                            </p>
                        </div>

                        <div class="tab-pane fade" id="tab-cert">
                            <div class="form-group">
                                <label>Ruta certificado digital (.p12 / .pfx)</label>
                                <input type="text" class="form-control form-control-sm" v-model="config.cert_path" placeholder="Ej: C:\certificados\contribuyente.p12">
                            </div>
                            <div class="form-group">
                                <label>Contraseña del certificado</label>
                                <input type="password" class="form-control form-control-sm" v-model="config.cert_password" autocomplete="new-password">
                            </div>
                            <div class="form-group">
                                <label>URL servicio — Prueba</label>
                                <input type="text" class="form-control form-control-sm" v-model="config.url_test" placeholder="https://sifen-test.set.gov.py/de/ws/sync/recibe">
                            </div>
                            <div class="form-group">
                                <label>URL servicio — Producción</label>
                                <input type="text" class="form-control form-control-sm" v-model="config.url_prod" placeholder="https://sifen.set.gov.py/de/ws/sync/recibe">
                            </div>
                            <p class="small text-muted">
                                URL activa según ambiente: <code>@{{ urlActiva }}</code><br>
                                Use el endpoint <strong>/recibe</strong> (sin .wsdl). Requiere TLS 1.2 con certificado cliente (.p12).<br>
                                En <strong>ambiente de prueba (test)</strong>, el certificado debe estar habilitado en el portal e-Kuatia (Marangatu) para <code>sifen-test.set.gov.py</code>. Si SET responde HTTP 302 hacia <code>hangup.php3</code>, el certificado aún no está autorizado en el ambiente test.
                            </p>
                        </div>

                        <div class="tab-pane fade" id="tab-csc">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>CSC ID</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.csc_id" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>CSC Token</label>
                                        <input type="text" class="form-control form-control-sm" v-model="config.csc_token">
                                    </div>
                                </div>
                            </div>
                            <p class="small text-muted mb-0">
                                El CSC se utiliza para generar el código QR del KuDE según normativa SET.
                            </p>
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
                                Listo para reservar documentos
                            </li>
                            <li class="mb-2">Formato: MT v@{{ config.version_formato }} · @{{ config.nota_tecnica }}</li>
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
                config: @json($config),
                catalogos: @json($catalogos),
                documentos: @json($documentos),
                faltantes: @json($faltantes),
                empresa: @json($empresa),
            },
            computed: {
                proximoNumero: function() {
                    return (parseInt(this.config.ultimo_numero, 10) || 0) + 1;
                },
                formatoNumeracion: function() {
                    return this.pad(this.config.establecimiento, 3) + '-' +
                        this.pad(this.config.punto_expedicion, 3) + '-' +
                        this.pad(this.proximoNumero, 7);
                },
                urlActiva: function() {
                    return this.config.ambiente === 'prod' ? this.config.url_prod : this.config.url_test;
                },
                puedeEmitir: function() {
                    return this.config.activo && this.faltantes.length === 0;
                },
                reglaReceptorNt23: function() {
                    var nat = this.config.natura_receptor_defecto;
                    var top = this.config.tipo_operacion_defecto;
                    if (parseInt(nat, 10) === 1) {
                        return 'NT23: no informar D208/D210 cuando D201 = Contribuyente.';
                    }
                    if (parseInt(top, 10) === 4) {
                        return 'NT23: operación B2F — validar identificación del receptor según Manual v150.';
                    }
                    return 'NT23: informar D208 y D210; D208 ≠ 5 (innominado) cuando corresponda.';
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
                normalizarFechaInput: function(val) {
                    if (!val) return '';
                    if (typeof val === 'string') {
                        if (val.indexOf('T') > -1) return val.split('T')[0];
                        if (val.indexOf(' ') > -1) return val.split(' ')[0];
                        return val.substring(0, 10);
                    }
                    return '';
                },
                guardar: function() {
                    var self = this;
                    self.guardando = true;
                    var payload = Object.assign({}, self.config, {
                        activo: self.config.activo ? 1 : 0,
                        vigencia_desde: self.normalizarFechaInput(self.config.vigencia_desde),
                        vigencia_hasta: self.normalizarFechaInput(self.config.vigencia_hasta),
                    });
                    axios.post('{{ route('sifen.update') }}', payload)
                        .then(function() {
                            self.guardando = false;
                            Toast.fire({ icon: 'success', title: 'Configuración SIFEN guardada' });
                            location.reload();
                        })
                        .catch(function(err) {
                            self.guardando = false;
                            Swal.fire('Error', err.message, 'error');
                        });
                },
                sincronizarEmpresa: function() {
                    var self = this;
                    axios.post('{{ route('sifen.sync') }}')
                        .then(function(res) {
                            self.config.ruc = res.data.config.ruc;
                            self.config.razon_social = res.data.config.razon_social;
                            self.config.direccion_emisor = res.data.config.direccion_emisor;
                            self.config.telefono_emisor = res.data.config.telefono_emisor;
                            self.config.email_emisor = res.data.config.email_emisor;
                            self.recalcularFaltantes();
                            Toast.fire({ icon: 'info', title: 'Datos copiados desde empresa' });
                        })
                        .catch(function(err) {
                            Swal.fire('Error', err.message, 'error');
                        });
                },
                cargarDocumentos: function() {
                    var self = this;
                    axios.get('{{ route('sifen.documentos') }}')
                        .then(function(res) {
                            self.documentos = res.data;
                        });
                },
                recalcularFaltantes: function() {
                    var f = [];
                    var c = this.config;
                    if (!c.ruc) f.push('RUC del contribuyente');
                    if (!c.razon_social) f.push('Razón social');
                    if (!c.timbrado) f.push('Timbrado');
                    if (!c.vigencia_desde) f.push('Vigencia desde del timbrado');
                    if (!c.establecimiento || !c.punto_expedicion) f.push('Establecimiento y punto de expedición');
                    if (!c.cert_path) f.push('Certificado digital');
                    if (!c.cert_password) f.push('Contraseña del certificado');
                    if (!c.csc_id) f.push('CSC ID');
                    if (!c.csc_token) f.push('CSC Token');
                    if (parseInt(c.version_formato, 10) !== 150) f.push('Versión de formato MT v150');
                    if (!c.cod_departamento || !c.cod_distrito || !c.cod_ciudad) f.push('Ubicación geográfica del emisor');
                    if (!c.desc_departamento || !c.desc_distrito || !c.desc_ciudad) f.push('Descripciones geográficas del emisor');
                    if (!c.telefono_emisor) f.push('Teléfono del emisor');
                    if (!c.email_emisor) f.push('Correo del emisor');
                    if (!c.direccion_emisor) f.push('Dirección del emisor');
                    if (!c.moneda) f.push('Moneda de la operación');
                    if (!c.cod_actividad_economica) f.push('Actividad económica del emisor');
                    this.faltantes = f;
                },
            },
            mounted: function() {
                this.config.activo = this.config.activo === true || this.config.activo === 1 || this.config.activo === '1';
                this.config.vigencia_desde = this.normalizarFechaInput(this.config.vigencia_desde);
                this.config.vigencia_hasta = this.normalizarFechaInput(this.config.vigencia_hasta);
                if (!this.config.version_formato) this.config.version_formato = 150;
                if (!this.config.nota_tecnica) this.config.nota_tecnica = 'NT23';
                if (!this.config.moneda) this.config.moneda = 'PYG';
                if (!this.config.pais_receptor_defecto) this.config.pais_receptor_defecto = 'PRY';
            },
        });

        activarMenu('m_mantenimiento', 'm_sifen');
    </script>
@endsection
