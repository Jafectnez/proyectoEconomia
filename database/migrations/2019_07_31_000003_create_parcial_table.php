<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcialTable extends Migration {
    /**
     * Run the migrations.
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles)
     * timestamps() - agregar created_at: la fecha en que se inserta el registro, 
     * updated_at: la fecha en que se actualiza el registro
     * @return void
     */

    public function up() {
        Schema::create('parcial', function (Blueprint $table) {
            $table->increments('id_parcial');
            $table->string('nombre_parcial', 45);
            $table->timestamps(); // created_at y updated_at
        });

        DB::table('parcial')
          ->insert(array('id_parcial' => 1, 'nombre_parcial' => 'Parcial I'));
        
        DB::table('parcial')
        ->insert(array('id_parcial' => 2, 'nombre_parcial' => 'Parcial II'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('parcial');
     }
}
