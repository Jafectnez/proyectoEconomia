<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clase';
    protected $primaryKey = 'id_clase';
  
    protected $fillable = [
      'id_maestro_encargado',
      'id_aula',
      'id_asignatura',
      'seccion',
      'hora_inicio',
      'hora_fin',
      'observacion',
      'estado'
    ];

    public function empleado(){
        return $this->hasOne('App\Empleado');
    }

    public function aula(){
      return $this->belongsTo('App\Aula');
    }

    public function asignatura(){
        return $this->hasOne('App\Asignatura');
    }

    public function alumno(){
      return $this->belongsToMany('App\Alumno', 'alumno_clase', 'id_clase', 'id_alumno');
    }

    public function calificacion(){
      return $this->hasMany('App\Calificacion');
    }

}
