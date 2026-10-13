<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $table= "configuraciones";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name', 'categoria', 'value', 'tipo_form'];

    /**
     * Flag 1/0 de configuraciones. Si no hay fila, usa el mismo valor
     * que muestra Ajustes generales.
     */
    public static function flag($name, $default = 1)
    {
        $value = static::where('name', $name)->value('value');
        if ($value === null || $value === '') {
            return (int) $default === 1 ? 1 : 0;
        }

        return (int) $value === 1 ? 1 : 0;
    }
}
