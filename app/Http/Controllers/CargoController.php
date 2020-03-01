<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cargo;

class CargoController extends Controller {

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
        $cargo = Cargo::orderBy('id_cargo', 'desc')->paginate(10);
      }
      else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $cargo = Cargo::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_cargo', 'desc')->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $cargo->total(), // Indica el total de registros
              'current_page' => $cargo->currentPage(),
              'per_page'     => $cargo->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $cargo->lastPage(), // Registros por página
              'from'         => $cargo->firstItem(), // Primera página
              'to'           => $cargo->lastItem(), // Cuantos registros se están viasualizando
          ],
          'cargo' => $cargo
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

      // Instancia de la clase Cargo, la clase Cargo es el modelo Cargo
      $cargo = new Cargo();
      
      $cargo->nombre_cargo = $request->nombre_cargo;
      // $cargo->descripcion_cargo = $request->descripcion_cargo;

      $cargo->save();
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

      $cargo = Cargo::findOrFail($request->id_cargo);

      $cargo->nombre_cargo = $request->nombre_cargo;
      // $cargo->descripcion_cargo = $request->descripcion_cargo;

      $cargo->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $cargo = Cargo::findOrFail($request->id_cargo);

      $cargo->estado = 'I'; // I: Inactivo
      
      $cargo->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $cargo = Cargo::findOrFail($request->id_cargo);

      $cargo->estado = 'A'; // A: Activo
      
      $cargo->save();
    }

    public function selectCargo(){

      $cargo = Cargo::where('estado', '=', 'A')
      ->select('id_cargo', 'nombre_cargo')
      ->orderBy('id_cargo', 'asc')
      ->get(); // Con el método get se obtiene toda la lista de los cargos de Usuario 

      // Se retornan todos los cargos de usuario cuyo estado sea 'A'
      return ['cargo' => $cargo]; 
    }

}
