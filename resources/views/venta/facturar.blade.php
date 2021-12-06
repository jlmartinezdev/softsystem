@extends('layouts.app')
@section('title', 'Facturar Venta [' . $id . ']')
@section('style')
    <style>
        table {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

    </style>
@endsection
@section('main')
    <div id="app" class="container">
        <div class="card">
            <div class="card-header">
                Factura Venta
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <table>
                            <tr>
                                <td><strong>Nivel 1</strong></td>
                                <td><strong>Nivel 2</strong></td>
                                <td><strong>Factura</strong></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control form-control-sm" placeholder="Nivel 1" readonly
                                        value="{{ $venta[0]->factura_status == '1' ? $factura[0]->nivel1 : '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" placeholder="Nivel 2" readonly
                                        value="{{ $venta[0]->factura_status == '1' ? $factura[0]->nivel2 : '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" placeholder="Factura" readonly
                                        value="{{ $venta[0]->factura_status == '1' ? str_pad($factura[0]->nro_factura, 7, '0', STR_PAD_LEFT) : '' }}">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table>
                            <tr>
                                <td><strong>ULTIMO FACTURA</strong></td>
                            </tr>
                            <tr>
                                <td>{{ str_pad($ultimo->nivel1, 3, '0', STR_PAD_LEFT) . '-' . $ultimo->nivel2 . '-' . $ultimo->nrofactura }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-5">
                        <table>
                            <tr>
                                <td><strong> R.U.C. / C.I.</strong></td>
                                <td><strong>Razon Social</strong></td>
                            </tr>
                            <tr>
                                <td><input type="text" placeholder="R.U.C." class="form-control form-control-sm"
                                        value="{{ $venta[0]->cliente_ruc }}"></td>
                                <td><input type="text" placeholder="Razon Social" class="form-control form-control-sm"
                                        value="{{ $venta[0]->cliente_nombre }}"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-7">
                        <table>
                            <tr>
                                <td><strong>Fecha</strong></td>
                                <td><strong>Direccion</strong></td>
                            </tr>
                            <tr>
                                <td><input type="text" placeholder="Fecha" class="form-control form-control-sm"
                                        value="{{ $venta[0]->venta_fecha }}"></td>
                                <td><input type="text" placeholder="Direccion" class="form-control form-control-sm"
                                        value="{{ $venta[0]->cliente_direccion }}"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-5">
                        <table>
                            <tr>
                                <td><strong>Nro. Venta</strong></td>
                                <td><strong>Monto Total</strong></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control form-control-sm" readonly
                                        value="{{ $venta[0]->nro_fact_ventas }}"></td>
                                <td><input type="text" class="form-control form-control-sm" readonly
                                        value="{{ number_format($venta[0]->venta_total, 2, ',', '.') }}"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-7">
                        <table>
                            <tr>
                                <td><strong>Estado de Venta</strong></td>
                                <td><strong>Condicion</strong></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control form-control-sm"
                                        style="color: {{ $venta[0]->factura_status == '1' ? 'green' : 'red' }}" readonly
                                        value="{{ $venta[0]->factura_status == '1' ? 'FACTURADO' : 'NO FACTURADO' }}">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm">
                                        <option value="1" {{ $venta[0]->tipo_factura == '1' ? 'selected' : '' }}>Contado
                                        </option>
                                        <option value="2" {{ $venta[0]->tipo_factura == '2' ? 'selected' : '' }}>Credito
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="m-auto">
                        <button class="btn btn-primary" @click="facturar"><span class="fa fa-save"></span>
                            Facturar</button>
                        <button class="btn btn-warning" @click="anular"><span class="fa fa-times"></span> Anular</button>
                        <a href="{{ $venta[0]->factura_status== '1' ? '/ticket/factura/'.$venta[0]->nro_fact_ventas :'#' }}" class="btn btn-secondary"><span class="fa fa-print"></span> Imprimir</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var app = new Vue({
            el: '#app',
            data: {
                nro_venta: {{ $venta[0]->nro_fact_ventas }},
                status: {{ $venta[0]->factura_status == '1' ? '1' : '0' }}
            },
            methods: {
                facturar: function() {
                    axios.post('/venta/facturar', {
                            nivel1: '{{ is_null($ultimo->nivel1) ? 0 : $ultimo->nivel1 }}',
                            nivel2: '{{ is_null($ultimo->nivel2) ? 0 : $ultimo->nivel2 }}',
                            factura: '{{ is_null($ultimo->nrofactura) ? 0 : $ultimo->nrofactura }}',
                            timbrado: {{ $ultimo->timbrado }},
                            nro_venta: this.nro_venta,
                            vencimiento: '{{ $ultimo->vencimiento }}',
                        })
                        .then(response => {
                            location.reload();
                        })
                },
                anular: function() {
                    if (this.status) {
                        Swal.fire({
                            title: '¿Desea anular Factura?',
                            text: 'Anular factura :{{ $venta[0]->factura_status == "1" ? $factura[0]->nivel1."-".$factura[0]->nivel2."-".str_pad($factura[0]->nro_factura, 7, "0", STR_PAD_LEFT) : "" }}',
                            icon: 'question',
                            showCancelButton: true,
                            //confirmButtonColor: 'btn-danger',
                            //cancelButtonColor: 'btn-secondary',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Si, anular!',
                            confirmButtonClass: 'bg-danger'
                        }).then((result) => {
                            if (result.value) {
                                axios.delete('/venta/facturar/' + this.nro_venta)
                                    .then(r => {
                                        Swal.fire(
                                            'Anulado!',
                                            'La factura ha sido eliminado.',
                                            'success'
                                        )
                                        location.reload();
                                    }).catch(e => {
                                        console.log(e.message);
                                    });
                            }
                        })
                    }
                }
            }
        })
    </script>
@endsection
