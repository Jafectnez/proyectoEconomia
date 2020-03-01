<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    protected $table = 'edificio';
    protected $primaryKey = 'id_edificio';
  
    protected $fillable = [
      'nombre_edificio',
      'codigo_edificio',
      'estado'
    ];

    public function aula(){
        return $this->hasMany('App\Aula');
    }
}
