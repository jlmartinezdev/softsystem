<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CelularImei extends Model
{
    protected $table = 'celulares_imei';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_interno',
        'ARTICULOS_cod',
        'suc_cod',
        'tipo_equipo',
        'marca',
        'modelo',
        'capacidad_gb',
        'ram_gb',
        'color',
        'imei_1',
        'imei_2',
        'condicion_ingreso',
        'grado_estetico',
        'bateria_salud',
        'libre_operadora',
        'icloud_google_limpio',
        'accesorios_incluidos',
        'checklist_funcional',
        'observaciones_equipo',
        'origen_tipo',
        'PROVEEDOR_cod',
        'CLIENTES_cod_origen',
        'canje_id',
        'nro_documento_compra',
        'fecha_ingreso',
        'cod_usuario_ingreso',
        'costo_adquisicion',
        'costo_reacondicionamiento',
        'costo_total',
        'precio_venta_contado',
        'precio_venta_credito',
        'precio_minimo',
        'reparacion_actual_id',
        'estado',
        'CLIENTES_cod_comprador',
        'fecha_venta',
        'precio_vendido',
        'descuento_venta',
        'monto_canje_descontado',
        'saldo_cobrado',
        'nro_fact_ventas',
        'cod_usuario_venta',
        'tiene_garantia',
        'dias_garantia',
        'fecha_vencimiento_garantia',
        'condiciones_garantia',
    ];

    protected $casts = [
        'bateria_salud'             => 'integer',
        'libre_operadora'           => 'boolean',
        'icloud_google_limpio'      => 'boolean',
        'checklist_funcional'       => 'array',
        'costo_adquisicion'         => 'float',
        'costo_reacondicionamiento' => 'float',
        'costo_total'               => 'float',
        'precio_venta_contado'      => 'float',
        'precio_venta_credito'      => 'float',
        'precio_minimo'             => 'float',
        'precio_vendido'            => 'float',
        'descuento_venta'           => 'float',
        'monto_canje_descontado'    => 'float',
        'saldo_cobrado'             => 'float',
        'tiene_garantia'            => 'boolean',
        'dias_garantia'             => 'integer',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'PROVEEDOR_cod', 'PROVEEDOR_cod');
    }

    public function clienteOrigen()
    {
        return $this->belongsTo(Cliente::class, 'CLIENTES_cod_origen', 'CLIENTES_cod');
    }

    public function clienteComprador()
    {
        return $this->belongsTo(Cliente::class, 'CLIENTES_cod_comprador', 'CLIENTES_cod');
    }

    public function usuarioIngreso()
    {
        return $this->belongsTo(User::class, 'cod_usuario_ingreso', 'cod_usuarios');
    }

    public function usuarioVenta()
    {
        return $this->belongsTo(User::class, 'cod_usuario_venta', 'cod_usuarios');
    }

    public function reparacionActual()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_actual_id', 'id');
    }

    public function canjeOrigen()
    {
        return $this->belongsTo(CelularCanje::class, 'canje_id', 'id');
    }

    public function imagenes()
    {
        return $this->hasMany(CelularImeiImagen::class, 'celular_imei_id', 'id')->orderBy('id', 'desc');
    }

    public function historial()
    {
        return $this->hasMany(CelularImeiHistorial::class, 'celular_imei_id', 'id')->orderBy('id', 'desc');
    }

    public function recalcularCostoTotal()
    {
        $this->costo_total = max(0, (float) $this->costo_adquisicion + (float) $this->costo_reacondicionamiento);
        $this->save();
        return $this;
    }

    public function scopeFiltroEstado($query, $estado)
    {
        if (!empty($estado) && $estado !== 'TODOS') {
            return $query->where('celulares_imei.estado', $estado);
        }
        return $query;
    }

    public function scopeFiltroCondicion($query, $condicion)
    {
        if (!empty($condicion) && $condicion !== 'TODOS') {
            return $query->where('celulares_imei.condicion_ingreso', $condicion);
        }
        return $query;
    }

    public function scopeFiltroSucursal($query, $suc)
    {
        if (!empty($suc) && $suc != '0') {
            return $query->where('celulares_imei.suc_cod', $suc);
        }
        return $query;
    }

    public function scopeFiltroBusqueda($query, $termino)
    {
        if (!empty($termino)) {
            $t = trim($termino);
            return $query->where(function ($q) use ($t) {
                $q->where('celulares_imei.codigo_interno', 'like', "%{$t}%")
                  ->orWhere('celulares_imei.imei_1', 'like', "%{$t}%")
                  ->orWhere('celulares_imei.imei_2', 'like', "%{$t}%")
                  ->orWhere('celulares_imei.marca', 'like', "%{$t}%")
                  ->orWhere('celulares_imei.modelo', 'like', "%{$t}%")
                  ->orWhere('celulares_imei.capacidad_gb', 'like', "%{$t}%")
                  ->orWhereHas('clienteOrigen', function ($qc) use ($t) {
                      $qc->where('cliente_nombre', 'like', "%{$t}%")
                         ->orWhere('cliente_ruc', 'like', "%{$t}%")
                         ->orWhere('cliente_ci', 'like', "%{$t}%");
                  })
                  ->orWhereHas('clienteComprador', function ($qc) use ($t) {
                      $qc->where('cliente_nombre', 'like', "%{$t}%")
                         ->orWhere('cliente_ruc', 'like', "%{$t}%")
                         ->orWhere('cliente_ci', 'like', "%{$t}%");
                  });
            });
        }
        return $query;
    }
}
