<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoTable extends Migration {
    /**
     * Run the migrations.
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * @return void
     */

    public function up() {
        Schema::create('archivo', function (Blueprint $table) {
            $table->increments('id_archivo');
            $table->string('nombre_archivo', 100);
            $table->date('fecha_subido');
            $table->string('url_archivo', 200);
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
       Schema::dropIfExists('archivo');
     }
}
