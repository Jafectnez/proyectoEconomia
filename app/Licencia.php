<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $table = 'licencia';
    protected $primaryKey = 'id_licencia';

    protected $fillable = [
        'id_licencia',
        'numero_licencia',
        'fecha_vencimiento',
        'estado'
    ];

}
