<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Grado;

class GradoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listar todos los registros de la tabla Alumno
     */
    public function index(Request $request){
        // Determina si la petición que se hace es diferente de ajax
        // De ser lo contrario, se va a a redirigir a la ruta raiz
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar == '') {
            $grado = Grado::orderBy('id_grado', 'desc')->paginate(10);
        } else {
            // Usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
            // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
            $grado = Grado::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_grado', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $grado->total(),
                'current_page' => $grado->currentPage(),
                'per_page'     => $grado->perPage(),
                'last_page'    => $grado->lastPage(),
                'from'         => $grado->firstItem(),
                'to'           => $grado->lastItem(),
            ],
            'grado' => $grado
        ];
    }

    /**
     * Guarda un registro nuevo en la base
     */
    public function store(Request $request){
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            // Instancia de la clase Grado, la clase grado es el modelo Grado
            $grado = new Grado();
            
            $grado->nombre_grado = $request->nombre_grado;
            $grado->codigo_grado = $request->codigo_grado;
            $grado->cantidad_asignatura = 0;
            $grado->estado = 'A'; // A: Activo

            $grado->save();
            
            DB::commit();

        }catch(Exception $e){
            DB::rollback();
        }
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

        try{
            DB::beginTransaction();

            // Instancia de la clase Grado, la clase grado es el modelo Grado
            $grado = Grado::findOrFail($request->id_grado);
            
            $grado->nombre_grado = $request->nombre_grado;
            $grado->codigo_grado = $request->codigo_grado;
            $grado->cantidad_asignatura = $request->cantidad_asignatura;
            $grado->save();

            DB::commit();
        
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function desactivar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        $grado = Grado::findOrFail($request->id_grado);

        $grado->estado = 'I'; // I: Inactivo
        
        $grado->save();
    }

    public function activar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');
        
        $grado = Grado::findOrFail($request->id_grado);

        $grado->estado = 'A'; // A: Activo
        
        $grado->save();
    }

    public function selectGrado(Request $request){
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $grado = Grado::select(
          'id_grado',
          'nombre_grado',
          'codigo_grado',
          'cantidad_asignatura',
          'estado')
      ->orderBy('id_grado', 'desc')
      ->get();

      return ['grado' => $grado];
    }
}
