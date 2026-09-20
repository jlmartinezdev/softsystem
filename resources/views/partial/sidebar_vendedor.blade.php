<aside class="main-sidebar sidebar-dark-primary elevation-2">
    <a href="{{ route('home') }}" class="brand-link">
        <span class="brand-text">VENTAPRO+</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                <li class="nav-item nav-work">
                    <a href="{{ route('home') }}" id="m_home" class="nav-link">
                        <i class="nav-icon fa fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-item nav-work">
                    <a href="{{ route('venta') }}" id="m_venta" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>Venta</p>
                    </a>
                </li>
                <li class="nav-item nav-work has-treeview">
                    <a href="#" class="nav-link" id="m_caja">
                        <i class="nav-icon fa fa-cash-register"></i>
                        <p>
                            Caja
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('apertura') }}" id="m_apertura" class="nav-link">
                                <i class="fa fa-chevron-right nav-icon"></i>
                                <p>Abrir o cerrar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('movimiento') }}" id="m_movimiento" class="nav-link">
                                <i class="fa fa-chevron-right nav-icon"></i>
                                <p>Movimientos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-work">
                    <a href="{{ route('cobro') }}" id="m_cobro" class="nav-link">
                        <i class="nav-icon fa fa-money-bill"></i>
                        <p>Cobro</p>
                    </a>
                </li>
                <li class="nav-item nav-work">
                    <a href="{{ route('sucursal.set') }}" class="nav-link" id="m_sucursal">
                        <i class="fa fa-warehouse nav-icon"></i>
                        <p><span id="sucursal"></span></p>
                    </a>
                </li>
                <li class="nav-item nav-more has-treeview">
                    <a href="#" class="nav-link" id="m_mas">
                        <i class="nav-icon fa fa-ellipsis-h"></i>
                        <p>
                            Más
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('articulo') }}" id="m_articulo" class="nav-link">
                                <i class="nav-icon fa fa-clone"></i>
                                <p>Artículos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('compra') }}" id="m_compra" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>Compra</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link" id="m_informe">
                                <i class="nav-icon fa fa-sticky-note"></i>
                                <p>
                                    Informes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('infcompra') }}" class="nav-link" id="m_icompra">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Compras</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('infventa') }}" class="nav-link" id="m_iventa">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Ventas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('infctacobrar') }}" id="m_ictacobrar" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Cuentas a cobrar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('infcobro') }}" id="m_ictacobro" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Cobros</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link" id="m_mantenimiento">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    Mantenimiento
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('cliente.index') }}" id="m_cliente" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Cliente</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('seccion.index') }}" id="m_seccion" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Sección</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reffactura.index') }}" id="m_reffactura" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Referencia factura</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('proveedor.index') }}" id="m_proveedor" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Proveedor</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ciudad.index') }}" id="m_ciudad" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Ciudad</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('unidades.index') }}" id="m_unidades" class="nav-link">
                                        <i class="fa fa-chevron-right nav-icon"></i>
                                        <p>Unidad</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
