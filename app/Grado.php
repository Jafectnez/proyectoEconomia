<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grado';
    protected $primaryKey = 'id_grado';
  
    protected $fillable = [
      'nombre_grado',
      'codigo_grado',
      'cantidad_asignatura',
      'estado'
    ];

    public function asignatura(){
        return $this->belongsToMany('App\Asignatura', 'asignatura_grado', 'id_grado','id_asignatura');
    }

    public function alumno(){
        return $this->hasMany('App\Alumno');
    }
}
