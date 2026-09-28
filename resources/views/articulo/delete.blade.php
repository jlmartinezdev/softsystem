<div class="modal fade" id="deleteArticulo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-danger text-white">
				<div class="d-flex align-items-center">
					<i class="fa fa-triangle-exclamation fa-lg mr-2"></i>
					<h5 class="modal-title font-weight-bold mb-0">Confirmar Eliminación</h5>
				</div>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-4 text-center">
				<p class="mb-2 lead font-weight-bold text-dark">¿Estás seguro de que deseas eliminar este artículo?</p>
				<div class="alert alert-light border font-weight-semibold text-secondary">
					@{{ articulo.descripcion }}
				</div>
				<small class="text-muted">Esta acción no se puede deshacer.</small>
			</div>
			<div class="modal-footer bg-light py-2">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">
					<i class="fa fa-times mr-1"></i> Cancelar
				</button>
				<button type="button" class="btn btn-danger px-3 font-weight-bold" @click="delArticulo">
					<i class="fa fa-trash mr-1"></i> Sí, Eliminar
				</button>
			</div>
		</div>
	</div>
</div>
