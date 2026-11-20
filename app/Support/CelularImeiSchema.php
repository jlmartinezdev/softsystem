<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Exception;

class CelularImeiSchema
{
    protected static $initialized = false;

    /**
     * Asegura que las tablas, permisos y ajustes del módulo de Compra-Venta por IMEI y Plan Canje existan.
     *
     * @return void
     */
    public static function ensureExists()
    {
        if (self::$initialized) {
            return;
        }

        try {
            ReparacionSchema::ensureExists();
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
        // 1. Tabla principal de equipos individualizados por IMEI
        if (!Schema::hasTable('celulares_imei')) {
            Schema::create('celulares_imei', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_interno', 30)->unique();
                $table->integer('ARTICULOS_cod')->nullable()->index();
                $table->integer('suc_cod')->default(1)->index();

                // Especificaciones del Celular
                $table->string('tipo_equipo', 40)->default('Celular');
                $table->string('marca', 80);
                $table->string('modelo', 120);
                $table->string('capacidad_gb', 40)->nullable();
                $table->string('ram_gb', 30)->nullable();
                $table->string('color', 50)->nullable();
                $table->string('imei_1', 80)->index();
                $table->string('imei_2', 80)->nullable();
                $table->string('condicion_ingreso', 30)->default('NUEVO'); // NUEVO, SEMINUEVO, USADO_CANJE, REACONDICIONADO
                $table->string('grado_estetico', 20)->default('A+'); // SELLADO, A+, A, B, C
                $table->integer('bateria_salud')->default(100);
                $table->boolean('libre_operadora')->default(true);
                $table->boolean('icloud_google_limpio')->default(true);
                $table->string('accesorios_incluidos', 255)->nullable();
                $table->text('checklist_funcional')->nullable();
                $table->text('observaciones_equipo')->nullable();

                // Origen / Adquisición
                $table->string('origen_tipo', 30)->default('COMPRA_PROVEEDOR'); // COMPRA_PROVEEDOR, PLAN_CANJE, COMPRA_PARTICULAR, INVENTARIO_INICIAL
                $table->integer('PROVEEDOR_cod')->nullable()->index();
                $table->integer('CLIENTES_cod_origen')->nullable()->index();
                $table->unsignedBigInteger('canje_id')->nullable()->index();
                $table->string('nro_documento_compra', 60)->nullable();
                $table->dateTime('fecha_ingreso');
                $table->integer('cod_usuario_ingreso')->nullable();

                // Costos, Reacondicionamiento y Precios
                $table->decimal('costo_adquisicion', 14, 2)->default(0);
                $table->decimal('costo_reacondicionamiento', 14, 2)->default(0);
                $table->decimal('costo_total', 14, 2)->default(0);
                $table->decimal('precio_venta_contado', 14, 2)->default(0);
                $table->decimal('precio_venta_credito', 14, 2)->default(0);
                $table->decimal('precio_minimo', 14, 2)->default(0);
                $table->unsignedBigInteger('reparacion_actual_id')->nullable()->index();

                // Estado en Inventario
                $table->string('estado', 30)->default('EN_STOCK')->index(); // EN_STOCK, EN_REACONDICIONAMIENTO, RESERVADO, VENDIDO, EN_GARANTIA, BAJA

                // Venta y Garantía Post-Venta
                $table->integer('CLIENTES_cod_comprador')->nullable()->index();
                $table->dateTime('fecha_venta')->nullable();
                $table->decimal('precio_vendido', 14, 2)->default(0);
                $table->decimal('descuento_venta', 14, 2)->default(0);
                $table->decimal('monto_canje_descontado', 14, 2)->default(0);
                $table->decimal('saldo_cobrado', 14, 2)->default(0);
                $table->integer('nro_fact_ventas')->nullable()->index();
                $table->integer('cod_usuario_venta')->nullable();
                $table->boolean('tiene_garantia')->default(true);
                $table->integer('dias_garantia')->default(90);
                $table->date('fecha_vencimiento_garantia')->nullable();
                $table->text('condiciones_garantia')->nullable();

                $table->timestamps();
            });
        }

        // 2. Tabla de Operaciones de Plan Canje (Trade-In) y Compra Directa de Usados
        if (!Schema::hasTable('celular_canjes')) {
            Schema::create('celular_canjes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_canje', 30)->unique();
                $table->string('tipo_operacion', 30)->default('PLAN_CANJE'); // PLAN_CANJE, COMPRA_DIRECTA
                $table->integer('suc_cod')->default(1)->index();
                $table->integer('CLIENTES_cod')->index();
                $table->unsignedBigInteger('celular_entregado_id')->index();
                $table->unsignedBigInteger('celular_llevado_id')->nullable()->index();
                $table->decimal('valor_tasacion_usado', 14, 2)->default(0);
                $table->decimal('precio_equipo_llevado', 14, 2)->default(0);
                $table->decimal('descuento_adicional', 14, 2)->default(0);
                $table->decimal('diferencia_a_pagar', 14, 2)->default(0);
                $table->integer('forma_pago')->default(1);
                $table->boolean('registrar_caja')->default(false);
                $table->integer('nro_operacion_caja')->nullable();
                $table->integer('nro_fact_ventas')->nullable()->index();
                $table->boolean('enviar_a_taller')->default(false);
                $table->unsignedBigInteger('reparacion_id')->nullable()->index();
                $table->string('estado', 30)->default('COMPLETADO'); // PENDIENTE_POS, COMPLETADO, ANULADO
                $table->text('observaciones')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->dateTime('fecha_operacion');
                $table->timestamps();
            });
        }

        // 3. Tabla de Imágenes del Equipo y C.I. del Titular
        if (!Schema::hasTable('celular_imei_imagenes')) {
            Schema::create('celular_imei_imagenes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('celular_imei_id')->index();
                $table->string('tipo', 30)->default('EQUIPO'); // EQUIPO, CI_FRENTE, CI_DORSO, IMEI_CAPTURA, ESTADO_BATERIA
                $table->string('url', 500);
                $table->string('filename', 255)->nullable();
                $table->string('public_id', 191)->nullable();
                $table->string('driver', 20)->default('local');
                $table->string('descripcion', 255)->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }

        // 4. Tabla de Historial y Trazabilidad del IMEI
        if (!Schema::hasTable('celular_imei_historial')) {
            Schema::create('celular_imei_historial', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('celular_imei_id')->index();
                $table->string('evento', 50);
                $table->string('titulo', 180);
                $table->text('detalle')->nullable();
                $table->decimal('costo_anterior', 14, 2)->default(0);
                $table->decimal('costo_nuevo', 14, 2)->default(0);
                $table->unsignedBigInteger('referencia_id')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->dateTime('created_at');
            });
        }
    }

    /**
     * Registra el formulario y las acciones de privilegios si no existen.
     */
    public static function seedPermissionsAndSettings()
    {
        if (Schema::hasTable('configuraciones')) {
            $exists = DB::table('configuraciones')->where('name', 'modulo_celulares_imei_activo')->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert([
                    'name'      => 'modulo_celulares_imei_activo',
                    'categoria' => 'celulares_imei',
                    'value'     => '1',
                    'tipo_form' => 'checkbox',
                ]);
            }
        }

        if (Schema::hasTable('formularios')) {
            $form = DB::table('formularios')->where('for_nombre', 'celulares_imei')->first();
            if (!$form) {
                $forCodigo = DB::table('formularios')->insertGetId([
                    'for_nombre'    => 'celulares_imei',
                    'for_titulo'    => 'Equipos por IMEI y Plan Canje',
                    'for_tipo'      => 'M',
                    'for_menu'      => 'Operaciones',
                    'for_submenu'   => 'Equipos IMEI / Canje',
                    'for_categoria' => 'Operaciones',
                    'for_icono'     => 'fas fa-exchange-alt',
                    'for_ruta'      => 'imei.index',
                    'for_orden'     => 26,
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
                            'per_edit'   => '1',
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
                        'clave_accion'       => 'imei_registrar_compra_canje',
                        'nombre_accion'      => 'Registrar ingreso de equipos y operaciones de Plan Canje',
                        'descripcion_accion' => 'Permite tasar celulares usados en Plan Canje, registrar compras de equipos por IMEI y emitir contratos.',
                        'orden'              => 1,
                    ],
                    [
                        'clave_accion'       => 'imei_vender',
                        'nombre_accion'      => 'Vender equipos por IMEI y transferir a Punto de Venta (POS)',
                        'descripcion_accion' => 'Permite vender equipos registrados por IMEI, emitir certificado de garantía y facturar en POS.',
                        'orden'              => 2,
                    ],
                    [
                        'clave_accion'       => 'imei_reacondicionar',
                        'nombre_accion'      => 'Enviar equipos a Servicio Técnico para reacondicionamiento',
                        'descripcion_accion' => 'Permite vincular un celular por IMEI con el taller y sumar el costo de repuestos al costo del equipo.',
                        'orden'              => 3,
                    ],
                    [
                        'clave_accion'       => 'imei_ver_costo',
                        'nombre_accion'      => 'Ver costo de compra/tasación, reacondicionamiento y margen de ganancia',
                        'descripcion_accion' => 'Muestra cuánto costó adquirir y reacondicionar cada IMEI y la ganancia neta obtenida en la venta.',
                        'orden'              => 4,
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
                                $permitido = ($acc['clave_accion'] === 'imei_ver_costo' && !$esAdmin) ? 0 : 1;
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
