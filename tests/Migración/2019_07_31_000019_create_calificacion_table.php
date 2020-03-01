<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalificacionTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'calificacion';

    /**
     * Run the migrations.
     * @table calificacion
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_calificacion');
            $table->integer('id_clase');
            $table->integer('id_alumno');
            $table->integer('id_parcial');
            $table->integer('id_tipo_calificacion');
            $table->integer('id_archivo_tarea')->nullable();
            $table->integer('nota');
            $table->string('observacion');

            $table->index(["id_tipo_calificacion"], 'fk_calificacion_tipo_calificacion1_idx');

            $table->index(["id_alumno"], 'fk_nota_alumno1_idx');

            $table->index(["id_parcial"], 'fk_nota_parcial1_idx');

            $table->index(["id_clase"], 'fk_nota_clase1_idx');

            $table->index(["id_archivo_tarea"], 'fk_calificacion_archivo1_idx');


            $table->foreign('id_alumno', 'fk_nota_alumno1_idx')
                ->references('id_alumno')->on('alumno')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_clase', 'fk_nota_clase1_idx')
                ->references('id_clase')->on('clase')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_parcial', 'fk_nota_parcial1_idx')
                ->references('id_parcial')->on('parcial')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_tipo_calificacion', 'fk_calificacion_tipo_calificacion1_idx')
                ->references('id_tipo_calificacion')->on('tipo_calificacion')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_archivo_tarea', 'fk_calificacion_archivo1_idx')
                ->references('id_archivo')->on('archivo')
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
