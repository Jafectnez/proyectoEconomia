<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
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
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('id_persona');
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('numero_identidad', 13)->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('correo_electronico', 100)->unique();
            $table->string('sexo', 1)->nullable(); // 'M': Masculino, 'F': Femenino
            $table->string('direccion')->nullable();
            $table->string('estado', 1); // 'A': Activo, 'I': Inactivo
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('persona');
     }
}
