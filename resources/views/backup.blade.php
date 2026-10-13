@extends('layouts.app')

@section('title', 'Copias de Seguridad de Base de Datos')

@section('style')
<style>
    /* ============================================================
     * ESTILOS DEL MÓDULO DE BACKUPS (SOFT-SYSTEM)
     * ============================================================ */
    .backup-container {
        font-family: 'Cairo', sans-serif;
    }

    /* Header y Tarjetas KPI */
    .backup-header-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .kpi-backup-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        height: 100%;
    }
    .kpi-backup-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    }

    .kpi-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .kpi-icon-primary { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .kpi-icon-info { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
    .kpi-icon-warning { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .kpi-icon-purple { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }

    .kpi-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
        margin-bottom: 0.2rem;
    }
    .kpi-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--dash-text-main);
        line-height: 1.2;
    }
    .kpi-sub {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin-top: 0.2rem;
    }

    /* Panel de Tabla */
    .table-backup-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
    }

    .table-toolbar {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .search-input-backup {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        padding: 0.45rem 0.85rem;
        font-size: 0.88rem;
        min-width: 260px;
    }
    .search-input-backup:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        outline: none;
    }

    /* Tabla Personalizada */
    .table-backup {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-backup th {
        background: rgba(0,0,0,0.02);
        border-bottom: 1px solid var(--dash-border);
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dash-text-muted);
        letter-spacing: 0.04em;
    }
    .table-backup td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.88rem;
        vertical-align: middle;
    }
    .table-backup tr:hover td {
        background-color: var(--dash-primary-light);
    }
    .table-backup tr:last-child td {
        border-bottom: none;
    }

    /* Botones de acción */
    .btn-pos-primary {
        background: #059669;
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 700;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.15s;
    }
    .btn-pos-primary:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .btn-pos-secondary {
        background: transparent;
        color: var(--dash-text-main);
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.5rem 0.95rem;
        font-weight: 600;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.15s;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }

    .btn-pos-warning {
        background: #d97706;
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.95rem;
        font-weight: 700;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.15s;
    }
    .btn-pos-warning:hover {
        background: #b45309;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        transition: all 0.15s;
        font-size: 0.85rem;
    }
    .btn-action-dl {
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
        border-color: rgba(59, 130, 246, 0.25);
    }
    .btn-action-dl:hover {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-action-restore {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.25);
    }
    .btn-action-restore:hover {
        background: #d97706;
        color: #ffffff;
    }
    .btn-action-del {
        background: rgba(239, 68, 68, 0.12);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.25);
    }
    .btn-action-del:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* Badges */
    .badge-gz {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
        font-weight: 700;
        font-size: 0.75rem;
    }
    .badge-sql {
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
        font-weight: 700;
        font-size: 0.75rem;
    }

    /* Callout Informativo */
    .callout-info-backup {
        background: rgba(59, 130, 246, 0.06);
        border-left: 4px solid #3b82f6;
        border-radius: 0 10px 10px 0;
        padding: 0.85rem 1.1rem;
        margin-bottom: 1.25rem;
        color: var(--dash-text-main);
        font-size: 0.85rem;
    }

    /* Soporte Modo Oscuro */
    body.dark-mode .backup-header-card,
    body.dark-mode .kpi-backup-card,
    body.dark-mode .table-backup-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .table-backup th {
        background-color: #111827;
        border-color: var(--dash-border);
    }
    body.dark-mode .table-backup td {
        border-color: var(--dash-border);
    }
    body.dark-mode .table-toolbar {
        border-color: var(--dash-border);
    }
</style>
@endsection

