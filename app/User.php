<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'cod_usuarios';
     public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom_usuarios', 'user_usuarios', 'clave_usuarios',
    ];


   /* protected $hidden = [
        'clave_usuarios'//, 'remember_token',
    ];*/

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getAuthPassword()
    {
        return $this->clave_usuarios;
    }
    public function roles(){
        return $this->belongsTo(Roles::class, 'cod_rol');
    }

    /**
     * Cache en memoria para permisos del usuario durante el ciclo de vida del request
     */
    protected $permisosCache = null;
    protected $accionesCache = null;

    public function esAdministrador()
    {
        if ((int) $this->cod_rol === 4) {
            return true;
        }

        if ($this->roles && strtolower(trim($this->roles->nom_rol)) === 'administrador') {
            return true;
        }

        return false;
    }

    /**
     * Cargar todos los permisos del rol en memoria para evitar queries repetidas
     */
    public function cargarPermisosEnMemoria()
    {
        if ($this->permisosCache !== null) {
            return;
        }

        $this->permisosCache = [];

        if (!$this->cod_rol) {
            return;
        }

        $rows = \DB::table('permiso')
            ->join('formularios', 'permiso.for_codigo', '=', 'formularios.for_codigo')
            ->where('permiso.cod_rol', $this->cod_rol)
            ->select('formularios.for_nombre', 'permiso.per_open', 'permiso.per_add', 'permiso.per_edit', 'permiso.per_del', 'permiso.per_export')
            ->get();

        foreach ($rows as $r) {
            $this->permisosCache[$r->for_nombre] = [
                'open'   => (string)$r->per_open === '1',
                'add'    => (string)$r->per_add === '1',
                'edit'   => (string)$r->per_edit === '1',
                'del'    => (string)$r->per_del === '1',
                'export' => (string)$r->per_export === '1',
            ];
        }
    }

    /**
     * Cargar todas las acciones permitidas del rol en memoria
     */
    public function cargarAccionesEnMemoria()
    {
        if ($this->accionesCache !== null) {
            return;
        }

        $this->accionesCache = [];

        if (!$this->cod_rol) {
            return;
        }

        $rows = \DB::table('accion_rol')
            ->join('accion', 'accion_rol.cod_per', '=', 'accion.cod_per')
            ->where('accion_rol.cod_rol', $this->cod_rol)
            ->where('accion_rol.permitido', 1)
            ->pluck('accion.clave_accion')
            ->toArray();

        foreach ($rows as $clave) {
            $this->accionesCache[$clave] = true;
        }
    }

    public function tienePermiso($formularioNombre, $tipo = 'open')
    {
        if ($this->esAdministrador()) {
            return true;
        }

        $tipo = strtolower(trim($tipo));
        if (substr($tipo, 0, 4) === 'per_') {
            $tipo = substr($tipo, 4);
        }

        if (!in_array($tipo, ['open', 'add', 'edit', 'del', 'export'])) {
            $tipo = 'open';
        }

        $this->cargarPermisosEnMemoria();

        if (isset($this->permisosCache[$formularioNombre])) {
            return !empty($this->permisosCache[$formularioNombre][$tipo]);
        }

        return false;
    }

    public function tieneAccion($claveAccion)
    {
        if ($this->esAdministrador()) {
            return true;
        }

        $this->cargarAccionesEnMemoria();

        return !empty($this->accionesCache[$claveAccion]);
    }
}

