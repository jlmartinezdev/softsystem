<aside class="main-sidebar sidebar-dark-primary elevation-2">
    <!-- Logo / Marca -->
    <a href="{{ route('home') }}" class="brand-link">
        <span class="brand-icon-box mr-2">
            <i class="fas fa-cash-register"></i>
        </span>
        <span class="brand-text">VENTAPRO+</span>
    </a>

    <div class="sidebar">
        <!-- Panel de Usuario y Sucursal Activa -->
        <div class="sidebar-user-panel mt-3 pb-3 mb-2 d-flex flex-column border-bottom">
            <div class="d-flex align-items-center mb-2 px-2">
                <div class="user-avatar-circle mr-2">
                    <i class="fas fa-user-shield text-success"></i>
                </div>
                <div class="user-info-text text-truncate">
                    <div class="font-weight-bold text-white text-truncate" style="font-size: 0.875rem;">
                        {{ Auth::user()->nom_usuarios ?? 'Administrador' }}
                    </div>
                    <span class="badge badge-success" style="font-size: 0.68rem; font-weight: 600;">Administrador</span>
                </div>
            </div>
            <a href="{{ route('sucursal.set') }}" class="branch-selector-chip mx-2" id="m_sucursal" title="Cambiar de sucursal">
                <i class="fas fa-warehouse mr-1 text-warning"></i>
                <span id="sucursal" class="text-truncate">Sucursal</span>
                <i class="fas fa-chevron-right ml-auto text-muted" style="font-size: 0.65rem;"></i>
            </a>
        </div>

        <!-- Navegación Principal -->
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="true">

                {{-- BLOQUE 1: OPERACIONES --}}
                <li class="nav-header">OPERACIONES</li>

                <li class="nav-item">
                    <a href="{{ route('home') }}" id="m_home" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('venta') }}" id="m_venta" class="nav-link nav-link-pos">
                        <i class="nav-icon fas fa-shopping-cart text-success"></i>
                        <p>
                            Punto de Venta
                            <span class="badge badge-warning right font-weight-bold">F2</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('presupuesto.index') }}" id="m_presupuesto" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar text-info"></i>
                        <p>Presupuestos</p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" id="m_caja">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Caja y Turno
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('apertura') }}" id="m_apertura" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Abrir / Cerrar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('movimiento') }}" id="m_movimiento" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Movimientos y Arqueo</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cobro') }}" id="m_cobro" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>Cobranzas</p>
                    </a>
                </li>

                {{-- BLOQUE 2: CATÁLOGO Y GESTIÓN --}}
                <li class="nav-header">CATÁLOGO Y GESTIÓN</li>

                <li class="nav-item">
                    <a href="{{ route('articulo') }}" id="m_articulo" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Artículos y Precios</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('combo.index') }}" id="m_combo" class="nav-link">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Combos y Paquetes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('oferta.index') }}" id="m_oferta" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Ofertas y Promos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('compra') }}" id="m_compra" class="nav-link">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Compras</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cliente.index') }}" id="m_cliente" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Clientes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('proveedor.index') }}" id="m_proveedor" class="nav-link">
                        <i class="nav-icon fas fa-industry"></i>
                        <p>Proveedores</p>
                    </a>
                </li>

                {{-- BLOQUE 3: INFORMES Y AUDITORÍA --}}
                <li class="nav-header">REPORTES Y AUDITORÍA</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" id="m_informe">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Informes
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('infventa') }}" class="nav-link" id="m_iventa">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Informe de Ventas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('infcompra') }}" class="nav-link" id="m_icompra">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Informe de Compras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('infctacobrar') }}" id="m_ictacobrar" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cuentas a Cobrar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('infcobro') }}" id="m_ictacobro" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cobros Realizados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('infstock') }}" id="m_istock" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventario / Stock</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('resumen') }}" id="m_iresumen" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Resumen Gerencial</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" id="m_anular">
                        <i class="nav-icon fas fa-trash-alt text-danger"></i>
                        <p>
                            Anulaciones
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('anularventa') }}" class="nav-link" id="m_aventa">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anular Venta</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('anularcobro') }}" class="nav-link" id="m_acobro">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anular Cobro</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('anularcompra') }}" class="nav-link" id="m_acompra">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anular Compra</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- BLOQUE 4: CONFIGURACIÓN Y SISTEMA --}}
                <li class="nav-header">CONFIGURACIÓN Y SISTEMA</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" id="m_sifen_menu">
                        <i class="nav-icon fas fa-file-invoice text-success"></i>
                        <p>
                            Facturación SIFEN
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('sifen.index') }}" id="m_sifen" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Configuración SIFEN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sifen.laboratorio') }}" id="m_sifen_lab" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laboratorio SIFEN</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link" id="m_mantenimiento">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Mantenimiento
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('usuario') }}" id="m_usuario" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permiso.index') }}" id="m_permiso" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Privilegios y Permisos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('empresa.index') }}" id="m_empresa" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Datos de Empresa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seccion.index') }}" id="m_seccion" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Secciones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('unidades.index') }}" id="m_unidades" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Unidades de Medida</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ciudad.index') }}" id="m_ciudad" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ciudades</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reffactura.index') }}" id="m_reffactura" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Referencia Factura</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ajuste.index') }}" id="m_ajuste" class="nav-link">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>Ajustes Generales</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
