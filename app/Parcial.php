<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parcial extends Model {
  protected $table = 'parcial';
  protected $primaryKey = 'id_parcial';

  protected $fillable = [
    'nombre_parcial'
  ];

  public function calificacion(){
    return $this->hasMany('App\Calificacion');
  }

}
