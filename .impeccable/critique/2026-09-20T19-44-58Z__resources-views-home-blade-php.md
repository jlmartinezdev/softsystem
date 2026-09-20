---
target: Browser / Inicio
total_score: 16
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
p2_count: 2
target_identity: "file:C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
target_fingerprint: "sha256:40ce45b822e20a81130bc92fb0f3acedda14b3c4407b328be9e1e4d8ddb9041b"
target_path: "C:\\laragon\\www\\softsystem\\resources\\views\\home.blade.php"
timestamp: 2026-09-20T19-44-58Z
slug: resources-views-home-blade-php
closed: true
---
# Critique — Inicio autenticado (AdminLTE)

**Target:** `resources/views/home.blade.php` · `http://softsystem.test/`
**Mode:** Operate (panel post-login; el producto pide llegar a cobrar)

## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 3 | Caja en curso (Abierta #4, sucursal, usuario, apertura) es el mejor estado de la pantalla; el resto son KPIs en cero sin contexto de turno |
| 2 | Match System / Real World | 1 | El cajero abre caja y aterriza en un dashboard de dueño; chrome SOFTSYSTEM 2.0 vs VENTAPRO+ / EMPRESA |
| 3 | User Control and Freedom | 2 | Cerrar / Movimientos existen; la salida al trabajo real (Nueva venta) es un `btn-sm` en la esquina |
| 4 | Consistency and Standards | 1 | Tres marcas a la vez; Ventas/Cobros duplicados en KPI y small-boxes; VENTA con ícono de engranaje |
| 5 | Error Prevention | 1 | Acceso Caja sigue yendo a apertura con caja ya abierta; gráfico vacío parece rotura |
| 6 | Recognition Rather Than Recall | 2 | Nav con labels; INICIO está casi al fondo; MANTENIMIENTO primero |
| 7 | Flexibility and Efficiency | 1 | Tras abrir caja el cajero no llega a cobrar; no hay atajo de mostrador |
| 8 | Aesthetic and Minimalist Design | 1 | KPI + small-boxes + 6 tiles + gráfico vacío + tabla: todo con el mismo peso visual |
| 9 | Error Recovery | 2 | Accesos rápidos y Ver informe existen; el vacío de 7 días no ofrece siguiente paso |
| 10 | Help and Documentation | 2 | Sin ayuda contextual; los tiles no explican el flujo del turno |
| **Total** | | **16/40** | **Poor** |

## Design Specificity Verdict

**LLM assessment:** Categoría-intercambiable. Es un dashboard AdminLTE de inventario/ERP: small-boxes de colores, gráfico Chart.js, tabla, tiles. Cualquier back-office Laravel podría usar esta composición sin cambiar una línea de negocio. PRODUCT.md pide VENTAPRO+ (POS Paraguay, ritual abrir caja → vender → facturar). Esta superficie contradice el principio 1 (el cajero llega a cobrar) y el 4 (una sola marca). El único fragmento autorizado para este producto es la tarjeta **Caja en curso**.

**Deterministic scan (CLI):** `impeccable detect --json resources/views/home.blade.php` → exit 0, `[]`. El Blade no es el DOM; el contraste vive en AdminLTE compilado.

**Visual overlays:** Inyección de `detect.js` en `http://softsystem.test/` (tab [Human]) con overlay visible. El live-server se detuvo al cerrar la corrida. Hallazgos en página (95 elementos): **15 low-contrast**, **67 layout-transition**, **6 tight-leading**, **5 text-occlusion**, **2 border-accent-on-rounded**, **1 line-length**, **1 clipped-overflow**, **1 dark-glow**.

Falsos positivos: `tight-leading` en tags `<style>` ocultos; `layout-transition` masivo del sidebar AdminLTE (`transition: margin-left`); `dark-glow` y `clipped-overflow` del OverlayScrollbars. Señal real: contraste en INICIO (blanco sobre `#17a2b8`, 3.0:1), **Nueva venta** (blanco sobre `#007bff`, 4.0:1), small-boxes teal (2.1:1), p de lightblue/success, badges Contado (3.1:1), saludo muted (4.3:1), línea del greeting demasiado larga.

## Overall Impression

Abrir caja funciona; aterrizar en Inicio no. El cajero ve un panel de dueño con números en cero, un gráfico plano y seis atajos, mientras **Nueva venta** es un botón chico. La oportunidad única: que Inicio sea el mostrador del turno (caja + cobrar), no un admin template.

## What's Working

1. **Caja en curso** — Operación #4, CASA CENTRAL · CAJA PRINCIPAL, usuario, apertura y Gs. 150.000, con Cerrar / Movimientos. Es el único objeto que habla el idioma del local.
2. **Últimas ventas** — Fecha, cliente, tipo, sucursal y total en Gs. anclan el día; Crédito/Contado son del dominio paraguayo.
3. **Estado Abierta** — El badge comunica que el turno está vivo; no hay que adivinar si hay caja.

## Priority Issues

### [P1] Aterrizaje de dueño: Nueva venta es `btn-sm`

