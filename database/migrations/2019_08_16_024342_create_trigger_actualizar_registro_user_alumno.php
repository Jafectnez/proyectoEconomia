<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerActualizarRegistroUserAlumno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      DB::unprepared('
        CREATE TRIGGER `TRG_ACTUALIZAR_REGISTRO_USER_ALUMNO` 
        AFTER UPDATE ON `alumno` 
        FOR EACH ROW 
          UPDATE users
          SET 
            `id_tipo_usuario` = NEW.ID_TIPO_USUARIO,
            `usuario` = NEW.CODIGO_ALUMNO, 
            `password` = NEW.CONTRASENA, 
            `estado` = NEW.ESTADO
          WHERE users.usuario = OLD.CODIGO_ALUMNO;
      ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::unprepared('DROP TRIGGER `TRG_ACTUALIZAR_REGISTRO_USER_ALUMNO`');
    }
}
