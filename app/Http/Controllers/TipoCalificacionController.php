<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoCalificacion;

class TipoCalificacionController extends Controller {

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
        $tipoCalificacion = TipoCalificacion::orderBy('id_tipo_calificacion', 'desc')->paginate(10);
      }
      else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $tipoCalificacion = TipoCalificacion::where($criterio, 'like', '%'. $buscar . '%')
        ->orderBy('id_tipo_calificacion', 'desc')
        ->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $tipoCalificacion->total(), // Indica el total de registros
              'current_page' => $tipoCalificacion->currentPage(),
              'per_page'     => $tipoCalificacion->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $tipoCalificacion->lastPage(), // Registros por página
              'from'         => $tipoCalificacion->firstItem(), // Primera página
              'to'           => $tipoCalificacion->lastItem(), // Cuantos registros se están viasualizando
          ],
          'tipoCalificacion' => $tipoCalificacion
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

      // Instancia de la clase TipoCalificacion, la clase TipoCalificacion es el modelo TipoCalificacion
      $tipoCalificacion = new TipoCalificacion();
      
      $tipoCalificacion->tipo_calificacion = $request->tipo_calificacion;

      $tipoCalificacion->save();
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

      $tipoCalificacion = TipoCalificacion::findOrFail($request->id_tipo_calificacion);

      $tipoCalificacion->tipo_calificacion = $request->tipo_calificacion;

      $tipoCalificacion->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $tipoCalificacion = TipoCalificacion::findOrFail($request->id_tipo_calificacion);

      $tipoCalificacion->estado = 'I'; // I: Inactivo
      
      $tipoCalificacion->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $tipoCalificacion = TipoCalificacion::findOrFail($request->id_tipo_calificacion);

      $tipoCalificacion->estado = 'A'; // A: Activo
      
      $tipoCalificacion->save();
    }

    public function selectTipoCalificacion(){

      $tipoCalificacion = TipoCalificacion::select('id_tipo_calificacion', 'tipo_calificacion')
      ->orderBy('id_tipo_calificacion', 'asc')
      ->get(); // Con el método get se obtiene toda la lista de los tipos de Calificacion 

      // Se retornan todos las calificaciones
      return ['tipoCalificacion' => $tipoCalificacion]; 
    }

}
