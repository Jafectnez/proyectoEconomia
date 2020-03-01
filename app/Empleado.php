<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model {
  protected $table = 'empleado';
  protected $primaryKey = 'id_empleado';

  protected $fillable = [
    'id_persona',
    'id_tipo_usuario',
    'id_cargo',
    'fecha_ingreso',
    'usuario',
    'contrasena',
    'foto_url',
    'sesion',
    'estado'
  ];
  
  protected $hidden = [
    'contrasena'
  ];
  
  public function persona(){
    return $this->belongsTo('App\Persona');
  }

  public function cargo(){
    return $this->belongsTo('App\Cargo');
  }

  public function tipoUsuario(){
    return $this->belongsTo('App\TipoUsuario');
  }

}
