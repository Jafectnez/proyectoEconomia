<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model {
  protected $table = 'persona';
  protected $primaryKey = 'id_persona';
  
  protected $fillable = [
    'id_emisor',
    'id_receptor',
    'mensaje',
    'fecha_envio',
    'estado'
  ];

  public function persona(){
    return $this->belongsTo('App\Persona');
  }

}
