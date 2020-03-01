<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalificacionTable extends Migration {

    /**
     * Run the migrations.
     * @table calificacion
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificacion', function (Blueprint $table) {
            $table->increments('id_calificacion');
            $table->integer('id_clase')->unsigned();
            $table->integer('id_alumno')->unsigned();
            $table->integer('id_parcial')->unsigned();
            $table->integer('id_tipo_calificacion')->unsigned();
            $table->integer('id_archivo_tarea')->unsigned()->nullable();
            $table->integer('nota');
            $table->string('observacion');
            $table->string('estado', 1);
            $table->timestamps(); // created_at y updated_at

            $table->foreign('id_clase')->references('id_clase')->on('clase');

            $table->foreign('id_alumno')->references('id_alumno')->on('alumno');

            $table->foreign('id_parcial')->references('id_parcial')->on('parcial');

            $table->foreign('id_tipo_calificacion')->references('id_tipo_calificacion')->on('tipo_calificacion');

            $table->foreign('id_archivo_tarea')->references('id_archivo')->on('archivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('calificacion');
     }
}
