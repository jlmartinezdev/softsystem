---
target: Inicio autenticado
total_score: 22
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
p2_count: 2
target_identity: "file:C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
target_fingerprint: "sha256:4422ef3c7d184a6097bde74688f5feba50331917436858809673df607f697630"
target_path: "C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
timestamp: 2026-09-20T20-23-00Z
slug: resources-views-home-blade-php
---
# Critique — Inicio autenticado (re-run)

**Target:** `resources/views/home.blade.php` · `http://softsystem.test/`
**Mode:** Operate (hub post-login; admin = estado del local, cajero = mostrador)

## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 2 | Caja abierta se ve; no hay pulso SIFEN; campana vacía |
| 2 | Match System / Real World | 3 | Voseo, Gs., caja, EMPRESA; el marco sigue siendo dashboard SaaS |
| 3 | User Control and Freedom | 3 | Venta, Cerrar, Movimientos, logout; filas de ventas inertes |
| 4 | Consistency and Standards | 2 | Logo SOFTSYSTEM + wordmark VENTAPRO+ + H1 EMPRESA; VENTA vs Nueva venta |
| 5 | Error Prevention | 2 | Sidebar VENTA sigue con caja cerrada; Cerrar y Movimientos mismo peso |
| 6 | Recognition Rather Than Recall | 2 | Atajos ok; SIFEN escondido; ventas no clicables |
| 7 | Flexibility and Efficiency | 2 | Caminos duplicados; cero teclas de POS |
| 8 | Aesthetic and Minimalist Design | 2 | Canvas destilado; chrome AdminLTE ruidoso (MAYÚSCULAS, campana, 4 info-boxes) |
| 9 | Error Recovery | 3 | Vacíos de caja y gráfico accionables; tabla vacía sin siguiente paso |
| 10 | Help and Documentation | 1 | Cero ayuda de tarea (abrir caja, cobrar, facturar) |
| **Total** | | **22/40** | **Acceptable** |

## Design Specificity Verdict

**LLM assessment:** Parcialmente authored. El canvas habla POS Paraguay: split admin/cajero, H1 = local, voseo, Gs., objeto Caja en curso, CTA `#0a4d36` 48px. El marco sigue siendo AdminLTE (info-boxes, Morris, sidebar-mini, campana). Un retail genérico reutilizaría el chrome; no el ritual de caja. SIFEN no aparece en Inicio.

**Deterministic scan (CLI):** `impeccable detect --json` sobre home, layout y ambos sidebars → exit 0, `[]`.

**Visual overlays:** Inyección en tab nuevo `http://softsystem.test/` (admin). Overlay visible. 75 elementos: **67 layout-transition**, **6 tight-leading**, **1 clipped-overflow**, **1 dark-glow**, **1 text-occlusion**, **0 low-contrast visibles**. Live-server detenido al cerrar.

Falsos positivos: transiciones `margin-left` del sidebar AdminLTE; `tight-leading` en `<style>` ocultos; OverlayScrollbars clip; glow del overlay. Señal real: el P2 de contraste de la corrida anterior (Nueva venta, teal, INICIO, badges) **ya no aparece**. El detector y la revisión coinciden en que el hueco ahora es IA/chrome, no AA de CTA.

## Overall Impression

El 16/40 era un dashboard de dueño con Nueva venta escondida. Esta corrida es un hub destilado con marca de producto y un mostrador para el cajero. Todavía se siente AdminLTE con un till pegado: el cajero conserva COMPRA/INFORMES/MANTENIMIENTO y Paraguay (SIFEN) no tiene pulso en Inicio.

## What's Working

1. **Split de roles** — el cajero recibe CTA a bloque + caja primero; el dueño recibe una fila de métricas y 4 atajos. No es el mismo home con un permiso escondido.
2. **Caja en curso** — operación, sucursal, usuario, apertura, Abierta, Cerrar/Movimientos. El atajo va a movimientos de la caja abierta, no a apertura.
3. **Contraste del canvas** — overlay sin low-contrast visible en CTA, INICIO activo, badges Contado/Crédito/Abierta.

## Priority Issues

### [P1] El cajero aterriza en un back-office

