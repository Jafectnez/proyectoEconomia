<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAulaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'aula';

    /**
     * Run the migrations.
     * @table aula
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_aula');
            $table->integer('id_edificio');
            $table->string('nombre_aula', 100);
            $table->string('codigo_aula', 100)->nullable();

            $table->index(["id_edificio"], 'fk_aula_edificio1_idx');


            $table->foreign('id_edificio', 'fk_aula_edificio1_idx')
                ->references('id_edificio')->on('edificio')
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
