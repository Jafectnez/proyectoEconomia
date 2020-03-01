<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Notifications\Notification;

use App\Mensaje;
use App\Persona;
use App\User;
use Auth;

use App\Notifications\NuevoMensaje;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listar todos los registros de la tabla mensaje
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(Request $request){
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

        // Se obtendran todos los mensajes que el usuario a enviado o recibido
        $user = $request->user();

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar == '') {
          $mensajesRecibidos = DB::table('mensaje')->join('persona', 'persona.id_persona', '=', 'mensaje.id_emisor')
          ->select(
            'mensaje.id_mensaje',
            'mensaje.id_receptor',
            'mensaje.id_emisor',
            'mensaje.mensaje',
            'mensaje.fecha_envio',
            'mensaje.estado',
            'persona.id_persona',
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido')
          ->where('mensaje.id_receptor','=', $user->id_persona)
          ->orderBy('mensaje.fecha_envio', 'asc')
          ->get();

          $mensajesEnviados = DB::table('mensaje')->join('persona', 'persona.id_persona', '=', 'mensaje.id_receptor')
          ->select(
            'mensaje.id_mensaje',
            'mensaje.id_receptor',
            'mensaje.id_emisor',
            'mensaje.mensaje',
            'mensaje.fecha_envio',
            'mensaje.estado',
            'persona.id_persona',
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido')
          ->where('mensaje.id_emisor', '=', $user->id_persona)
          ->orderBy('mensaje.fecha_envio', 'asc')
          ->get();
        }else{
          $persona = Persona::where([[$criterio, 'like', '%'. $buscar .'%'], ['id_persona', '<>', $user->id_persona], ['estado', '=', 'A']])->orderBy('id_persona', 'desc')->get();
          return ['usuario' => $user->id_persona, 
                  'personas' => $persona];
        }

        //Se crea un arreglo de contactos a partir de los mensajes enviados y/o recibidos verificando que no se repitan
        $contactos = array();
        $id_contactos = array();

        foreach ($mensajesRecibidos as $key => $value) {
          if(!in_array($value->id_emisor, $id_contactos)){
            $temporal = array("id_persona" => $value->id_emisor,
                              "primer_nombre" => $value->primer_nombre,
                              "segundo_nombre" => $value->segundo_nombre,
                              "primer_apellido" => $value->primer_apellido,
                              "segundo_apellido" => $value->segundo_apellido);

            array_push($contactos, $temporal);
            array_push($id_contactos, $value->id_emisor);
          }
        }
        
        foreach ($mensajesEnviados as $key => $value) {
          if(!in_array($value->id_receptor, $id_contactos)){
            $temporal = array("id_persona" => $value->id_receptor,
                              "primer_nombre" => $value->primer_nombre,
                              "segundo_nombre" => $value->segundo_nombre,
                              "primer_apellido" => $value->primer_apellido,
                              "segundo_apellido" => $value->segundo_apellido);

            array_push($contactos, $temporal);
            array_push($id_contactos, $value->id_receptor);
          }
        }

        //Se crea un arreglo de mensajes para guardar todos
        $mensajes = array();

        foreach ($mensajesEnviados as $key => $value) {
            array_push($mensajes, $value);
        }

        foreach ($mensajesRecibidos as $key => $value) {
            array_push($mensajes, $value);
        }

        //Ordenamos los mensajes por fecha
        for ($i = 0 ; $i < count($mensajes) - 1 ; $i++) {
          $min = $i;

          //buscamos el de menor fecha de envio
          for ($j = $i + 1 ; $j < count($mensajes) ; $j++) {
            if ($mensajes[$j]->fecha_envio < $mensajes[$min]->fecha_envio) {
              $min = $j;    //encontramos el de menor fecha de envio
            }
          }

          if ($i != $min) {
            //permutamos los valores
            $aux = $mensajes[$i];
            $mensajes[$i] = $mensajes[$min];
            $mensajes[$min] = $aux;
          }
        }

        return ["usuario" => $user->id_persona,
                "mensajes" => $mensajes,
                "contactos" => $contactos];
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $user = Auth::user();

      // Instancia de la clase Mensaje, la clase mensaje es el modelo Mensaje
      try{
        DB::beginTransaction();
        
        $fecha_local = (localtime(time(),true));

        $mensaje = DB::table('mensaje')->insert([
          ['id_emisor' => $user->id_persona,
          'id_receptor' => $request->id_contacto,
          'mensaje' => $request->contenido_mensaje,
          'fecha_envio' => (1900+$fecha_local['tm_year']).'-'.$fecha_local['tm_mon'].'-'.$fecha_local['tm_mday'].' '.$fecha_local['tm_hour'].':'.$fecha_local['tm_min'],
          'estado' => 'E'] // E: Enviado (Se guarda con este estado)
        ]);

        
        //*****Notificaciones******
        //Se buscan los datos del emisor
        $emisor = DB::table('persona')->where('id_persona', '=', $user->id_persona)->get(); 
        $receptor = User::where('users.id_persona', $request->id_contacto)->first();
        
        // Se envia la notificación en el receptor
        $receptor->notify(new NuevoMensaje($emisor[0]));
        // Notification::send($receptor, new NuevoMensaje());
        DB::commit();

      }catch(Exception $e){
        DB::rollback();
      }
      
      $mensajesRecibidos = DB::table('mensaje')->join('persona', 'persona.id_persona', '=', 'mensaje.id_emisor')
      ->select(
        'mensaje.id_mensaje',
        'mensaje.id_receptor',
        'mensaje.id_emisor',
        'mensaje.mensaje',
        'mensaje.fecha_envio',
        'mensaje.estado',
        'persona.id_persona',
        'persona.primer_nombre',
        'persona.segundo_nombre',
        'persona.primer_apellido',
        'persona.segundo_apellido')
      ->where([
              ['mensaje.id_receptor', '=', $user->id_persona], 
              ['mensaje.id_emisor', '=', $request->id_contacto]
            ])
      ->orderBy('mensaje.fecha_envio', 'asc')
      ->get();

      $mensajesEnviados = DB::table('mensaje')->join('persona', 'persona.id_persona', '=', 'mensaje.id_receptor')
          ->select(
            'mensaje.id_mensaje',
            'mensaje.id_receptor',
            'mensaje.id_emisor',
            'mensaje.mensaje',
            'mensaje.fecha_envio',
            'mensaje.estado',
            'persona.id_persona',
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido')
          ->where([
                  ['mensaje.id_emisor', '=', $user->id_persona], 
                  ['mensaje.id_receptor', '=', $request->id_contacto]
                ])
          ->orderBy('mensaje.fecha_envio', 'asc')
          ->get();

      //Se crea un arreglo de mensajes para guardar todos
      $mensajes = array();

      foreach ($mensajesEnviados as $key => $value) {
          array_push($mensajes, $value);
      }

      foreach ($mensajesRecibidos as $key => $value) {
          array_push($mensajes, $value);
      }

      //Ordenamos los mensajes por fecha
      for ($i = 0 ; $i < count($mensajes) - 1 ; $i++) {
        $min = $i;

        //buscamos el de menor fecha de envio
        for ($j = $i + 1 ; $j < count($mensajes) ; $j++) {
          if ($mensajes[$j]->fecha_envio < $mensajes[$min]->fecha_envio) {
            $min = $j;    //encontramos el de menor fecha de envio
          }
        }

        if ($i != $min) {
          //permutamos los valores
          $aux = $mensajes[$i];
          $mensajes[$i] = $mensajes[$min];
          $mensajes[$min] = $aux;
        }
      }

      return ["usuario" => $user->id_persona,
              "mensajes" => $mensajes];
    }
    
    /**
     * Update the specified resource in storage.
     * Realiza una comproabación antes de salvar los datos mediante el método find
     * o el método findOrFail
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $mensaje = Mensaje::findOrFail($request->id_mensaje);

      $mensaje->id_emisor = $request->id_emisor;
      $mensaje->id_receptor = $request->id_receptor;
      $mensaje->mensaje = $request->mensaje; 
      $mensaje->fecha_envio = $request->fecha_envio;
      $mensaje->estado = 'E'; // E: Enviado (Se guarda con este estado)

      $mensaje->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $mensaje = Mensaje::findOrFail($request->id_mensaje);

      $mensaje->estado = 'E'; // E: Enviado
      
      $mensaje->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $mensaje = Mensaje::findOrFail($request->id_mensaje);

      $mensaje->estado = 'L'; // L: Leido
      
      $mensaje->save();
    }

}
