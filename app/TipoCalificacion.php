<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCalificacion extends Model {
  protected $table = 'tipo_calificacion';
  protected $primaryKey = 'id_tipo_calificacion';

  protected $fillable = [
    'tipo_calificacion',
  ];
  
  public function calificacion(){
    return $this->hasMany('App\Calificacion');
  }
}
