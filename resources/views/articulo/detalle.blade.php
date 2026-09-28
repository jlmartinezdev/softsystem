
<style>
    body.dark-mode #detalleArticulo .modal-content {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode #detalleArticulo .modal-body,
    body.dark-mode #detalleArticulo .modal-footer {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }
    body.dark-mode #detalleArticulo .table {
        color: #f3f4f6 !important;
    }
    body.dark-mode #detalleArticulo .table thead.thead-light th {
        background-color: #111827 !important;
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    body.dark-mode #detalleArticulo .table td {
        border-color: #374151 !important;
    }
    body.dark-mode #detalleArticulo .card.card-body {
        background-color: #111827 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode #detalleArticulo .form-control {
        background-color: #111827 !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }
</style>
<div class="modal fade" id="detalleArticulo" tabindex="-1" role="dialog" aria-labelledby="detalleArticuloTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header text-white" style="background: linear-gradient(135deg, #0a4d36 0%, #073827 100%);">
				<div class="d-flex align-items-center">
					<i class="fa fa-boxes-stacked fa-lg mr-2"></i>
					<div>
						<h5 class="modal-title font-weight-bold mb-0" id="detalleArticuloTitle">Stock por Sucursal & Transferencia</h5>
						<small class="text-white-50">@{{ articulo.descripcion }}</small>
					</div>
				</div>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-3">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="text-secondary font-weight-bold small text-uppercase">Existencias registradas</span>
					<span class="badge px-3 py-2 font-weight-bold" style="background: var(--dash-primary-light, #eaf3ef); color: var(--dash-primary, #0a4d36); border: 1px solid var(--dash-primary-border, #c8dfd5); border-radius: 20px;">Total Stock: @{{ totalStock }}</span>
				</div>
				<div class="table-responsive">
					<table class="table table-sm table-bordered table-hover mb-3">
						<thead class="thead-light">
							<tr class="small text-muted">
								<th>Sucursal</th>
								<th class="text-center" style="width: 15%;">Stock</th>
								<th style="width: 20%;">Lote</th>
								<th style="width: 25%;">Vencimiento</th>
								<th class="text-center" style="width: 15%;">Transferir</th>
							</tr>
						</thead>
						<tbody>
							<template v-if="stocks && stocks.length > 0">
								<tr v-for="(stock, index) in stocks" :key="stock.id" :class="{'table-primary' : color(index)}">
									<td class="align-middle font-weight-semibold">@{{ getByIdSucursal(stock.sucursal) }}</td>
									<td class="align-middle text-center font-weight-bold">
										<span class="badge" :class="stock.cantidad > 0 ? 'badge-success' : 'badge-danger'" style="font-size: 0.85rem;">
											@{{ stock.cantidad }}
										</span>
									</td>
									<td class="align-middle text-muted small">@{{ stock.lotenew || 'S/N' }}</td>
									<td class="align-middle text-muted small">@{{ stock.vencimiento || 'Sin vencimiento' }}</td>
									<td class="align-middle text-center">
										<button class="btn btn-outline-warning btn-sm"
												data-toggle="collapse"
												data-target="#accordiontransferir"
												@click="setStock(stock, index)"
												aria-expanded="false"
												title="Iniciar transferencia desde este lote">
											<i class="fa fa-retweet mr-1"></i> Mover
										</button>
									</td>
								</tr>
							</template>
							<tr v-else>
								<td colspan="5" class="text-center text-muted py-3">
									<em>No hay registros de stock cargados para este artículo.</em>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="collapse mt-2" id="accordiontransferir">
					<div class="card border-warning shadow-sm p-3 bg-light" style="border-radius: 8px;">
						<h6 class="font-weight-bold text-dark mb-2">
							<i class="fa fa-truck-moving text-warning mr-1"></i> Transferir Unidades a otra Sucursal
						</h6>
						<div class="row align-items-end">
							<div class="form-group col-md-5 mb-2">
								<label class="small text-muted font-weight-bold mb-1">Sucursal Destino:</label>
								<select class="custom-select custom-select-sm" v-model.number="frmt.suc">
									<option value="0">-- Seleccionar Sucursal --</option>
									<template v-for="sucursal in sucursales">
						      			<option :value="sucursal.suc_cod">@{{ sucursal.suc_desc.trim() }}</option>
						      		</template>
								</select>
							</div>
							<div class="form-group col-md-4 mb-2">
								<label class="small text-muted font-weight-bold mb-1">Cantidad a Transferir:</label>
								<input type="number"
									   v-model.number="frmt.cant"
									   class="form-control form-control-sm font-weight-bold text-center"
									   min="1"
									   placeholder="0">
							</div>	
							<div class="form-group col-md-3 mb-2 text-right">
								<button class="btn btn-success btn-sm font-weight-bold mr-1" @click="transladarStock">
									<i class="fa fa-check mr-1"></i> Confirmar
								</button>
								<button class="btn btn-secondary btn-sm" @click="cancelTrans">
									<i class="fa fa-times"></i>
								</button>	
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer bg-light py-2">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">
					<i class="fa fa-times mr-1"></i> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>