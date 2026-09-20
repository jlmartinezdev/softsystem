---
target: Browser / login
total_score: 17
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
p2_count: 2
target_identity: "file:C:\\laragon\\www\\softsystem\\resources\\views\\auth\\login.blade.php"
target_fingerprint: "sha256:71a9da43eae79226d3c6c4b3bd618797650d7b5dcc85d9e37c2a8c8221468bff"
target_path: "C:\\laragon\\www\\softsystem\\resources\\views\\auth\\login.blade.php"
timestamp: 2026-09-20T19-03-50Z
slug: resources-views-auth-login-blade-php
closed: true
---
# Critique — Login Softsystem (live)

**Target:** `resources/views/auth/login.blade.php` · `http://softsystem.test/login`
**Mode:** Operate (login gate into POS/admin)

## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 1 | `Acceder al Sistema` no muestra loading; `isRequest` no deshabilita; `this.error` no se pinta; 500 recarga en silencio |
| 2 | Match System / Real World | 2 | Español operativo vs `::VENTAPRO+::` / Softsystem / “Gestion”; typos `Atencion`, `erroneo` |
| 3 | User Control and Freedom | 2 | Se puede cambiar el select; lockout sin tiempo de espera ni escape claro |
| 4 | Consistency and Standards | 2 | Login suelto (no `layouts.app`); VENTAPRO+ 2.1 ≠ SOFTSYSTEM 2.0; Swal vs banner inline |
| 5 | Error Prevention | 2 | Dropdown evita tipear user; `Seleccionar` se envía como user válido; CTA no se deshabilita |
| 6 | Recognition Rather Than Recall | 2 | Elegir usuario + last-user es recognition de POS; campos sin label; iconos mudos |
| 7 | Flexibility and Efficiency | 2 | Enter en password + autofocus si hay last-user; sin búsqueda en el select |
| 8 | Aesthetic and Minimalist Design | 2 | Poco contenido (bien); chrome `::titulo::` + footer + fondo de red genérico + card AdminLTE |
| 9 | Error Recovery | 1 | Swals vagos; lockout sin Retry-After; 500 pierde la clave; `error` nunca visible |
| 10 | Help and Documentation | 1 | Cero ayuda contextual, cero “olvidé contraseña”, cero contacto de admin en lockout |
| **Total** | | **17/40** | **Poor** |

## Design Specificity Verdict

**LLM assessment:** Intercambiable de categoría. Es un login AdminLTE (`card` 350px + `input-group` + `btn-success`) sobre un fondo de red cian de stock. Cualquier ERP podría usarlo cambiando el string `::VENTAPRO+ v2.1::`. La identidad se parte en tres: el gate dice VENTAPRO+ v2.1, el `<title>` dice Login Softsystem, el sidebar post-login dice SOFTSYSTEM 2.0. El logo no aparece. El producto real (Gs., caja, Nueva venta) empieza *después* del login.

**Deterministic scan (CLI):** `impeccable detect --json resources/views/auth/login.blade.php` → exit 0, `[]`. El blade no tiene CSS computado; el detector de archivo no ve contraste ni leading del HTML renderizado.

**Visual overlays:** Inyección de `http://localhost:8400/detect.js` exitosa en el tab live. Overlay visible. Hallazgos en página:
- `low-contrast` en `h4` `::VENTAPRO+ v2.1::` — 2.1:1 (need 3:1) texto `#ffffff` sobre `#b3b3b3`
- `low-contrast` en `button.btn.btn-success` — 3.1:1 (need 4.5:1) texto `#ffffff` sobre `#28a745`
- `tight-leading` ×5 en `<style>` ocultos — falso positivo (nodos de stylesheet, no texto visible)
- `dark-glow` en `body` (halo `#ffba00`) — falso positivo del propio overlay del detector

## Overall Impression

El happy path es corto y correcto para un POS compartido (elegí usuario → clave → entrar). El resto es un portal genérico: marca partida, errores que regañan sin orientar, y un CTA verde que el detector marca por contraste. La mayor oportunidad es convertir este gate en “abrir el turno” con identidad única, labels reales y recuperación de error que un cajero pueda usar a las 18:00.

## What's Working

1. **Select de usuarios + last-user en `localStorage` + foco a Contraseña.** En vivo el combo ya venía en `PROPIETARIO` (también `ADMIN SISTEMA`). En un POS compartido, reconocer el nombre y saltar a la clave es el gesto correcto.
2. **Un solo CTA.** `Acceder al Sistema` es la única acción; Enter en el password llama `enviar()`.
3. **Idioma de operación.** `Iniciar Sesión`, `Contraseña`, `Acceder al Sistema` están en español del usuario.