@section('main')
<div id="app" class="pt-2 backup-container" v-cloak>
    <!-- Encabezado Principal y Barra de Acciones -->
    <div class="backup-header-card d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">
                    <i class="fas fa-shield-alt mr-1"></i> SEGURIDAD DE DATOS
                </span>
                <span class="badge badge-secondary px-2 py-1" style="font-size: 0.75rem;">
                    MySQL @{{ stats.version }} &bull; @{{ stats.database }}
                </span>
            </div>
            <h4 class="font-weight-bold font-cairo mb-0 text-success">
                <i class="fas fa-database mr-2"></i> Copias de Seguridad (Backup)
            </h4>
            <div class="small text-muted mt-1">
                Generá respaldos completos de la base de datos, descargalos a tu dispositivo o restaurá puntos anteriores en segundos.
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Crear Backup Rápido -->
            <button type="button" class="btn-pos-primary" @click="crearBackupRapido" :disabled="procesando">
                <span v-if="creandoBackup" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                <i v-else class="fas fa-plus-circle"></i>
                Crear Backup Ahora
            </button>

            <!-- Botón Opciones Avanzadas de Backup -->
            <button type="button" class="btn-pos-secondary" @click="abrirModalOpciones" :disabled="procesando">
                <i class="fas fa-sliders-h"></i> Personalizar
            </button>

            <!-- Botón Subir y Restaurar -->
            <button type="button" class="btn-pos-warning" @click="abrirModalSubir" :disabled="procesando">
                <i class="fas fa-upload"></i> Subir y Restaurar
            </button>

            <!-- Botón Refrescar -->
            <button type="button" class="btn-pos-secondary" @click="cargarListado" :disabled="procesando" title="Recargar lista">
                <i class="fas fa-sync-alt" :class="{'fa-spin': cargando}"></i>
            </button>
        </div>
    </div>

    <!-- 4 Tarjetas de Estadísticas KPI -->
    <div class="row mb-3">
        <!-- KPI 1: Base de Datos Activa -->
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="kpi-backup-card">
                <div class="kpi-icon-box kpi-icon-primary">
                    <i class="fas fa-server"></i>
                </div>
                <div>
                    <div class="kpi-title">Base de Datos</div>
                    <div class="kpi-value">@{{ stats.database }}</div>
                    <div class="kpi-sub">
                        <i class="fas fa-plug text-success mr-1"></i> @{{ stats.host }}:@{{ stats.port }}
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Tablas y Tamaño DB -->
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="kpi-backup-card">
                <div class="kpi-icon-box kpi-icon-info">
                    <i class="fas fa-table"></i>
                </div>
                <div>
                    <div class="kpi-title">Estructura Actual</div>
                    <div class="kpi-value">@{{ stats.tables_count }} Tablas</div>
                    <div class="kpi-sub">
                        <i class="fas fa-weight-hanging text-info mr-1"></i> Tamaño: ~@{{ stats.db_size_mb }} MB
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Copias Disponibles -->
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="kpi-backup-card">
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="fas fa-archive"></i>
                </div>
                <div>
                    <div class="kpi-title">Copias en Servidor</div>
                    <div class="kpi-value">@{{ backups.length }} Archivos</div>
                    <div class="kpi-sub">
                        <i class="fas fa-hdd text-purple mr-1"></i> Ocupado: @{{ stats.total_backup_size }}
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Último Respaldo -->
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="kpi-backup-card">
                <div class="kpi-icon-box kpi-icon-warning">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <div class="kpi-title">Último Respaldo</div>
                    <div class="kpi-value" style="font-size: 1.05rem;" v-if="stats.last_backup">
                        @{{ stats.last_backup.created_at_human }}
                    </div>
                    <div class="kpi-value text-muted" style="font-size: 1.05rem;" v-else>
                        Sin copias
                    </div>
                    <div class="kpi-sub" v-if="stats.last_backup">
                        @{{ stats.last_backup.created_at }}
                    </div>
                    <div class="kpi-sub" v-else>
                        Hacé clic en Crear Backup
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Callout de Seguridad y Buenas Prácticas -->
    <div class="callout-info-backup d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-info-circle text-primary fa-lg"></i>
            <div>
                <strong>Almacenamiento seguro:</strong> Los respaldos se guardan en el directorio protegido 
                <code>storage/app/backups</code>. Se recomienda descargar periódicamente copias a un disco externo o nube 
                para prevenir imprevistos de hardware.
            </div>
        </div>
        <div>
            <a href="{{ route('backup.directo') }}?compress=1" class="btn btn-sm btn-outline-primary font-weight-bold">
                <i class="fas fa-cloud-download-alt mr-1"></i> Descarga Directa Express (.sql.gz)
            </a>
        </div>
    </div>

    <!-- Tabla Principal de Backups Almacenados -->
    <div class="table-backup-card">
        <div class="table-toolbar">
            <div class="d-flex align-items-center gap-2">
                <span class="font-weight-bold" style="font-size: 0.95rem;">
                    <i class="fas fa-folder-open text-warning mr-1"></i> Respaldos en el Servidor
                </span>
                <span class="badge badge-light border">@{{ filteredBackups.length }}</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Buscador de Archivos -->
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm border-left-0 search-input-backup" 
                           v-model="filtroTexto" placeholder="Buscar por nombre o fecha...">
                </div>

                <!-- Filtro de Compresión -->
                <select class="form-control form-control-sm" v-model="filtroFormato" style="width: 140px;">
                    <option value="todos">Todos los formatos</option>
                    <option value="gz">Solo Comprimidos (.gz)</option>
                    <option value="sql">Solo SQL planos (.sql)</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-backup">
                <thead>
                    <tr>
                        <th style="width: 50px;">Tipo</th>
                        <th>Nombre del Archivo</th>
                        <th>Fecha de Creación</th>
                        <th>Tamaño</th>
                        <th>Formato</th>
                        <th class="text-right" style="width: 160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="cargando">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="spinner-border text-success" role="status"></div>
                            <div class="mt-2 font-weight-bold">Cargando copias de seguridad...</div>
                        </td>
                    </tr>
                    <tr v-else-if="filteredBackups.length === 0">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-archive fa-3x mb-3 text-muted opacity-50"></i>
                            <div class="font-weight-bold" style="font-size: 1rem;">No se encontraron copias de seguridad</div>
                            <div class="small">Creá tu primer respaldo haciendo clic en "Crear Backup Ahora".</div>
                        </td>
                    </tr>
                    <tr v-for="(b, idx) in filteredBackups" :key="b.filename">
                        <td class="text-center">
                            <i v-if="b.is_compressed" class="fas fa-file-archive text-success fa-lg" title="Archivo Comprimido"></i>
                            <i v-else class="fas fa-file-code text-primary fa-lg" title="Script SQL"></i>
                        </td>
                        <td>
                            <div class="font-weight-bold" style="font-family: monospace; font-size: 0.9rem;">
                                @{{ b.filename }}
                            </div>
                            <div class="small text-muted">
                                <span class="badge badge-light border">@{{ b.type.toUpperCase() }}</span>
                                <span v-if="idx === 0" class="badge badge-success ml-1">MÁS RECIENTE</span>
                            </div>
                        </td>
                        <td>
                            <div class="font-weight-bold">@{{ b.created_at }}</div>
                            <div class="small text-muted">@{{ b.created_at_human }}</div>
                        </td>
                        <td>
                            <span class="font-weight-bold">@{{ b.size_formatted }}</span>
                            <div class="small text-muted">@{{ b.size_bytes.toLocaleString() }} bytes</div>
                        </td>
                        <td>
                            <span v-if="b.is_compressed" class="badge-gz">
                                <i class="fas fa-compress-arrows-alt mr-1"></i> GZIP (.sql.gz)
                            </span>
                            <span v-else class="badge-sql">
                                <i class="fas fa-file-alt mr-1"></i> SQL Plano (.sql)
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <!-- Descargar -->
                                <a :href="'{{ url('backup/descargar') }}/' + encodeURIComponent(b.filename)" 
                                   class="btn-action-icon btn-action-dl mr-1" title="Descargar a mi PC">
                                    <i class="fas fa-download"></i>
                                </a>

                                <!-- Restaurar -->
                                <button type="button" class="btn-action-icon btn-action-restore mr-1" 
                                        @click="prepararRestauracion(b)" title="Restaurar base de datos desde este backup">
                                    <i class="fas fa-undo"></i>
                                </button>

                                <!-- Eliminar -->
                                <button type="button" class="btn-action-icon btn-action-del" 
                                        @click="eliminarBackup(b)" title="Eliminar copia de seguridad">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Opciones Personalizadas de Backup -->
    <div class="modal fade" id="modalOpcionesBackup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--dash-border);">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold font-cairo">
                        <i class="fas fa-sliders-h text-success mr-2"></i> Nuevo Backup Personalizado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">Nombre Personalizado (Opcional):</label>
                        <input type="text" class="form-control" v-model="formBackup.name" 
                               placeholder="Ej: antes_de_actualizacion_2026">
                        <small class="text-muted">Si se deja vacío, se generará con la fecha y hora actual automáticamente.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">Contenido del Respaldo:</label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="optTipoCompleto" name="tipoContenido" class="custom-control-input" 
                                   value="completo" v-model="formBackup.tipo">
                            <label class="custom-control-label" for="optTipoCompleto">
                                <strong>Completo (Estructura + Datos)</strong>
                                <div class="small text-muted">Recomendado. Incluye todas las tablas, vistas, triggers y registros.</div>
                            </label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="optTipoSoloDatos" name="tipoContenido" class="custom-control-input" 
                                   value="datos" v-model="formBackup.tipo">
                            <label class="custom-control-label" for="optTipoSoloDatos">
                                <strong>Solo Datos (Sin CREATE TABLE)</strong>
                                <div class="small text-muted">Útil para exportar registros sin alterar la estructura existente.</div>
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="optTipoSoloEstructura" name="tipoContenido" class="custom-control-input" 
                                   value="estructura" v-model="formBackup.tipo">
                            <label class="custom-control-label" for="optTipoSoloEstructura">
                                <strong>Solo Estructura (Sin registros)</strong>
                                <div class="small text-muted">Exporta el esquema de tablas en blanco para crear nuevas bases.</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold small text-muted">Compresión:</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="switchComprimir" 
                                   v-model="formBackup.compress">
                            <label class="custom-control-label font-weight-bold" for="switchComprimir">
                                Comprimir con GZIP (.sql.gz)
                            </label>
                        </div>
                        <small class="text-muted">Reduce el tamaño del archivo entre un 80% y 90% para ahorrar espacio en disco.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-pos-primary" @click="crearBackupPersonalizado" :disabled="procesando">
                        <span v-if="creandoBackup" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                        <i v-else class="fas fa-save mr-1"></i> Generar Respaldo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Restauración de Seguridad con Doble Verificación -->
    <div class="modal fade" id="modalRestaurar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: 1px solid #ef4444;" v-if="backupParaRestaurar">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold font-cairo">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Restauración Crítica
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3">
                    <div class="alert alert-warning mb-3">
                        <h6 class="font-weight-bold mb-1"><i class="fas fa-radiation mr-1"></i> ¡Acción Irreversible!</h6>
                        <small>
                            La restauración <strong>sobrescribirá las tablas y registros actuales</strong> de la base de datos 
                            <code>@{{ stats.database }}</code> con la información contenida en este archivo de respaldo.
                        </small>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="font-weight-bold text-dark font-monospace mb-1">
                            @{{ backupParaRestaurar.filename }}
                        </div>
                        <div class="small text-muted">
                            Creado: @{{ backupParaRestaurar.created_at }} &bull; Tamaño: @{{ backupParaRestaurar.size_formatted }}
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold small text-danger">
                            Para confirmar, escribí la palabra <span class="badge badge-danger">RESTAURAR</span> en el campo inferior:
                        </label>
                        <input type="text" class="form-control text-center font-weight-bold font-monospace" 
                               v-model="palabraConfirmacion" placeholder="Escribí RESTAURAR aquí" 
                               style="letter-spacing: 0.1em; font-size: 1.05rem;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal" :disabled="restaurando">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold" 
                            :disabled="palabraConfirmacion.trim().toUpperCase() !== 'RESTAURAR' || restaurando"
                            @click="ejecutarRestauracion">
                        <span v-if="restaurando" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                        <i v-else class="fas fa-undo mr-1"></i> Sí, Restaurar Base de Datos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Subir y Restaurar Backup Externo -->
    <div class="modal fade" id="modalSubirBackup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--dash-border);">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold font-cairo">
                        <i class="fas fa-upload text-warning mr-2"></i> Subir y Restaurar Copia Externa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3">
                    <div class="alert alert-info small mb-3">
                        Seleccioná un archivo de respaldo generado previamente con extensión <strong>.sql</strong> o <strong>.sql.gz</strong>.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">Archivo de Respaldo:</label>
                        <input type="file" ref="inputFileBackup" accept=".sql,.gz" class="form-control-file" @change="onArchivoSeleccionado">
                    </div>

                    <div v-if="archivoSeleccionado" class="p-2 border rounded bg-light mb-3 small">
                        <strong>Archivo:</strong> @{{ archivoSeleccionado.name }} (@{{ (archivoSeleccionado.size / 1024 / 1024).toFixed(2) }} MB)
                    </div>

                    <div class="form-group mb-2" v-if="archivoSeleccionado">
                        <label class="font-weight-bold small text-danger">
                            Escribí <span class="badge badge-danger">RESTAURAR</span> para proceder:
                        </label>
                        <input type="text" class="form-control text-center font-weight-bold font-monospace" 
                               v-model="palabraConfirmacionSubida" placeholder="RESTAURAR">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal" :disabled="subiendoYRestaurando">Cancelar</button>
                    <button type="button" class="btn btn-warning font-weight-bold text-white" 
                            :disabled="!archivoSeleccionado || palabraConfirmacionSubida.trim().toUpperCase() !== 'RESTAURAR' || subiendoYRestaurando"
                            @click="ejecutarSubidaYRestauracion">
                        <span v-if="subiendoYRestaurando" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                        <i v-else class="fas fa-cloud-upload-alt mr-1"></i> Subir y Restaurar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-loading-skeleton">
    <div class="v-cloak-spinner"></div>
    <div class="font-weight-bold mt-2">Cargando módulo de copias de seguridad...</div>
