<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno_Clase extends Model
{
    protected $table = 'alumno_clase';
    protected $primaryKey = 'id';
  
    protected $fillable = [
      'id_alumno',
      'id_clase'
    ];

    public function clase(){
        return $this->belongsToMany('App\Clase')->withPivot('relacion');
    }

    public function alumno(){
        return $this->belongsToMany('App\Alumno')->withPivot('relacion');
    }
}
