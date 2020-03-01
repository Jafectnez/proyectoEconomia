<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Se importa en caso de trabajar con transacciones
use Illuminate\Support\Facades\Auth; // Se importa en caso de trabajar con el usuario logeado
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Clase;
use App\Empleado;
use App\Aula;
use App\Asignatura;
use App\Cargo;
use App\Persona;
use App\Alumno;
use App\Alumno_Clase;
use App\Calificacion;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listar todos los registros de la tabla Clase
     */
    public function index(Request $request){
        // Determina si la petición que se hace es diferente de ajax
        // De ser lo contrario, se va a a redirigir a la ruta raiz
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar == '') {
          $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
          ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
          ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
          ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
          ->select(
              'clase.id_clase',
              'clase.seccion',
              'clase.hora_inicio',
              'clase.hora_fin',
              'clase.anio',
              DB::raw('YEAR(clase.anio) as anio_clase'),
              'clase.observacion',
              'clase.estado',
                            
              'persona.primer_nombre',
              'persona.segundo_nombre',
              'persona.primer_apellido',
              'persona.segundo_apellido',
              'persona.correo_electronico',
              
              'empleado.id_empleado',
              'empleado.foto_url',
              
              'aula.id_aula',
              'aula.nombre_aula',
              'aula.codigo_aula',
              
              'asignatura.id_asignatura',
              'asignatura.nombre_asignatura',
              'asignatura.dias',
              'asignatura.codigo_asignatura')
          ->orderBy('clase.id_clase', 'desc')
          ->paginate(10);
        } else {
          // Usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
          // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
          $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
          ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
          ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
          ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
          ->select(
              'clase.id_clase',
              'clase.seccion',
              'clase.hora_inicio',
              'clase.hora_fin',
              'clase.anio',
              DB::raw('YEAR(clase.anio) as anio_clase'),
              'clase.observacion',
              'clase.estado',
                            
              'persona.primer_nombre',
              'persona.segundo_nombre',
              'persona.primer_apellido',
              'persona.segundo_apellido',
              'persona.correo_electronico',
              
              'empleado.id_empleado',
              'empleado.foto_url',
              
              'aula.id_aula',
              'aula.nombre_aula',
              'aula.codigo_aula',
              'asignatura.id_asignatura',
              
              'asignatura.nombre_asignatura',
              'asignatura.dias',
              'asignatura.codigo_asignatura')
          ->where('clase.'.$criterio, 'like', '%'. $buscar . '%')
          ->orderBy('clase.id_clase', 'desc')
          ->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $clase->total(),
                'current_page' => $clase->currentPage(),
                'per_page'     => $clase->perPage(),
                'last_page'    => $clase->lastPage(),
                'from'         => $clase->firstItem(),
                'to'           => $clase->lastItem(),
            ],
            'clase' => $clase
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
            // Instancia de la clase Clase, la clase clase es el modelo Clase
            $clase = new Clase();
            
            $clase->id_maestro_encargado = $request->id_maestro_encargado;
            $clase->id_aula = $request->id_aula;
            $clase->id_asignatura = $request->id_asignatura;
            $clase->seccion = $request->seccion;
            $clase->hora_inicio = $request->hora_inicio;
            $clase->hora_fin = $request->hora_fin;
            $clase->anio = $request->anio;
            $clase->observacion = $request->observacion;
            $clase->estado = 'A'; // A: Activo

            //Validación de profesor ocupado a la hora
            //Variables de control
            $error = false;
            $errores = array(); 

            $count = DB::table('clase')
                ->whereRaw("id_maestro_encargado = ".$request->id_maestro_encargado."
                  AND hora_inicio >= '".$request->hora_inicio."'
                  AND hora_fin <= '".$request->hora_fin."'
                  AND YEAR(anio) = YEAR('".$request->anio."')
                  AND estado = 'A'"
                )->get();

            if(sizeof($count) != 0){
              $error = true;
              array_push($errores, ['error' => 1,
                                    'mensaje' => '! El maestro esta registrado en otra clase en este rango de hora.']);
            }

            //Validación de aula ocupada a la hora
            $count = DB::table('clase')
                ->whereRaw("id_aula = ".$request->id_aula."
                  AND hora_inicio >= '".$request->hora_inicio."'
                  AND hora_fin <= '".$request->hora_fin."'
                  AND YEAR(anio) = YEAR('".$request->anio."')
                  AND estado = 'A'"
                )->get();
  
            if(sizeof($count) != 0){
              $error = true;
              array_push($errores, ['error' => 2,
                                    'mensaje' => '! Hay otra clase registrada en el aula en este rango de hora.']);
            }

            if($error == true){
              return ['error' => $error,
                      'errores' => $errores];
            }
            
            $clase->save();
            
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

        try {
            DB::beginTransaction();

            // Instancia de la clase Clase, la clase clase es el modelo Clase
            $clase = Clase::findOrFail($request->id_clase);
            
            $clase->id_maestro_encargado = $request->id_maestro_encargado;
            $clase->id_aula = $request->id_aula;
            $clase->id_asignatura = $request->id_asignatura;
            $clase->seccion = $request->seccion;
            $clase->hora_inicio = $request->hora_inicio;
            $clase->hora_fin = $request->hora_fin;
            $clase->anio = $request->anio;
            $clase->observacion = $request->observacion;

            //Validación de profesor ocupado a la hora
            //Variables de control
            $error = false;
            $errores = array(); 

            $count = DB::table('clase')
                ->whereRaw("id_maestro_encargado = ".$request->id_maestro_encargado."
                  AND hora_inicio >= '".$request->hora_inicio."'
                  AND hora_fin <= '".$request->hora_fin."'
                  AND YEAR(anio) = YEAR('".$request->anio."')
                  AND estado = 'A'"
                )->get();

            if(sizeof($count) != 0){
              $error = true;
              array_push($errores, ['error' => 1,
                                    'mensaje' => '! El maestro esta registrado en otra clase en este rango de hora.']);
            }

            //Validación de aula ocupada a la hora
            $count = DB::table('clase')
                ->whereRaw("id_aula = ".$request->id_aula."
                  AND hora_inicio >= '".$request->hora_inicio."'
                  AND hora_fin <= '".$request->hora_fin."'
                  AND YEAR(anio) = YEAR('".$request->anio."')
                  AND estado = 'A'"
                )->get();
  
            if(sizeof($count) != 0){
              $error = true;
              array_push($errores, ['error' => 2,
                                    'mensaje' => '! Hay otra clase registrada en el aula en este rango de hora.']);
            }

            if($error == true){
              return ['error' => $error,
                      'errores' => $errores];
            }
            
            $clase->save();

            DB::commit();
        
        } catch(Exception $e){
            DB::rollback();
        }
    }

    public function desactivar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');

        $clase = Clase::findOrFail($request->id_clase);

        $clase->estado = 'I'; // I: Inactivo
        
        $clase->save();
    }

    public function activar(Request $request) {
        // Determinar si la petición que se hace es diferente de ajax
        // De lo contrario va a a redirigir a la ruta raiz
        if(!$request->ajax()) return redirect('/');
        
        $clase = Clase::findOrFail($request->id_clase);

        $clase->estado = 'A'; // A: Activo
        
        $clase->save();
    }

    // Determina las clases que tiene un maestro
    public function clasesMaestro(Request $request) {
      if (!$request->ajax()) return redirect('/');

      // Obtiene el usuario autenticado actualmente
      $maestro = \Auth::user()->usuario;

      $buscar = $request->buscar;
      $criterio = $request->criterio;

      if ($buscar == ''){
        $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
        ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
        ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
        ->join('cargo', 'empleado.id_cargo', '=', 'cargo.id_cargo')
        ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
        ->select(
            'clase.id_clase',
            'clase.seccion',
            'clase.hora_inicio',
            'clase.hora_fin',
            'clase.anio',
             DB::raw('YEAR(clase.anio) as anio_clase'),
            'clase.observacion',
            'clase.estado',
  
            'cargo.nombre_cargo',
  
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido',
            'persona.correo_electronico',
  
            'empleado.id_empleado',
            'empleado.foto_url',
  
            'aula.id_aula',
            'aula.nombre_aula',
            'aula.codigo_aula',
  
            'asignatura.id_asignatura',
            'asignatura.nombre_asignatura',
            'asignatura.dias',
            'asignatura.codigo_asignatura')
        ->where('empleado.usuario', '=', $maestro)
        ->orderBy('clase.id_clase', 'desc')
        ->get();
      } else {
        $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
        ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
        ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
        ->join('cargo', 'empleado.id_cargo', '=', 'cargo.id_cargo')
        ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
        ->select(
            'clase.id_clase',
            'clase.seccion',
            'clase.hora_inicio',
            'clase.hora_fin',
            'clase.anio',
             DB::raw('YEAR(clase.anio) as anio_clase'),
            'clase.observacion',
            'clase.estado',
  
            'cargo.nombre_cargo',
  
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido',
            'persona.correo_electronico',
  
            'empleado.id_empleado',
            'empleado.foto_url',
  
            'aula.id_aula',
            'aula.nombre_aula',
            'aula.codigo_aula',
  
            'asignatura.id_asignatura',
            'asignatura.nombre_asignatura',
            'asignatura.dias',
            'asignatura.codigo_asignatura')
        ->where([['empleado.usuario', '=', $maestro], ['clase.'.$criterio, 'like', '%'. $buscar . '%']])
        ->orderBy('clase.id_clase', 'desc')
        ->get();
      }
      
      
      
      return ['clase' => $clase];
    }

    // Determina las clases en las que está matriculado un alumno
    public function clasesAlumno(Request $request) {
      if (!$request->ajax()) return redirect('/');
      // Obtiene el usuario autenticado actualmente
      //$alumno = \Auth::user()->usuario;
      
      $alumno = DB::table('alumno')
        ->select(DB::raw('id_alumno'))
        ->where('codigo_alumno', '=', \Auth::user()->usuario)->get();

      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == '') {
        $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
        ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
        ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
        ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')  
        ->join('alumno_clase', 'clase.id_clase', '=', 'alumno_clase.id_clase')
        ->join('alumno', 'alumno_clase.id_alumno', '=', 'alumno.id_alumno')
        ->select(
          'alumno.indice_global',
          'alumno.id_alumno',
          'alumno_clase.id',
          
          'clase.id_clase',
          'clase.seccion',
          'clase.hora_inicio',
          'clase.hora_fin',
          'clase.anio',
          DB::raw('YEAR(clase.anio) as anio_clase'),
          'clase.observacion',
          'clase.estado',

          'alumno_clase.indice_clase',

          'aula.id_aula',
          'aula.nombre_aula',
          'aula.codigo_aula',

          'asignatura.id_asignatura',
          'asignatura.nombre_asignatura',
          'asignatura.dias',
          'asignatura.codigo_asignatura',
          
          'persona.primer_nombre',
          'persona.segundo_nombre',
          'persona.primer_apellido',
          'persona.segundo_apellido',
          'persona.correo_electronico',

          'empleado.id_empleado',
          'empleado.foto_url')
        ->where('alumno_clase.id_alumno', '=', $alumno[0]->id_alumno)
        ->orderBy('alumno_clase.id', 'desc')
        ->paginate(10);

      } else {
        $clase = Clase::join('empleado', 'clase.id_maestro_encargado', '=', 'empleado.id_empleado')
          ->join('aula', 'clase.id_aula', '=', 'aula.id_aula')
          ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
          ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
          ->join('alumno_clase', 'alumno_clase.id_clase', '=', 'clase.id_clase')
          ->select(
            'persona.primer_nombre',
            'persona.segundo_nombre',
            'persona.primer_apellido',
            'persona.segundo_apellido',
            'persona.correo_electronico',

            'empleado.id_empleado',
            'empleado.foto_url',

            'clase.id_clase',
            'clase.seccion',
            'clase.hora_inicio',
            'clase.hora_fin',
            DB::raw('YEAR(clase.anio) as anio'),
            'clase.observacion',
            'clase.estado',

            'aula.id_aula',
            'aula.nombre_aula',
            'aula.codigo_aula',

            'asignatura.id_asignatura',
            'asignatura.nombre_asignatura',
            'asignatura.dias',
            'asignatura.codigo_asignatura')
          ->where([['alumno_clase.id_alumno', '=', $alumno[0]->id_alumno], ['asignatura.'.$criterio, 'like', '%'. $buscar . '%']])
          ->orderBy('alumno_clase.id', 'desc')
          ->paginate(10);
      }
      
      return [
        'pagination' => [
          'total'        => $clase->total(),
          'current_page' => $clase->currentPage(),
          'per_page'     => $clase->perPage(),
          'last_page'    => $clase->lastPage(),
          'from'         => $clase->firstItem(),
          'to'           => $clase->lastItem(),
        ],
        'clase' => $clase
      ];
    }

    // Se encarga de listar todos los alumnos de una clase especifica
    public function listarAlumnosClase(Request $request) {

      if (!$request->ajax()) return redirect('/');

      $clase = $request->id_clase;
      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == '') {
        $alumno = Clase::join('alumno_clase', 'alumno_clase.id_clase', '=', 'clase.id_clase')
        ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
        ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
        ->select(
          'alumno.id_alumno',
          'alumno.codigo_alumno',
  
          'persona.primer_nombre', 
          'persona.segundo_nombre', 
          'persona.primer_apellido', 
          'persona.segundo_apellido')
        ->where('clase.id_clase', '=', $clase)
        ->orderBy('alumno.id_alumno', 'desc')
        ->paginate(10);
      } else {
        $alumno = Clase::join('alumno_clase', 'alumno_clase.id_clase', '=', 'clase.id_clase')
        ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
        ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
        ->select(
          'alumno.id_alumno',
          'alumno.codigo_alumno',
  
          'persona.primer_nombre', 
          'persona.segundo_nombre', 
          'persona.primer_apellido', 
          'persona.segundo_apellido')
        ->where([['clase.id_clase', '=', $clase], ['persona.'.$criterio, 'like', '%'. $buscar . '%']])
        ->orderBy('alumno.id_alumno', 'desc')
        ->paginate(10);
      }

      return [
        'pagination' => [
            'total'        => $alumno->total(),
            'current_page' => $alumno->currentPage(),
            'per_page'     => $alumno->perPage(),
            'last_page'    => $alumno->lastPage(),
            'from'         => $alumno->firstItem(),
            'to'           => $alumno->lastItem(),
        ],
        'alumno' => $alumno
      ];
    }

    // Se encarga de listar todos los alumnos de una clase
    public function alumnosClase(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $maestro = \Auth::user()->usuario;
      $clase = $request->id_clase;

      $alumno = Clase::where('clase.id_clase', '=', $clase)
      ->join('alumno_clase', 'alumno_clase.id_clase', '=', 'clase.id_clase')
      ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
      ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
      ->leftJoin('calificacion', 'alumno.id_alumno', '=', 'calificacion.id_alumno')
      ->select(
        'alumno.id_alumno',
        'alumno.codigo_alumno',

        'persona.primer_nombre', 
        'persona.segundo_nombre', 
        'persona.primer_apellido', 
        'persona.segundo_apellido',
        
        'calificacion.id_calificacion',
        'calificacion.id_clase',
        'calificacion.id_parcial',
        'calificacion.id_tipo_calificacion',
        'calificacion.id_archivo_tarea',
        'calificacion.nota',
        'calificacion.observacion')
      ->get();
      
      return $alumno;
    }

    // Se encarga de listar las notas de un alumno en especifico de una clase en particular
    public function verNotasAlumno(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $maestro = \Auth::user()->usuario;
      $clase = $request->id_clase;
      $alumno = $request->id_alumno;
      $filtro_parcial = $request->filtro_parcial;
      
      if ($filtro_parcial == '') {
        $nota = Clase::join('calificacion', 'calificacion.id_clase', '=', 'clase.id_clase')
          ->join('alumno', 'alumno.id_alumno', '=', 'calificacion.id_alumno')
          ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
          ->join('tipo_calificacion', 'tipo_calificacion.id_tipo_calificacion', '=', 'calificacion.id_tipo_calificacion')
          ->join('parcial', 'parcial.id_parcial', '=', 'calificacion.id_parcial')
          ->select(
            'alumno.id_alumno',
            'alumno.codigo_alumno',
    
            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido',
            
            'calificacion.id_calificacion',
            'calificacion.id_clase',
            'calificacion.id_parcial',
            'calificacion.id_tipo_calificacion',
            'calificacion.id_archivo_tarea',
            'calificacion.nota',
            'calificacion.observacion',
            
            'tipo_calificacion.tipo_calificacion',
            'parcial.nombre_parcial')
          ->where([['calificacion.id_clase', '=', $clase], ['calificacion.id_alumno', '=', $alumno]])
          ->get();

      } else {
        $nota = Clase::where([['clase.id_clase', '=', $clase], ['alumno.id_alumno', '=', $alumno], ['parcial.id_parcial', '=', $filtro_parcial]])
          ->join('alumno_clase', 'alumno_clase.id_clase', '=', 'clase.id_clase')
          ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
          ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
          ->leftJoin('calificacion', 'alumno.id_alumno', '=', 'calificacion.id_alumno')
          ->join('tipo_calificacion', 'tipo_calificacion.id_tipo_calificacion', '=', 'calificacion.id_tipo_calificacion')
          ->join('parcial', 'calificacion.id_parcial', '=', 'parcial.id_parcial')
          ->select(
            'alumno.id_alumno',
            'alumno.codigo_alumno',
    
            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido',
            
            'calificacion.id_calificacion',
            'calificacion.id_clase',
            'calificacion.id_parcial',
            'calificacion.id_tipo_calificacion',
            'calificacion.id_archivo_tarea',
            'calificacion.nota',
            'calificacion.observacion',
            
            'tipo_calificacion.tipo_calificacion',
            'parcial.nombre_parcial')
          ->get();
      }

      return $nota;
    }

    // Se encarga de listar las notas de un alumno en especifico autenticado
    public function verNotasDeAlumno(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $idAlumno = DB::table('alumno')
        ->select(DB::raw('id_alumno'))
        ->where('codigo_alumno', '=', \Auth::user()->usuario)
        ->get();

      $idClase = $request->id_clase;

      $notas = Calificacion::where([['calificacion.id_clase', '=', $idClase], ['calificacion.id_alumno', '=', $idAlumno[0]->id_alumno]])
        ->join('alumno', 'alumno.id_alumno', '=', 'calificacion.id_alumno')  
        ->join('persona', 'alumno.id_persona' , '=', 'persona.id_persona')
        ->join('clase', 'calificacion.id_clase', '=', 'clase.id_clase')
        ->join('parcial', 'calificacion.id_parcial', '=', 'parcial.id_parcial')
        ->join('tipo_calificacion', 'calificacion.id_tipo_calificacion', '=', 'tipo_calificacion.id_tipo_calificacion')
        ->select(
          'alumno.id_alumno',
          'alumno.codigo_alumno',

          'clase.id_clase',

          'persona.primer_nombre', 
          'persona.segundo_nombre', 
          'persona.primer_apellido', 
          'persona.segundo_apellido',
          
          'calificacion.id_calificacion',
          'calificacion.id_parcial',
          'calificacion.id_tipo_calificacion',
          'calificacion.id_archivo_tarea',
          'calificacion.nota',
          'calificacion.observacion',
          
          'parcial.nombre_parcial',
          'tipo_calificacion.tipo_calificacion')
        ->orderBy('parcial.nombre_parcial', 'desc')
        ->get();
      
      return $notas;
    }

    public function registrarNota(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $conteo = 0;

      // Verifica si es una nota final de parcial que se quiere insertar
      if($request->id_tipo_calificacion == 1){
        // Busca si existe una nota final de un parcial
        $notasParcial = Calificacion::where([
          ['id_clase', '=', $request->id_clase], 
          ['id_alumno', '=', $request->id_alumno], 
          ['id_tipo_calificacion', '=', 1],
          ['id_parcial', '=', $request->id_parcial]])
        ->select('nota')
        ->get();

        $conteo = sizeof($notasParcial);
      }

      // Si no existe una nota final, se intenta insertar la nueva nota
      if ($conteo == 0) {
        try {
          DB::beginTransaction();
          $calificacion = new Calificacion();
  
          $calificacion->id_clase = $request->id_clase;
          $calificacion->id_alumno = $request->id_alumno;
          $calificacion->id_parcial = $request->id_parcial;
          $calificacion->id_tipo_calificacion = $request->id_tipo_calificacion;
          $calificacion->id_archivo_tarea = 1; //$request->id_archivo_tarea;
          $calificacion->nota = $request->nota;
          $calificacion->observacion = $request->observacion;
          $calificacion->estado = 'F'; // Final
          $calificacion->save();
          
          DB::commit();
  
        } catch (exception $e) {
          DB::rollBack();
        }
      }

      return ['conteo' => $conteo];
    }

    /**
     * Realiza una comproabación antes de salvar los datos mediante el
     * método find o el método findOrFail
     */
    public function actualizarNota(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      try {
        DB::beginTransaction();

        $calificacion = Calificacion::findOrFail($request->id_calificacion);

        $calificacion->id_clase = $request->id_clase;
        $calificacion->id_alumno = $request->id_alumno;
        $calificacion->id_parcial = $request->id_parcial;
        $calificacion->id_tipo_calificacion = $request->id_tipo_calificacion;
        $calificacion->id_archivo_tarea = $request->id_archivo_tarea;
        $calificacion->nota = $request->nota;
        $calificacion->observacion = $request->observacion;
        $calificacion->save();

        DB::commit();

      } catch (exception $e) {
        DB::rollBack();
      }
    }

    // Se encarga de listar todos los alumnos matriculados de una clase, para el administrador
    public function alumnosClaseMatriculados(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

        $alumno = DB::table('alumno_clase')
          ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
          ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
          ->select(
            'alumno_clase.id_alumno', 
            'alumno.codigo_alumno',

            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido')
          ->where('alumno_clase.id_clase', '=', $request->id_clase)
          ->get();
        return $alumno;
    }

    // Se encarga de listar el indice parcial de un alumno
    public function ListarIndiceParcialClase(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

        $indiceParcial = DB::table('alumno_clase')
          ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
          ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
          ->select(
            'alumno_clase.id_alumno', 
            'alumno.codigo_alumno',

            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido')
          ->where('alumno_clase.id_clase', '=', $request->id_clase)
          ->get();

        return $indiceParcial;
    }

    // Se encarga de calcular el indice global de un alumno
    public function calcularIndiceClase(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      // Encuentra el id del alumno loggeado
      $idAlumno = DB::table('alumno')
        ->select(DB::raw('id_alumno'))
        ->where('codigo_alumno', '=', \Auth::user()->usuario)
        ->get();

      // El id de la clase se envia
      $idClase = $request->id_clase;

      // Cuenta cuantos parciales hay en la base
      $totalParciales = DB::table('parcial')->count();

      // Guarda el arreglo de notas
      $notasParcial = Calificacion::where([['id_clase', '=', $idClase], ['id_alumno', '=', $idAlumno[0]->id_alumno], ['id_tipo_calificacion', '=', 1]])
        ->select('nota')
        ->get();

      $conteo = sizeof($notasParcial);

      // Calculo del promedio
      $nuevoIndice = 0;
      foreach ($notasParcial as $key => $nota) {
        $nuevoIndice = $nuevoIndice + $nota->nota;
      }

      if ($conteo > 0) {
        $nuevoIndice = $nuevoIndice/($conteo);
      }
      
      try {
        DB::beginTransaction();

        $alumno_clase = Alumno_Clase::where([['id_clase', '=', $idClase], ['id_alumno', '=', $idAlumno[0]->id_alumno]])->firstOrFail();
        $alumno_clase->indice_clase = $nuevoIndice;
        $alumno_clase->save();

        DB::commit();
    
      } catch(Exception $e) {
        DB::rollback();
      }

      //return ['alumno_clase' => $alumno_clase, 'conteo' => $totalParciales, 'notas' => $notasParcial];
    }

    // Se encarga de calcular el indice parcial de un alumno
    public function calcularIndiceGlobal(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      // Encuentra el id del alumno loggeado
      $idAlumno = DB::table('alumno')
        ->select(DB::raw('id_alumno'))
        ->where('codigo_alumno', '=', \Auth::user()->usuario)
        ->get();

      // Guarda el arreglo de notas
      $notasClase = Alumno_Clase::where('id_alumno', '=', $idAlumno[0]->id_alumno)
        ->select('indice_clase')
        ->get();

      $conteo = sizeof($notasClase);

      // Calculo del promedio
      $nuevoIndiceGlobal = 0;
      foreach ($notasClase as $key => $indice_clase) {
        $nuevoIndiceGlobal = $nuevoIndiceGlobal + $indice_clase->indice_clase;
      }

      if ($conteo > 0) {
        $nuevoIndiceGlobal = $nuevoIndiceGlobal/($conteo);
      }

      try {
        DB::beginTransaction();

        $alumno = Alumno::where('id_alumno', '=', $idAlumno[0]->id_alumno)->firstOrFail();
        $alumno->indice_global = $nuevoIndiceGlobal;
        $alumno->save();

        DB::commit();
    
      } catch(Exception $e) {
        DB::rollback();
      }
      
      return ['notas clases' => $notasClase, 'alumno' => $alumno];
    }

    // Se encarga de matricular los alumnos en una clase
    public function matricularAlumno(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $alumnos = $request->data; //Array de alumnos
      
      //Se eliminan todos los registros anteriores de la actual clase
      DB::table('alumno_clase')->where('id_clase', '=', $request->id_clase)->delete();

      //Recorre todos los elementos
      foreach($alumnos as $ep=>$alum) {
        $alumnoClase = new Alumno_Clase();
        $alumnoClase->id_clase = $request->id_clase;
        $alumnoClase->id_alumno = $alum['id_alumno'];
        $alumnoClase->save();
      } 

      return $alumnos;
    }

    // Se encarga de generar el reporte de aprobados por sección
    public function listarPDF(Request $request) {
      $clase = $request->id_clase;

      $alumnos = DB::table('alumno_clase')
          ->join('alumno', 'alumno.id_alumno', '=', 'alumno_clase.id_alumno')
          ->join('persona', 'persona.id_persona', '=', 'alumno.id_persona')
          // ->join('calificacion', 'alumno.id_alumno', '=', 'calificacion.id_alumno')
          ->select(
            'alumno.id_alumno', 
            'alumno.codigo_alumno',
            'alumno.indice_global',

            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido'

            // 'calificacion.id_calificacion',
            // 'calificacion.id_clase',
            // 'calificacion.id_parcial',
            // 'calificacion.id_tipo_calificacion',
            // 'calificacion.id_archivo_tarea',
            // 'calificacion.nota',
            // 'calificacion.observacion'
          )
          ->where('alumno_clase.id_clase', '=', $request->id_clase)
          ->orderBy('alumno.codigo_alumno')
          ->get();

      $clase = Clase::join('aula', 'clase.id_aula', '=', 'aula.id_aula')
      ->join('asignatura', 'clase.id_asignatura', '=', 'asignatura.id_asignatura')
      ->join('empleado', 'empleado.id_empleado', '=', 'clase.id_maestro_encargado')
      ->select(
        'clase.id_maestro_encargado',
        'clase.id_aula',
        'clase.id_asignatura',
        'clase.seccion',
        'clase.hora_inicio',
        'clase.hora_fin',
        'clase.observacion',
        'clase.estado',

        'aula.nombre_aula',
        'aula.codigo_aula',

        'asignatura.nombre_asignatura',
        'asignatura.codigo_asignatura',
        'asignatura.dias'
      )
      ->where('clase.id_clase', '=', $request->id_clase)
      ->get();
            
      $maestro = Empleado::join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
            ->select(
              'persona.primer_nombre',
              'persona.segundo_nombre',
              'persona.primer_apellido',
              'persona.segundo_apellido',

              'empleado.estado'
            )
            ->where('empleado.id_empleado', '=', $clase[0]->id_maestro_encargado)
            ->get();

      $aprobados = 0;
      $reprobados = 0;

      foreach ($alumnos as $key => $alumno) {
        if($alumno->indice_global >= 70){
          $aprobados++;
        }else{
          $reprobados++;
        }
      }

      $count = sizeof($alumnos);

      if(($aprobados + $reprobados) != $count){
        return 0;
      }

      $pdf = \PDF::loadView('pdf.aprobadospdf', 
        ['alumnos' => $alumnos, 
         'clase' => $clase, 
         'maestro' => $maestro, 
         'aprobados' => $aprobados, 
         'reprobados' => $reprobados, 
         'count' => $count]);

      return $pdf->download('aprobados'.$request->id_clase.'.pdf');
    }
}
