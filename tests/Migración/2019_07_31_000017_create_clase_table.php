<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaseTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'clase';

    /**
     * Run the migrations.
     * @table clase
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_clase');
            $table->integer('id_maestro_encargado');
            $table->integer('id_aula');
            $table->integer('id_asignatura');
            $table->string('seccion', 100);
            $table->string('hora_inicio', 5);
            $table->string('hora_fin', 5);
            $table->string('observacion')->nullable();
            $table->string('estado', 1);

            $table->index(["id_maestro_encargado"], 'fk_clase_empleado1_idx');

            $table->index(["id_aula"], 'fk_clase_aula1_idx');

            $table->index(["id_asignatura"], 'fk_clase_asignatura1_idx');


            $table->foreign('id_maestro_encargado', 'fk_clase_empleado1_idx')
                ->references('id_empleado')->on('empleado')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_aula', 'fk_clase_aula1_idx')
                ->references('id_aula')->on('aula')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_asignatura', 'fk_clase_asignatura1_idx')
                ->references('id_asignatura')->on('asignatura')
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
