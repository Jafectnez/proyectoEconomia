<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * @return void
     */
    public function up() {
      Schema::create('cargo', function (Blueprint $table) {
        $table->increments('id_cargo');
        $table->string('nombre_cargo', 100);
        $table->string('descripcion', 200)->nullable();
        $table->string('estado', 1)->default('A');
        $table->timestamps(); // created_at y updated_at
      });

      DB::table('cargo')
      ->insert(array('id_cargo' => '1', 'nombre_cargo' => 'Maestro Primaria', 'descripcion' => 'Maestro de Primaria'));
      
      DB::table('cargo')
      ->insert(array('id_cargo' => '2', 'nombre_cargo' => 'Maestro Secundaria', 'descripcion' => 'Maestro de secundaria'));
      
      DB::table('cargo')
      ->insert(array('id_cargo' => '3', 'nombre_cargo' => 'Psicólogo ', 'descripcion' => 'Empleado encargado de la salud mental'));

      DB::table('cargo')
      ->insert(array('id_cargo' => '4', 'nombre_cargo' => 'Recursos Humanos', 'descripcion' => 'Empleado de recursos humanos'));

      DB::table('cargo')
      ->insert(array('id_cargo' => '5', 'nombre_cargo' => 'Contabilidad', 'descripcion' => 'Empleado encargado de la contabilidad'));

      DB::table('cargo')
      ->insert(array('id_cargo' => '6', 'nombre_cargo' => 'Seguridad', 'descripcion' => 'Empleado encargado de la seguridad'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('cargo');
     }
}
