<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefonoTable extends Migration
{

    /**
     * Run the migrations.
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * unsigned() - llave foranea
     *
     * @return void
     */
    public function up() {
      Schema::create('telefono', function (Blueprint $table) {
        $table->increments('id_telefono');
        $table->integer('id_persona')->unsigned();
        $table->string('telefono', 20)->unique();
        $table->timestamps(); // created_at y updated_at

        $table->foreign('id_persona')->references('id_persona')->on('persona');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('telefono');
     }
}
