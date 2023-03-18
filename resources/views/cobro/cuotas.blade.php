<div class="modal fade" id="selCuotas">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Seleccionar Cuota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table tabla table-sm table-hover table-striped">
                    <tr>
                        <td>Sel.</td>
                        <td>N°</td>
                        <td>Vencimiento</td>
                        <td>Cuota</td>
                        <td>Cobrado</td>
                        <td>Saldo</td>
                        <td>Mora</td>
                        <td>Estado</td>
                    </tr>
                    <template v-if="request.cuota">
                        <tr>
                            <td colspan="6"><span class="spinner-border spinner-border-sm" role="status"></span><span
                                    class="sr-only">Cargando...</span> Cargando...</td>
                        </tr>

                    </template>
                    <template v-for="(cuota, index) in cuotas">
                        <template v-if="parseInt(cuota.monto_cuota) == 0 && index == 0">
                        </template>
                        <template v-else>
                            <tr>
                                <td>
                                    <template v-if="cuota.monto_cobrado != cuota.monto_cuota">
                                        <div class="icheck-primary">
                                            <input type="checkbox" @click="checkCuota(index)" v-model="cuota.check"
                                                :id="'check'+index">
                                            <label :for="'check'+index"></label>
                                        </div>
                                    </template>
                                    <template v-else>
                                        &nbsp;
                                    </template>
                                </td>
                                <td>
                                    @{{ checkPrimeraCuota(cuota.nro_cuotas,cuota.nro_fact_ventas) }}
                                </td>
                                <td>@{{ formatFecha(cuota.fecha_venc) }}</td>
                                <td>@{{ format(cuota.monto_cuota) }}</td>
                                <td>@{{ format(cuota.monto_cobrado) }}</td>
                                <td>@{{ format(cuota.monto_cuota - cuota.monto_cobrado) }}</td>
                                <td>@{{ diferenciaFecha(cuota.fecha_venc, cuota.monto_saldo, cuota.estado_interes) }}</td>
                                <td>
                                    <template v-if="cuota.monto_cobrado == cuota.monto_cuota">
                                        <span class="badge badge-success">Cobrado</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge badge-danger">Pendiente</span>
                                    </template>
                                </td>
                            </tr>
                        </template>

                    </template>

                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" @click="addCuota"> <span class="fa fa-check"></span> Aceptar</button>
            </div>
        </div>
    </div>
</div>
