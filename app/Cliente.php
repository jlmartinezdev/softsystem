<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'CLIENTES_cod';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'CLIENTES_cod',
        'CIUDAD_cod',
        'cliente_ci',
        'cliente_nombre',
        'cliente_ruc',
        'cliente_direccion',
        'cliente_latitud',
        'cliente_longitud',
        'cliente_ubicacion_url',
        'cliente_telef',
        'cliente_cel',
        'cliente_correo',
        'cliente_calificacion',
        'cliente_credito',
        'cliente_profesion',
        'cliente_foto_ci_dorso',
        'cliente_foto_ci_reverso',
        'cliente_referente_nombre',
        'cliente_referente_cel',
        'cliente_referencia_laboral'
    ];

    protected $appends = [
        'foto_frente_url',
        'foto_dorso_url',
        'gps_url'
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'CIUDAD_cod', 'CIUDAD_cod');
    }

    public function getFotoFrenteUrlAttribute()
    {
        if (empty($this->cliente_foto_ci_dorso)) {
            return null;
        }

        // Si es una ruta completa o URL
        if (filter_var($this->cliente_foto_ci_dorso, FILTER_VALIDATE_URL)) {
            return $this->cliente_foto_ci_dorso;
        }

        return asset('storage/clientes/' . $this->cliente_foto_ci_dorso);
    }

    public function getFotoDorsoUrlAttribute()
    {
        if (empty($this->cliente_foto_ci_reverso)) {
            return null;
        }

        if (filter_var($this->cliente_foto_ci_reverso, FILTER_VALIDATE_URL)) {
            return $this->cliente_foto_ci_reverso;
        }

        return asset('storage/clientes/' . $this->cliente_foto_ci_reverso);
    }

    public function getGpsUrlAttribute()
    {
        if (!empty($this->cliente_latitud) && !empty($this->cliente_longitud)) {
            return "https://www.google.com/maps?q={$this->cliente_latitud},{$this->cliente_longitud}";
        }
        return $this->cliente_ubicacion_url;
    }

    public function scopeNombre($query, $nombre)
    {
        if ($nombre) {
            return $query->whereRaw('upper(clientes.cliente_nombre) like ?', ["%{$nombre}%"]);
        }
    }

    public function scopeDocumento($query, $documento)
    {
        if ($documento) {
            return $query->where('clientes.cliente_ci', '=', $documento);
        }
    }
}