- **What:** Tras abrir caja, el landing es “Panel de inicio” con Artículos/Clientes y un gráfico de 7 días en cero. La acción de cobrar es un botón primario pequeño arriba a la derecha.
- **Why it matters:** El cajero no cobra; recorre un dashboard. Viola el principio 1 de PRODUCT.md.
- **Fix:** Para rol vendedor, Inicio = mostrador (caja + Nueva venta a tamaño de trabajo). El panel de KPIs es del administrador, no el default del turno.
- **Suggested command:** `/impeccable shape` (landing por rol) → `/impeccable distill`

### [P1] Tres nombres: SOFTSYSTEM 2.0, EMPRESA, VENTAPRO+

- **What:** Sidebar “SOFTSYSTEM 2.0”, H1 “EMPRESA”, producto VENTAPRO+. Title `SOFTSYSTEM — Inicio`.
- **Why it matters:** El cajero no sabe en qué comercio está ni qué sistema opera. El login ya eligió marca de producto + local; el shell la deshace.
- **Fix:** Chrome = VENTAPRO+; H1 = nombre de `empresa`. Retirar SOFTSYSTEM del sidebar y del title.
- **Suggested command:** `/impeccable clarify`

### [P1] KPIs duplicados + 6 tiles + gráfico vacío

- **What:** Ventas/Cobros Setiembre aparecen en la fila de info-boxes y otra vez en small-boxes. Abajo, seis Accesos rápidos (Venta, Cobrar, Artículos, Clientes, Compra, Caja). El gráfico 14/09–20/09 está plano.
- **Why it matters:** Carga cognitiva alta (6/8 fallos). Nada es primario.
- **Fix:** Una fila de estado del día. Quitar duplicados. Mutear o sustituir el gráfico vacío. ≤4 atajos, Venta primero.
- **Suggested command:** `/impeccable distill`

### [P2] IA del sidebar: MANTENIMIENTO primero, INICIO al fondo, VENTA con engranaje

- **What:** El menú del administrador abre con MANTENIMIENTO. VENTA usa `fa-cog`. INICIO está entre CASA CENTRAL y AJUSTES.
- **Why it matters:** El orden no es el del turno (vender → caja → luego configurar).
- **Fix:** Orden de trabajo: Inicio / Venta / Caja / Cobros, mantenimiento al final. Ícono de venta = carrito o ticket.
- **Suggested command:** `/impeccable layout`

### [P2] Contraste AA roto en CTAs, tiles y badges (detector)

- **What:** Overlay: Nueva venta 4.0:1; Artículos teal 2.1:1; INICIO activo 3.0:1; badges Contado 3.1:1; Abierta sobre fondo de tarjeta.
- **Why it matters:** El único CTA de cobro y el estado de caja fallan WCAG AA.
- **Fix:** Texto oscuro sobre teal/amarillo; primary más oscuro o texto que llegue a 4.5:1; badges con contraste.
- **Suggested command:** `/impeccable audit`

## Persona Red Flags

**Cajero de mostrador (producto):** Abre caja y espera cobrar. Encuentra Hola PROPIETARIO · Panel de inicio, Artículos 8, gráfico vacío. Nueva venta es fácil de no ver. Riesgo: vuelve al login o llama al dueño.

**Alex (Power User):** 10+ ítems de nav, 6 tiles, 2 filas de métricas. Cero atajos de teclado hacia venta. El camino más corto sigue siendo cazar el `btn-sm`. Abandono por fricción, no por falta de features.

**Sam (Accesibilidad):** 15 textos bajo 4.5:1, incluido el CTA. Badges Crédito/Contado son color + palabra corta; el color no alcanza AA. Small-boxes “Abrir” casi invisibles sobre el color saturado.

**Jordan (First-Timer):** MANTENIMIENTO como primera palabra. No hay copy de “tu caja está abierta, cobrá”. El gráfico en cero se lee como error.

## Cognitive Load

**6/8 fallos = alta (crítica).** Fallan: single focus, chunking, visual hierarchy, one thing at a time, minimal choices, progressive disclosure. Pasan: grouping (cards), working memory (datos en pantalla). Decisiones visibles: ~10 nav + 6 tiles + 2 CTAs de caja + Nueva venta = sobrecarga.

## Minor Observations

- Title y logo del sidebar siguen en SOFTSYSTEM; el login ya no.
- VENTA `fa-cog` parece Ajustes.
- Tile Caja vs tarjeta Caja en curso: dos verdades.
- `info-box` Por cobrar Gs. 2.625.000 es el único número del negocio; compite con ceros.
- Line-length del saludo en una sola línea de contenido.
- `border-accent-on-rounded` en gráfico y caja (borde izquierdo sobre radius): detalle de AdminLTE, no de POS.

## Questions to Consider

- ¿Y si Inicio del vendedor fuera solo caja + cobrar, y este dashboard existiera solo para administrador?
- ¿Qué pasaría si Nueva venta ocupara el lugar de los small-boxes?
- ¿El gráfico de 7 días sirve el día 0, o es ruido hasta que hay movimiento?
