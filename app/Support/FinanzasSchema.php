<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Exception;

class FinanzasSchema
{
    protected static $initialized = false;

    /**
     * Asegura que las tablas, categorías base, permisos y ajustes del módulo de Gastos, Cuentas a Pagar y Resultados existan.
     *
     * @return void
     */
    public static function ensureExists()
    {
        if (self::$initialized) {
            return;
        }

        try {
            ComisionSchema::ensureExists();
            self::createTables();
            self::seedDefaultCategories();
            self::seedPermissionsAndSettings();
            self::$initialized = true;
        } catch (Exception $e) {
            // Silencioso en caso de fallo temprano
        }
    }

    /**
     * Crea las tablas del módulo si no existen.
     */
    public static function createTables()
    {
        // 1. Categorías de Gastos Operativos
        if (!Schema::hasTable('gasto_categorias')) {
            Schema::create('gasto_categorias', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nombre', 100);
                $table->string('tipo', 30)->default('FIJO'); // FIJO, VARIABLE, TALLER, IMPUESTOS, PERSONAL
                $table->string('icono', 60)->default('fas fa-receipt');
                $table->string('color', 25)->default('#0a4d36');
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

        // 2. Registro de Gastos Operativos Fijos y Variables
        if (!Schema::hasTable('gastos_operativos')) {
            Schema::create('gastos_operativos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_gasto', 30)->unique();
                $table->unsignedBigInteger('categoria_id')->index();
                $table->integer('suc_cod')->default(1)->index();
                $table->integer('PROVEEDOR_cod')->nullable()->index();
                $table->dateTime('fecha_gasto')->index();
                $table->string('nro_comprobante', 60)->nullable();
                $table->string('concepto', 255);
                $table->decimal('monto', 14, 2)->default(0);
                $table->integer('iva_incluido')->default(10);
                $table->integer('forma_pago')->default(1); // 1: Efectivo, 2: Transferencia, 3: Tarjeta/Cheque
                $table->boolean('registrar_caja')->default(false);
                $table->integer('nro_operacion_caja')->nullable();
                $table->string('estado', 20)->default('ACTIVO')->index(); // ACTIVO, ANULADO
                $table->text('observaciones')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }

        // 3. Cuentas a Pagar a Proveedores (Sincronizadas con Compras a Crédito o Manuales)
        if (!Schema::hasTable('ctas_pagar')) {
            Schema::create('ctas_pagar', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_cta', 30)->unique();
                $table->integer('compra_cod')->nullable()->index();
                $table->integer('PROVEEDOR_cod')->index();
                $table->integer('suc_cod')->default(1)->index();
                $table->string('nro_factura_proveedor', 60)->nullable();
                $table->string('concepto', 255);
                $table->date('fecha_emision');
                $table->date('fecha_vencimiento')->index();
                $table->integer('nro_cuota')->default(1);
                $table->integer('total_cuotas')->default(1);
                $table->decimal('monto_original', 14, 2)->default(0);
                $table->decimal('monto_pagado', 14, 2)->default(0);
                $table->decimal('monto_saldo', 14, 2)->default(0);
                $table->string('estado', 20)->default('PENDIENTE')->index(); // PENDIENTE, PARCIAL, PAGADO, ANULADO
                $table->text('observaciones')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }

        // 4. Pagos y Abonos realizados a Proveedores
        if (!Schema::hasTable('ctas_pagar_pagos')) {
            Schema::create('ctas_pagar_pagos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('cta_pagar_id')->index();
                $table->integer('PROVEEDOR_cod')->index();
                $table->integer('suc_cod')->default(1);
                $table->dateTime('fecha_pago');
                $table->decimal('monto', 14, 2)->default(0);
                $table->integer('forma_pago')->default(1);
                $table->string('nro_recibo_comprobante', 60)->nullable();
                $table->boolean('registrar_caja')->default(false);
                $table->integer('nro_operacion_caja')->nullable();
                $table->string('observacion', 255)->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Inserta las categorías de gastos operativos estándar si la tabla está vacía.
     */
    public static function seedDefaultCategories()
    {
        if (!Schema::hasTable('gasto_categorias')) {
            return;
        }

        if (DB::table('gasto_categorias')->count() > 0) {
            return;
        }

        $now = now();
        $cats = [
            ['nombre' => 'Alquiler del Local', 'tipo' => 'FIJO', 'icono' => 'fas fa-building', 'color' => '#0a4d36'],
            ['nombre' => 'ANDE / Energía Eléctrica', 'tipo' => 'FIJO', 'icono' => 'fas fa-bolt', 'color' => '#d97706'],
            ['nombre' => 'Agua / ESSAP', 'tipo' => 'FIJO', 'icono' => 'fas fa-tint', 'color' => '#0284c7'],
            ['nombre' => 'Internet y Telefonía', 'tipo' => 'FIJO', 'icono' => 'fas fa-wifi', 'color' => '#4f46e5'],
            ['nombre' => 'Salarios y Jornales Fijos', 'tipo' => 'PERSONAL', 'icono' => 'fas fa-users', 'color' => '#059669'],
            ['nombre' => 'Insumos y Herramientas de Taller', 'tipo' => 'TALLER', 'icono' => 'fas fa-tools', 'color' => '#ea580c'],
            ['nombre' => 'Publicidad y Redes Sociales', 'tipo' => 'VARIABLE', 'icono' => 'fas fa-bullhorn', 'color' => '#db2777'],
            ['nombre' => 'Honorarios Contables e Impuestos', 'tipo' => 'IMPUESTOS', 'icono' => 'fas fa-file-invoice-dollar', 'color' => '#7c3aed'],
            ['nombre' => 'Fletes, Encomiendas y Delivery', 'tipo' => 'VARIABLE', 'icono' => 'fas fa-shipping-fast', 'color' => '#0891b2'],
            ['nombre' => 'Limpieza, Cafetería y Gastos Varios', 'tipo' => 'VARIABLE', 'icono' => 'fas fa-coffee', 'color' => '#64748b'],
        ];

        foreach ($cats as $c) {
            DB::table('gasto_categorias')->insert([
                'nombre'     => $c['nombre'],
                'tipo'       => $c['tipo'],
                'icono'      => $c['icono'],
                'color'      => $c['color'],
                'activo'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Registra el formulario y las acciones de privilegios si no existen.
     */
    public static function seedPermissionsAndSettings()
    {
        if (Schema::hasTable('configuraciones')) {
            $exists = DB::table('configuraciones')->where('name', 'modulo_finanzas_activo')->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert([
                    'name'      => 'modulo_finanzas_activo',
                    'categoria' => 'finanzas',
                    'value'     => '1',
                    'tipo_form' => 'checkbox',
                ]);
            }
        }

        if (Schema::hasTable('formularios')) {
            $form = DB::table('formularios')->where('for_nombre', 'finanzas')->first();
            if (!$form) {
                $forCodigo = DB::table('formularios')->insertGetId([
                    'for_nombre'    => 'finanzas',
                    'for_titulo'    => 'Gastos, Ctas. a Pagar y Resultados',
                    'for_tipo'      => 'M',
                    'for_menu'      => 'Operaciones',
                    'for_submenu'   => 'Gastos y Ctas. a Pagar',
                    'for_categoria' => 'Operaciones',
                    'for_icono'     => 'fas fa-chart-pie',
                    'for_ruta'      => 'finanzas.index',
                    'for_orden'     => 28,
                    'activo'        => 1,
                ]);

                if (Schema::hasTable('roles') && Schema::hasTable('permiso')) {
                    $roles = DB::table('roles')->get();
                    $now = now();
                    foreach ($roles as $r) {
                        $esAdmin = ((int) $r->cod_rol === 4 || in_array(strtolower(trim($r->nom_rol)), ['superadministrador', 'super administrador', 'superadmin'])) ? '1' : '0';
                        DB::table('permiso')->insertOrIgnore([
                            'for_codigo' => $forCodigo,
                            'cod_rol'    => $r->cod_rol,
                            'permiso'    => '1',
                            'per_open'   => '1',
                            'per_add'    => '1',
                            'per_edit'   => $esAdmin,
                            'per_del'    => $esAdmin,
                            'per_export' => '1',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            } else {
                $forCodigo = $form->for_codigo;
            }

            if (Schema::hasTable('accion') && !empty($forCodigo)) {
                $accionesDef = [
                    [
                        'clave_accion'       => 'finanzas_registrar_gasto',
                        'nombre_accion'      => 'Registrar y gestionar Gastos Operativos',
                        'descripcion_accion' => 'Permite cargar gastos fijos y variables del local y descontarlos de caja.',
                        'orden'              => 1,
                    ],
                    [
                        'clave_accion'       => 'finanzas_cuentas_pagar',
                        'nombre_accion'      => 'Gestionar Cuentas a Pagar y pagos a Proveedores',
                        'descripcion_accion' => 'Permite administrar vencimientos de facturas de proveedores y registrar pagos.',
                        'orden'              => 2,
                    ],
                    [
                        'clave_accion'       => 'finanzas_ver_resultados',
                        'nombre_accion'      => 'Visualizar Estado de Resultados (Ganancia Neta Real)',
                        'descripcion_accion' => 'Muestra el dashboard financiero con utilidad bruta, comisiones, gastos y ganancia neta real.',
                        'orden'              => 3,
                    ],
                ];

                foreach ($accionesDef as $acc) {
                    $existAcc = DB::table('accion')->where('clave_accion', $acc['clave_accion'])->first();
                    if (!$existAcc) {
                        $codPer = DB::table('accion')->insertGetId([
                            'for_codigo'         => $forCodigo,
                            'clave_accion'       => $acc['clave_accion'],
                            'nombre_accion'      => $acc['nombre_accion'],
                            'descripcion_accion' => $acc['descripcion_accion'],
                            'orden'              => $acc['orden'],
                            'tipo'               => 'check',
                        ]);

                        if (Schema::hasTable('accion_rol') && Schema::hasTable('roles')) {
                            $roles = DB::table('roles')->get();
                            $now = now();
                            foreach ($roles as $r) {
                                $esAdmin = ((int) $r->cod_rol === 4 || in_array(strtolower(trim($r->nom_rol)), ['superadministrador', 'super administrador', 'superadmin']));
                                $permitido = ($acc['clave_accion'] === 'finanzas_ver_resultados' && !$esAdmin) ? 0 : 1;
                                DB::table('accion_rol')->insertOrIgnore([
                                    'cod_per'    => $codPer,
                                    'cod_rol'    => $r->cod_rol,
                                    'permitido'  => $permitido,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
