<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Abrir caja — {{ $empresa->emp_nombre ?? 'VENTAPRO+' }}</title>
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: "Cairo";
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url("{{ asset('webfonts/Cairo-Bold.ttf') }}") format("truetype");
        }
        @font-face {
            font-family: "Sofia Sans";
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url("{{ asset('webfonts/SofiaSans-Regular.ttf') }}") format("truetype");
        }

        :root {
            --ink: #10241c;
            --muted: #2b3d35;
            --paper: #f3f6f4;
            --ticket: #ffffff;
            --line: #5d6f67;
            --accent: #0a4d36;
            --accent-hover: #083c2a;
            --danger: #8f1d1d;
            --danger-bg: #f8ecec;
            --focus: #b8860b;
            --counter: #0e2a22;
            --on-dark: #e8f0eb;
        }

        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: "Sofia Sans", "Segoe UI", sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: var(--ink);
            background: var(--counter);
        }
        ::selection { background: #cde4d8; color: var(--ink); }
        :focus-visible {
            outline: 2px solid var(--focus);
            outline-offset: 2px;
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px 28px;
        }

        .login-brand {
            width: min(420px, 100%);
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--on-dark);
            margin-bottom: 20px;
        }
        .login-brand img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
            flex-shrink: 0;
        }
        .login-brand h1 {
            font-family: Cairo, sans-serif;
            font-size: 1.35rem;
            line-height: 1.3;
            margin: 0;
        }
        .login-brand p {
            margin: 4px 0 0;
            font-size: 1rem;
            color: var(--on-dark);
        }

        .login-ticket {
            width: min(420px, 100%);
            background: var(--ticket);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 28px 24px 22px;
        }
        .login-ticket h2 {
            font-family: Cairo, sans-serif;
            font-size: 1.5rem;
            line-height: 1.3;
            margin: 0 0 6px;
        }
        .login-lead {
            margin: 0 0 22px;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.5;
        }

        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 6px;
        }
        .field-control {
            position: relative;
            display: flex;
            align-items: stretch;
        }
        .field-control .fa-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }
        select,
        input[type="password"],
        input[type="text"] {
            width: 100%;
            min-height: 48px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 14px 0 40px;
            font: inherit;
            font-size: 1rem;
            line-height: 1.5;
            color: var(--ink);
            background: var(--paper);
            appearance: none;
        }
        select {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8'><path fill='%2310241c' d='M0 0l6 8 6-8z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        input[type="password"],
        input[type="text"] {
            padding-right: 56px;
        }
        .is-invalid {
            border-color: var(--danger);
            border-width: 2px;
            background: var(--danger-bg);
        }
        .toggle-clave {
            position: absolute;
            right: 2px;
            top: 50%;
            transform: translateY(-50%);
            min-width: 44px;
            min-height: 44px;
            border: 0;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            font: inherit;
            font-size: 1rem;
            font-weight: 700;
        }
        .field-error,
        .banner,
        .list-status {
            margin: 8px 0 0;
            color: var(--danger);
            font-size: 1rem;
            line-height: 1.45;
        }
        .banner {
            margin: 0 0 16px;
            padding: 12px 14px;
            background: var(--danger-bg);
            border-radius: 8px;
        }
        .list-status {
            margin: 0 0 16px;
            color: var(--muted);
        }

        .btn-abrir {
            width: 100%;
            min-height: 48px;
            margin-top: 8px;
            border: 2px solid var(--accent);
            border-radius: 8px;
            background: transparent;
            color: var(--accent);
            font-family: "Sofia Sans", "Segoe UI", sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.3;
            cursor: pointer;
        }
        .btn-abrir.is-ready {
            background: var(--accent);
            color: #fff;
        }
        .btn-abrir.is-ready:hover:not(:disabled) { background: var(--accent-hover); }
        .btn-abrir:disabled {
            cursor: not-allowed;
            background: #245c48;
            border-color: #245c48;
            color: #e8f0eb;
        }
        .help {
            margin: 16px 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.45;
            text-align: center;
        }

        @media (max-width: 640px) {
            .login-shell {
                justify-content: flex-end;
                padding: 16px 0 0;
            }
            .login-brand { width: 100%; padding: 0 16px; }
            .login-ticket {
                width: 100%;
                margin-top: auto;
                border-radius: 16px 16px 0 0;
                padding: 24px 16px 28px;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell" id="app">
        <header class="login-brand">
            <img src="{{ asset('img/logo-softsystem.PNG') }}" width="52" height="52" alt="{{ $empresa->emp_nombre ?? 'VENTAPRO+' }}">
            <div>
                <h1>{{ $empresa->emp_nombre ?? 'VENTAPRO+' }}</h1>
                <p>Turno del {{ $fecha_turno }}</p>
            </div>
        </header>

        <section class="login-ticket" aria-labelledby="login-title">
            <h2 id="login-title">Abrir caja</h2>
            <p class="login-lead">Elegí tu usuario y escribí tu clave para empezar el turno.</p>

            <form v-on:submit.prevent="enviar">
            <div v-if="intento" class="banner" role="alert">
                @{{ lockMessage }}
            </div>
            <p v-else-if="listaError" class="list-status" role="status">@{{ listaError }}</p>
            <p v-else-if="cargandoLista" class="list-status" role="status">Cargando cajeros…</p>

            <div class="field">
                <label for="usuario">Usuario</label>
                <div class="field-control">
                    <span class="fa fa-user fa-prefix" aria-hidden="true"></span>
                    <select
                        id="usuario"
                        name="username"
                        ref="inputUser"
                        v-model="usuario"
                        autocomplete="username"
                        :class="{ 'is-invalid': field === 'usuario' }"
                        :disabled="intento || isRequest"
                        :aria-invalid="field === 'usuario' ? 'true' : 'false'"
                        :aria-describedby="field === 'usuario' ? 'usuario-error' : null"
                    >
                        <option value="" disabled>Elegí tu usuario</option>
                        <option v-for="item in usuarios" :key="item.user_usuarios" :value="item.user_usuarios">
                            @{{ item.nom_usuarios }}
                        </option>
                    </select>
                </div>
                <p id="usuario-error" class="field-error" role="alert" v-show="field === 'usuario'">@{{ field === 'usuario' ? error : '' }}</p>
            </div>

            <div class="field">
                <label for="clave">Clave</label>
                <div class="field-control">
                    <span class="fa fa-key fa-prefix" aria-hidden="true"></span>
                    <input
                        id="clave"
                        ref="inputPassword"
                        :type="verClave ? 'text' : 'password'"
                        v-model="password"
                        maxlength="64"
                        autocomplete="current-password"
                        :class="{ 'is-invalid': field === 'password' }"
                        :disabled="intento || isRequest"
                        :aria-invalid="field === 'password' ? 'true' : 'false'"
                        :aria-describedby="field === 'password' ? 'clave-error' : null"
                    >
                    <button
                        type="button"
                        class="toggle-clave"
                        :aria-label="verClave ? 'Ocultar clave' : 'Mostrar clave'"
                        :aria-pressed="verClave ? 'true' : 'false'"
                        v-on:click="verClave = !verClave"
                    >
                        @{{ verClave ? 'Ocultar' : 'Ver' }}
                    </button>
                </div>
                <p id="clave-error" class="field-error" role="alert" v-show="field === 'password'">@{{ field === 'password' ? error : '' }}</p>
            </div>

            <button
                type="submit"
                class="btn-abrir"
                :class="{ 'is-ready': usuario && password && !isRequest }"
                :disabled="!puedeEnviar"
            >
                <span v-if="isRequest">Abriendo caja…</span>
                <span v-else>Abrir caja</span>
            </button>
            <p class="help" v-if="!intento && !isRequest && field !== 'password'">Si no recordás la clave, pedí al administrador que la resetee.</p>
            </form>
        </section>
    </main>

    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                usuario: '',
                usuarios: [],
                password: '',
                error: '',
                field: '',
                isRequest: false,
                intento: false,
                lockSeconds: 0,
                verClave: false,
                cargandoLista: true,
                listaError: '',
                lockTimer: null
            },
            computed: {
                puedeEnviar: function () {
                    return !this.isRequest && !this.intento;
                },
                lockMessage: function () {
                    if (this.lockSeconds > 0) {
                        return 'Caja bloqueada. Esperá ' + this.lockSeconds + ' segundos.';
                    }
                    return 'Caja bloqueada. Esperá un momento e intentá de nuevo.';
                }
            },
            methods: {
                getUser: function () {
                    var self = this;
                    this.cargandoLista = true;
                    axios.get('{{ route('showalluser') }}')
                        .then(function (response) {
                            var saved = self.getUserData();
                            self.usuarios = self.ordenarCajeros(response.data || [], saved);
                            self.cargandoLista = false;
                            var exists = saved && self.usuarios.some(function (item) {
                                return item.user_usuarios === saved;
                            });
                            if (exists) {
                                self.usuario = saved;
                                self.$nextTick(function () { self.focusInput(false); });
                            } else {
                                self.$nextTick(function () { self.focusInput(true); });
                            }
                        })
                        .catch(function () {
                            self.cargandoLista = false;
                            self.listaError = 'No se pudo cargar la lista de usuarios. Recargá la página.';
                        });
                },
                ordenarCajeros: function (list, saved) {
                    var esSistema = function (item) {
                        var n = ((item.nom_usuarios || '') + ' ' + (item.user_usuarios || '')).toUpperCase();
                        return n.indexOf('ADMIN') !== -1 || n.indexOf('SISTEMA') !== -1 || n.indexOf('ROOT') !== -1;
                    };
                    return list.slice().sort(function (a, b) {
                        if (saved) {
                            if (a.user_usuarios === saved) return -1;
                            if (b.user_usuarios === saved) return 1;
                        }
                        var asys = esSistema(a);
                        var bsys = esSistema(b);
                        if (asys !== bsys) {
                            return asys ? 1 : -1;
                        }
                        return (a.nom_usuarios || '').localeCompare(b.nom_usuarios || '', 'es');
                    });
                },
                enviar: function () {
                    if (this.isRequest || this.intento) {
                        return;
                    }
                    if (!this.usuario) {
                        this.field = 'usuario';
                        this.error = 'Elegí tu usuario para abrir la caja.';
                        this.focusInput(true);
                        return;
                    }
                    if (!this.password) {
                        this.field = 'password';
                        this.error = 'Escribí tu clave.';
                        this.focusInput(false);
                        return;
                    }

                    var self = this;
                    this.isRequest = true;
                    this.error = '';
                    this.field = '';

                    axios.post('{{ url('login') }}', {
                        user_usuarios: this.usuario.trim(),
                        password: this.password
                    }, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function (response) {
                            self.isRequest = false;
                            if (response.data.success === 'no') {
                                self.field = response.data.field || 'password';
                                self.error = response.data.message || 'La clave no coincide. Probá de nuevo.';
                                self.focusInput(self.field !== 'usuario');
                                return;
                            }
                            self.storeUserData(self.usuario.trim());
                            window.location.href = '{{ route('home') }}';
                        })
                        .catch(function (e) {
                            self.isRequest = false;
                            var status = e.response ? e.response.status : 0;
                            var data = e.response && e.response.data ? e.response.data : {};

                            if (status === 429) {
                                var seconds = parseInt(data.retry_after || (e.response.headers && e.response.headers['retry-after']) || 60, 10);
                                self.startLockout(seconds);
                                return;
                            }
                            if (status === 419) {
                                self.field = 'password';
                                self.error = 'La sesión del formulario venció. Recargá la página e intentá de nuevo.';
                                return;
                            }
                            if (status === 422) {
                                if (data.field) {
                                    self.field = data.field;
                                    self.error = data.message;
                                } else if (data.errors) {
                                    if (data.errors.user_usuarios) {
                                        self.field = 'usuario';
                                        self.error = data.errors.user_usuarios[0];
                                    } else if (data.errors.password) {
                                        self.field = 'password';
                                        self.error = data.errors.password[0];
                                    }
                                }
                                self.focusInput(self.field !== 'usuario');
                                return;
                            }
                            self.field = 'password';
                            self.error = 'No se pudo abrir la caja. Tu clave sigue escrita; probá de nuevo.';
                        });
                },
                startLockout: function (seconds) {
                    var self = this;
                    this.intento = true;
                    this.lockSeconds = seconds;
                    if (this.lockTimer) {
                        clearInterval(this.lockTimer);
                    }
                    this.lockTimer = setInterval(function () {
                        self.lockSeconds -= 1;
                        if (self.lockSeconds <= 0) {
                            self.intento = false;
                            clearInterval(self.lockTimer);
                            self.lockTimer = null;
                        }
                    }, 1000);
                },
                focusInput: function (toUser) {
                    var self = this;
                    this.$nextTick(function () {
                        var el = toUser ? self.$refs.inputUser : self.$refs.inputPassword;
                        if (el && el.focus) {
                            el.focus();
                        }
                    });
                },
                storeUserData: function (data) {
                    if (!data) {
                        return;
                    }
                    localStorage.setItem('login', JSON.stringify(data));
                },
                getUserData: function () {
                    try {
                        return JSON.parse(localStorage.getItem('login'));
                    } catch (err) {
                        return null;
                    }
                }
            },
            mounted: function () {
                this.getUser();
            },
            beforeDestroy: function () {
                if (this.lockTimer) {
                    clearInterval(this.lockTimer);
                }
            }
        });
    </script>
</body>
</html>
