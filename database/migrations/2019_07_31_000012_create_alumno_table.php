<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoTable extends Migration
{
    /**
     * Run the migrations.
     * @table alumno
     *
     * @return void
     */
    public function up()
    {
        Schema::create("alumno", function (Blueprint $table) {
            $table->increments('id_alumno');
            $table->integer('id_persona')->unsigned();
            $table->integer('id_tipo_usuario')->unsigned();
            $table->string('codigo_alumno', 50);
            $table->string('contrasena', 200);
            $table->decimal('indice_global', 11, 2)->default(0);
            $table->date('fecha_ingreso');
            $table->string('foto_url')->nullable();
            $table->timestamp('sesion')->nullable();
            $table->string('estado', 1);

            $table->foreign('id_persona')->references('id_persona')->on('persona');

            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists("alumno");
     }
}
