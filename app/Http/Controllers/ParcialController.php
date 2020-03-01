<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parcial;

class ParcialController extends Controller {

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
        $parcial = Parcial::orderBy('id_parcial', 'desc')->paginate(10);
      }
      else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $parcial = Parcial::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_parcial', 'desc')->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $parcial->total(), // Indica el total de registros
              'current_page' => $parcial->currentPage(),
              'per_page'     => $parcial->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $parcial->lastPage(), // Registros por página
              'from'         => $parcial->firstItem(), // Primera página
              'to'           => $parcial->lastItem(), // Cuantos registros se están viasualizando
          ],
          'parcial' => $parcial
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
      //Reglas y mensajes de errores para validación de persona
      $reglasPersona = [
        'nombre_parcial' => 'required|max:50|unique:parcial,nombre_parcial'
      ];

      $erroresPersona = [
        'nombre_parcial.required' => 'El nombre no debe ir vacío.',
        'nombre_parcial.unique' => 'Hay un parcial registrado con éste nombre.',
      ];

      //Se validan los datos recibidos
      $this->validate($request, $reglasPersona, $erroresPersona);

      // Instancia de la clase Parcial, la clase Parcial es el modelo Parcial
      $parcial = new Parcial();
      
      $parcial->nombre_parcial = $request->nombre_parcial;

      $parcial->save();
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

      $parcial = Parcial::findOrFail($request->id_parcial);

      $parcial->nombre_parcial = $request->nombre_parcial;

      $parcial->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $parcial = Parcial::findOrFail($request->id_parcial);

      $parcial->estado = 'I'; // I: Inactivo
      
      $parcial->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $parcial = Parcial::findOrFail($request->id_parcial);

      $parcial->estado = 'A'; // A: Activo
      
      $parcial->save();
    }

    public function selectParcial(){

      $parcial = Parcial::select('id_parcial', 'nombre_parcial')
      ->orderBy('id_parcial', 'asc')
      ->get(); // Con el método get se obtiene toda la lista de los parciales

      // Se retornan todos los parciales
      return ['parcial' => $parcial]; 
    }

}
