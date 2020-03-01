<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Archivo;

class ArchivoController extends Controller {

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
        $archivo = Archivo::orderBy('id_archivo', 'desc')->paginate(10);
      } else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $archivo = Archivo::where($criterio, 'like', '%'. $buscar . '%')
        ->selesct('nombre_archiv',
        'fecha_subido',
        'url_archivo')
        ->orderBy('id_archivo', 'desc')
        ->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $archivo->total(), // Indica el total de registros
              'current_page' => $archivo->currentPage(),
              'per_page'     => $archivo->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $archivo->lastPage(), // Registros por página
              'from'         => $archivo->firstItem(), // Primera página
              'to'           => $archivo->lastItem(), // Cuantos registros se están viasualizando
          ],
          'archivo' => $archivo
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

      // Instancia de la clase Archivo, la clase Archivo es el modelo Archivo
      $archivo = new Archivo();
      
      $archivo->nombre_archivo = $request->nombre_archivo;
      $archivo->fecha_subido = date("Y").'-'.date("m").'-'.date("d"); // $request->fecha_subido;
      $archivo->url_archivo = "url.com"; // $request->url_archivo;

      $archivo->save();
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

      $archivo = Archivo::findOrFail($request->id_archivo);

      $archivo->nombre_archivo = $request->nombre_archivo;
      $archivo->fecha_subido = date("Y").'-'.date("m").'-'.date("d"); //$request->fecha_subido;
      $archivo->url_archivo = "url.com"; // $request->url_archivo;

      $archivo->save();
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $archivo = Archivo::findOrFail($request->id_archivo);

      $archivo->estado = 'I'; // I: Inactivo
      
      $archivo->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $archivo = Archivo::findOrFail($request->id_archivo);

      $archivo->estado = 'A'; // A: Activo
      
      $archivo->save();
    }

    public function selectTipo(){

      $archivo = Archivo::where('estado', '=', 'A')
      ->select('id_archivo', 'nombre_archivo')
      ->orderBy('id_archivo', 'asc')
      ->get(); // Con el método get se obtiene toda la lista de los Archivos 

      // Se retornan todos los Archivos cuyo estado sea 'A'
      return ['archivo' => $archivo]; 
    }

}
