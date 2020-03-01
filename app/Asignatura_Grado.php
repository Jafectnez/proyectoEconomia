<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asignatura_Grado extends Model
{
    protected $table = 'asignatura_grado';
    protected $primaryKey = 'id';
  
    protected $fillable = [
      'id_asignatura',
      'id_grado'
    ];

    public function grado(){
        return $this->belongsToMany('App\Grado')->withPivot('relacion');
    }

    public function asignatura(){
        return $this->belongsToMany('App\Asignatura')->withPivot('relacion');
    }
}
