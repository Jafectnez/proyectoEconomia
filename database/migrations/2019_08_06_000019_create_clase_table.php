<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("clase", function (Blueprint $table) {
            $table->increments('id_clase');
            $table->integer('id_maestro_encargado')->unsigned();
            $table->integer('id_aula')->unsigned();
            $table->integer('id_asignatura')->unsigned();
            $table->string('seccion', 100);
            $table->string('hora_inicio', 5);
            $table->string('hora_fin', 5);
            $table->date('anio');
            $table->string('observacion', 300)->nullable();
            $table->string('estado', 1);
            $table->timestamps(); // created_at y updated_at

            $table->foreign('id_maestro_encargado')->references('id_empleado')->on('empleado');

            $table->foreign('id_aula')->references('id_aula')->on('aula');

            $table->foreign('id_asignatura')->references('id_asignatura')->on('asignatura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clase');
    }
}
