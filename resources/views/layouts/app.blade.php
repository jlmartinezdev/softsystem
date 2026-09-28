<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="refresh" content="7200">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>


    <!-- Scripts -->

    <!-- Styles -->
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vue-good-table.min.css') }}" rel="stylesheet">
    <style>
        .theme-switch {
            /* display: inline-block; */
            height: 24px;
            position: relative;
            width: 50px;
        }

        .theme-switch input {
            display: none;
        }

        .slider {
            background-color: #ccc;
            
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: 400ms;
        }

        .slider::before {
           /* background-color: #fff;*/
            background-image: url({{ asset('css/day.svg') }});
            color: white;
            bottom: 4px;
            content: "";
            height: 16px;
            left: 4px;
            position: absolute;
            transition: 400ms;
            width: 16px;
        }

        input:checked+.slider {
            background-color: #66bb6a;
        }
        input:checked+.slider::before {
           /* background-color: #fff;*/
            background-image: url({{ asset('css/night.svg') }});
        }

        input:checked+.slider::before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round::before {
            border-radius: 50%;
        }
        .table {
            margin-bottom: 0;
        }
        .main-sidebar .brand-link {
            display: flex;
            align-items: center;
            min-height: 57px;
            padding: 0.8rem 1rem;
            background: #073827;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .main-sidebar .brand-icon-box {
            width: 32px;
            height: 32px;
            background: #0a4d36;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
        }
        .main-sidebar .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #ffffff;
        }
        .sidebar-user-panel {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .user-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .branch-selector-chip {
            display: flex;
            align-items: center;
            padding: 0.4rem 0.7rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.15s;
        }
        .branch-selector-chip:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-color: #b8860b;
        }
        .nav-sidebar .nav-header {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: rgba(255, 255, 255, 0.42) !important;
            padding: 1rem 0.85rem 0.35rem !important;
            text-transform: uppercase;
        }
        .nav-sidebar .nav-link {
            border-radius: 6px;
            padding: 0.45rem 0.75rem;
            margin-bottom: 2px;
            transition: background 0.12s, color 0.12s;
        }
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.07);
            color: #ffffff;
        }
        aside.main-sidebar .nav-sidebar > .nav-item > .nav-link.active,
        aside.main-sidebar .nav-sidebar .nav-treeview > .nav-item > .nav-link.active {
            background-color: #0a4d36 !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        aside.main-sidebar .nav-sidebar .nav-link.active p,
        aside.main-sidebar .nav-sidebar .nav-link.active i {
            color: #ffffff !important;
        }
        aside.main-sidebar .nav-sidebar .nav-treeview .nav-link {
            padding-left: 2rem;
            font-size: 0.875rem;
        }
        aside.main-sidebar .nav-sidebar .nav-treeview .nav-link i.nav-icon {
            font-size: 0.65rem;
            margin-right: 0.4rem;
        }
        .nav-link-pos {
            background: rgba(10, 77, 54, 0.35);
            border: 1px solid rgba(10, 77, 54, 0.5);
        }
        .nav-link-pos:hover {
            background: #0a4d36 !important;
        }
        :focus-visible {
            outline: 2px solid #b8860b;
            outline-offset: 2px;
        }
        ::selection {
            background: #cde4d8;
            color: #10241c;
        }

        /* Prevenir FOUC en Vue antes de compilar */
        [v-cloak] {
            display: none !important;
        }

        .app-loading-skeleton {
            display: none;
        }
        [v-cloak] + .app-loading-skeleton {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 45vh;
            flex-direction: column;
            gap: 1rem;
            color: #0a4d36;
        }
        body.dark-mode [v-cloak] + .app-loading-skeleton {
            color: #10b981;
        }
        .v-cloak-spinner {
            width: 40px;
            height: 40px;
            border: 3.5px solid rgba(10, 77, 54, 0.15);
            border-top-color: #0a4d36;
            border-radius: 50%;
            animation: vCloakSpin 0.75s infinite linear;
        }
        body.dark-mode .v-cloak-spinner {
            border-color: rgba(16, 185, 129, 0.2);
            border-top-color: #10b981;
        }
        @keyframes vCloakSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed{{ request()->routeIs('venta') ? ' venta-caja' : '' }}">
    <div class="wrapper">
        @guest
        @else
            @include('partial.sidebar_top')
            @if (Auth::user()->esAdministrador())
                @include('partial.sidebar_administrador')
            @else
                @include('partial.sidebar_vendedor')
            @endif
        @endguest
        <div class="content-wrapper">
            <section class="content">
                <div id="main" class="container-fluid">
                    @yield('main')
                </div>
            </section>
        </div>
        
    </div>
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="{{ asset('js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/date.format.js') }}"></script>
    <script src="{{ asset('js/vue-good-table.min.js') }}"></script>

    <script type="text/javascript">
        function abrirAncestrosMenu(el) {
            var node = el ? el.parentElement : null;
            while (node) {
                if (node.classList && node.classList.contains('nav-item') && node.querySelector(':scope > ul.nav-treeview')) {
                    node.classList.add('menu-open');
                }
                node = node.parentElement;
            }
        }

        function activarMenu(nivel1, subnivel) {
            if (subnivel && subnivel.length > 0) {
                var menu_subnivel = document.getElementById(subnivel);
                if (menu_subnivel) {
                    menu_subnivel.classList.add("active");
                    abrirAncestrosMenu(menu_subnivel);
                }
            }

            if (nivel1 && nivel1.length > 0) {
                var menu_nivel1 = document.getElementById(nivel1);
                if (menu_nivel1) {
                    menu_nivel1.classList.add("active");
                    abrirAncestrosMenu(menu_nivel1);
                }
            }
        }

        function soloNumero(event) {
            if (event.charCode >= 48 && event.charCode <= 57) {
                return true;
            }
            return false;
        }

        function format(input) {
            var num = input.value.replace(/\./g, "");
            num = num.toString().split("").reverse().join("").replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split("").reverse().join("").replace(/^[\.]/, "");
            input.value = num;
        }

        function getSucursal() {
            var id = localStorage.getItem("suc_cod");
            var desc = localStorage.getItem("suc_desc");
            var obj = document.getElementById("sucursal");
            if (!obj) {
                return;
            }
            if (desc != null) {
                obj.setAttribute('data-id', id);
                obj.innerHTML = " " + desc;
            } else {
                obj.innerHTML = " Sel. Sucursal";
            }
        }

        function separador(input) {
            // var separador = document.getElementById('separadorMiles');

            input.addEventListener('input', (e) => {
                var entrada = e.target.value.split(','),
                    parteEntera = entrada[0].replace(/\./g, ''),
                    parteDecimal = entrada[1],
                    salida = parteEntera.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                e.target.value = salida + (parteDecimal !== undefined ? ',' + parteDecimal : '');
            }, false);
        }

        var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
        var currentTheme = localStorage.getItem('theme');
        var mainHeader = document.querySelector('.main-header');

        if (currentTheme === 'dark' && toggleSwitch) {
            if (!document.body.classList.contains('dark-mode')) {
                document.body.classList.add("dark-mode");
            }
            if (mainHeader && mainHeader.classList.contains('navbar-light')) {
                mainHeader.classList.add('navbar-dark');
                mainHeader.classList.remove('navbar-light');
            }
            toggleSwitch.checked = true;
        }

        function switchTheme(e) {
            if (e.target.checked) {
                if (!document.body.classList.contains('dark-mode')) {
                    document.body.classList.add("dark-mode");
                }
                if (mainHeader && mainHeader.classList.contains('navbar-light')) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                localStorage.setItem('theme', 'dark');
            } else {
                if (document.body.classList.contains('dark-mode')) {
                    document.body.classList.remove("dark-mode");
                }
                if (mainHeader && mainHeader.classList.contains('navbar-dark')) {
                    mainHeader.classList.add('navbar-light');
                    mainHeader.classList.remove('navbar-dark');
                }
                localStorage.setItem('theme', 'light');
            }
        }

        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', switchTheme, false);
        }

        @auth
        getSucursal();
        @endauth
    </script>
    @yield('script')
</body>

</html>
