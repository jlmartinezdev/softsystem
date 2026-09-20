---
target: Inicio autenticado
total_score: 23
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 2
target_identity: "file:C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
target_fingerprint: "sha256:bc387f8e08ca228929e61bedf834d477b6bc5f3ad7d466b5b673685f2dad74c2"
target_path: "C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
timestamp: 2026-09-20T20-42-17Z
slug: resources-views-home-blade-php
---
# Critique — Inicio autenticado

**Target:** `resources/views/home.blade.php` · `http://softsystem.test/`
**Mode:** Operate (hub post-login; dueño = estado del local, cajero = mostrador)

## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 3 | SIFEN listo (prueba), caja abierta y Hoy se ven; el pulso es texto de cuerpo; la campana es un stub |
| 2 | Match System / Real World | 3 | Gs., voseo, Contado/Crédito, caja; “EMPRESA”, “Cta. cobrar”, nav en MAYÚSCULAS |
| 3 | User Control and Freedom | 2 | Salidas existen; **Cerrar caja** usa el mismo `.btn-venta` que **Nueva venta** |
| 4 | Consistency and Standards | 2 | AdminLTE + botones POS; dos sidebars (info vs primary); Cairo solo en el h1 |
| 5 | Error Prevention | 2 | El dueño siempre tiene **Nueva venta** aunque no haya caja; Cerrar parece vender |
| 6 | Recognition Rather Than Recall | 3 | **Facturar** está etiquetado; el rail de 11 ítems sigue pidiendo mapa mental |
| 7 | Flexibility and Efficiency | 2 | Caminos duplicados (atajos = sidebar); cero aceleradores en Inicio |
| 8 | Aesthetic and Minimalist Design | 2 | Lectura más quieta; rail + tabla + caja + 4 atajos siguen compitiendo |
| 9 | Error Recovery | 2 | Vacíos con CTA; cajero con SIFEN apagado solo ve “Avisá al dueño” (sin enlace) |
| 10 | Help and Documentation | 1 | Sin ayuda de tarea; `Falta {{ $sifenFalta }}` puede dump de campos API |
| **Total** | | **23/40** | **Acceptable** |

## Design Specificity Verdict

**Start here.** Copy and states are authored for VENTAPRO+ / Paraguay. The object is still an AdminLTE dashboard.

**LLM assessment:** El canvas habla POS: `Gs.` con miles por punto, voseo (“Cobrá el turno”, “Avisá al dueño”), Contado/Crédito, sucursal · caja, h1 = nombre del local, SIFEN como pulso del cobro, CTA **Nueva venta** / **Abrir caja** según rol y turno. Eso no es un SaaS genérico. El chasis sí lo es: `sidebar-dark-*`, campana, fullscreen, theme slider, cards AdminLTE, tabla striped, atajos de 4 baldosas. Cambiá los strings y esto es cualquier home Laravel. La marca en pantalla es un wordmark `VENTAPRO+` (sin disco SOFTSYSTEM) más un local placeholder “EMPRESA”. El ritual “abrir caja, no iniciar sesión” lo cumple el cajero con CTA a ancho; el dueño sigue viendo widgets del día.

**Deterministic scan:** `impeccable detect --json resources/views/home.blade.php` → exit 0, `[]`. Cero hallazgos CLI (Blade no computa CSS). El detector no vio el rail ni el contraste; eso solo salió en overlay.

**Visual overlays:** Inyección OK en la pestaña **[Human]** (`Inicio — EMPRESA [Human]`, view `aadab5`). 76 overlays: **67 layout-property-animation** (transición `margin-left` del sidebar AdminLTE — falso positivo), **6 tight line height**, **3 positioned child clipped by overflow**. **0 contrast**. 21 visibles (18 animación de layout + 3 clipped).

## Overall Impression

Inicio ya cobra y factura en el idioma del mostrador: SIFEN listo en prueba, filas con **Facturar**, tres lecturas en vez de info-boxes, wordmark VENTAPRO+. Sigue siendo un dashboard con un POS pegado. La oportunidad más grande: que el rail deje de ser el producto y que **Cerrar caja** no se vista de vender.

## What's Working

1. **Acción primaria según rol.** Cajero: CTA a ancho **Nueva venta** si hay caja, **Abrir caja** si no. Dueño: **Nueva venta** arriba a la derecha. Es el ritual del producto.
2. **SIFEN está en Inicio.** En vivo: “Listo para factura electrónica (prueba SIFEN).” y las filas dicen **Facturar**. Cuando falta config, el dueño ve Completar; el cajero, Avisá al dueño. Ya no es invisible.
3. **Lectura del día, no arcoíris.** Hoy / Por cobrar / mes con números grandes. Contado/Crédito como dominio, no “paid/unpaid”. Wordmark sin disco SOFTSYSTEM.

## Priority Issues

