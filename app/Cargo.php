<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model {
  protected $table = 'cargo';
  protected $primaryKey = 'id_cargo';

  protected $fillable = [
    'nombre_cargo',
    'descripcion_cargo',
    'estado'
  ];

  public function empleado(){
    return $this->hasMany('App\Empleado');
  }
}
