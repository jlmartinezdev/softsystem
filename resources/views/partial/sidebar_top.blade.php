<nav class="main-header navbar navbar-expand ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="{{ request()->routeIs('venta') ? 'Más' : 'Menú' }}">
                <i class="fas fa-bars mx-1" aria-hidden="true"></i>
            </a>
        </li>
        

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <!--li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Buscar"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li -->

        @unless(request()->routeIs('venta'))
        <li class="nav-item">
            <div class="theme-switch-wrapper nav-link">
                <label class="theme-switch" for="checkbox">
                    <span class="sr-only">Tema claro u oscuro</span>
                    <input type="checkbox" id="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
        </li>
        @endunless
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
            aria-haspopup="true" aria-expanded="false" href="#" aria-label="Cuenta de {{ Auth::user()->nom_usuarios }}">
                <i class="fas fa-user" aria-hidden="true"></i> <b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ Auth::user()->nom_usuarios }}</span>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-header"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </li>
    </ul>
</nav>
