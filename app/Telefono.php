<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model {
  protected $table = 'telefono';
  protected $primaryKey = 'id_telefono';

  protected $fillable = [
    'id_persona',
    'telefono'
  ];

  public function persona(){
    return $this->belongsTo('App\Persona');
  }
}
