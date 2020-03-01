<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoGradoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'alumno_grado';

    /**
     * Run the migrations.
     * @table alumno_grado
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id_grado');
            $table->integer('id_alumno');
            $table->date('anio_estudio')->nullable();

            $table->index(["id_alumno"], 'fk_grado_has_alumno_alumno1_idx');

            $table->index(["id_grado"], 'fk_grado_has_alumno_grado1_idx');


            $table->foreign('id_grado', 'fk_grado_has_alumno_grado1_idx')
                ->references('id_grado')->on('grado')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_alumno', 'fk_grado_has_alumno_alumno1_idx')
                ->references('id_alumno')->on('alumno')
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
