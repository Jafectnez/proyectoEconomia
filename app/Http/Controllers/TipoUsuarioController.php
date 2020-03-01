<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoUsuario;

class TipoUsuarioController extends Controller {

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == ''){
        // Los registro recien insertados se muestran al inicio (desc)
        $tipoUsuario = TipoUsuario::orderBy('id_tipo_usuario', 'desc')->paginate(10);
      }
      else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $tipoUsuario = TipoUsuario::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_tipo_usuario', 'desc')->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $tipoUsuario->total(), // Indica el total de registros
              'current_page' => $tipoUsuario->currentPage(),
              'per_page'     => $tipoUsuario->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $tipoUsuario->lastPage(), // Registros por página
              'from'         => $tipoUsuario->firstItem(), // Primera página
              'to'           => $tipoUsuario->lastItem(), // Cuantos registros se están viasualizando
          ],
          'tipoUsuario' => $tipoUsuario
      ];
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      // Instancia de la clase TipoUsuario, la clase TipoUsuario es el modelo TipoUsuario
      $tipoUsuario = new TipoUsuario();
      
      $tipoUsuario->tipo_usuario = $request->tipo_usuario;

      $tipoUsuario->save();
    }

    /**
     * Update the specified resource in storage.
     * update Realiza una comproabación antes de salvar los datos mediante el método find
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

      $tipoUsuario = TipoUsuario::findOrFail($request->id_tipo_usuario);

      $tipoUsuario->tipo_usuario = $request->tipo_usuario;

      $tipoUsuario->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $tipoUsuario = TipoUsuario::findOrFail($request->id_tipo_usuario);

      $tipoUsuario->estado = 'I'; // I: Inactivo
      
      $tipoUsuario->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $tipoUsuario = TipoUsuario::findOrFail($request->id_tipo_usuario);

      $tipoUsuario->estado = 'A'; // A: Activo
      
      $tipoUsuario->save();
    }

    public function selectTipo(){

      $tipoUsuario = TipoUsuario::where('estado', '=', 'A')
      ->select('id_tipo_usuario', 'tipo_usuario')
      ->orderBy('id_tipo_usuario', 'asc')
      ->get(); // Con el método get se obtiene toda la lista de los tipos de Usuario 

      // Se retornan todos los tipos de usuario cuyo estado sea 'A'
      return ['tipoUsuario' => $tipoUsuario]; 
    }

}
