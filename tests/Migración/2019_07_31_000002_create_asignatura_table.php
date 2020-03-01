<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignaturaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'asignatura';

    /**
     * Run the migrations.
     * @table asignatura
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_asignatura');
            $table->string('nombre_asignatura', 100);
            $table->string('dias', 200);
            $table->string('codigo_asignatura', 100)->nullable();
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
