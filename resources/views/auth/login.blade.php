<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Iniciar Sesión — {{ $empresa->emp_nombre ?? 'SOFTSYSTEM' }}</title>
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
            --brand-primary: #0a4d36;
            --brand-hover: #083c2a;
            --brand-light: #166b4d;
            --brand-accent: #10b981;
            --bg-deep: #061610;
            --bg-card: #ffffff;
            --text-main: #0f241d;
            --text-muted: #52665e;
            --border-soft: #d3dfd8;
            --danger: #b91c1c;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --warning: #d97706;
            --warning-bg: #fffbeb;
        }

        * { box-sizing: border-box; }
        html, body {
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Sofia Sans", "Segoe UI", system-ui, -apple-system, sans-serif;
            font-size: 15px;
            color: var(--text-main);
            background: radial-gradient(circle at 50% 15%, #184838 0%, #0d2a20 50%, var(--bg-deep) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 16px;
        }

        /* Ambient glow backdrop elements */
        .ambient-glow {
            position: fixed;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(10, 77, 54, 0) 70%);
            top: 20%;
            left: 50%;
            transform: translate(-50%, -30%);
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 430px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Brand Header */
        .brand-header {
            text-align: center;
            margin-bottom: 22px;
            color: #ffffff;
        }
        .brand-logo-container {
            width: 68px;
            height: 68px;
            margin: 0 auto 12px;
            border-radius: 20px;
            background: #ffffff;
            padding: 6px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 14px;
        }
        .brand-title {
            font-family: "Cairo", sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .brand-subtitle {
            margin: 5px 0 0;
            font-size: 0.92rem;
            color: #bbf7d0;
            font-weight: 500;
            opacity: 0.9;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            font-size: 0.8rem;
            color: #e2e8f0;
        }
        .status-pill .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 8px #34d399;
        }

        /* Login Card */
        .login-card {
            width: 100%;
            background: var(--bg-card);
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.08);
            padding: 30px 28px 24px;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #0a4d36 50%, #10b981 100%);
        }

        .card-header-text {
            margin-bottom: 20px;
        }
        .card-title {
            font-family: "Cairo", sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 4px;
            line-height: 1.25;
        }
        .card-desc {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin: 0;
        }

        /* User input mode switcher */
        .mode-switcher {
            display: flex;
            background: #f1f5f3;
            border-radius: 10px;
            padding: 3px;
            margin-bottom: 18px;
            gap: 2px;
        }
        .mode-btn {
            flex: 1;
            padding: 7px 10px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 0;
            background: transparent;
            color: var(--text-muted);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .mode-btn.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        /* Form elements */
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.88rem;
            color: #1e3a30;
            margin-bottom: 7px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-group .input-icon {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            height: 48px;
            border: 1.5px solid var(--border-soft);
            border-radius: 10px;
            padding: 0 14px 0 42px;
            font-size: 0.98rem;
            font-family: inherit;
            color: var(--text-main);
            background: #f9fbf9;
            transition: all 0.2s ease;
            appearance: none;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--brand-accent);
            background: #ffffff;
            box-shadow: 0 0 0 3.5px rgba(16, 185, 129, 0.16);
        }
        .input-group:focus-within .input-icon {
            color: var(--brand-primary);
        }

        select.form-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path fill='%2352665e' d='M1.41 0L6 4.58 10.59 0 12 1.41l-6 6-6-6z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 38px;
        }

        .form-input.has-error {
            border-color: var(--danger) !important;
            background-color: var(--danger-bg) !important;
        }

        /* Toggle password button */
        .btn-toggle-eye {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: 0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-toggle-eye:hover {
            color: var(--brand-primary);
            background: #eef5f1;
        }

        /* CapsLock warning */
        .caps-warning {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 0.8rem;
            color: var(--warning);
            background: var(--warning-bg);
            padding: 4px 9px;
            border-radius: 6px;
        }

        .input-feedback {
            margin: 6px 0 0;
            font-size: 0.83rem;
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Alert Banners */
        .alert-banner {
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 18px;
            font-size: 0.88rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
        }
        .alert-banner.danger {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger);
        }
        .alert-banner.warning {
            background: var(--warning-bg);
            border: 1px solid #fde68a;
            color: #92400e;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #0a4d36 0%, #13694b 100%);
            color: #ffffff;
            font-family: "Cairo", sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(10, 77, 54, 0.35);
            margin-top: 6px;
        }
        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #083c2a 0%, #0f583e 100%);
            box-shadow: 0 6px 18px rgba(10, 77, 54, 0.45);
            transform: translateY(-1px);
        }
        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }
        .btn-submit:disabled {
            background: #94a3b8;
            box-shadow: none;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .btn-submit .fa-circle-notch {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Shake animation on invalid credential */
        .shake-animation {
            animation: shake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Card Footer */
        .login-footer {
            margin-top: 18px;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
            border-top: 1px solid #edf2ef;
            padding-top: 14px;
        }
        .login-footer a {
            color: var(--brand-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .app-version {
            margin-top: 16px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.78rem;
            text-align: center;
        }

        [v-cloak] { display: none !important; }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .login-card { padding: 22px 18px 20px; border-radius: 16px; }
            .brand-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>

    <div class="login-wrapper" id="app" v-cloak>
        <!-- Header Branding -->
        <header class="brand-header">
            <div class="brand-logo-container">
                <img src="{{ asset('img/logo-softsystem.PNG') }}" alt="{{ $empresa->emp_nombre ?? 'SOFTSYSTEM' }}">
            </div>
            <h1 class="brand-title">{{ $empresa->emp_nombre ?? 'SOFTSYSTEM' }}</h1>
            <p class="brand-subtitle">Punto de Venta &amp; Gestión Comercial</p>
            <div class="status-pill">
                <span class="dot"></span>
                <span>Turno del {{ $fecha_turno }}</span>
            </div>
        </header>

        <!-- Login Ticket Card -->
        <div class="login-card" :class="{ 'shake-animation': shakeCard }">
            <div class="card-header-text">
                <h2 class="card-title">Iniciar Sesión</h2>
                <p class="card-desc">Seleccione su usuario e ingrese su contraseña para abrir turno.</p>
            </div>

            <!-- Lockout / Throttled Alert -->
            <div v-if="intento" class="alert-banner danger" role="alert">
                <i class="fas fa-lock" style="margin-top: 2px;"></i>
                <div>
                    <strong>Caja Bloqueada</strong><br>
                    <span>@{{ lockMessage }}</span>
                </div>
            </div>

            <!-- Error Banner -->
            <div v-if="bannerError && !intento" class="alert-banner danger" role="alert">
                <i class="fas fa-exclamation-triangle" style="margin-top: 2px;"></i>
                <div>@{{ bannerError }}</div>
            </div>

            <!-- Mode Switcher: Dropdown vs Manual Typing -->
            <div class="mode-switcher" v-if="!intento">
                <button
                    type="button"
                    class="mode-btn"
                    :class="{ active: inputMode === 'select' }"
                    @click="switchMode('select')"
                >
                    <i class="fas fa-users"></i> Lista de Cajeros
                </button>
                <button
                    type="button"
                    class="mode-btn"
                    :class="{ active: inputMode === 'manual' }"
                    @click="switchMode('manual')"
                >
                    <i class="fas fa-keyboard"></i> Escribir Usuario
                </button>
            </div>

            <form @submit.prevent="enviar" novalidate>
                <!-- Modo 1: Selector de Usuario -->
                <div class="form-group" v-if="inputMode === 'select'">
                    <label class="form-label" for="usuario-select">Cajero / Usuario</label>
                    <div class="input-group">
                        <i class="fas fa-user-circle input-icon"></i>
                        <select
                            id="usuario-select"
                            ref="inputSelect"
                            v-model="usuario"
                            class="form-input"
                            :class="{ 'has-error': field === 'usuario' }"
                            :disabled="intento || isRequest"
                        >
                            <option value="" disabled>-- Seleccione su usuario --</option>
                            <option
                                v-for="item in usuarios"
                                :key="item.user_usuarios"
                                :value="item.user_usuarios"
                            >
                                @{{ item.nom_usuarios }} (@{{ item.user_usuarios }})
                            </option>
                        </select>
                    </div>
                    <div class="input-feedback" v-if="field === 'usuario'">
                        <i class="fas fa-info-circle"></i> @{{ error }}
                    </div>
                </div>

                <!-- Modo 2: Input Manual de Usuario (Scanner / Teclado) -->
                <div class="form-group" v-else>
                    <label class="form-label" for="usuario-text">Nombre de Usuario o Código</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input
                            id="usuario-text"
                            ref="inputText"
                            type="text"
                            v-model="usuario"
                            class="form-input"
                            :class="{ 'has-error': field === 'usuario' }"
                            placeholder="Ingrese su usuario..."
                            autocomplete="username"
                            :disabled="intento || isRequest"
                        >
                    </div>
                    <div class="input-feedback" v-if="field === 'usuario'">
                        <i class="fas fa-info-circle"></i> @{{ error }}
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group">
                    <label class="form-label" for="clave">Contraseña de Acceso</label>
                    <div class="input-group">
                        <i class="fas fa-key input-icon"></i>
                        <input
                            id="clave"
                            ref="inputPassword"
                            :type="verClave ? 'text' : 'password'"
                            v-model="password"
                            class="form-input"
                            :class="{ 'has-error': field === 'password' }"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            maxlength="64"
                            :disabled="intento || isRequest"
                            @keyup="checkCapsLock"
                            @keydown.enter="enviar"
                        >
                        <button
                            type="button"
                            class="btn-toggle-eye"
                            :title="verClave ? 'Ocultar contraseña' : 'Ver contraseña'"
                            @click="verClave = !verClave"
                            tabindex="-1"
                        >
                            <i :class="verClave ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Indicador CapsLock -->
                    <div class="caps-warning" v-if="capsLockOn">
                        <i class="fas fa-arrow-up"></i> Bloq Mayús activado
                    </div>

                    <div class="input-feedback" v-if="field === 'password'">
                        <i class="fas fa-times-circle"></i> @{{ error }}
                    </div>
                </div>

                <!-- Botón de Envío -->
                <button
                    type="submit"
                    class="btn-submit"
                    :disabled="isRequest || intento"
                >
                    <template v-if="isRequest">
                        <i class="fas fa-circle-notch"></i>
                        <span>Validando credenciales...</span>
                    </template>
                    <template v-else>
                        <i class="fas fa-cash-register"></i>
                        <span>Abrir Caja e Ingresar</span>
                    </template>
                </button>
            </form>

            <div class="login-footer">
                <span>¿Problemas para acceder? Contacte al Administrador del sistema.</span>
            </div>
        </div>

        <div class="app-version">
            Softsystem ERP &copy; {{ date('Y') }} &bull; Versión Segura
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                usuario: '',
                // Pre-carga inmediata desde el servidor para 0ms de espera
                usuarios: {!! json_encode($usuarios ?? []) !!},
                password: '',
                error: '',
                field: '',
                bannerError: '',
                isRequest: false,
                intento: false,
                lockSeconds: 0,
                verClave: false,
                inputMode: 'select',
                capsLockOn: false,
                shakeCard: false,
                lockTimer: null
            },
            computed: {
                lockMessage: function () {
                    if (this.lockSeconds > 0) {
                        return 'Demasiados intentos fallidos. Intente de nuevo en ' + this.lockSeconds + ' segundos.';
                    }
                    return 'Caja bloqueada momentáneamente. Intente de nuevo.';
                }
            },
            methods: {
                switchMode: function (mode) {
                    this.inputMode = mode;
                    var self = this;
                    this.$nextTick(function () {
                        if (mode === 'select' && self.$refs.inputSelect) {
                            self.$refs.inputSelect.focus();
                        } else if (mode === 'manual' && self.$refs.inputText) {
                            self.$refs.inputText.focus();
                        }
                    });
                },
                checkCapsLock: function (e) {
                    if (e && typeof e.getModifierState === 'function') {
                        this.capsLockOn = e.getModifierState('CapsLock');
                    }
                },
                triggerShake: function () {
                    var self = this;
                    this.shakeCard = true;
                    setTimeout(function () {
                        self.shakeCard = false;
                    }, 500);
                },
                cargarUsuariosFallback: function () {
                    // Si la precarga vino vacía, se consulta el endpoint público
                    if (this.usuarios && this.usuarios.length > 0) {
                        this.recuperarUsuarioRecordado();
                        return;
                    }

                    var self = this;
                    axios.get('{{ route('showalluser') }}')
                        .then(function (response) {
                            self.usuarios = response.data || [];
                            self.recuperarUsuarioRecordado();
                        })
                        .catch(function () {
                            // Si falla, se conmuta a modo manual
                            self.inputMode = 'manual';
                        });
                },
                recuperarUsuarioRecordado: function () {
                    var saved = this.getUserData();
                    if (!saved) {
                        this.focusInput();
                        return;
                    }

                    var exists = this.usuarios.some(function (item) {
                        return item.user_usuarios === saved;
                    });

                    if (exists) {
                        this.usuario = saved;
                        this.inputMode = 'select';
                        var self = this;
                        this.$nextTick(function () {
                            if (self.$refs.inputPassword) {
                                self.$refs.inputPassword.focus();
                            }
                        });
                    } else {
                        this.focusInput();
                    }
                },
                focusInput: function () {
                    var self = this;
                    this.$nextTick(function () {
                        if (self.inputMode === 'select' && self.$refs.inputSelect) {
                            self.$refs.inputSelect.focus();
                        } else if (self.$refs.inputText) {
                            self.$refs.inputText.focus();
                        }
                    });
                },
                enviar: function () {
                    if (this.isRequest || this.intento) {
                        return;
                    }

                    var userVal = (this.usuario || '').trim();
                    if (!userVal) {
                        this.field = 'usuario';
                        this.error = this.inputMode === 'select' ? 'Seleccione su usuario de la lista.' : 'Escriba su nombre de usuario.';
                        this.triggerShake();
                        this.focusInput();
                        return;
                    }

                    if (!this.password) {
                        this.field = 'password';
                        this.error = 'Ingrese su contraseña de acceso.';
                        this.triggerShake();
                        if (this.$refs.inputPassword) {
                            this.$refs.inputPassword.focus();
                        }
                        return;
                    }

                    var self = this;
                    this.isRequest = true;
                    this.error = '';
                    this.field = '';
                    this.bannerError = '';

                    axios.post('{{ url('login') }}', {
                        user_usuarios: userVal,
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
                            self.error = response.data.message || 'Contraseña incorrecta.';
                            self.triggerShake();
                            if (self.$refs.inputPassword) {
                                self.$refs.inputPassword.focus();
                            }
                            return;
                        }

                        // Guardar usuario en localStorage para el próximo inicio
                        self.storeUserData(userVal);
                        window.location.href = '{{ route('home') }}';
                    })
                    .catch(function (e) {
                        self.isRequest = false;
                        var status = e.response ? e.response.status : 0;
                        var data = e.response && e.response.data ? e.response.data : {};

                        self.triggerShake();

                        if (status === 429) {
                            var seconds = parseInt(data.retry_after || (e.response.headers && e.response.headers['retry-after']) || 60, 10);
                            self.startLockout(seconds);
                            return;
                        }

                        if (status === 419) {
                            self.bannerError = 'La sesión expiró por inactividad. La página se recargará automáticamente...';
                            setTimeout(function () {
                                window.location.reload();
                            }, 1800);
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
                            return;
                        }

                        self.field = 'password';
                        self.error = 'No fue posible iniciar sesión. Verifique sus datos e intente nuevamente.';
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
                storeUserData: function (data) {
                    try {
                        localStorage.setItem('softsystem_last_user', JSON.stringify(data));
                    } catch (e) {}
                },
                getUserData: function () {
                    try {
                        return JSON.parse(localStorage.getItem('softsystem_last_user')) || JSON.parse(localStorage.getItem('login'));
                    } catch (err) {
                        return null;
                    }
                }
            },
            mounted: function () {
                this.cargarUsuariosFallback();
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
