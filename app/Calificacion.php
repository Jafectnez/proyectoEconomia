<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model {
    protected $table = 'calificacion';
    protected $primaryKey = 'id_calificacion';
  
    protected $fillable = [
      'id_clase',
      'id_alumno',
      'id_parcial',
      'id_tipo_calificacion',
      'id_archivo_tarea',
      'nota',
      'observacion',
      'estado'
    ];

    public function clase(){
      return $this->belongsTo('App\Clase');
    }

    public function alumno(){
      return $this->belongsTo('App\Alumno');
    }

    public function parcial(){
      return $this->belongsTo('App\Parcial');
    }

    public function tipoCalificacion(){
      return $this->belongsTo('App\TipoCalificacion');
    }

    public function archivo(){
      return $this->belongsTo('App\Archivo');
  }
}
