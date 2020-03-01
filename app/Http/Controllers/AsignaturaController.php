<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Asignatura_Grado;
use App\Asignatura;

class AsignaturaController extends Controller
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
            $asignatura = Asignatura::join('asignatura_grado', 'asignatura.id_asignatura', '=', 'asignatura_grado.id_asignatura')
            ->join('grado', 'asignatura_grado.id_grado', '=', 'grado.id_grado')
            ->select(
                'asignatura.id_asignatura',
                'asignatura.nombre_asignatura',
                'asignatura.codigo_asignatura',
                'asignatura.dias',
                'asignatura.estado',
                
                'asignatura_grado.id',

                'grado.id_grado',
                'grado.nombre_grado',
                'grado.codigo_grado')
            ->orderBy('asignatura.id_asignatura', 'desc')
            ->paginate(10);
        } else {
            // Usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
            // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
            $asignatura = Asignatura::join('asignatura_grado', 'asignatura.id_asignatura', '=', 'asignatura_grado.id_asignatura')
            ->join('grado', 'asignatura_grado.id_grado', '=', 'grado.id_grado')
            ->select(
                'asignatura.id_asignatura',
                'asignatura.nombre_asignatura',
                'asignatura.codigo_asignatura',
                'asignatura.dias',
                'asignatura.estado',

                'asignatura_grado.id',

                'grado.id_grado',
                'grado.nombre_grado',
                'grado.codigo_grado'
            )
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('asignatura.id_asignatura', 'desc')
            ->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $asignatura->total(),
                'current_page' => $asignatura->currentPage(),
                'per_page'     => $asignatura->perPage(),
                'last_page'    => $asignatura->lastPage(),
                'from'         => $asignatura->firstItem(),
                'to'           => $asignatura->lastItem(),
            ],
            'asignatura' => $asignatura
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
            // Instancia de la clase Asignatura, la clase asignatura es el modelo Asignatura
            $asignatura = new Asignatura();
            
            $asignatura->nombre_asignatura = $request->nombre_asignatura;
            $asignatura->codigo_asignatura = $request->codigo_asignatura;

            $dias = "";
            foreach ($request->dias as $key => $value) {
                $dias = $dias . $value;
            }

            $asignatura->dias = $dias;
            $asignatura->estado = 'A'; // A: Activo

            $asignatura->save();

            $relacion = new Asignatura_Grado();

            $relacion->id_grado = $request->id_grado;
            $relacion->id_asignatura = $asignatura->id_asignatura;

            $relacion->save();
            
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

            // Instancia de la clase Asignatura, la clase asignatura es el modelo Asignatura

            $relacion = Asignatura_Grado::findOrFail($request->id_relacion);
            
            $relacion->id_grado = $request->id_grado;

            $relacion->save();

            $asignatura = Asignatura::findOrFail($request->id_asignatura);
            
            $asignatura->nombre_asignatura = $request->nombre_asignatura;
            $asignatura->codigo_asignatura = $request->codigo_asignatura;

            $dias = "";
            foreach ($request->dias as $key => $value) {
                $dias = $dias . $value;
            }

            $asignatura->dias = $dias;

            $asignatura->save();

            DB::commit();
        
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function desactivar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        $asignatura = Asignatura::findOrFail($request->id_asignatura);

        $asignatura->estado = 'I'; // I: Inactivo
        
        $asignatura->save();
    }

    public function activar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');
        
        $asignatura = Asignatura::findOrFail($request->id_asignatura);

        $asignatura->estado = 'A'; // A: Activo
        
        $asignatura->save();
    }

    public function selectAsignatura(Request $request){
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $asignatura = Asignatura::select(
          'id_asignatura',
          'nombre_asignatura',
          'codigo_asignatura',
          'dias',
          'estado')
      ->orderBy('id_asignatura', 'desc')
      ->get();
      
      return ['asignatura' => $asignatura]; 
    }
}
