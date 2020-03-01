<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAulaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aula', function (Blueprint $table) {
            $table->increments('id_aula');
            $table->integer('id_edificio')->unsigned();
            $table->string('nombre_aula', 100);
            $table->string('codigo_aula', 100)->nullable();
            $table->string('estado', 1);
            $table->timestamps(); // created_at y updated_at

            $table->foreign('id_edificio')->references('id_edificio')->on('edificio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aula');
    }
}
