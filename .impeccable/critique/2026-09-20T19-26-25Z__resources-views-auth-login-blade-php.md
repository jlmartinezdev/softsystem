---
target: login / abrir caja
total_score: 28
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
p2_count: 2
target_identity: "file:C:\\laragon\\www\\softsystem\\resources\\views\\auth\\login.blade.php"
target_fingerprint: "sha256:09b57e875865a9aaa4709cf5633685321863697e298162455a5a42dba94ca83e"
target_path: "C:\\laragon\\www\\softsystem\\resources\\views\\auth\\login.blade.php"
timestamp: 2026-09-20T19-26-25Z
slug: resources-views-auth-login-blade-php
---
# Critique — Abrir caja (re-run)

**Target:** `resources/views/auth/login.blade.php` · `http://softsystem.test/login`
**Mode:** Operate (abrir turno)

## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 3 | Carga, Abriendo caja…, errores y countdown; no hay intentos restantes; aria-describedby nace roto |
| 2 | Match System / Real World | 3 | Voseo + Abrir caja + setiembre + clave; EMPRESA vs SOFTSYSTEM y ADMIN SISTEMA filtran sistema |
| 3 | User Control and Freedom | 2 | Ver/Ocultar y cambio de usuario; el 429 bloquea todo hasta el timer |
| 4 | Consistency and Standards | 3 | Labels y 48px coherentes; CTA type=button; pie no comparte marca con el header |
| 5 | Error Prevention | 2 | Select evita tipeo; Abrir caja nace enabled vacío; lockout no avisa el presupuesto |
| 6 | Recognition Rather Than Recall | 3 | Lista por nombre + último usuario; select sin autocomplete=username |
| 7 | Flexibility and Efficiency | 3 | Enter en Clave y restore; Enter desde Usuario no envía |
| 8 | Aesthetic and Minimalist Design | 3 | Mostrador + ticket + una tarea; el error duplica el help del admin |
| 9 | Error Recovery | 3 | Copy preciso e inline; lockout = esperar; field-error sin role=alert |
| 10 | Help and Documentation | 3 | Ayuda de clave en contexto; no dice qué pasa al abrir |
| **Total** | | **28/40** | **Good** |

## Design Specificity Verdict

**LLM assessment:** Autorizado para este producto. El título es Abrir caja, el lead habla de turno y cajeros, la paleta es mostrador `#0e2a22` + ticket, Cairo + Sofia Sans. No es el AdminLTE intercambiable de la corrida anterior. El carácter se pierde en el pie SOFTSYSTEM · stock, compra y venta y en el tenant EMPRESA / ADMIN SISTEMA.

**Deterministic scan (CLI):** `impeccable detect --json resources/views/auth/login.blade.php` → exit 0, `[]`.

**Visual overlays:** Inyección de `detect.js` exitosa. `impeccableDetectAsync()` devolvió 0 hallazgos. No hay badges visibles porque no hay qué marcar (en la corrida anterior: low-contrast en header 2.1:1 y CTA 3.1:1). Overlay limpio = detector de acuerdo con el contraste nuevo.

## Overall Impression

Subió de un portal genérico a una puerta de turno. El happy path es corto y el copy es de caja. Lo que queda es oficio de form (a11y, Enter, intentos) y una sola marca.

## What's Working

1. Marco de turno: Abrir caja, Turno del 20 de setiembre, Cargando cajeros…, voseo PY.
2. Errores inline en el idioma del cajero, clave conservada, foco al campo.
3. Oficio: 48px, Ver con palabra, foco oro, last-user.

## Priority Issues

**[P1] Lockout acantilado**
What: 429 deshabilita todo; no hay “te quedan N intentos”.
Why: un cajero a las 7:00 se queda fuera sin aviso previo.
Fix: intentos restantes desde el primer fallo; countdown como único objeto; no repetir el párrafo del admin.
Suggested command: `/impeccable harden`

**[P1] Anuncio de error / Ver para lector**
What: aria-describedby apunta a IDs que no existen hasta el v-if. Ver sin aria-label. field-error sin role=alert. Toggle 44×40.
Why: Sam puede no oír el error.
Fix: IDs siempre en el DOM; aria-label/aria-pressed; role=alert; alto 44px.
Suggested command: `/impeccable audit`

**[P1] Dos marcas en la puerta**
What: Header EMPRESA + pie SOFTSYSTEM · stock, compra y venta.
Why: Jordan no sabe de quién es la caja.
Fix: una sola firma (local + turno). Pie fuera o convertido en la fecha.
Suggested command: `/impeccable distill` · `/impeccable clarify`

**[P2] Enter y password managers**
What: select sin autocomplete=username; CTA type=button; Abrir caja enabled vacío.
Fix: form submit; autocomplete username; no aparentar listo vacío.
Suggested command: `/impeccable harden`

**[P2] ADMIN SISTEMA primero**
What: showAll ordena por nom_usuarios; Jordan elige el primero.
Fix: last-user first; bajar cuentas de sistema.
Suggested command: `/impeccable onboard`

## Persona Red Flags

**Jordan:** entiende Abrir caja; duda EMPRESA vs SOFTSYSTEM; ve ADMIN SISTEMA primero; el segundo fallo lo manda dos veces al admin.

**Sam:** labels y contraste OK; aria-describedby roto; Ver anuncia “Ver”; foco tras clave mala no garantizado.

**Casey:** CTA en zona baja en 390; Ver 40px de alto; teclado virtual sobre CTA no verificado.

## Minor Observations

- Cairo en el botón es display sobre Operate.
- FA user/key genéricos.
- Disabled #1e4a3a ≈ accent.
- Sheet móvil no pega al bottom.
- Help + error de clave = mismo admin dos veces.

## Questions to Consider

- Si el acto es Abrir caja, ¿el pie tiene que vender stock, compra y venta?
- ¿El primer nombre a las 7:00 debería ser ADMIN SISTEMA?
- ¿El segundo fallo antes del 429 es un acantilado o “te queda 1 intento”?
