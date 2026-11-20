<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Exception;

class ComisionSchema
{
    protected static $initialized = false;

    /**
     * Asegura que las tablas, permisos y ajustes del módulo de Comisiones de Técnicos y Vendedores existan.
     *
     * @return void
     */
    public static function ensureExists()
    {
        if (self::$initialized) {
            return;
        }

        try {
            CelularImeiSchema::ensureExists();
            self::createTables();
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
        // 1. Configuración de tasas y reglas de comisión por cada Técnico / Vendedor
        if (!Schema::hasTable('comision_config_usuarios')) {
            Schema::create('comision_config_usuarios', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('cod_usuarios')->unique();
                $table->boolean('activo_comision')->default(true);

                // Servicio Técnico (Taller)
                $table->boolean('comisiona_taller')->default(true);
                $table->decimal('porc_taller', 6, 2)->default(40.00);
                $table->string('base_taller', 30)->default('MANO_OBRA'); // MANO_OBRA, UTILIDAD_NETA, TOTAL_ORDEN
                $table->boolean('descontar_garantias_taller')->default(true);

                // Ventas Generales en Punto de Venta (POS)
                $table->boolean('comisiona_ventas')->default(true);
                $table->decimal('porc_venta_contado', 6, 2)->default(3.00);
                $table->decimal('porc_venta_credito', 6, 2)->default(2.00);

                // Venta de Celulares por IMEI / Plan Canje
                $table->boolean('comisiona_imei')->default(true);
                $table->decimal('porc_imei', 6, 2)->default(5.00);
                $table->string('base_imei', 30)->default('UTILIDAD_NETA'); // UTILIDAD_NETA, PRECIO_VENTA
                $table->decimal('bono_fijo_imei', 14, 2)->default(0);

                // Cobranzas de Cuotas Recuperadas
                $table->boolean('comisiona_cobranzas')->default(true);
                $table->decimal('porc_cobranzas', 6, 2)->default(2.00);

                $table->decimal('sueldo_base_referencia', 14, 2)->default(0);
                $table->string('observaciones', 255)->nullable();
                $table->timestamps();
            });
        }

        // 2. Cabecera de Liquidaciones de Comisiones
        if (!Schema::hasTable('comision_liquidaciones')) {
            Schema::create('comision_liquidaciones', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_liquidacion', 30)->unique();
                $table->integer('cod_usuarios')->index();
                $table->integer('suc_cod')->default(1)->index();
                $table->date('fecha_desde');
                $table->date('fecha_hasta');
                $table->dateTime('fecha_liquidacion');

                // Subtotales por concepto
                $table->integer('cant_reparaciones')->default(0);
                $table->decimal('total_base_taller', 14, 2)->default(0);
                $table->decimal('comision_taller', 14, 2)->default(0);

                $table->integer('cant_ventas_pos')->default(0);
                $table->decimal('total_base_ventas', 14, 2)->default(0);
                $table->decimal('comision_ventas', 14, 2)->default(0);

                $table->integer('cant_equipos_imei')->default(0);
                $table->decimal('total_base_imei', 14, 2)->default(0);
                $table->decimal('comision_imei', 14, 2)->default(0);

                $table->integer('cant_cobranzas')->default(0);
                $table->decimal('total_base_cobranzas', 14, 2)->default(0);
                $table->decimal('comision_cobranzas', 14, 2)->default(0);

                // Ajustes y Neto a Pagar
                $table->decimal('subtotal_comisiones', 14, 2)->default(0);
                $table->decimal('bono_premio', 14, 2)->default(0);
                $table->string('motivo_bono', 180)->nullable();
                $table->decimal('descuento_adelanto', 14, 2)->default(0);
                $table->string('motivo_descuento', 180)->nullable();
                $table->decimal('total_neto_pagar', 14, 2)->default(0);

                // Pago y Estado
                $table->string('estado', 25)->default('PAGADO')->index(); // PENDIENTE, PAGADO, ANULADO
                $table->integer('forma_pago')->default(1);
                $table->boolean('registrar_caja')->default(false);
                $table->integer('nro_operacion_caja')->nullable();
                $table->text('observaciones')->nullable();
                $table->integer('cod_usuario_liquidador')->nullable();
                $table->timestamps();
            });
        }

        // 3. Detalle de Operaciones Liquidadas
        if (!Schema::hasTable('comision_liquidacion_detalles')) {
            Schema::create('comision_liquidacion_detalles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('liquidacion_id')->index();
                $table->integer('cod_usuarios')->index();
                $table->string('origen_modulo', 30)->index(); // TALLER, VENTA_POS, EQUIPO_IMEI, COBRANZA
                $table->unsignedBigInteger('referencia_id')->index();
                $table->string('codigo_comprobante', 60);
                $table->dateTime('fecha_operacion')->nullable();
                $table->string('descripcion', 255);
                $table->decimal('monto_operacion', 14, 2)->default(0);
                $table->decimal('costo_operacion', 14, 2)->default(0);
                $table->decimal('base_comisionable', 14, 2)->default(0);
                $table->decimal('porcentaje_aplicado', 6, 2)->default(0);
                $table->decimal('bono_fijo', 14, 2)->default(0);
                $table->decimal('monto_comision', 14, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Registra el formulario y las acciones de privilegios si no existen.
     */
    public static function seedPermissionsAndSettings()
    {
        if (Schema::hasTable('configuraciones')) {
            $exists = DB::table('configuraciones')->where('name', 'modulo_comisiones_activo')->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert([
                    'name'      => 'modulo_comisiones_activo',
                    'categoria' => 'comisiones',
                    'value'     => '1',
                    'tipo_form' => 'checkbox',
                ]);
            }
        }

        if (Schema::hasTable('formularios')) {
            $form = DB::table('formularios')->where('for_nombre', 'comisiones')->first();
            if (!$form) {
                $forCodigo = DB::table('formularios')->insertGetId([
                    'for_nombre'    => 'comisiones',
                    'for_titulo'    => 'Comisiones de Técnicos y Vendedores',
                    'for_tipo'      => 'M',
                    'for_menu'      => 'Operaciones',
                    'for_submenu'   => 'Comisiones y Liquidación',
                    'for_categoria' => 'Operaciones',
                    'for_icono'     => 'fas fa-percentage',
                    'for_ruta'      => 'comision.index',
                    'for_orden'     => 27,
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
                            'per_add'    => $esAdmin,
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
                        'clave_accion'       => 'comision_configurar_tasas',
                        'nombre_accion'      => 'Configurar porcentajes y reglas de comisión por empleado',
                        'descripcion_accion' => 'Permite definir qué porcentaje gana cada técnico en taller y cada vendedor en POS, Equipos IMEI y Cobranzas.',
                        'orden'              => 1,
                    ],
                    [
                        'clave_accion'       => 'comision_generar_liquidacion',
                        'nombre_accion'      => 'Generar, aprobar y pagar liquidaciones de comisiones',
                        'descripcion_accion' => 'Permite emitir liquidaciones por período, agregar bonos/descuentos y registrar pagos en caja.',
                        'orden'              => 2,
                    ],
                    [
                        'clave_accion'       => 'comision_ver_todos',
                        'nombre_accion'      => 'Ver comisiones de todos los técnicos y vendedores',
                        'descripcion_accion' => 'Si está desmarcado, el usuario solo podrá consultar sus propias comisiones y liquidaciones.',
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
                                DB::table('accion_rol')->insertOrIgnore([
                                    'cod_per'    => $codPer,
                                    'cod_rol'    => $r->cod_rol,
                                    'permitido'  => $esAdmin ? 1 : 0,
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
