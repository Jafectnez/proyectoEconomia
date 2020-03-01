<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * @return void
     */
    public function up() {
      Schema::create("tipo_usuario", function (Blueprint $table) {
        $table->increments('id_tipo_usuario');
        $table->string('tipo_usuario', 100);
        $table->string('descripcion', 200)->nullable();
        $table->string('estado', 1)->default('A');
        $table->timestamps(); // created_at y updated_at
      });

      DB::table('tipo_usuario')
      ->insert(array('id_tipo_usuario' => '1', 'tipo_usuario' => 'Administrador', 'descripcion' => 'Usuarios Adminstradores'));
      
      DB::table('tipo_usuario')
      ->insert(array('id_tipo_usuario' => '2', 'tipo_usuario' => 'Empleado Administrador', 'descripcion' => 'Usuarios de Recursos Humanos'));
      
      DB::table('tipo_usuario')
      ->insert(array('id_tipo_usuario' => '3', 'tipo_usuario' => 'Maestro', 'descripcion' => 'Usuarios Maestros'));

      DB::table('tipo_usuario')
      ->insert(array('id_tipo_usuario' => '4', 'tipo_usuario' => 'Alumno', 'descripcion' => 'Usuarios Alumnos'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists("tipo_usuario");
     }
}
