<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('grado', function (Blueprint $table) {
          $table->increments('id_grado');
          $table->string('nombre_grado', 150);
          $table->string('codigo_grado', 100);
          $table->integer('cantidad_asignatura');
          $table->string('estado', 1)->default('A');
          $table->timestamps(); // created_at y updated_at
        });

        DB::table('grado')
          ->insert(array('id_grado' => '1', 'nombre_grado' => 'Primer Grado', 'codigo_grado' => 'GP1', 'cantidad_asignatura' => '12'));
        
        DB::table('grado')
        ->insert(array('id_grado' => '2', 'nombre_grado' => 'Segundo Grado', 'codigo_grado' => 'GS2', 'cantidad_asignatura' => '12'));
        
        DB::table('grado')
        ->insert(array('id_grado' => '3', 'nombre_grado' => 'Tercer Grado', 'codigo_grado' => 'GT3', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '4', 'nombre_grado' => 'Cuarto Grado', 'codigo_grado' => 'GC4', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '5', 'nombre_grado' => 'Quinto Grado', 'codigo_grado' => 'GQ5', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '6', 'nombre_grado' => 'Sexto Grado', 'codigo_grado' => 'GS6', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '7', 'nombre_grado' => 'I Ciclo Común Grado', 'codigo_grado' => 'CC1', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '8', 'nombre_grado' => 'II Ciclo Común', 'codigo_grado' => 'CC2', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '9', 'nombre_grado' => 'III Ciclo Común', 'codigo_grado' => 'CC3', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '10', 'nombre_grado' => 'Bachiller en Ciencias y Humanidades', 'codigo_grado' => 'BCH', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '11', 'nombre_grado' => 'Bachiller en Contaduría y Finanzas', 'codigo_grado' => 'BCF', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '12', 'nombre_grado' => 'Bachiller Técnico en Ciencias de la Computación', 'codigo_grado' => 'BTC', 'cantidad_asignatura' => '12'));

        DB::table('grado')
        ->insert(array('id_grado' => '13', 'nombre_grado' => 'Bachiller en Ciencias Agropecuarias', 'codigo_grado' => 'BCA', 'cantidad_asignatura' => '12'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grado');
    }
}
