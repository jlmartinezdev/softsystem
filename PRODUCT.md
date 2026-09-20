# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Cajero en el mostrador: abre el turno, cobra y cierra caja. Dueño o administrador: configura el local, stock, compras, SIFEN e informes. El cajero opera el día; el dueño controla y deja listo el sistema.

## Product Purpose

VENTAPRO+ es el sistema de punto de venta del local. Tiene que dejar cobrar rápido, abrir y cerrar caja, y emitir factura electrónica de Paraguay (SIFEN). El éxito es un turno que se abre, se cobra y se factura sin salir del mostrador.

## Positioning

No es un POS genérico: opera en Paraguay (guaraníes, sucursal, caja) y emite comprobantes SIFEN / e-KUADE. El ritual del producto es abrir caja, no “iniciar sesión”.

## Operating Context

Mostrador, turno del día, sucursal y caja. Moneda Gs. Voseo en la UI de caja. Flujo típico: elegir cajero → clave → abrir caja → vender → facturar. Roles en código: administrador y vendedor. El nombre del local (tabla `empresa`, hoy “EMPRESA”) identifica el comercio frente al cajero.

## Capabilities and Constraints

Confirmado en el código: venta, compra, stock, artículos, combos, ofertas, cobros, apertura/cierre de caja, clientes, proveedores, informes, facturación y configuración SIFEN. Stack existente: Laravel 5.8, Blade, Vue 2, AdminLTE. La interfaz post-login sigue el shell AdminLTE. No inventar módulos, precios ni clientes.

Abierto: cómo convive el nombre VENTAPRO+ con el logo actual `public/img/logo-softsystem.PNG` y el texto “SOFTSYSTEM 2.0” del sidebar.

## Brand Commitments

Nombre de producto: **VENTAPRO+**. El nombre del local (empresa) se muestra al cajero en la puerta de caja. No usar VENTAPRO+ y SOFTSYSTEM como si fueran el mismo nombre frente al usuario.

## Evidence on Hand

- Logo actual: `public/img/logo-softsystem.PNG`
- Login de abrir caja: `resources/views/auth/login.blade.php`
- Shell: `resources/views/layouts/app.blade.php`, `resources/views/partial/sidebar_administrador.blade.php`, `resources/views/partial/sidebar_vendedor.blade.php`
- SIFEN: `resources/views/sifen/config.blade.php` y servicios en `app/Services/Sifen/`

No hay testimonios, casos ni cifras de clientes. No fabricarlos.

## Product Principles

1. El cajero llega a cobrar con el menor número de pasos.
2. Abrir caja es el ritual; el dueño configura fuera de ese camino.
3. La factura Paraguay (SIFEN) es parte del cobro, no un extra.
4. Una sola marca de producto (VENTAPRO+) y el nombre del local donde el cajero necesita saber en qué comercio está.
5. No inventar prueba comercial ni cambiar hechos de negocio.
