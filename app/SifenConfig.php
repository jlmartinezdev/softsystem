<?php



namespace App;



use Illuminate\Database\Eloquent\Model;



class SifenConfig extends Model

{

    protected $table = 'sifen_config';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;



    protected $fillable = [

        'activo',

        'ambiente',

        'version_formato',

        'nota_tecnica',

        'id_software',

        'tipo_emision',

        'ruc',

        'razon_social',

        'tipo_contribuyente',

        'tipo_regimen',

        'timbrado',

        'vigencia_desde',

        'vigencia_hasta',

        'establecimiento',

        'punto_expedicion',

        'ultimo_numero',

        'tipo_documento',

        'tipo_transaccion',

        'tipo_impuesto',

        'moneda',

        'indicador_presencia',

        'condicion_operacion_defecto',

        'cod_departamento',

        'desc_departamento',

        'cod_distrito',

        'desc_distrito',

        'cod_ciudad',

        'desc_ciudad',

        'direccion_emisor',

        'numero_casa',

        'telefono_emisor',

        'email_emisor',

        'cod_actividad_economica',

        'desc_actividad_economica',

        'natura_receptor_defecto',

        'tipo_operacion_defecto',

        'pais_receptor_defecto',

        'csc_id',

        'csc_token',

        'cert_path',

        'cert_password',

        'url_test',

        'url_prod',

        'observaciones',

    ];



    protected $casts = [

        'activo' => 'boolean',

    ];

}


