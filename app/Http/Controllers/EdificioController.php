<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Edificio;

class EdificioController extends Controller
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
            $edificio = Edificio::orderBy('id_edificio', 'desc')->paginate(10);
        } else {
            // Usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
            // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
            $edificio = Edificio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_edificio', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $edificio->total(),
                'current_page' => $edificio->currentPage(),
                'per_page'     => $edificio->perPage(),
                'last_page'    => $edificio->lastPage(),
                'from'         => $edificio->firstItem(),
                'to'           => $edificio->lastItem(),
            ],
            'edificio' => $edificio
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
            // Instancia de la clase Edificio, la clase edificio es el modelo Edificio
            $edificio = new Edificio();
            
            $edificio->nombre_edificio = $request->nombre_edificio;
            $edificio->codigo_edificio = $request->codigo_edificio;
            $edificio->estado = 'A'; // A: Activo

            $edificio->save();
            
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

            // Instancia de la clase Edificio, la clase edificio es el modelo Edificio
            $edificio = Edificio::findOrFail($request->id_edificio);
            
            $edificio->nombre_edificio = $request->nombre_edificio;
            $edificio->codigo_edificio = $request->codigo_edificio;
            $edificio->save();

            DB::commit();
        
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function desactivar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        $edificio = Edificio::findOrFail($request->id_edificio);

        $edificio->estado = 'I'; // I: Inactivo
        
        $edificio->save();
    }

    public function activar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');
        
        $edificio = Edificio::findOrFail($request->id_edificio);

        $edificio->estado = 'A'; // A: Activo
        
        $edificio->save();
    }
}
