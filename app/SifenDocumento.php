<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SifenDocumento extends Model
{
    protected $table = 'sifen_documentos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nro_fact_ventas',
        'cdc',
        'timbrado',
        'establecimiento',
        'punto_expedicion',
        'numero',
        'tipo_documento',
        'codigo_seguridad',
        'estado',
        'codigo_respuesta',
        'mensaje_respuesta',
        'xml_enviado',
        'xml_respuesta',
        'qr_url',
        'digest_value',
        'fecha_emision',
        'fecha_envio',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_envio' => 'datetime',
    ];

    public function scopeRecientes($query, $limit = 50)
    {
        return $query->orderBy('id', 'desc')->limit($limit);
    }
}
