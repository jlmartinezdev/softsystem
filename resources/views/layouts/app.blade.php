<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <meta http-equiv="refresh" content="7200">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>


    <!-- Scripts -->

    <!-- Styles -->
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
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
    </style>
    @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @guest
        @else
            @include('partial.sidebar_top')
            @if (Auth::user()->cod_rol == 4)
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
        <footer class="main-footer">
            @yield('footer')
        </footer>
    </div>
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="{{ asset('js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/date.format.js') }}"></script>
    <script src="{{ asset('js/vue-good-table.min.js') }}"></script>

    <script type="text/javascript">
        function activarMenu(nivel1, subnivel) {
            let menu_nivel1 = document.getElementById(nivel1);
            menu_nivel1.className += " active";


            if (subnivel.length > 0) {
                let menu_subnivel = document.getElementById(subnivel);
                menu_subnivel.className += " active";
                setTimeout(() => {
                    hacerClick(nivel1);
                }, 500);

            }
            // menu_nivel1.click();


        }

        function hacerClick(n) {
            let menu = document.getElementById(n);
            menu.click();
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
            if (desc != null) {
                var obj = document.getElementById("sucursal");
                obj.setAttribute('data-id', id);
                obj.innerHTML = " " + desc
            } else {
                var obj = document.getElementById("sucursal");
                obj.innerHTML = " Sel. Sucursal"
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

        if (currentTheme) {
            if (currentTheme === 'dark') {
                if (!document.body.classList.contains('dark-mode')) {
                    document.body.classList.add("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-light')) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                toggleSwitch.checked = true;
            }
        }

        function switchTheme(e) {
            if (e.target.checked) {
                if (!document.body.classList.contains('dark-mode')) {
                    document.body.classList.add("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-light')) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                localStorage.setItem('theme', 'dark');
            } else {
                if (document.body.classList.contains('dark-mode')) {
                    document.body.classList.remove("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-dark')) {
                    mainHeader.classList.add('navbar-light');
                    mainHeader.classList.remove('navbar-dark');
                }
                localStorage.setItem('theme', 'light');
            }
        }


        toggleSwitch.addEventListener('change', switchTheme, false);

        @auth
        getSucursal();
        @endauth
    </script>
    @yield('script')
</body>

</html>
