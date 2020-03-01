<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $table = 'asignatura';
    protected $primaryKey = 'id_asignatura';
  
    protected $fillable = [
      'nombre_asignatura',
      'dias',
      'codigo_asignatura'
    ];

    public function clase(){
        return $this->belongsTo('App\Clase');
    }

    public function grado(){
        return $this->belongsToMany('App\Grado', 'asignatura_grado', 'id_asignatura', 'id_grado');
    }
}
