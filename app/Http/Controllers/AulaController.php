<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Aula;
use App\Edificio;

class AulaController extends Controller
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
            $aula = Aula::join('edificio', 'aula.id_edificio', '=', 'edificio.id_edificio')
            ->select(
                'aula.id_aula',
                'aula.nombre_aula',
                'aula.codigo_aula',
                'aula.estado',
                'edificio.id_edificio',
                'edificio.nombre_edificio',
                'edificio.codigo_edificio'
            )
            ->orderBy('aula.id_aula', 'desc')
            ->paginate(10);
        } else {
            // Usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
            // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
            $aula = Aula::join('edificio', 'aula.id_edificio', '=', 'edificio.id_edificio')
            ->select(
                'aula.id_aula',
                'aula.nombre_aula',
                'aula.codigo_aula',
                'aula.estado',
                'edificio.id_edificio',
                'edificio.nombre_edificio',
                'edificio.codigo_edificio'
            )
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('aula.id_aula', 'desc')
            ->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $aula->total(),
                'current_page' => $aula->currentPage(),
                'per_page'     => $aula->perPage(),
                'last_page'    => $aula->lastPage(),
                'from'         => $aula->firstItem(),
                'to'           => $aula->lastItem(),
            ],
            'aula' => $aula
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
            //Validaciones
            $reglasAula = [
                'nombre_aula' => 'required',
                'codigo_aula' => 'unique:aula,codigo_aula'
            ];

            $erroresAula = [
                'nombre_aula.required' => 'El nombre del aula no debe ir vacío.',
                'codigo_aula.unique' => 'El código del aula ya está registrado.'
            ];

            //Se validan los datos recibidos
            $this->validate($request, $reglasAula, $erroresAula);

            // Instancia de la clase Aula, la clase aula es el modelo Aula
            $aula = new Aula();
            
            $aula->id_edificio = $request->id_edificio;
            $aula->nombre_aula = $request->nombre_aula;
            $aula->codigo_aula = $request->codigo_aula;
            $aula->estado = 'A'; // A: Activo

            $aula->save();
            
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

            // Instancia de la clase Aula, la clase aula es el modelo Aula
            $aula = Aula::findOrFail($request->id_aula);
            
            $aula->id_edificio = $request->id_edificio;
            $aula->nombre_aula = $request->nombre_aula;
            $aula->codigo_aula = $request->codigo_aula;
            $aula->save();

            DB::commit();
        
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function desactivar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        $aula = Aula::findOrFail($request->id_aula);

        $aula->estado = 'I'; // I: Inactivo
        
        $aula->save();
    }

    public function activar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');
        
        $aula = Aula::findOrFail($request->id_aula);

        $aula->estado = 'A'; // A: Activo
        
        $aula->save();
    }

    public function selectAula(Request $request){
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');
      
      $aula = Aula::join('edificio', 'aula.id_edificio', '=', 'edificio.id_edificio')
      ->select(
          'aula.id_aula',
          'aula.nombre_aula',
          'aula.codigo_aula',
          'aula.estado',

          'edificio.id_edificio',
          'edificio.nombre_edificio',
          'edificio.codigo_edificio')
      ->orderBy('aula.id_aula', 'desc')
      ->get();

      return ['aula' => $aula];
    }
}
