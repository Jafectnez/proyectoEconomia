<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadoTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * unsigned() - llave foranea
     *
     * @return void
     */
    public function up(){
      Schema::create('empleado', function (Blueprint $table) {
        $table->increments('id_empleado'); // Llave Primaria
        $table->integer('id_persona')->unsigned(); // Llave Foranea que se indica con unsigned
        $table->integer('id_tipo_usuario')->unsigned(); // Llave Foranea que se indica con unsigned
        $table->integer('id_cargo')->unsigned(); // Llave Foranea que se indica con unsigned
        $table->date('fecha_ingreso');
        $table->string('usuario', 50)->unique();
        $table->string('contrasena', 200);
        $table->string('foto_url')->nullable();
        $table->timestamp('sesion')->nullable();
        $table->string('estado', 1); // 'A': Activo, 'I': Inactivo
        $table->timestamps(); // created_at y updated_at

        // Llaves Foraneas:
        $table->foreign('id_persona')->references('id_persona')->on('persona');
        $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuario');
        $table->foreign('id_cargo')->references('id_cargo')->on('cargo');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('empleado');
     }
}
