<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'alumno';

    /**
     * Run the migrations.
     * @table alumno
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_alumno');
            $table->integer('id_persona');
            $table->integer('id_tipo_usuario');
            $table->string('codigo_alumno', 50);
            $table->string('contrasena', 200);
            $table->integer('promedio');
            $table->date('fecha_ingreso');
            $table->string('foto_url')->nullable();
            $table->timestamp('sesion')->nullable();
            $table->string('estado', 1);

            $table->index(["id_tipo_usuario"], 'fk_empleado_tipo_usuario1_idx');

            $table->index(["id_persona"], 'fk_empleado_persona_idx');


            $table->foreign('id_persona', 'fk_empleado_persona_idx')
                ->references('id_persona')->on('persona')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_tipo_usuario', 'fk_empleado_tipo_usuario1_idx')
                ->references('id_tipo_usuario')->on('tipo_usuario')
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
