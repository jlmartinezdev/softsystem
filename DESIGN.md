---
name: Planilla de arqueo
description: Mundo visual de abrir caja — planilla sellada, no tarjetas AdminLTE.
colors:
  sello: "#b42318"
  tinta: "#1c2430"
  birome: "#31445f"
  grilla: "#c5d4e8"
  papel: "#f3f6f2"
  mesa: "#e7eee6"
  tinta-hover: "#2a3544"
  sello-wash: "#f8ecea"
  sello-selection: "#f3c1bb"
  mesa-dark: "#1a2420"
typography:
  display:
    fontFamily: "Archivo, Segoe UI, sans-serif"
    fontSize: "clamp(2.6rem, 6vw, 4.5rem)"
    fontWeight: 800
    lineHeight: 1
    letterSpacing: "-0.04em"
  headline:
    fontFamily: "Archivo, Segoe UI, sans-serif"
    fontSize: "clamp(1.6rem, 3vw, 2.4rem)"
    fontWeight: 800
    lineHeight: 1.05
    letterSpacing: "-0.03em"
  title:
    fontFamily: "Archivo, Segoe UI, sans-serif"
    fontSize: "1.35rem"
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: "-0.03em"
  body:
    fontFamily: "Archivo, Segoe UI, sans-serif"
    fontSize: "1rem"
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: "normal"
  label:
    fontFamily: "Archivo, Segoe UI, sans-serif"
    fontSize: "0.82rem"
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: "0.04em"
rounded:
  none: "0"
spacing:
  xs: "0.35rem"
  sm: "0.75rem"
  md: "1rem"
  lg: "1.25rem"
  xl: "1.75rem"
  stack: "2.75rem"
components:
  button-sello:
    backgroundColor: "transparent"
    textColor: "{colors.sello}"
    rounded: "{rounded.none}"
    padding: "0"
    width: "9.5rem"
    height: "9.5rem"
  button-filtrar:
    backgroundColor: "{colors.tinta}"
    textColor: "{colors.papel}"
    rounded: "{rounded.none}"
    padding: "0 1rem"
    height: "2.75rem"
  button-filtrar-hover:
    backgroundColor: "{colors.tinta-hover}"
    textColor: "{colors.papel}"
    rounded: "{rounded.none}"
    padding: "0 1rem"
    height: "2.75rem"
  input-casilla:
    backgroundColor: "{colors.papel}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "0.45rem 0.7rem"
    height: "2.75rem"
  input-monto:
    backgroundColor: "transparent"
    textColor: "{colors.tinta}"
    typography: "{typography.display}"
    rounded: "{rounded.none}"
    padding: "0 0 0.35rem"
  link-accion:
    backgroundColor: "transparent"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "0"
  surface-hoja:
    backgroundColor: "{colors.papel}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "1.75rem 1.75rem 1.5rem"
  mark-ahora:
    backgroundColor: "transparent"
    textColor: "{colors.sello}"
    rounded: "{rounded.none}"
    padding: "0"
---

# Design System: Planilla de arqueo

## Overview

**Creative North Star: "La planilla sellada"**

Este sistema describe el mundo visual de **abrir caja** en VENTAPRO+: una hoja de arqueo sobre la mesa del mostrador, no un formulario en tarjetas. El cajero ve el nombre del local y la fecha, escribe el fondo en birome, elige sucursal y caja, y apoya un sello de goma. Si ya hay turno abierto, el margen muestra ABIERTA y el sello no dispara. El historial es otra hoja — la pila — debajo de la planilla del día.

El shell AdminLTE (sidebar, topbar) es chrome incumbente fuera de este mundo; no se interpreta como parte del rediseño. Las superficies que adopten este sistema heredan papel, tinta, grilla y sello; no inventan pastillas ni paneles elevados al estilo dashboard.

**Key Characteristics:**
- Papel de planilla `#f3f6f2` sobre mesa `#e7eee6`, reglas `#c5d4e8`, tinta carbón `#1c2430`, acento sello `#b42318`
- Tipografía Archivo self-hosted (400 / 600 / 800) con números tabulares
- Esquinas a 0; profundidad por sombra única de hoja, no por cards apiladas
- Acción primaria = sello raster en el margen (rotar −8°); monto = único número hero
- Sin tarjetas, sin pastillas, sin segundo diálogo de confirmación al sellar

## Colors

Paleta de escritorio de caja: papel frío de planilla, tinta de impresión, birome en campos, sello rojo de goma, grilla azulada de reglas.

### Primary
- **Sello de goma** (`sello`): tinta del sello Abrir / ABIERTA, avisos, marca «Ahora», focus-visible, hover de enlaces de acción, confirmación SweetAlert. Rareza intencional: es la única voz roja.

### Neutral
- **Tinta carbón** (`tinta`): texto principal, botón Filtrar, filas activas de paginación.
- **Birome** (`birome`): monto vacío / Gs., fecha, labels, texto secundario, vacío de tabla.
- **Grilla impresa** (`grilla`): bordes de hoja, casillas, reglas de cabeza y filas.
- **Papel de planilla** (`papel`): fondo de `.hoja` y `.pila`, texto sobre tinta en botones.
- **Mesa** (`mesa`): fondo del content-wrapper detrás de la planilla; en dark-mode del shell pasa a `mesa-dark`.
- **Tinta al pasar** (`tinta-hover`): hover del botón Filtrar.
- **Lavado en curso** (`sello-wash`): fondo de fila abierta (`tr.en-curso`).
- **Selección sello** (`sello-selection`): `::selection` dentro de `.planilla`.

### Named Rules
**The One Stamp Rule.** El rojo sello aparece solo en sello, avisos, «Ahora», focus y hovers de acción. No teñir fondos enteros ni botones secundarios de rojo.

