<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model {
  protected $table = 'persona';
  protected $primaryKey = 'id_persona';
  
  //const CREATED_AT = 'fecha_creado';
  //const UPDATED_AT = 'fecha_actualizado';

  protected $fillable = [
    'primer_nombre',
    'segundo_nombre',
    'primer_apellido',
    'segundo_apellido',
    'numero_identidad',
    'fecha_nacimiento',
    'correo_electronico',
    'sexo',
    'direccion',
    'estado'
  ];
  
  public function telefono(){
    return $this->hasMany('App\Telefono');
  }

  public function mensaje(){
    return $this->hasMany('App\Mensaje');
  }

  public function alumno(){
    return $this->hasOne('App\Alumno');
  }

  public function empleado(){
    return $this->hasOne('App\Empleado');
  }
}
