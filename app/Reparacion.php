<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Reparacion extends Model
{
    protected $table = 'reparaciones';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_orden',
        'CLIENTES_cod',
        'suc_cod',
        'cod_usuario_recepcion',
        'cod_usuario_tecnico',
        'cod_usuario_entrega',
        'reparacion_origen_id',
        'es_reingreso_garantia',
        'tipo_equipo',
        'marca',
        'modelo',
        'color',
        'imei_serie',
        'tipo_bloqueo',
        'clave_bloqueo',
        'patron_bloqueo',
        'accesorios_dejados',
        'checklist_recepcion',
        'estado_fisico_obs',
        'falla_reportada',
        'prioridad',
        'fecha_recepcion',
        'fecha_prometida',
        'diagnostico_tecnico',
        'solucion_aplicada',
        'observaciones_internas',
        'fecha_diagnostico',
        'fecha_reparado',
        'estado',
        'costo_mano_obra',
        'total_repuestos',
        'costo_repuestos',
        'descuento',
        'total_general',
        'monto_adelanto',
        'total_pagado',
        'saldo_pendiente',
        'estado_pago',
        'nro_fact_ventas',
        'fecha_entrega',
        'retirado_por_nombre',
        'retirado_por_ci',
        'conformidad_cliente',
        'tiene_garantia',
        'dias_garantia',
        'fecha_vencimiento_garantia',
        'condiciones_garantia',
    ];

    protected $casts = [
        'es_reingreso_garantia' => 'boolean',
        'tiene_garantia'        => 'boolean',
        'checklist_recepcion'   => 'array',
        'costo_mano_obra'       => 'float',
        'total_repuestos'       => 'float',
        'costo_repuestos'       => 'float',
        'descuento'             => 'float',
        'total_general'         => 'float',
        'monto_adelanto'        => 'float',
        'total_pagado'          => 'float',
        'saldo_pendiente'       => 'float',
        'dias_garantia'         => 'integer',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'CLIENTES_cod', 'CLIENTES_cod');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function usuarioRecepcion()
    {
        return $this->belongsTo(User::class, 'cod_usuario_recepcion', 'cod_usuarios');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'cod_usuario_tecnico', 'cod_usuarios');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'cod_usuario_entrega', 'cod_usuarios');
    }

    public function ordenOrigen()
    {
        return $this->belongsTo(self::class, 'reparacion_origen_id', 'id');
    }

    public function reingresos()
    {
        return $this->hasMany(self::class, 'reparacion_origen_id', 'id');
    }

    public function repuestos()
    {
        return $this->hasMany(ReparacionRepuesto::class, 'reparacion_id', 'id')->orderBy('id', 'asc');
    }

    public function imagenes()
    {
        return $this->hasMany(ReparacionImagen::class, 'reparacion_id', 'id')->orderBy('id', 'desc');
    }

    public function pagos()
    {
        return $this->hasMany(ReparacionPago::class, 'reparacion_id', 'id')->orderBy('fecha_pago', 'desc');
    }

    public function historial()
    {
        return $this->hasMany(ReparacionHistorial::class, 'reparacion_id', 'id')->orderBy('id', 'desc');
    }

    public function garantias()
    {
        return $this->hasMany(ReparacionGarantia::class, 'reparacion_id', 'id')->orderBy('id', 'desc');
    }

    /**
     * Recalcula totales de repuestos, general, pagado y saldo pendiente.
     */
    public function recalcularTotales()
    {
        $repuestos = ReparacionRepuesto::where('reparacion_id', $this->id)->get();
        $totalRepuestos = 0;
        $costoRepuestos = 0;

        foreach ($repuestos as $r) {
            $cant = (float) $r->cantidad;
            $totalRepuestos += (float) $r->subtotal;
            $costoRepuestos += ($cant * (float) $r->costo_unitario);
        }

        $manoObra = max(0, (float) $this->costo_mano_obra);
        $descuento = max(0, (float) $this->descuento);

        if ($this->es_reingreso_garantia && $manoObra == 0 && $totalRepuestos == 0) {
            $totalGeneral = 0;
        } else {
            $totalGeneral = max(0, ($manoObra + $totalRepuestos) - $descuento);
        }

        $pagos = ReparacionPago::where('reparacion_id', $this->id)->get();
        $totalPagado = 0;
        $totalAdelanto = 0;

        foreach ($pagos as $p) {
            if ($p->tipo_pago === 'DEVOLUCION') {
                $totalPagado -= (float) $p->monto;
            } else {
                $totalPagado += (float) $p->monto;
                if ($p->tipo_pago === 'ADELANTO') {
                    $totalAdelanto += (float) $p->monto;
                }
            }
        }

        $totalPagado = max(0, $totalPagado);
        $saldoPendiente = max(0, $totalGeneral - $totalPagado);

        if ($this->es_reingreso_garantia && $totalGeneral == 0) {
            $estadoPago = 'SIN_CARGO';
        } elseif ($totalGeneral > 0 && $saldoPendiente <= 0) {
            $estadoPago = 'PAGADO';
        } elseif ($totalPagado > 0) {
            $estadoPago = 'PARCIAL';
        } else {
            $estadoPago = 'PENDIENTE';
        }

        $this->total_repuestos = $totalRepuestos;
        $this->costo_repuestos = $costoRepuestos;
        $this->total_general   = $totalGeneral;
        $this->monto_adelanto  = $totalAdelanto;
        $this->total_pagado    = $totalPagado;
        $this->saldo_pendiente = $saldoPendiente;
        $this->estado_pago     = $estadoPago;
        $this->save();

        return $this;
    }

    public function scopeFiltroFecha($query, $desde, $hasta)
    {
        if (!empty($desde) && !empty($hasta)) {
            return $query->whereBetween(DB::raw("DATE(reparaciones.fecha_recepcion)"), [$desde, $hasta]);
        }
        return $query;
    }

    public function scopeFiltroEstado($query, $estado)
    {
        if (!empty($estado) && $estado !== 'TODOS') {
            if ($estado === 'EN_TALLER') {
                return $query->whereIn('reparaciones.estado', [
                    'RECIBIDO', 'EN_DIAGNOSTICO', 'PRESUPUESTADO', 'APROBADO', 'ESPERANDO_REPUESTO', 'EN_REPARACION'
                ]);
            }
            if ($estado === 'LISTOS_ENTREGA') {
                return $query->whereIn('reparaciones.estado', ['REPARADO', 'NO_REPARABLE']);
            }
            return $query->where('reparaciones.estado', $estado);
        }
        return $query;
    }

    public function scopeFiltroSucursal($query, $suc)
    {
        if (!empty($suc) && $suc != '0') {
            return $query->where('reparaciones.suc_cod', $suc);
        }
        return $query;
    }

    public function scopeFiltroTecnico($query, $tec)
    {
        if (!empty($tec) && $tec != '0') {
            return $query->where('reparaciones.cod_usuario_tecnico', $tec);
        }
        return $query;
    }

    public function scopeFiltroBusqueda($query, $termino)
    {
        if (!empty($termino)) {
            $t = trim($termino);
            return $query->where(function ($q) use ($t) {
                $q->where('reparaciones.codigo_orden', 'like', "%{$t}%")
                  ->orWhere('reparaciones.id', $t)
                  ->orWhere('reparaciones.marca', 'like', "%{$t}%")
                  ->orWhere('reparaciones.modelo', 'like', "%{$t}%")
                  ->orWhere('reparaciones.imei_serie', 'like', "%{$t}%")
                  ->orWhere('reparaciones.falla_reportada', 'like', "%{$t}%")
                  ->orWhereHas('cliente', function ($qc) use ($t) {
                      $qc->where('cliente_nombre', 'like', "%{$t}%")
                         ->orWhere('cliente_ruc', 'like', "%{$t}%")
                         ->orWhere('cliente_ci', 'like', "%{$t}%")
                         ->orWhere('cliente_cel', 'like', "%{$t}%");
                  });
            });
        }
        return $query;
    }
}