**The No-Card Rule.** No hay paneles con radio, padding de card ni pares formulario/estado. Una hoja + margen; debajo, la pila.

## Typography

**Display Font:** Archivo (self-hosted `public/webfonts/Archivo-400/600/800.woff2`, OFL en `public/webfonts/OFL-Archivo.txt`; fallback Segoe UI, sans-serif)
**Body Font:** Archivo (misma familia)
**Label/Mono Font:** Archivo con `font-variant-numeric: tabular-nums`

**Character:** Tipografía de planilla industrial: densa, tabular, sin serifas de display. El monto vive a otra escala que el resto de la grilla.

### Hierarchy
- **Display** (600 vacío → 800 entintado, `clamp(2.6rem, 6vw, 4.5rem)`, lh 1): monto de apertura; único número hero.
- **Headline** (800, `clamp(1.6rem, 3vw, 2.4rem)`, lh 1.05): nombre del local en la cabeza de la hoja.
- **Title** (800, 1.35rem): «Historial de aperturas»; Gs. del monto (1.35rem / 800).
- **Body** (600, 0.95–1rem, max ~65ch en `.quien` / `.aviso`): quién abrió, avisos, celdas.
- **Label** (600–700, 0.75–0.82rem, letter-spacing 0.04–0.05em, uppercase): casilla-label, thead, marca «Ahora» (0.72rem / 800 / 0.08em).

### Named Rules
**The Inked Amount Rule.** El monto empieza en birome; al tener valor válido pasa a tinta 800 (`.entintado`). No hay otro número a escala display.

## Layout

Contenido: `.planilla` con padding `1.25rem 0 2.5rem`. La hoja es un grid `minmax(0,1fr) 12.5rem` (margen derecho del sello); cuerpo `1.75rem` de padding. Sucursal y caja: dos casillas `1fr 1fr` con gap `1rem 1.25rem`. La pila separa con `margin-top: 2.75rem`. Filtros: grid `1.4fr 0.8fr 0.9fr 0.9fr auto`. Bajo `860px` la hoja apila (margen abajo con borde superior), filtros a dos columnas, tabla a bloques.

## Elevation & Depth

Una sola sombra estructural en hoja y pila: `0 14px 32px rgba(28, 36, 48, 0.12)`. No hay elevación por hover de cards. El sello «apoya» con translateY, no con sombra extra.

### Shadow Vocabulary
- **Hoja sobre mesa** (`box-shadow: 0 14px 32px rgba(28, 36, 48, 0.12)`): `.hoja` y `.pila` únicamente.

### Named Rules
**The Paper-Not-Card Rule.** La sombra es de papel sobre mesa. No apilar sombras ni usar offset duros de neobrutalismo.

## Shapes

Todo es esquina viva: `border-radius: 0` en casillas, selects, fechas, Filtrar, paginación. Bordes 1px `grilla`. El sello es un cuadrado de imagen 9.5rem rotado −8°, no un botón píldora.

## Components

### Buttons
- **Shape:** radio 0; sello = imagen 9.5×9.5rem sin borde.
- **Primary (sello Abrir):** raster `public/img/sello-abrir.png`; disabled hasta monto válido; hover/focus `rotate(-6deg) translateY(2px)`; active/apoyado `rotate(-3deg) translateY(8px)` en 180ms `cubic-bezier(0.16, 1, 0.3, 1)`; envía el form sin segundo diálogo. Estado ABIERTA: `sello-abierta.png`, grayscale deshabilitado no aplica — cursor default, sin gesto.
- **Secondary (Filtrar):** fondo tinta, texto papel, min-height 2.75rem; hover `tinta-hover`.
- **Ghost / links:** `.accion` / `.limpiar` — underline offset 0.18em; hover a sello.

### Inputs / Fields
- **Style:** casilla papel, borde grilla, radio 0, peso 600, min-height 2.75rem.
- **Monto:** borde inferior de regla; transparente; placeholder birome; `.entintado` → tinta 800.
- **Focus:** outline 2px sello, offset 2px (`:focus-visible`).
- **Disabled:** opacity 1 (sigue legible como dato impreso).

### Tables / Pila
- Filas con `border-top` grilla; thead label uppercase birome.
- Fila abierta: fondo `sello-wash` + marca `.ahora` en sello.
- Paginación: borde grilla; activo = tinta relleno / texto papel.

### Signature: Margen del sello
Columna derecha (o franja inferior en móvil) con borde grilla; única acción primaria del ritual. Enlaces «Cerrar caja» / «Ver movimientos» solo cuando hay ABIERTA.

### Navigation
Fuera de alcance: sidebar/topbar AdminLTE permanecen como chrome del producto; no reestilizarlas con tokens de planilla.

## Do's and Don'ts

### Do:
- **Do** usar Archivo self-hosted y `tabular-nums` en montos y tablas.
- **Do** tratar el sello raster como CTA primario; el monto como único display.
- **Do** mantener radio 0 y reglas `grilla` 1px.
- **Do** entintar el monto y habilitar el sello en el mismo gesto de escritura.
- **Do** marcar la apertura en curso con lavado `sello-wash` + «Ahora».

### Don't:
- **Don't** introducir tarjetas, pastillas, chips ni botones redondeados en superficies de este mundo.
- **Don't** poner un segundo diálogo de confirmación al apoyar el sello Abrir.
- **Don't** usar el rojo sello como fondo de secciones enteras.
- **Don't** pretender que el rediseño de apertura reescribe el shell AdminLTE.
- **Don't** sustituir Archivo por Inter, Roboto, Arial o system-ui como display.
