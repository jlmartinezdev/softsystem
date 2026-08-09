<div class="modal fade" id="busquedaCliente">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title mb-0">
					<span class="fa fa-user"></span> Buscar cliente
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><span class="fa fa-search"></span></span>
						</div>
						<input type="text"
							id="txtclienteVenta"
							v-model="txtcliente"
							@input="onBuscarClienteInput"
							@keydown.enter.prevent="seleccionarPrimerCliente"
							@keydown.down.prevent="moverSeleccionCliente(1)"
							@keydown.up.prevent="moverSeleccionCliente(-1)"
							class="form-control"
							placeholder="Escribí nombre, CI o RUC..."
							autocomplete="off"
							autofocus>
						<div class="input-group-append" v-if="txtcliente">
							<button type="button" class="btn btn-outline-secondary" @click="limpiarBusquedaCliente" title="Limpiar">
								<span class="fa fa-times"></span>
							</button>
						</div>
					</div>
					<small class="text-muted">
						Al abrir se listan los primeros 10 · escribí 2+ caracteres para filtrar · Enter selecciona
					</small>
				</div>

				<div class="text-center text-muted py-4" v-if="clienteBuscando">
					<span class="spinner-border spinner-border-sm"></span> Buscando...
				</div>

				<div class="alert alert-light border text-muted mb-0" v-else-if="!clientes.length && !clienteBuscando">
					No hay clientes para mostrar.
				</div>

				<div class="table-responsive" v-if="clientes.length && !clienteBuscando">
					<table class="table table-sm table-striped table-hover mb-0">
						<thead>
							<tr>
								<th>Documento</th>
								<th>Nombre</th>
								<th>Dirección</th>
								<th>Celular</th>
								<th style="width: 70px;"></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(cliente, index) in clientes"
								:key="cliente.clientes_cod"
								:class="{ 'table-primary': index === clienteIndexActivo }"
								style="cursor: pointer;"
								@click="selectCliente(cliente.clientes_cod, cliente.cliente_nombre)"
								@mouseenter="clienteIndexActivo = index">
								<td>@{{ cliente.cliente_ci || cliente.cliente_ruc || '—' }}</td>
								<td><strong>@{{ cliente.cliente_nombre }}</strong></td>
								<td>@{{ cliente.cliente_direccion || '—' }}</td>
								<td>@{{ cliente.cliente_cel || '—' }}</td>
								<td class="text-right">
									<button type="button" class="btn btn-outline-primary btn-sm"
										@click.stop="selectCliente(cliente.clientes_cod, cliente.cliente_nombre)"
										title="Seleccionar">
										<span class="fa fa-user-check"></span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="small text-muted mt-2">@{{ clientes.length }} resultado(s)</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<span class="fa fa-times"></span> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>