**[P1] El rail sigue siendo el home.** 11 destinos de dueño (9 de cajero) antes del turno. `nav-work` / `nav-quiet` baja opacidad; no agrupa Turno vs Backoffice. ARTÍCULOS, COMPRA, INFORMES, ANULAR, MANTENIMIENTO y AJUSTES siguen a un clic de VENTA.
- **Why it matters:** El cajero no llega a cobrar con el menor número de pasos. Once opciones superan la memoria de trabajo.
- **Fix:** Tres ítems de trabajo visibles (Inicio, Venta, Caja). El resto en un solo “Más” o cajón del dueño. COMPRA/INFORMES pueden quedarse, no a pleno peso ni a la misma altura que Venta.
- **Suggested command:** `/impeccable distill`

**[P1] Cerrar caja se viste de Nueva venta.** `.btn-venta` `#0a4d36` es el premio de cobrar. El cierre de turno lo reutiliza al lado de **Movimientos** outline.
- **Why it matters:** Fin de turno de alto riesgo con el chrome de la acción feliz. Un misclick cierra la caja.
- **Fix:** **Nueva venta** se queda verde. **Cerrar caja** outline/warn, copy “Vas a cerrar el turno.”
- **Suggested command:** `/impeccable harden`

**[P2] Inicio cuenta tres historias.** El lead ya dice “Hoy 0 ventas · Gs. 0.”; `.home-lectura` repite Hoy; luego tabla; luego atajos que clonan el sidebar.
- **Why it matters:** No hay un solo “estado del turno”. El dueño escanea, no decide.
- **Fix:** Una banda (caja + SIFEN + hoy). Una lista (últimas ventas *o* gráfico). Atajos solo si el rail se destila.
- **Suggested command:** `/impeccable quieter`

**[P2] El dueño puede vender sin caja.** El header **Nueva venta** sigue arriba aunque el rail diga “No hay caja abierta.”
- **Why it matters:** Rompe el ritual para quien todavía cobra.
- **Fix:** Sin turno abierto, la primaria es **Abrir caja**; **Nueva venta** se degrada o se bloquea con “Abrí caja para registrar ventas.”
- **Suggested command:** `/impeccable onboard`

**[P2] El local se llama EMPRESA.** El h1 y el título son el placeholder de `empresa.emp_nombre`. No hay puerta del comercio; hay un admin header.
- **Why it matters:** El cajero necesita saber en qué local está. Un heading genérico borra esa identidad.
- **Fix:** Tratar el placeholder como dato incompleto (aviso al dueño), no como marca de página. El h1 puede ser “Inicio” + local como contexto, o el local real cuando exista.
- **Suggested command:** `/impeccable clarify`

## Persona Red Flags

**Alex (power user):** Ignora Inicio y pega **VENTA** en el rail. Cero teclas a Nueva venta. Dos enlaces por fila al mismo href. Atajos = sidebar más lento.

**Sam (a11y):** Fullscreen sin nombre. Campana “No hay Notificacion.” SIFEN listo/bloqueado es sobre todo color (`#0a4d36` vs `#6b4f00`). El gráfico Morris (si hay datos) es canvas Raphael sin alternativa. `dark-mode` pelea con cards `#fff` hardcodeadas. Overlay: 0 contraste, 6 tight line-height.

**Cajero:** Con caja abierta el CTA y “Cobrá el turno” aciertan. Después recibe la misma tabla del dueño (columna Sucursal, Facturar). El rail todavía ofrece COMPRA / INFORMES / MANTENIMIENTO. Si SIFEN se apaga, “Avisá al dueño” no cierra el cobro.

**Dueño:** Ve Hoy / Por cobrar / mes, caja, SIFEN prueba. Por cobrar Gs. 2.625.000 está como KPI, no como empujón a Cobro. ANULAR y AJUSTES confirman nav de dueño en el mismo rail que la caja del día.

## Minor Observations

- Overlay: 67 transiciones de layout del sidebar (FP AdminLTE); 3 hijos positioned recortados por overflow.
- Navbar: hamburguesa, Inicio, campana vacía, fullscreen, tema, usuario — chrome genérico.
- “No hay Notificacion”; “ARTICULOS”; “Seccion.”
- Título `Inicio — EMPRESA` vs h1 solo EMPRESA.
- `meta refresh 7200` recarga el turno en silencio.
- Sucursal desde `localStorage` puede mostrar “Sel. Sucursal” como tercera identidad.
- Dueño `sidebar-dark-info` vs cajero `sidebar-dark-primary` — dos productos.
- Logo `logo-softsystem.PNG` sigue en disco; este brand-link ya no lo usa.

## Questions to Consider

- Si abrir caja es el ritual, ¿el cajero necesita un dashboard o solo estado de caja + un botón?
- ¿Un SIFEN bloqueado debería apagar **Nueva venta** o solo cambiar el verbo de la tabla?
- ¿Los atajos existen porque el rail falló?
- ¿“EMPRESA” como h1 es un bug de datos o una decisión de marca?
