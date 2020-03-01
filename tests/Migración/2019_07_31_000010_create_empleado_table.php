<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'empleado';

    /**
     * Run the migrations.
     * @table empleado
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_empleado');
            $table->integer('id_persona');
            $table->integer('id_tipo_usuario');
            $table->integer('id_cargo');
            $table->date('fecha_ingreso');
            $table->string('usuario', 50);
            $table->string('contrasena', 200);
            $table->string('foto_url')->nullable();
            $table->timestamp('sesion')->nullable();
            $table->string('estado', 1);

            $table->index(["id_cargo"], 'fk_empleado_cargo1_idx');

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

            $table->foreign('id_cargo', 'fk_empleado_cargo1_idx')
                ->references('id_cargo')->on('cargo')
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
