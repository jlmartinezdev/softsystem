<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="favicon.ico">
    <title>Login Softsystem</title>

    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <style>
        .abs-center {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        body{
            background-image: url("{{ asset('img/fondo.png') }}")
        }
        .transparent{
            background-color: rgba(255, 255, 255, 0.8);
        }
        .fixed-bottom{
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .fixed-top{
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0,0,0,.3);
        }

    </style>
</head>

<body class="h-100">
    <div class="fixed-top">
        <div class="text-center text-white font-weigh-bold p-2"><h4>::VENTAPRO+ v2.1::</h4></div>
    </div>
    <div class="container" id="app">
        <div class="abs-center">
            <div class="card transparent" style="width: 350px">
                <div class="card-body">
                    <div class="pl-3 pr-3">
                    <strong>Iniciar Sesi&oacute;n</strong>
                    <hr>
                      <template v-if="intento">
                        <div class="text-center text-danger font-weigh-bold p-2">&nbsp;Demasiado intento erroneo...</div>
                      </template>
                        
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-user"></span>
                                </span>
                            </span>
                            <select v-model="usuario" class="custom-select" ref="inputUser">
                                <option>Seleccionar</option>
                                <template v-for="usuario in usuarios">
                                    <option v-bind:value="usuario.user_usuarios">
                                        @{{ usuario.nom_usuarios }}
                                    </option>
                                </template>
                            </select>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-key"></span>
                                </span>
                            </span>
                            <input ref="inputPassword" v-on:keyup.enter="enviar()" type="password" v-model="password"
                                class="form-control" placeholder="Contraseña">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <center><button v-on:click="enviar()" class="btn btn-success btn-block"><span
                                class="fa fa-sign-in-alt"></span>&nbsp;Acceder al Sistema</button></center>
                  
                    
                </div>

            </div>
        </div>
        <div class="fixed-bottom">
            <div class="text-center text-muted font-weigh-bold p-2">&nbsp;Sistema de Gestion de Stock, Compra, Venta &copy; {{ date('Y') }}</div>
        </div>

    </div>
</body>
<script src="{{ asset(mix('js/app.js')) }}"></script>
<script>
    //const axios = require('axios').default;
    var app = new Vue({
        el: '#app',
        data: {
            usuario: 'Seleccionar',
            usuarios: [],
            password: '',
            error: '',
            isRequest: false,
            intento: false,
        },
        methods: {
            getUser: function() {
                var url = '{{ route('showalluser') }}';
                axios.get(url)
                    .then(response => {
                        this.usuarios = response.data;
                        let user = this.getUserData();
                        if(user != null)
                          this.usuario = user;
                          this.focusInput(user == null);

                    })
                    .catch(e => {
                        this.error = e.message;
                    })
            },
            enviar: function() {
                if (this.isRequest && this.intento)
                    return false;

                var url = 'login';
                if (this.usuario.length > 0 && this.password.length > 0) {
                    this.isRequest = true;
                    axios.post(url, {
                            user_usuarios: this.usuario.trim(),
                            password: this.password
                        })
                        .then(response => {
                          this.isRequest= false;

                            if (response.data.success == "no") {
                                Swal.fire(
                                    'Atencion!',
                                    'Contraseña incorrecta...',
                                    'error'
                                )
                            } else {
                                this.storeUserData(this.usuario.trim());
                                window.location.href = "{{ route('home') }}";
                            }
                        })
                        .catch(e => {
                          if(e.response.status==429){
                            this.intento= true;
                          }else if(e.response.status==500){
                            window.location.reload();
                          }
                          this.isRequest= false;
                          this.error = e.message;
                        });
                } else {
                    Swal.fire(
                        'Atencion!',
                        'Complete los campos!',
                        'warning'
                    )
                }
            },
            focusInput: function(flag) {
                if(flag)
                  this.$refs.inputUser.focus();
                else
                this.$refs.inputPassword.focus();
            },
            storeUserData: function(data) {
                if(data == 'Seleccionar')
                  return false;
                localStorage.setItem('login', JSON.stringify(data));
            },
            getUserData: function() {
                return JSON.parse(localStorage.getItem('login'));
            },
        },
        mounted() {
            this.getUser();
        }
    })
</script>

</html>
