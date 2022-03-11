<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="/img/logo-softsystem.PNG" alt="Softystem" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">SOFTSYSTEM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image text-white">
                <i class="fa fa-user"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block"> {{ Auth::user()->nom_usuarios }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                <li class="nav-item">
                    <a href="#" class="nav-link" id="m_mantenimiento">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Mantimiento
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
                                <p>Seccion</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reffactura.index') }}" id="m_reffactura" class="nav-link">
                                <i class="fa fa-chevron-right nav-icon"></i>
                                <p>Referencia Factura</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('proveedor.index') }}" id="m_proveedor" class="nav-link">
                                <i class="fa fa-chevron-right nav-icon"></i>
                                <p>Proveedor</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('articulo') }}" id="m_articulo" class="nav-link">
                        <i class="nav-icon fa fa-clone"></i>
                        <p>
                            Articulos
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('compra') }}" id="m_compra" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Compra
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('venta') }}" id="m_venta" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Venta
                        </p>
                    </a>
                </li>
                <li class="nav-item">
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
                                <p>Apert. - Cierre Caja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('movimiento') }}" id="m_movimiento" class="nav-link">
                                <i class="fa fa-chevron-right nav-icon"></i>
                                <p>Movimiento caja</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cobro') }}" id="m_cobro" class="nav-link">
                        <i class="nav-icon fa fa-money-bill"></i>
                        <p>
                            COBRO DE CUENTAS
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sucursal.set') }}" class="nav-link" id="m_sucursal">
                        <i class="fa fa-warehouse nav-icon"></i>
                        <p><span id="sucursal"></span></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-sign-out-alt"></i>
                        <p>
                            Cerrar Sesion
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
