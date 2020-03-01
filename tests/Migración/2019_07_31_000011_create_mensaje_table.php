<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensajeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'mensaje';

    /**
     * Run the migrations.
     * @table mensaje
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_mensaje');
            $table->integer('id_emisor');
            $table->integer('id_receptor');
            $table->string('mensaje', 250);
            $table->date('fecha_envio');
            $table->string('estado', 1);

            $table->index(["id_emisor"], 'fk_mensajes_persona1_idx');

            $table->index(["id_receptor"], 'fk_mensajes_persona2_idx');


            $table->foreign('id_emisor', 'fk_mensajes_persona1_idx')
                ->references('id_persona')->on('persona')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_receptor', 'fk_mensajes_persona2_idx')
                ->references('id_persona')->on('persona')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
