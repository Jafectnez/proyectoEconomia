<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerActualizarRegistroUserEmpleado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      DB::unprepared('
        CREATE TRIGGER `TRG_ACTUALIZAR_REGISTRO_USER_EMPLEADO` 
        AFTER UPDATE ON `empleado` 
        FOR EACH ROW 
          UPDATE users
          SET 
            `id_tipo_usuario` = NEW.ID_TIPO_USUARIO,
            `usuario` = NEW.USUARIO, 
            `password` = NEW.CONTRASENA, 
            `estado` = NEW.ESTADO
          WHERE users.usuario = OLD.USUARIO;
      ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::unprepared('DROP TRIGGER `TRG_ACTUALIZAR_REGISTRO_USER_EMPLEADO`');
    }
}
