{{-- Estilos de los modales de artículo y precios a crédito. Se incluye en @section('style') porque Vue descarta los <style> dentro de #app. --}}
<style>
    /* ===== Modales de Artículo ===== */
    #modalArticulo,
    #precioArticulo {
        --am-primary: var(--dash-primary, #0a4d36);
        --am-primary-dark: var(--dash-primary-dark, #073827);
        --am-primary-light: var(--dash-primary-light, #eaf3ef);
        --am-primary-border: var(--dash-primary-border, #c8dfd5);
        --am-card: var(--dash-card-bg, #ffffff);
        --am-panel: #f8fafc;
        --am-border: var(--dash-border, #e2e8f0);
        --am-text: var(--dash-text-main, #1c2430);
        --am-muted: var(--dash-text-muted, #64748b);
        --am-danger: #dc2626;
        --am-danger-light: #fef2f2;
        --am-ok: #16a34a;
        --am-ok-light: #f0fdf4;
        --am-warn-light: #fffbeb;
    }
    body.dark-mode #modalArticulo,
    body.dark-mode #precioArticulo {
        --am-panel: #111827;
        --am-danger: #f87171;
        --am-danger-light: rgba(220, 38, 38, 0.15);
        --am-ok: #34d399;
        --am-ok-light: rgba(16, 185, 129, 0.12);
        --am-warn-light: rgba(245, 158, 11, 0.12);
    }
    #modalArticulo .modal-content,
    #precioArticulo .modal-content {
        background: var(--am-card);
        color: var(--am-text);
        border: 1px solid var(--am-border) !important;
        border-radius: 16px;
        overflow: hidden;
    }
    /* Encabezado */
    #modalArticulo .am-header,
    #precioArticulo .am-header {
        background: var(--am-card);
        border-bottom: 1px solid var(--am-border);
        padding: 0.9rem 1.25rem;
        align-items: center;
    }
    #modalArticulo .am-header-icon,
    #precioArticulo .am-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: var(--am-primary-light);
        border: 1px solid var(--am-primary-border);
        color: var(--am-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
    #modalArticulo .am-header-icon.is-edit,
    #precioArticulo .am-header-icon.is-edit {
        background: var(--am-panel);
        border-color: var(--am-border);
        color: var(--am-text);
    }
    #modalArticulo .am-title,
    #precioArticulo .am-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--am-text);
        line-height: 1.25;
    }
    #modalArticulo .am-subtitle,
    #precioArticulo .am-subtitle {
        font-size: 0.8rem;
        color: var(--am-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 520px;
        display: block;
    }
    #modalArticulo .am-code,
    #precioArticulo .am-code {
        font-family: 'SFMono-Regular', Consolas, Menlo, monospace;
        font-size: 0.75rem;
        background: var(--am-panel);
        border: 1px solid var(--am-border);
        border-radius: 6px;
        padding: 1px 6px;
        margin-right: 0.35rem;
        color: var(--am-text);
    }
    #modalArticulo .am-header .close,
    #precioArticulo .am-header .close {
        color: var(--am-muted);
        text-shadow: none;
        opacity: 1;
    }
    #modalArticulo .am-header .close:hover,
    #precioArticulo .am-header .close:hover {
        color: var(--am-text);
    }
    /* Pestañas */
    #modalArticulo #tabArticulo {
        display: flex;
        gap: 0.25rem;
        padding: 0 1.25rem;
        margin: 0;
        border-bottom: 1px solid var(--am-border);
        background: var(--am-card);
        flex-wrap: nowrap;
    }
    #modalArticulo #tabArticulo .nav-item {
        flex: 0 0 auto;
    }
    #modalArticulo #tabArticulo .nav-link {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        border: 0;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        background: transparent !important;
        color: var(--am-muted);
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.7rem 0.85rem 0.6rem;
        box-shadow: none !important;
        white-space: nowrap;
    }
    #modalArticulo #tabArticulo .nav-link:hover {
        color: var(--am-text);
    }
    #modalArticulo #tabArticulo .nav-link.active {
        color: var(--am-primary) !important;
        border-bottom-color: var(--am-primary);
    }
    #modalArticulo .am-tab-count {
        font-size: 0.7rem;
        font-weight: 700;
        background: var(--am-panel);
        border: 1px solid var(--am-border);
        color: var(--am-muted);
        border-radius: 999px;
        padding: 0 0.45rem;
        line-height: 1.5;
    }
    #modalArticulo .nav-link.active .am-tab-count {
        background: var(--am-primary-light);
        border-color: var(--am-primary-border);
        color: var(--am-primary);
    }
    #modalArticulo .am-tab-alert {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--am-danger);
        display: inline-block;
    }
    #modalArticulo .modal-body,
    #precioArticulo .modal-body {
        background: var(--am-card);
    }
    #modalArticulo #tabArticuloContent {
        padding: 1.1rem 1.25rem;
    }
    /* Campos */
    #modalArticulo .am-label,
    #precioArticulo .am-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--am-text);
        margin-bottom: 0.3rem;
    }
    #modalArticulo .am-label .am-req,
    #precioArticulo .am-label .am-req {
        color: var(--am-danger);
        margin-left: 2px;
    }
    #modalArticulo .am-help,
    #precioArticulo .am-help {
        font-size: 0.75rem;
        color: var(--am-muted);
        margin-top: 0.25rem;
    }
    #modalArticulo .form-control,
    #precioArticulo .form-control {
        border-radius: 8px;
        border-color: var(--am-border);
    }
    #modalArticulo .form-control:focus,
    #precioArticulo .form-control:focus {
        border-color: var(--am-primary);
        box-shadow: 0 0 0 3px rgba(10, 77, 54, 0.12);
    }
    #modalArticulo .input-group > .form-control:not(:first-child),
    #precioArticulo .input-group > .form-control:not(:first-child),
    #modalArticulo .input-group > .in-number:not(:first-child),
    #precioArticulo .input-group > .in-number:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    #modalArticulo .input-group > .form-control:not(:last-child),
    #precioArticulo .input-group > .form-control:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    #modalArticulo .input-group-text,
    #precioArticulo .input-group-text {
        background: var(--am-panel);
        border-color: var(--am-border);
        color: var(--am-muted);
        border-radius: 8px;
        font-size: 0.85rem;
    }
    #modalArticulo .input-group-prepend .input-group-text,
    #precioArticulo .input-group-prepend .input-group-text {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    #modalArticulo .input-group-append .input-group-text,
    #precioArticulo .input-group-append .input-group-text,
    #modalArticulo .input-group-append .btn,
    #precioArticulo .input-group-append .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    #modalArticulo .am-input-main,
    #precioArticulo .am-input-main {
        font-size: 1.05rem;
        font-weight: 600;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        height: auto;
    }
    #modalArticulo .is-invalid,
    #precioArticulo .is-invalid,
    #modalArticulo .is-invalid:focus,
    #precioArticulo .is-invalid:focus {
        border-color: var(--am-danger) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }
    #modalArticulo .am-invalid-msg,
    #precioArticulo .am-invalid-msg {
        font-size: 0.75rem;
        color: var(--am-danger);
        margin-top: 0.25rem;
    }
    #modalArticulo .btn-am-ghost,
    #precioArticulo .btn-am-ghost {
        border: 1px solid var(--am-border);
        background: var(--am-card);
        color: var(--am-text);
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: 8px;
    }
    #modalArticulo .btn-am-ghost:hover,
    #precioArticulo .btn-am-ghost:hover {
        background: var(--am-primary-light);
        border-color: var(--am-primary-border);
        color: var(--am-primary);
    }
    /* Bloques */
    #modalArticulo .am-section-title,
    #precioArticulo .am-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--am-muted);
        margin: 0.25rem 0 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #modalArticulo .am-section-title::after,
    #precioArticulo .am-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--am-border);
    }
    #modalArticulo .am-toggle-card,
    #precioArticulo .am-toggle-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        background: var(--am-panel);
        border: 1px solid var(--am-border);
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
    }
    #modalArticulo .am-toggle-card .custom-control-label,
    #precioArticulo .am-toggle-card .custom-control-label {
        font-weight: 600;
        font-size: 0.88rem;
    }
    #modalArticulo .am-pill,
    #precioArticulo .am-pill {
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        white-space: nowrap;
    }
    #modalArticulo .am-pill-ok,
    #precioArticulo .am-pill-ok {
        background: var(--am-ok-light);
        color: var(--am-ok);
        border: 1px solid rgba(22, 163, 74, 0.3);
    }
    #modalArticulo .am-pill-off,
    #precioArticulo .am-pill-off {
        background: var(--am-danger-light);
        color: var(--am-danger);
        border: 1px solid rgba(220, 38, 38, 0.3);
    }
    #modalArticulo .am-collapse-btn,
    #precioArticulo .am-collapse-btn {
        color: var(--am-muted);
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        padding: 0;
    }
    #modalArticulo .am-collapse-btn:hover,
    #precioArticulo .am-collapse-btn:hover {
        color: var(--am-primary);
    }
    #modalArticulo .am-collapse-btn .fa-chevron-down,
    #precioArticulo .am-collapse-btn .fa-chevron-down {
        transition: transform 0.15s;
    }
    #modalArticulo .am-collapse-btn[aria-expanded="true"] .fa-chevron-down,
    #precioArticulo .am-collapse-btn[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }
    /* Precios */
    #modalArticulo .am-price-card {
        border: 1px solid var(--am-border);
        border-radius: 12px;
        background: var(--am-card);
        padding: 0.85rem 1rem;
        margin-bottom: 0.75rem;
    }
    #modalArticulo .am-price-card.is-main {
        border: 2px solid var(--am-primary);
        background: linear-gradient(180deg, var(--am-primary-light) 0%, var(--am-card) 70%);
    }
    #modalArticulo .am-price-card.is-cost {
        background: var(--am-panel);
    }
    #modalArticulo .am-price-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }
    #modalArticulo .am-price-title {
        font-weight: 700;
        font-size: 0.9rem;
    }
    #modalArticulo .am-badge-main,
    #precioArticulo .am-badge-main {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: var(--am-primary);
        color: #fff;
        border-radius: 6px;
        padding: 0.15rem 0.5rem;
    }
    #modalArticulo .am-price-input {
        font-size: 1.25rem !important;
        font-weight: 700;
        text-align: right;
        height: calc(1.5em + 1rem + 2px) !important;
    }
    #modalArticulo .am-margin-chips,
    #precioArticulo .am-margin-chips {
        display: flex;
        gap: 0.3rem;
        margin-top: 0.4rem;
        flex-wrap: wrap;
    }
    #modalArticulo .am-chip,
    #precioArticulo .am-chip {
        border: 1px solid var(--am-border);
        background: var(--am-card);
        color: var(--am-text);
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.1rem 0.55rem;
        cursor: pointer;
    }
    #modalArticulo .am-chip:hover,
    #precioArticulo .am-chip:hover {
        border-color: var(--am-primary);
        color: var(--am-primary);
    }
    #modalArticulo .am-chip.active,
    #precioArticulo .am-chip.active {
        background: var(--am-primary);
        border-color: var(--am-primary);
        color: #fff;
    }
    #modalArticulo .am-profit,
    #precioArticulo .am-profit {
        border-radius: 10px;
        padding: 0.45rem 0.7rem;
        background: var(--am-ok-light);
        color: var(--am-ok);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    #modalArticulo .am-profit.is-loss,
    #precioArticulo .am-profit.is-loss {
        background: var(--am-danger-light);
        color: var(--am-danger);
    }
    #modalArticulo .am-profit.is-empty,
    #precioArticulo .am-profit.is-empty {
        background: var(--am-panel);
        color: var(--am-muted);
    }
    #modalArticulo .am-profit-label,
    #precioArticulo .am-profit-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.85;
    }
    #modalArticulo .am-profit-value,
    #precioArticulo .am-profit-value {
        font-size: 1.05rem;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    #modalArticulo .am-lists {
        border: 1px solid var(--am-border);
        border-radius: 12px;
        overflow: hidden;
    }
    #modalArticulo .am-list-row {
        display: grid;
        grid-template-columns: 90px 130px 1fr 130px;
        gap: 0.6rem;
        align-items: center;
        padding: 0.5rem 0.85rem;
        border-bottom: 1px solid var(--am-border);
    }
    #modalArticulo .am-list-row:last-child {
        border-bottom: 0;
    }
    #modalArticulo .am-list-row.is-head {
        background: var(--am-panel);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--am-muted);
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
    }
    #modalArticulo .am-list-name {
        font-weight: 600;
        font-size: 0.85rem;
    }
    #modalArticulo .am-list-profit {
        font-size: 0.8rem;
        font-weight: 700;
        text-align: right;
        font-variant-numeric: tabular-nums;
        color: var(--am-ok);
    }
    #modalArticulo .am-list-profit.is-loss {
        color: var(--am-danger);
    }
    #modalArticulo .am-list-profit.is-empty {
        color: var(--am-muted);
        font-weight: 500;
    }
    /* Stock */
    #modalArticulo .am-stock-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }
    #modalArticulo .am-stock-kpi {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
    }
    #modalArticulo .am-stock-kpi strong {
        font-size: 1.35rem;
        color: var(--am-primary);
        font-variant-numeric: tabular-nums;
    }
    #modalArticulo .am-stock-kpi span {
        font-size: 0.82rem;
        color: var(--am-muted);
    }
    .stock-form-card {
        background: var(--am-panel, #f8fafc);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
    }
    .stock-table-card {
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        background: var(--dash-card-bg);
    }
    .stock-table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .stock-table thead th {
        background: var(--am-panel, #f1f5f9);
        color: var(--dash-text-muted);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        padding: 0.6rem 0.9rem;
        white-space: nowrap;
        vertical-align: middle;
    }
    .stock-table tbody td {
        padding: 0.6rem 0.9rem;
        vertical-align: middle;
        border-top: 0;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.88rem;
    }
    .stock-table tbody tr:last-child td {
        border-bottom: 0;
    }
    .stock-table tbody tr.stock-row-edit {
        background: var(--dash-primary-light);
    }
    .stock-table tbody tr:hover {
        background-color: var(--am-panel, #f8fafc);
    }
    .btn-action-stock-edit,
    .btn-action-stock-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: transparent;
        color: var(--dash-text-muted);
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-action-stock-edit:hover {
        background: var(--dash-primary);
        color: #ffffff;
        border-color: var(--dash-primary);
    }
    .btn-action-stock-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }
    /* Pie */
    #modalArticulo .am-footer,
    #precioArticulo .am-footer {
        background: var(--am-panel);
        border-top: 1px solid var(--am-border);
        padding: 0.7rem 1.25rem;
    }
    #modalArticulo .am-footer .btn-link,
    #precioArticulo .am-footer .btn-link {
        color: var(--am-muted);
        font-size: 0.82rem;
    }
    #modalArticulo .am-footer .btn-link:hover,
    #precioArticulo .am-footer .btn-link:hover {
        color: var(--am-primary);
    }
    /* Modo oscuro: formularios */
    body.dark-mode #modalArticulo .form-control,
    body.dark-mode #precioArticulo .form-control {
        background-color: #111827 !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }
    body.dark-mode #modalArticulo .form-control:focus,
    body.dark-mode #precioArticulo .form-control:focus {
        background-color: #0b1120 !important;
        border-color: #10b981 !important;
    }
    body.dark-mode #modalArticulo .form-control.is-invalid,
    body.dark-mode #precioArticulo .form-control.is-invalid {
        border-color: var(--am-danger) !important;
    }
    body.dark-mode #modalArticulo .stock-table tbody td,
    body.dark-mode #precioArticulo .stock-table tbody td {
        color: #f3f4f6 !important;
    }

    /* Pantallas medianas y celular */
    @media (max-width: 767.98px) {
    #modalArticulo .am-header-icon,
    #precioArticulo .am-header-icon {
            width: 36px;
            height: 36px;
        }
    #modalArticulo .am-subtitle,
    #precioArticulo .am-subtitle {
            max-width: 220px;
        }
    #modalArticulo #tabArticulo {
            padding: 0 0.5rem;
        }
    #modalArticulo #tabArticulo .nav-item {
            flex: 1 1 0;
        }
    #modalArticulo #tabArticulo .nav-link {
            justify-content: center;
            min-height: 44px;
            padding: 0.5rem 0.25rem !important;
            font-size: 0.82rem;
        }
    #modalArticulo .am-list-row {
            grid-template-columns: 1fr 1fr;
        }
    #modalArticulo .am-list-row.is-head {
            display: none;
        }
    #modalArticulo .am-list-name {
            grid-column: 1 / -1;
        }
    #modalArticulo .am-list-profit {
            grid-column: 1 / -1;
            text-align: left;
        }
    #modalArticulo .am-footer,
    #precioArticulo .am-footer {
            padding: 0.75rem 0.85rem;
        }
    }
    /* ===== Precios a crédito ===== */
    #precioArticulo .pc-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 0.7rem 1.25rem;
        border-bottom: 1px solid var(--am-border);
        background: var(--am-card);
    }
    #precioArticulo .pc-switches {
        display: flex;
        gap: 1.25rem;
        font-size: 0.85rem;
    }
    #precioArticulo .pc-table {
        margin: 0;
        padding: 0;
        border: 0;
        min-width: 0;
    }
    #precioArticulo .pc-row {
        display: grid;
        grid-template-columns: 110px 120px 1fr 1fr;
        gap: 0.6rem;
        align-items: center;
        padding: 0.4rem 1.25rem;
        border-bottom: 1px solid var(--am-border);
    }
    #precioArticulo .pc-row:last-child {
        border-bottom: 0;
    }
    #precioArticulo .pc-row:not(.is-head):hover {
        background: var(--am-panel);
    }
    #precioArticulo .pc-row.is-head {
        position: sticky;
        top: 0;
        z-index: 2;
        background: var(--am-panel);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--am-muted);
        padding-top: 0.45rem;
        padding-bottom: 0.45rem;
    }
    #precioArticulo .pc-row.is-empty .pc-plan,
    #precioArticulo .pc-row.is-empty .form-control {
        opacity: 0.6;
    }
    #precioArticulo .pc-row.is-empty .form-control:focus {
        opacity: 1;
    }
    #precioArticulo .pc-plan {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    #precioArticulo .pc-plan-num {
        min-width: 30px;
        height: 30px;
        border-radius: 8px;
        background: var(--am-primary-light);
        border: 1px solid var(--am-primary-border);
        color: var(--am-primary);
        font-weight: 800;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    #precioArticulo .pc-row.is-empty .pc-plan-num {
        background: var(--am-panel);
        border-color: var(--am-border);
        color: var(--am-muted);
    }
    #precioArticulo .pc-plan-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--am-muted);
    }
    #precioArticulo .pc-times {
        min-width: 46px;
        justify-content: center;
        font-weight: 700;
    }
    #precioArticulo fieldset:disabled .form-control {
        background: var(--am-panel);
        opacity: 1;
    }
    @media (max-width: 767.98px) {
        #precioArticulo .pc-toolbar {
            padding: 0.6rem 0.85rem;
        }
        #precioArticulo .pc-row {
            grid-template-columns: 1fr 1fr;
            padding: 0.55rem 0.85rem;
            gap: 0.4rem 0.6rem;
        }
        #precioArticulo .pc-row.is-head {
            display: none;
        }
        #precioArticulo .pc-plan {
            grid-column: 1 / -1;
        }
        #precioArticulo .pc-row > .input-group:nth-child(3) {
            grid-column: 2;
            grid-row: 2;
        }
        #precioArticulo .pc-row > .input-group:nth-child(2) {
            grid-row: 2;
        }
        #precioArticulo .pc-row > .input-group:nth-child(4) {
            grid-column: 1 / -1;
        }
    }
</style>