## Priority Issues

**[P1] Lockout y errores no recuperables**
What: Vacío dispara Swal `Atencion!` / `Complete los campos!` (sin tilde, sin decir qué falta). Clave mala: `Contraseña incorrecta...`. 429: `Demasiado intento erroneo...` sin countdown. El guard `isRequest && intento` no corta reintentos. 500 recarga y pierde la clave.
Why it matters: el momento de más riesgo (caja bloqueada) es el peor diseñado.
Fix: errores inline bajo el campo; countdown con Retry-After; deshabilitar CTA; “Si no recordás la clave, pedí al administrador que la resetee”; no recargar en 500.
Suggested command: `/impeccable clarify` · `/impeccable harden`

**[P1] `Seleccionar` es un usuario enviable**
What: default `'Seleccionar'` tiene length > 0. Con clave, POST `user_usuarios: "Seleccionar"`.
Why it matters: el empty-state del select *es* un error silencioso.
Fix: `value=""` + option disabled; CTA disabled hasta user real + password; `required`.
Suggested command: `/impeccable harden`

**[P1] Campos sin nombre accesible**
What: `<select>` sin label (solo `fa-user`). Password solo `placeholder="Contraseña"`. `Iniciar Sesión` no es `h1`; el único heading es el `h4` del producto. Targets ~38px.
Why it matters: lector de pantalla no oye “usuario”; el placeholder desaparece al tipear.
Fix: labels visibles “Usuario” / “Contraseña”; `autocomplete`; `h1`; iconos `aria-hidden`; targets 44px.
Suggested command: `/impeccable audit` · `/impeccable clarify`

**[P2] Identidad partida y plantilla genérica**
What: `::VENTAPRO+ v2.1::` vs title Softsystem vs sidebar SOFTSYSTEM 2.0. Logo ausente. Fondo de red stock. Header blanco sobre gris a 2.1:1.
Why it matters: el cajero no confirma qué sistema abre; el chrome de marca es ilegible.
Fix: un nombre, un logo, una versión; header con contraste AA; alinear con el home (Cairo, primary) o con el sidebar.
Suggested command: `/impeccable polish` · `/impeccable distill`

**[P2] Submit sin estado + lista pública de usuarios**
What: el botón no entra en loading. Overlay confirma blanco sobre `#28a745` a 3.1:1. `GET /usuario/all` expone ADMIN SISTEMA y PROPIETARIO sin sesión.
Why it matters: doble clic y “¿mandó?”; cualquiera ve quién existe.
Fix: spinner + disabled; CTA más oscuro o texto más contrastado; endpoint que no publique todos los usuarios.
Suggested command: `/impeccable harden` · `/impeccable audit`

## Persona Red Flags

**Jordan (primera vez):** Lee VENTAPRO+ y un title Softsystem. El combo dice `Seleccionar`, no “Elegí tu usuario”. Ve ADMIN SISTEMA y PROPIETARIO. Swal `Complete los campos!` no dice si faltó el select o la clave. No hay ayuda.

**Sam (teclado / SR / contraste):** Select sin nombre accesible. Header 2.1:1. CTA 3.1:1. Iconos mudos. Swal trap modal. Targets ~38px. `font-weigh-bold` no aplica.

**Casey (móvil):** Card 350px sin media queries. CTA al centro vertical, fuera de la zona del pulgar. Header/footer fixed. Sin mostrar/ocultar clave. Last-user sí sobrevive si se va a WhatsApp.

## Minor Observations

- Typo CSS `font-weigh-bold`; copy `Gestion` / `Atencion` / `erroneo`.
- `<center>` en el footer del card.
- `sidebar_login.blade.php` existe y no se incluye.
- Favicon relativo `favicon.ico`.
- `background-image` sin `cover` / color de fallback (en vivo el PNG de red cubre, pero no está declarado).
- Condición de lockout con `&&` en vez de `||`.

## Questions to Consider

- ¿Este gate debería sentirse como abrir la caja del turno o como un login de admin AdminLTE?
- Si last-user es para un equipo chico, ¿por qué `usuario/all` publica todos los nombres antes de autenticar?
- ¿Qué debería ver un cajero bloqueado a las 18:00 — un countdown y un teléfono de admin, o `Demasiado intento erroneo...`?
