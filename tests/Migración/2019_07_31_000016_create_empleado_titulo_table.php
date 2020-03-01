<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadoTituloTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'empleado_titulo';

    /**
     * Run the migrations.
     * @table empleado_titulo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id_empleado');
            $table->integer('id_titulo');

            $table->index(["id_empleado"], 'fk_empleado_has_titulo_empleado1_idx');

            $table->index(["id_titulo"], 'fk_empleado_has_titulo_titulo1_idx');


            $table->foreign('id_empleado', 'fk_empleado_has_titulo_empleado1_idx')
                ->references('id_empleado')->on('empleado')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_titulo', 'fk_empleado_has_titulo_titulo1_idx')
                ->references('id_titulo')->on('titulo')
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
