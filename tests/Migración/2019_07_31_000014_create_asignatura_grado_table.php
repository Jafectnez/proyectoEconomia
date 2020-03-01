<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignaturaGradoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'asignatura_grado';

    /**
     * Run the migrations.
     * @table asignatura_grado
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id_grado');
            $table->integer('id_asignatura');

            $table->index(["id_grado"], 'fk_grado_has_asignatura_grado1_idx');

            $table->index(["id_asignatura"], 'fk_grado_has_asignatura_asignatura1_idx');


            $table->foreign('id_grado', 'fk_grado_has_asignatura_grado1_idx')
                ->references('id_grado')->on('grado')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_asignatura', 'fk_grado_has_asignatura_asignatura1_idx')
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
