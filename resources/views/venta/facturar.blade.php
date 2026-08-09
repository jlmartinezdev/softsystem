@extends('layouts.app')
@section('title', 'Factura electrónica SIFEN — Venta ' . $id)
@section('style')
    <style>
        #main table.detalle-factura {
            font-size: 10pt;
        }
    </style>
@endsection
@section('main')
    <div id="app" class="container-fluid">
        <div class="content-header mb-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="m-0">Factura electrónica SIFEN</h4>
                    <p class="text-muted mb-0 small">Venta Nº {{ $cabecera->nro_fact_ventas }}</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('infventa.imprimir') }}" class="btn btn-outline-secondary btn-sm">
                        <span class="fa fa-arrow-left"></span> Volver a imprimir
                    </a>
                    <a href="{{ route('sifen.index') }}" class="btn btn-outline-info btn-sm">
                        <span class="fa fa-cog"></span> Config SIFEN
                    </a>
                </div>
            </div>
        </div>

        @if(count($faltantes) && !$documento)
            <div class="alert alert-warning">
                <strong>Configuración SIFEN incompleta:</strong>
                {{ implode(', ', $faltantes) }}.
                <a href="{{ route('sifen.index') }}">Completar configuración</a>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <strong>Datos de la venta</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><span class="text-muted">Cliente:</span> <strong>{{ $cabecera->cliente_nombre }}</strong></p>
                                <p class="mb-1"><span class="text-muted">RUC / CI:</span> {{ $cabecera->cliente_ruc ?: $cabecera->cliente_ci }}</p>
                                <p class="mb-0"><span class="text-muted">Dirección:</span> {{ $cabecera->cliente_direccion }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><span class="text-muted">Fecha venta:</span> {{ $cabecera->venta_fecha }}</p>
                                <p class="mb-1"><span class="text-muted">Condición:</span> {{ $cabecera->tipo_factura == '1' ? 'Contado' : 'Crédito' }}</p>
                                <p class="mb-0"><span class="text-muted">Total:</span> <strong>Gs. {{ number_format($cabecera->venta_total, 0, ',', '.') }}</strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped detalle-factura">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th class="text-right">Cant.</th>
                                        <th class="text-right">Precio</th>
                                        <th class="text-right">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalle as $d)
                                        <tr>
                                            <td>{{ $d->producto_c_barra }}</td>
                                            <td>{{ $d->producto_nombre }}</td>
                                            <td class="text-right">{{ number_format($d->venta_cantidad, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($d->venta_precio, 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($d->venta_precio * $d->venta_cantidad, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-success">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Documento SIFEN</strong>
                        <span class="badge badge-pill" :class="badgeEstado">@{{ estadoLabel }}</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <span class="text-muted d-block small">Ambiente</span>
                            <strong>@{{ ambienteLabel }}</strong>
                        </p>
                        <p class="mb-2" v-if="documento.numero">
                            <span class="text-muted d-block small">Número factura</span>
                            <strong>@{{ numeroFormateado }}</strong>
                        </p>
                        <p class="mb-2" v-if="documento.timbrado">
                            <span class="text-muted d-block small">Timbrado</span>
                            @{{ documento.timbrado }}
                        </p>
                        <p class="mb-2" v-if="documento.cdc">
                            <span class="text-muted d-block small">CDC</span>
                            <code class="small">@{{ documento.cdc }}</code>
                        </p>
                        <p class="mb-2" v-if="documento.mensaje_respuesta">
                            <span class="text-muted d-block small">Respuesta SET</span>
                            <span class="small">@{{ documento.mensaje_respuesta }}</span>
                        </p>
                        <p class="mb-0" v-if="!documento.id">
                            <span class="text-muted">Sin documento emitido para esta venta.</span>
                        </p>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-sm btn-block" @click="emitir" :disabled="procesando || !puedeEmitir">
                            <span class="fa fa-file-invoice"></span>
                            @{{ documento.estado === 'aprobado' ? 'Documento aprobado' : 'Emitir factura electrónica' }}
                        </button>
                        <button type="button" class="btn btn-warning btn-sm btn-block mt-2" @click="anular" :disabled="procesando || !puedeAnular">
                            <span class="fa fa-times"></span> Anular documento
                        </button>
                        <a v-if="documento.estado === 'aprobado'" :href="urlTicket" class="btn btn-secondary btn-sm btn-block mt-2" target="_blank">
                            <span class="fa fa-print"></span> Imprimir ticket KuDE
                        </a>
                        <a v-if="documento.estado === 'aprobado'" :href="urlKudePdf" class="btn btn-outline-dark btn-sm btn-block mt-2" target="_blank">
                            <span class="fa fa-file-pdf"></span> Descargar KuDE PDF
                        </a>
                    </div>
                </div>

                <div class="card card-outline card-info mt-3">
                    <div class="card-body small">
                        <p class="mb-1"><strong>Próximo número:</strong> {{ str_pad($config->establecimiento, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($config->punto_expedicion, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($config->ultimo_numero + 1, 7, '0', STR_PAD_LEFT) }}</p>
                        <p class="mb-0 text-muted">El DE se construye, firma con certificado digital y se envía a SIFEN vía SOAP.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                nro_venta: {{ $cabecera->nro_fact_ventas }},
                procesando: false,
                configActiva: {{ $config->activo ? 'true' : 'false' }},
                ambiente: @json($config->ambiente),
                documento: @json($documento ? $documento : ['id' => null, 'estado' => null]),
                faltantes: @json($faltantes),
            },
            computed: {
                numeroFormateado: function() {
                    if (!this.documento.numero) return '—';
                    return this.pad(this.documento.establecimiento, 3) + '-' +
                        this.pad(this.documento.punto_expedicion, 3) + '-' +
                        this.pad(this.documento.numero, 7);
                },
                ambienteLabel: function() {
                    return this.ambiente === 'prod' ? 'Producción' : 'Prueba (test)';
                },
                estadoLabel: function() {
                    var map = {
                        pendiente: 'Pendiente',
                        enviado: 'Enviado',
                        aprobado: 'Aprobado',
                        rechazado: 'Rechazado',
                        anulado: 'Anulado',
                    };
                    return this.documento.estado ? (map[this.documento.estado] || this.documento.estado) : 'Sin emitir';
                },
                badgeEstado: function() {
                    var map = {
                        pendiente: 'badge-warning',
                        enviado: 'badge-info',
                        aprobado: 'badge-success',
                        rechazado: 'badge-danger',
                        anulado: 'badge-secondary',
                    };
                    return this.documento.estado ? (map[this.documento.estado] || 'badge-light') : 'badge-light';
                },
                puedeEmitir: function() {
                    if (!this.configActiva || this.faltantes.length) return false;
                    return !this.documento.estado || this.documento.estado === 'rechazado' || this.documento.estado === 'anulado' || this.documento.estado === 'pendiente' || this.documento.estado === 'enviado';
                },
                puedeAnular: function() {
                    return this.documento.estado === 'aprobado' || this.documento.estado === 'enviado' || this.documento.estado === 'pendiente';
                },
                urlTicket: function() {
                    return '{{ url('ticket/factura') }}/' + this.nro_venta;
                },
                urlKudePdf: function() {
                    return '{{ url('pdf/kude') }}/' + this.nro_venta;
                },
            },
            methods: {
                pad: function(val, len) {
                    var s = String(val == null ? '' : val).trim();
                    if (!s) s = '0';
                    return s.length >= len ? s : ('0'.repeat(len) + s).slice(-len);
                },
                emitir: function() {
                    if (this.documento.estado === 'aprobado') return;
                    var self = this;
                    self.procesando = true;
                    axios.post('{{ url('venta/facturar') }}', { nro_venta: self.nro_venta })
                        .then(function(res) {
                            self.procesando = false;
                            self.documento = res.data.documento;
                            Swal.fire({
                                icon: res.data.facturado ? 'success' : 'info',
                                title: res.data.facturado ? 'Factura aprobada' : 'Documento generado',
                                text: res.data.facturado
                                    ? 'Nº ' + res.data.numero
                                    : (res.data.documento.mensaje_respuesta || 'Revise el estado del documento'),
                            }).then(function() {
                                if (res.data.facturado) location.reload();
                            });
                        })
                        .catch(function(err) {
                            self.procesando = false;
                            var msg = (err.response && err.response.data && err.response.data.mensaje) ? err.response.data.mensaje : err.message;
                            if (err.response && err.response.data && err.response.data.documento) {
                                self.documento = err.response.data.documento;
                            }
                            Swal.fire('Error', msg, 'error').then(function() {
                                location.reload();
                            });
                        });
                },
                anular: function() {
                    var self = this;
                    Swal.fire({
                        title: '¿Anular documento SIFEN?',
                        text: self.numeroFormateado !== '—' ? self.numeroFormateado : 'Venta ' + self.nro_venta,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Sí, anular',
                    }).then(function(result) {
                        if (!result.value) return;
                        self.procesando = true;
                        axios.delete('{{ url('venta/facturar') }}/' + self.nro_venta)
                            .then(function() {
                                self.procesando = false;
                                Swal.fire('Anulado', 'El documento fue anulado.', 'success').then(function() {
                                    location.reload();
                                });
                            })
                            .catch(function(err) {
                                self.procesando = false;
                                var msg = (err.response && err.response.data && err.response.data.mensaje) ? err.response.data.mensaje : err.message;
                                Swal.fire('Error', msg, 'error');
                            });
                    });
                },
            },
        });
    </script>
@endsection
