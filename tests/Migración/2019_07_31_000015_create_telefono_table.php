<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefonoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'telefono';

    /**
     * Run the migrations.
     * @table telefono
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_telefono');
            $table->integer('id_persona');
            $table->string('telefono', 20);

            $table->index(["id_persona"], 'fk_table1_persona1_idx');


            $table->foreign('id_persona', 'fk_table1_persona1_idx')
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
