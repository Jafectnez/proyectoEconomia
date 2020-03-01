<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model {
  protected $table = 'archivo';
  protected $primaryKey = 'id_archivo';

  protected $fillable = [
    'nombre_archivo',
    'fecha_subido',
    'url_archivo'
  ];

  public function calificacion(){
    return $this->hasMany('App\Calificacion');
  }

}
