<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EnhancePermisosAndFormulariosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Mejorar tabla `formularios`
        Schema::table('formularios', function (Blueprint $table) {
            if (!Schema::hasColumn('formularios', 'for_categoria')) {
                $table->string('for_categoria', 60)->nullable()->default('OPERACIONES')->after('for_submenu');
            }
            if (!Schema::hasColumn('formularios', 'for_icono')) {
                $table->string('for_icono', 60)->nullable()->default('fas fa-window-maximize')->after('for_categoria');
            }
            if (!Schema::hasColumn('formularios', 'for_ruta')) {
                $table->string('for_ruta', 100)->nullable()->after('for_icono');
            }
            if (!Schema::hasColumn('formularios', 'for_orden')) {
                $table->integer('for_orden')->default(0)->after('for_ruta');
            }
            if (!Schema::hasColumn('formularios', 'activo')) {
                $table->tinyInteger('activo')->default(1)->after('for_orden');
            }
        });

        // 2. Mejorar tabla `permiso`
        Schema::table('permiso', function (Blueprint $table) {
            if (!Schema::hasColumn('permiso', 'per_export')) {
                $table->char('per_export', 1)->default('0')->after('per_del')->comment('1: Permite exportar/imprimir reportes');
            }
            if (!Schema::hasColumn('permiso', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('permiso', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        // 3. Mejorar tabla `accion`
        Schema::table('accion', function (Blueprint $table) {
            if (!Schema::hasColumn('accion', 'clave_accion')) {
                $table->string('clave_accion', 60)->nullable()->after('for_codigo')->index();
            }
        });

        // 4. Crear tabla `accion_rol` para vincular acciones especiales con roles
        if (!Schema::hasTable('accion_rol')) {
            Schema::create('accion_rol', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('cod_per');
                $table->unsignedInteger('cod_rol');
                $table->tinyInteger('permitido')->default(1);
                $table->timestamps();

                $table->unique(['cod_per', 'cod_rol'], 'accion_rol_unique');
                $table->foreign('cod_per')->references('cod_per')->on('accion')->onDelete('cascade');
                $table->foreign('cod_rol')->references('cod_rol')->on('roles')->onDelete('cascade');
            });
        }

        // 5. Mejorar tabla `roles`
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'descripcion_rol')) {
                $table->string('descripcion_rol', 150)->nullable()->after('nom_rol');
            }
            if (!Schema::hasColumn('roles', 'color_rol')) {
                $table->string('color_rol', 25)->nullable()->default('#0a4d36')->after('descripcion_rol');
            }
            if (!Schema::hasColumn('roles', 'activo')) {
                $table->tinyInteger('activo')->default(1)->after('color_rol');
            }
        });

        // 6. Actualizar y completar catálogos de formularios
        $modulos = [
            // OPERACIONES
            ['nombre' => 'ventas', 'titulo' => 'Punto de Venta', 'cat' => 'OPERACIONES', 'icono' => 'fas fa-shopping-cart', 'ruta' => 'venta', 'orden' => 10],
            ['nombre' => 'presupuesto', 'titulo' => 'Presupuestos de Venta', 'cat' => 'OPERACIONES', 'icono' => 'fas fa-file-invoice-dollar', 'ruta' => 'presupuesto.index', 'orden' => 20],
            ['nombre' => 'compra', 'titulo' => 'Gestión de Compras', 'cat' => 'OPERACIONES', 'icono' => 'fas fa-truck-loading', 'ruta' => 'compra', 'orden' => 30],

            // CAJA Y COBROS
            ['nombre' => 'apert_cierres_caja', 'titulo' => 'Apertura y Cierre de Caja', 'cat' => 'CAJA Y COBROS', 'icono' => 'fas fa-door-open', 'ruta' => 'apertura', 'orden' => 40],
            ['nombre' => 'movimiento_caja', 'titulo' => 'Movimientos de Caja y Arqueo', 'cat' => 'CAJA Y COBROS', 'icono' => 'fas fa-cash-register', 'ruta' => 'movimiento', 'orden' => 50],
            ['nombre' => 'cobranzas', 'titulo' => 'Cobranzas y Recibos', 'cat' => 'CAJA Y COBROS', 'icono' => 'fas fa-hand-holding-usd', 'ruta' => 'cobro', 'orden' => 60],
            ['nombre' => 'ctas_cobrar', 'titulo' => 'Cuentas a Cobrar', 'cat' => 'CAJA Y COBROS', 'icono' => 'fas fa-file-invoice', 'ruta' => 'infctacobrar', 'orden' => 70],

            // CATALOGO
            ['nombre' => 'articulos', 'titulo' => 'Artículos y Precios', 'cat' => 'CATALOGO', 'icono' => 'fas fa-boxes', 'ruta' => 'articulo', 'orden' => 80],
            ['nombre' => 'combos', 'titulo' => 'Combos y Paquetes', 'cat' => 'CATALOGO', 'icono' => 'fas fa-layer-group', 'ruta' => 'combo.index', 'orden' => 90],
            ['nombre' => 'ofertas', 'titulo' => 'Ofertas y Promociones', 'cat' => 'CATALOGO', 'icono' => 'fas fa-tags', 'ruta' => 'oferta.index', 'orden' => 100],
            ['nombre' => 'clientes', 'titulo' => 'Clientes', 'cat' => 'CATALOGO', 'icono' => 'fas fa-users', 'ruta' => 'cliente.index', 'orden' => 110],
            ['nombre' => 'proveedor', 'titulo' => 'Proveedores', 'cat' => 'CATALOGO', 'icono' => 'fas fa-industry', 'ruta' => 'proveedor.index', 'orden' => 120],

            // INVENTARIO Y STOCK
            ['nombre' => 'inventario', 'titulo' => 'Inventario y Existencias', 'cat' => 'INVENTARIO', 'icono' => 'fas fa-warehouse', 'ruta' => 'infstock', 'orden' => 130],
            ['nombre' => 'ajuste', 'titulo' => 'Ajustes Manuales de Stock', 'cat' => 'INVENTARIO', 'icono' => 'fas fa-sliders-h', 'ruta' => 'articulo.cm', 'orden' => 140],

            // INFORMES
            ['nombre' => 'inf_venta', 'titulo' => 'Informe de Ventas', 'cat' => 'INFORMES', 'icono' => 'fas fa-chart-line', 'ruta' => 'infventa', 'orden' => 150],
            ['nombre' => 'inf_compra', 'titulo' => 'Informe de Compras', 'cat' => 'INFORMES', 'icono' => 'fas fa-chart-bar', 'ruta' => 'infcompra', 'orden' => 160],
            ['nombre' => 'inf_cobros', 'titulo' => 'Informe de Cobros', 'cat' => 'INFORMES', 'icono' => 'fas fa-receipt', 'ruta' => 'infcobro', 'orden' => 170],
            ['nombre' => 'inf_stock', 'titulo' => 'Informe de Stock Valorizado', 'cat' => 'INFORMES', 'icono' => 'fas fa-clipboard-list', 'ruta' => 'infstock', 'orden' => 180],
            ['nombre' => 'inf_resumen', 'titulo' => 'Resumen Ejecutivo y Cierre', 'cat' => 'INFORMES', 'icono' => 'fas fa-chart-pie', 'ruta' => 'resumen', 'orden' => 190],

            // AUDITORIA Y ANULACIONES
            ['nombre' => 'anular_venta', 'titulo' => 'Anular Ventas', 'cat' => 'AUDITORIA', 'icono' => 'fas fa-ban', 'ruta' => 'anularventa', 'orden' => 200],
            ['nombre' => 'anular_compra', 'titulo' => 'Anular Compras', 'cat' => 'AUDITORIA', 'icono' => 'fas fa-undo-alt', 'ruta' => 'anularcompra', 'orden' => 210],
            ['nombre' => 'anular_cobros', 'titulo' => 'Anular Cobros', 'cat' => 'AUDITORIA', 'icono' => 'fas fa-times-circle', 'ruta' => 'anularcobro', 'orden' => 220],

            // CONFIGURACION Y SISTEMA
            ['nombre' => 'sifen', 'titulo' => 'Facturación Electrónica SIFEN', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-qrcode', 'ruta' => 'sifen.index', 'orden' => 230],
            ['nombre' => 'presentacion', 'titulo' => 'Secciones y Categorías', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-th-list', 'ruta' => 'seccion.index', 'orden' => 240],
            ['nombre' => 'unidad', 'titulo' => 'Unidades de Medida', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-balance-scale', 'ruta' => 'unidades.index', 'orden' => 250],
            ['nombre' => 'reffactura', 'titulo' => 'Facturas y Talonarios', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-file-invoice', 'ruta' => 'reffactura.index', 'orden' => 260],
            ['nombre' => 'empresa', 'titulo' => 'Datos de la Empresa', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-building', 'ruta' => 'empresa.index', 'orden' => 270],
            ['nombre' => 'ciudad', 'titulo' => 'Ciudades y Localidades', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-map-marker-alt', 'ruta' => 'ciudad.index', 'orden' => 280],
            ['nombre' => 'usuarios', 'titulo' => 'Usuarios del Sistema', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-user-cog', 'ruta' => 'usuario', 'orden' => 290],
            ['nombre' => 'permisos', 'titulo' => 'Privilegios y Permisos', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-user-shield', 'ruta' => 'permiso.index', 'orden' => 300],
            ['nombre' => 'ajuste_sistema', 'titulo' => 'Ajustes y Parámetros', 'cat' => 'CONFIGURACION', 'icono' => 'fas fa-cogs', 'ruta' => 'ajuste.index', 'orden' => 310],
        ];

        foreach ($modulos as $mod) {
            $exist = DB::table('formularios')->where('for_nombre', $mod['nombre'])->first();
            if ($exist) {
                DB::table('formularios')->where('for_codigo', $exist->for_codigo)->update([
                    'for_titulo' => $mod['titulo'],
                    'for_categoria' => $mod['cat'],
                    'for_icono' => $mod['icono'],
                    'for_ruta' => $mod['ruta'],
                    'for_orden' => $mod['orden'],
                    'activo' => 1
                ]);
            } else {
                DB::table('formularios')->insert([
                    'for_nombre' => $mod['nombre'],
                    'for_titulo' => $mod['titulo'],
                    'for_tipo' => $mod['cat'],
                    'for_menu' => $mod['cat'],
                    'for_submenu' => null,
                    'for_categoria' => $mod['cat'],
                    'for_icono' => $mod['icono'],
                    'for_ruta' => $mod['ruta'],
                    'for_orden' => $mod['orden'],
                    'activo' => 1
                ]);
            }
        }

        // Marcar categorías para formularios restantes
        DB::table('formularios')->whereNull('for_categoria')->update(['for_categoria' => 'OTROS']);

        // 7. Poblar acciones especiales en `accion`
        $ventaForm = DB::table('formularios')->where('for_nombre', 'ventas')->first();
        $cajaForm = DB::table('formularios')->where('for_nombre', 'apert_cierres_caja')->first();
        $artForm = DB::table('formularios')->where('for_nombre', 'articulos')->first();
        $compForm = DB::table('formularios')->where('for_nombre', 'compra')->first();
        $cobForm = DB::table('formularios')->where('for_nombre', 'cobranzas')->first();

        $accionesEspeciales = [
            [
                'for_codigo' => $ventaForm ? $ventaForm->for_codigo : 46,
                'clave_accion' => 'venta_modificar_precio',
                'nombre_accion' => 'Modificar Precios en Venta',
                'descripcion_accion' => 'Permite cambiar el precio unitario del artículo en la pantalla de cobranza/ticket.',
                'orden' => 1,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $ventaForm ? $ventaForm->for_codigo : 46,
                'clave_accion' => 'venta_descuento',
                'nombre_accion' => 'Aplicar Descuentos',
                'descripcion_accion' => 'Permite otorgar descuentos generales o por ítem en el punto de venta.',
                'orden' => 2,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $ventaForm ? $ventaForm->for_codigo : 46,
                'clave_accion' => 'venta_sin_stock',
                'nombre_accion' => 'Vender Sin Stock',
                'descripcion_accion' => 'Permite emitir comprobantes de artículos cuya existencia actual sea cero o negativa.',
                'orden' => 3,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $ventaForm ? $ventaForm->for_codigo : 46,
                'clave_accion' => 'venta_autorizar_credito',
                'nombre_accion' => 'Autorizar Venta a Crédito',
                'descripcion_accion' => 'Permite seleccionar condición crédito y generar cuotas para el cliente.',
                'orden' => 4,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $cajaForm ? $cajaForm->for_codigo : 4,
                'clave_accion' => 'caja_reabrir_turno',
                'nombre_accion' => 'Reabrir Turnos Cerrados',
                'descripcion_accion' => 'Permite revertir o reabrir una caja que ya cuenta con cierre asentado.',
                'orden' => 5,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $cajaForm ? $cajaForm->for_codigo : 4,
                'clave_accion' => 'caja_movimientos_manuales',
                'nombre_accion' => 'Movimientos Manuales de Caja',
                'descripcion_accion' => 'Permite ingresar o retirar dinero de caja chica por conceptos varios.',
                'orden' => 6,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $artForm ? $artForm->for_codigo : 5,
                'clave_accion' => 'articulo_ver_costo',
                'nombre_accion' => 'Ver Costos de Compra',
                'descripcion_accion' => 'Muestra el precio y costo de compra en catálogo y listados.',
                'orden' => 7,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $compForm ? $compForm->for_codigo : 13,
                'clave_accion' => 'compra_modificar_costo',
                'nombre_accion' => 'Actualizar Precios desde Compra',
                'descripcion_accion' => 'Permite actualizar automáticamente el costo de referencia y venta al asentar factura de compra.',
                'orden' => 8,
                'tipo' => 'S'
            ],
            [
                'for_codigo' => $cobForm ? $cobForm->for_codigo : 12,
                'clave_accion' => 'cobro_descuento_cuota',
                'nombre_accion' => 'Condonar Interés / Descuento Cuota',
                'descripcion_accion' => 'Permite aplicar reducciones sobre cuotas vencidas o intereses acumulados.',
                'orden' => 9,
                'tipo' => 'S'
            ],
        ];

        foreach ($accionesEspeciales as $acc) {
            $existAcc = DB::table('accion')->where('clave_accion', $acc['clave_accion'])->first();
            if (!$existAcc) {
                $idAcc = DB::table('accion')->insertGetId($acc);
                // Por defecto habilitar a rol Administrador (cod_rol = 4)
                DB::table('accion_rol')->insertOrIgnore([
                    'cod_per' => $idAcc,
                    'cod_rol' => 4,
                    'permitido' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accion_rol');

        Schema::table('permiso', function (Blueprint $table) {
            if (Schema::hasColumn('permiso', 'per_export')) {
                $table->dropColumn('per_export');
            }
            if (Schema::hasColumn('permiso', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('permiso', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });

        Schema::table('formularios', function (Blueprint $table) {
            if (Schema::hasColumn('formularios', 'for_categoria')) {
                $table->dropColumn('for_categoria');
            }
            if (Schema::hasColumn('formularios', 'for_icono')) {
                $table->dropColumn('for_icono');
            }
            if (Schema::hasColumn('formularios', 'for_ruta')) {
                $table->dropColumn('for_ruta');
            }
            if (Schema::hasColumn('formularios', 'for_orden')) {
                $table->dropColumn('for_orden');
            }
            if (Schema::hasColumn('formularios', 'activo')) {
                $table->dropColumn('activo');
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'descripcion_rol')) {
                $table->dropColumn('descripcion_rol');
            }
            if (Schema::hasColumn('roles', 'color_rol')) {
                $table->dropColumn('color_rol');
            }
            if (Schema::hasColumn('roles', 'activo')) {
                $table->dropColumn('activo');
            }
        });
    }
}
