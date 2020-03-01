<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table = 'aula';
    protected $primaryKey = 'id_aula';
  
    protected $fillable = [
      'id_edificio',
      'nombre_aula',
      'codigo_aula',
      'estado'
    ];

    public function clase(){
        return $this->hasOne('App\Clase');
    }

    public function edificio(){
        return $this->belongsTo('App\Edificio');
    }
}
