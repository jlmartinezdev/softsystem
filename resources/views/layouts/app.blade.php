<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="7200">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>


    <!-- Scripts -->

    <!-- Styles -->
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    @yield("style")
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
                <div class="container-fluid">
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


        @auth
            getSucursal();
        @endauth
    </script>
    @yield('script')
</body>

</html>
