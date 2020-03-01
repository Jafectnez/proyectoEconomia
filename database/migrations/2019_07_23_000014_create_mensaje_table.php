<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensajeTable extends Migration
{

    /**
     * Run the migrations.
     * @table mensaje
     * nullable() - el campo pueda ser nulo (por default es: No Nulo)
     * unique() - el campo es unico (default: campos repetibles) 
     * @return void
     */
    public function up()
    {
        Schema::create('mensaje', function (Blueprint $table) {
            $table->increments('id_mensaje');
            
            $table->integer('id_emisor')->unsigned();
            $table->integer('id_receptor')->unsigned();
            $table->string('mensaje', 250);
            $table->datetime('fecha_envio');
            $table->string('estado', 1); // 'L':Leido, 'E':Enviado
            
            $table->timestamps();

            $table->foreign('id_emisor')->references('id_persona')->on('persona');
            $table->foreign('id_receptor')->references('id_persona')->on('persona');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('mensaje');
     }
}
