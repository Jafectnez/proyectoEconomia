<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCrearRegistroUserAlumno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      DB::unprepared('
        CREATE TRIGGER `TRG_CREAR_REGISTRO_USER_ALUMNO` 
        BEFORE INSERT ON `alumno` 
        FOR EACH ROW 
          INSERT INTO users(id_tipo_usuario, id_persona, usuario, password, estado) 
          VALUES(NEW.ID_TIPO_USUARIO, NEW.ID_PERSONA, NEW.CODIGO_ALUMNO, NEW.CONTRASENA, NEW.ESTADO);
      ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::unprepared('DROP TRIGGER `TRG_CREAR_REGISTRO_USER_ALUMNO`');
    }
}
