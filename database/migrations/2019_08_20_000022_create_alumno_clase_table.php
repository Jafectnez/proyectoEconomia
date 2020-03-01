<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoClaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_clase', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_alumno')->unsigned();
            $table->integer('id_clase')->unsigned();
            $table->decimal('indice_clase', 11, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('id_alumno')->references('id_alumno')->on('alumno');
            $table->foreign('id_clase')->references('id_clase')->on('clase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_clase');
    }
}
