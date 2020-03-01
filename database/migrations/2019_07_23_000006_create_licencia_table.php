<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('licencia', function (Blueprint $table) {
        $table->increments('id_licencia');
        $table->string('numero_licencia', 255);
        $table->date('fecha_vencimiento');
        $table->string('estado', 1); // 'A': Activo, 'I': Inactivo
      });

      DB::table('licencia')
        ->insert(array('id_licencia' => '1', 'numero_licencia' => 'asd.456', 'fecha_vencimiento' => '2019/12/12', 'estado' => 'A'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('licencia');
    }
}