- **What:** `sidebar_vendedor` sigue con ARTICULOS, COMPRA, INFORMES, MANTENIMIENTO. El canvas dice cobrá; el rail dice administrar.
- **Why it matters:** Principio 1: el cajero llega a cobrar con el menor número de pasos. Once destinos ganan al CTA.
- **Fix:** Rail del vendedor = Inicio / Venta / Caja / Cobro (+ sucursal). El resto detrás de un solo “Más” o solo en admin.
- **Suggested command:** `/impeccable distill` (sidebar vendedor) o `/impeccable layout`

### [P1] SIFEN es invisible en Inicio

- **What:** El positioning es factura Paraguay. Inicio no dice si se puede facturar. `documento` se consulta en ventas recientes y no se pinta.
- **Why it matters:** Principio 3: SIFEN es parte del cobro, no un extra. El hub se siente retail genérico con Gs.
- **Fix:** Un estado de turno: caja + “listo para factura electrónica” o el bloqueo concreto. Sin inventar clientes ni cifras.
- **Suggested command:** `/impeccable onboard` (estado fiscal del turno) o `/impeccable clarify`

### [P1] Jerarquía admin = info-boxes AdminLTE

- **What:** Cuatro iconos de color empatados; “Estado del local” no es una lectura; Nueva venta es header, no el trabajo; Atajos duplican Venta.
- **Why it matters:** El squint test no elige un primario. Sigue el template de métricas.
- **Fix:** Una lectura del día (hoy + por cobrar) y el resto en informe. Un solo camino a vender.
- **Suggested command:** `/impeccable quieter` o `/impeccable distill`

### [P2] Tres nombres en el vidrio

- **What:** Disco `logo-softsystem.PNG` + wordmark VENTAPRO+ + H1 EMPRESA. El chrome ya no dice SOFTSYSTEM 2.0.
- **Why it matters:** El cajero no sabe qué nombre importa en el mostrador.
- **Fix:** Wordmark o disco, no ambos pelea; el local queda en el H1. Reemplazar el PNG o tratarlo como marca de producto.
- **Suggested command:** `/impeccable clarify`

### [P2] Últimas ventas son un periódico

- **What:** Ocho filas globales, sin href, sin estado fiscal, Cerrar y Movimientos mismo peso.
- **Why it matters:** No aceleran el cobro ni recuperan un ticket.
- **Fix:** Filas clicables al detalle; Cerrar más claro que Movimientos; vacío con “Nueva venta”.
- **Suggested command:** `/impeccable harden`

## Persona Red Flags

**Cajero de mostrador:** El canvas (“Cobrá el turno”, caja, CTA) está bien. El rail (COMPRA, INFORMES, MANTENIMIENTO) y la ausencia de SIFEN lo sacan del till. Con caja cerrada, VENTA en el menú sigue ahí.

**Alex:** Cero F-keys. Filas no clicables. KPIs que no cobran. Campana y fullscreen de adorno.

**Sam:** Overlay limpio de contraste en el canvas. Siguen: switch de tema sin nombre, campana/fullscreen icon-only, Morris sin texto cuando hay datos, `meta refresh 7200`.

## Cognitive Load

**7/8 fallos = alta.** Pasan: grouping. Fallan: single focus, chunking (11 ítems de nav), hierarchy, one thing at a time, minimal choices, working memory (SIFEN/sucursal), progressive disclosure. El canvas del cajero es más liviano que el del admin; el chrome no.

## Minor Observations

- Nav en MAYÚSCULAS; “Cerrar Sesion” sin tilde; “Apert. - Cierre Caja”.
- Cairo solo en el H1.
- Sidebars con elevation y skins distintas (info vs primary).
- Logo circular sobre un PNG que no es avatar.
- Cajero: “Abrir caja” hero y “Ir a apertura” duplicados si no hay caja.
- Admin: Nueva venta + atajo Venta.
- Dark mode sin tokens para los verdes del home.

## Questions to Consider

- ¿Y si el Inicio del cajero fuera la caja, no un dashboard con un botón para irse a vender?
- Si la factura Paraguay es parte del cobro, ¿cómo puede un Inicio sano no mencionar SIFEN?
- ¿EMPRESA es la vidriera y VENTAPRO+ debe receder, o el disco SOFTSYSTEM sigue siendo lo que se ve?
