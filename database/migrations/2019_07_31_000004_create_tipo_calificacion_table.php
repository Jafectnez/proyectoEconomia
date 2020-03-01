<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoCalificacionTable extends Migration {
    /**
     * Run the migrations.
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * @return void
     */
    
    public function up() {
        Schema::create('tipo_calificacion', function (Blueprint $table) {
            $table->increments('id_tipo_calificacion');
            $table->string('tipo_calificacion', 100);
            $table->timestamps(); // created_at y updated_at
        });

        DB::table('tipo_calificacion')
          ->insert(array('id_tipo_calificacion' => 1, 'tipo_calificacion' => 'Nota Final Parcial'));

        DB::table('tipo_calificacion')
        ->insert(array('id_tipo_calificacion' => 2, 'tipo_calificacion' => 'Examen'));

        DB::table('tipo_calificacion')
        ->insert(array('id_tipo_calificacion' => 3, 'tipo_calificacion' => 'Tarea'));

        DB::table('tipo_calificacion')
        ->insert(array('id_tipo_calificacion' => 4, 'tipo_calificacion' => 'Exposición'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('tipo_calificacion');
     }
}
