<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumno';
    protected $primaryKey = 'id_alumno';

    protected $fillable = [
        'id_persona',
        'id_tipo_usuario',
        'fecha_ingreso',
        'codigo_alumno',
        'contrasena',
        'foto_url',
        'indice_global',
        'estado'
    ];
    
    protected $hidden = [
      'contrasena'
    ];

    public $timestamps = false;

    public function persona(){
      return $this->belongsTo('App\Persona');
    }

    public function tipoUsuario(){
      return $this->belongsTo('App\TipoUsuario');
    }

    public function calificacion(){
      return $this->hasMany('App\Alumno');
    }

    public function clase(){
      return $this->belongsToMany('App\Clase', 'alumno_clase', 'id_alumno', 'id_clase');
    }
}