</div>
@endsection

@section('script')
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    var app = new Vue({
        el: '#app',
        data: {
            cargando: false,
            creandoBackup: false,
            restaurando: false,
            subiendoYRestaurando: false,
            filtroTexto: '',
            filtroFormato: 'todos',
            stats: {!! json_encode($stats ?? [
                'database' => 'softsystem',
                'host' => 'localhost',
                'port' => 3306,
                'version' => 'MySQL',
                'tables_count' => 0,
                'rows_count' => 0,
                'db_size_mb' => 0,
                'backups_count' => 0,
                'total_backup_size' => '0 B',
                'last_backup' => null
            ]) !!},
            backups: {!! json_encode($backups ?? []) !!},
            formBackup: {
                name: '',
                tipo: 'completo',
                compress: true
            },
            backupParaRestaurar: null,
            palabraConfirmacion: '',
            archivoSeleccionado: null,
            palabraConfirmacionSubida: ''
        },
        computed: {
            procesando: function () {
                return this.creandoBackup || this.restaurando || this.subiendoYRestaurando || this.cargando;
            },
            filteredBackups: function () {
                var list = [...this.backups];

                // Filtro por formato
                if (this.filtroFormato === 'gz') {
                    list = list.filter(function (b) { return b.is_compressed; });
                } else if (this.filtroFormato === 'sql') {
                    list = list.filter(function (b) { return !b.is_compressed; });
                }

                // Filtro por texto
                if (this.filtroTexto && this.filtroTexto.trim() !== '') {
                    var q = this.filtroTexto.toLowerCase().trim();
                    list = list.filter(function (b) {
                        return b.filename.toLowerCase().includes(q) 
                            || b.created_at.toLowerCase().includes(q)
                            || b.type.toLowerCase().includes(q);
                    });
                }

                return list;
            }
        },
        methods: {
            cargarListado: function () {
                var self = this;
                this.cargando = true;
                axios.get('{{ route("backup.listar") }}')
                    .then(function (res) {
                        self.cargando = false;
                        if (res.data.success) {
                            self.stats = res.data.stats;
                            self.backups = res.data.backups;
                        }
                    })
                    .catch(function (err) {
                        self.cargando = false;
                        Toast.fire({ icon: 'error', title: 'No se pudo actualizar el listado de backups' });
                    });
            },

            crearBackupRapido: function () {
                var self = this;
                this.creandoBackup = true;
                Toast.fire({ icon: 'info', title: 'Generando copia de seguridad...' });

                axios.post('{{ route("backup.crear") }}', {
                    compress: true,
                    include_data: true,
                    include_structure: true
                }).then(function (res) {
                    self.creandoBackup = false;
                    if (res.data.success) {
                        Swal.fire({
                            title: '¡Backup Generado!',
                            text: res.data.message,
                            icon: 'success',
                            confirmButtonText: 'Entendido'
                        });
                        self.cargarListado();
                    } else {
                        Swal.fire('Error', res.data.message || 'No se pudo crear el backup', 'error');
                    }
                }).catch(function (err) {
                    self.creandoBackup = false;
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al generar backup.';
                    Swal.fire('Error', msg, 'error');
                });
            },

            abrirModalOpciones: function () {
                this.formBackup = {
                    name: '',
                    tipo: 'completo',
                    compress: true
                };
                $('#modalOpcionesBackup').modal('show');
            },

            crearBackupPersonalizado: function () {
                var self = this;
                this.creandoBackup = true;

                var incData = (this.formBackup.tipo === 'completo' || this.formBackup.tipo === 'datos');
                var incStruct = (this.formBackup.tipo === 'completo' || this.formBackup.tipo === 'estructura');

                axios.post('{{ route("backup.crear") }}', {
                    compress: this.formBackup.compress,
                    include_data: incData,
                    include_structure: incStruct,
                    name: this.formBackup.name
                }).then(function (res) {
                    self.creandoBackup = false;
                    $('#modalOpcionesBackup').modal('hide');
                    if (res.data.success) {
                        Swal.fire({
                            title: '¡Backup Generado!',
                            text: res.data.message,
                            icon: 'success',
                            confirmButtonText: 'Genial'
                        });
                        self.cargarListado();
                    } else {
                        Swal.fire('Error', res.data.message || 'No se pudo crear el backup', 'error');
                    }
                }).catch(function (err) {
                    self.creandoBackup = false;
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al generar backup.';
                    Swal.fire('Error', msg, 'error');
                });
            },

            prepararRestauracion: function (b) {
                this.backupParaRestaurar = b;
                this.palabraConfirmacion = '';
                $('#modalRestaurar').modal('show');
            },

            ejecutarRestauracion: function () {
                var self = this;
                if (!this.backupParaRestaurar) return;

                this.restaurando = true;
                axios.post('{{ route("backup.restaurar_existente") }}', {
                    filename: this.backupParaRestaurar.filename
                }).then(function (res) {
                    self.restaurando = false;
                    $('#modalRestaurar').modal('hide');
                    if (res.data.success) {
                        Swal.fire({
                            title: '¡Restauración Exitosa!',
                            text: res.data.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        self.cargarListado();
                    } else {
                        Swal.fire('Fallo de Restauración', res.data.message, 'error');
                    }
                }).catch(function (err) {
                    self.restaurando = false;
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al restaurar la base de datos.';
                    Swal.fire('Error', msg, 'error');
                });
            },

            abrirModalSubir: function () {
                this.archivoSeleccionado = null;
                this.palabraConfirmacionSubida = '';
                if (this.$refs.inputFileBackup) {
                    this.$refs.inputFileBackup.value = '';
                }
                $('#modalSubirBackup').modal('show');
            },

            onArchivoSeleccionado: function (e) {
                this.archivoSeleccionado = e.target.files[0] || null;
            },

            ejecutarSubidaYRestauracion: function () {
                var self = this;
                if (!this.archivoSeleccionado) return;

                var formData = new FormData();
                formData.append('archivo', this.archivoSeleccionado);

                this.subiendoYRestaurando = true;
                axios.post('{{ route("backup.subir_restaurar") }}', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(function (res) {
                    self.subiendoYRestaurando = false;
                    $('#modalSubirBackup').modal('hide');
                    if (res.data.success) {
                        Swal.fire({
                            title: '¡Restauración Completa!',
                            text: res.data.message,
                            icon: 'success'
                        });
                        self.cargarListado();
                    } else {
                        Swal.fire('Error', res.data.message, 'error');
                    }
                }).catch(function (err) {
                    self.subiendoYRestaurando = false;
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al subir y restaurar.';
                    Swal.fire('Error', msg, 'error');
                });
            },

            eliminarBackup: function (b) {
                var self = this;
                Swal.fire({
                    title: '¿Eliminar copia de seguridad?',
                    text: b.filename + ' (' + b.size_formatted + ')',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc2626'
                }).then(function (res) {
                    if (!res.value) return;

                    axios.delete('{{ url("backup/eliminar") }}/' + encodeURIComponent(b.filename))
                        .then(function (resp) {
                            if (resp.data.success) {
                                Toast.fire({ icon: 'success', title: resp.data.message });
                                self.cargarListado();
                            } else {
                                Swal.fire('Error', resp.data.message, 'error');
                            }
                        })
                        .catch(function (err) {
                            Swal.fire('Error', 'No se pudo eliminar el archivo.', 'error');
                        });
                });
            }
        },
        mounted: function () {
            activarMenu('m_backup', 'm_mantenimiento');
        }
    });
</script>
@endsection
