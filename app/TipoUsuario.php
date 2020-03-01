<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model {
  protected $table = 'tipo_usuario';
  protected $primaryKey = 'id_tipo_usuario';

  protected $fillable = [
    'tipo_usuario',
    'descripcion',
    'estado'
  ];

  public function alumno(){
    return $this->hasMany('App\Alumno');
  }

  public function empleado(){
    return $this->hasMany('App\Empleado');
  }
}
