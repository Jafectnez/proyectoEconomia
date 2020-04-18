<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Se importa en caso de trabajar con transacciones
use Illuminate\Support\Facades\Hash;

use App\Alumno;
use App\Persona;
use App\Telefono;

class AlumnoController extends Controller
{

    public $ultimo_alumno;

    /**
     * Display a listing of the resource.
     * Listar todos los registros de la tabla Alumno
     */
    public function index(Request $request) {
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');
      
      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == '') {
          $alumno = Alumno::join('persona', 'alumno.id_persona', '=', 'persona.id_persona')
          ->join('telefono', 'telefono.id_persona', '=', 'persona.id_persona')
          ->join('tipo_usuario', 'tipo_usuario.id_tipo_usuario', '=', 'alumno.id_tipo_usuario')
          ->select(
            'persona.id_persona',
            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido', 
            'persona.numero_identidad', 
            'persona.fecha_nacimiento', 
            'persona.correo_electronico', 
            'persona.sexo', 
            'persona.direccion',

            'telefono.id_telefono',
            'telefono.telefono',
              
            'alumno.id_alumno',
            'alumno.id_tipo_usuario',
            'alumno.codigo_alumno', 
            'alumno.contrasena', 
            'alumno.indice_global', 
            'alumno.fecha_ingreso', 
            'alumno.foto_url',
            'alumno.sesion',
            'alumno.estado',
            
            'tipo_usuario.tipo_usuario')
            ->orderBy('alumno.id_persona', 'desc')
            ->paginate(10);
      } else {
          // usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
          // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
          $alumno = Alumno::join('persona', 'alumno.id_persona', '=', 'persona.id_persona')
          ->join('telefono', 'telefono.id_persona', '=', 'persona.id_persona')
          ->join('tipo_usuario', 'tipo_usuario.id_tipo_usuario', '=', 'alumno.id_tipo_usuario')
          ->select(
            'persona.id_persona',
            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido', 
            'persona.numero_identidad', 
            'persona.fecha_nacimiento', 
            'persona.correo_electronico', 
            'persona.sexo', 
            'persona.direccion',

            'telefono.id_telefono',
            'telefono.telefono',
              
            'alumno.id_alumno',
            'alumno.id_tipo_usuario',
            'alumno.codigo_alumno', 
            'alumno.contrasena', 
            'alumno.indice_global ', 
            'alumno.fecha_ingreso', 
            'alumno.foto_url',
            'alumno.sesion',
            'alumno.estado',
            
            'tipo_usuario.tipo_usuario')
          ->where($criterio, 'like', '%'. $buscar .'%') // Es necesario el nombre de la tabla + el nombre del campo
          ->orderBy('alumno.id_persona', 'desc') 
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
  
    /**
     * Guarda un registro nuevo en la base
     */
    public function store(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      try {
        DB::beginTransaction();
        //Reglas y mensajes de errores para validación de persona
        $reglasPersona = [
          'primer_nombre' => 'required|max:50',
          'primer_apellido' => 'required|max:50',
          'numero_identidad' => 'required|unique:persona,numero_identidad',
          'correo_electronico' => 'required|email|unique:persona,correo_electronico',
          'fecha_nacimiento' => 'date|before_or_equal:'.date('Y-m-d', strtotime("-4 years")),
          'telefono' => 'required|unique:telefono,telefono'
        ];

        $erroresPersona = [
          'primer_nombre.required' => 'El primer nombre no debe ir vacío.',
          'primer_apellido.required' => 'El primer apellido no debe ir vacío.',
          'numero_identidad.required' => 'El número de identidad no debe ir vacío.',
          'numero_identidad.unique' => 'El número de identidad ya está registrado.',
          'correo_electronico.required' => 'El correo electrónico no debe ir vacío.',
          'correo_electronico.unique' => 'El correo electrónico ya está registrado.',
          'correo_electronico.email' => 'El correo electrónico no es válido.',
          'telefono.required' => 'El teléfono no puede ir vacío.',
          'telefono.unique' => 'El teléfono ya está registrado.',
          'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
          'fecha_nacimiento.before_or_equal' => 'El alumno debe tener 4 años o más para ser aceptado.'
        ];

        //Se validan los datos recibidos
        $this->validate($request, $reglasPersona, $erroresPersona);
        
        // Instancia de la clase Persona, la clase persona es el modelo Persona
        $persona = new Persona();
        
        $persona->primer_nombre = $request->primer_nombre;
        $persona->segundo_nombre = $request->segundo_nombre;
        $persona->primer_apellido = $request->primer_apellido;
        $persona->segundo_apellido = $request->segundo_apellido;
        $persona->numero_identidad = $request->numero_identidad;
        $persona->fecha_nacimiento = $request->fecha_nacimiento;
        $persona->correo_electronico = $request->correo_electronico;
        $persona->sexo = $request->sexo;
        $persona->direccion = $request->direccion;
        $persona->estado = 'A'; // A: Activo

        $persona->save();

        $telefono = new Telefono();
        // Se va a almacenar lo que se guardó en la columna id_persona de la tabla persona
        $telefono->id_persona = $persona->id_persona;
        $telefono->telefono = $request->telefono;
        
        $telefono->save();

        $alumno = new Alumno();
        // Se va a almacenar lo que se guardó en la columna id_persona de la tabla persona
        $alumno->id_persona = $persona->id_persona;
        $alumno->id_tipo_usuario = 4;

        //Se verifica si la variable global $ultimo_alumno esta nula, si lo está se asigna el ultimo alumno de la base de datos
        if(!$this->ultimo_alumno) {
          $all = DB::table('alumno')->select()->get();
          $this->ultimo_alumno = $all[sizeof($all)-1];
        }

        $alumno->codigo_alumno = (int)$this->ultimo_alumno->codigo_alumno + 1;
        // $alumno->codigo_alumno = '20' . (string)rand(15,19) . '100' . (string)rand(1, 9999);
        
        $alumno->contrasena = Hash::make($request->contrasena);

        $alumno->indice_global  = 0;
        $alumno->fecha_ingreso = date("Y").'-'.date("m").'-'.date("d");
        $alumno->foto_url = "url.com"; //$request->foto_url;
        $alumno->sesion = date("Y").'-'.date("m").'-'.date("d"); // $request->sesion;
        $alumno->estado = 'A'; // A: Activo 

        $alumno->save();

        $this->ultimo_alumno = $alumno;
        
        DB::commit();

      } catch (Exception $e){
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
        // Instancia de la clase Persona, la clase persona es el modelo Persona
        $alumno = Alumno::findOrFail($request->id_alumno);
        $persona = Persona::findOrFail($alumno->id_persona);
        $telefono = Telefono::where('id_persona', '=', $persona->id_persona)->first();

        //Reglas y mensajes de errores para validación de persona
        $reglasPersona = [
          'primer_nombre' => 'required|max:50',
          'primer_apellido' => 'required|max:50',
          'numero_identidad' => 'required|unique:persona,numero_identidad,'.$alumno->id_persona.',id_persona',
          'correo_electronico' => 'required|unique:persona,correo_electronico,'.$alumno->id_persona.',id_persona',
          'fecha_nacimiento' => 'date|before_or_equal:'.date('Y-m-d', strtotime("-4 years")),
          'telefono' => 'required|unique:telefono,telefono,'.$alumno->id_persona.',id_persona'
        ];

        $erroresPersona = [
          'primer_nombre.required' => 'El primer nombre no debe ir vacío.',
          'primer_apellido.required' => 'El primer apellido no debe ir vacío.',
          'numero_identidad.required' => 'El número de identidad no debe ir vacío.',
          'numero_identidad.unique' => 'El número de identidad ya está registrado.',
          'correo_electronico.required' => 'El correo electrónico no debe ir vacío.',
          'correo_electronico.unique' => 'El correo electrónico ya está registrado.',
          'correo_electronico.email' => 'El correo electrónico no es válido.',
          'telefono.required' => 'El teléfono no puede ir vacío.',
          'telefono.unique' => 'El teléfono ya está registrado.',
          'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
          'fecha_nacimiento.before_or_equal' => 'El alumno debe tener 4 años o más para ser aceptado.'
        ];

        //Se validan los datos recibidos
        $this->validate($request, $reglasPersona, $erroresPersona);
        
        $persona->primer_nombre = $request->primer_nombre;
        $persona->segundo_nombre = $request->segundo_nombre;
        $persona->primer_apellido = $request->primer_apellido;
        $persona->segundo_apellido = $request->segundo_apellido;
        $persona->numero_identidad = $request->numero_identidad;
        $persona->fecha_nacimiento = $request->fecha_nacimiento;
        $persona->correo_electronico = $request->correo_electronico;
        $persona->sexo = $request->sexo;
        $persona->direccion = $request->direccion;
        $persona->save();

        $telefono->telefono = $request->telefono;
        $telefono->save();

        $alumno->id_tipo_usuario = 4; //El alumno siempre tendra como tipo de usuario 4
        //$alumno->codigo_alumno = $request->codigo_alumno; // No deberia ser posible cambiarlo una vez creado
        $alumno->contrasena = Hash::make($request->contrasena);
        //$alumno->indice_global  = $request->indice_global ;
        //$alumno->fecha_ingreso = $request->fecha_ingreso; // No deberia ser posible cambiarlo una vez creado
        $alumno->foto_url = "url.com"; // $request->foto_url;
        $alumno->sesion = date("Y").'-'.date("m").'-'.date("d"); //$request->sesion;
        $alumno->estado = 'A'; // A: Activo
        $alumno->save();

        DB::commit();
        
      } catch(Exception $e) {
        DB::rollback();
      }
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $alumno = Alumno::findOrFail($request->id_alumno);

      $alumno->estado = 'I'; // I: Inactivo
      
      $alumno->save();

      $persona = Persona::findOrFail($alumno->id_persona);

      $persona->estado = 'I';

      $persona->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $alumno = Alumno::findOrFail($request->id_alumno);

      $alumno->estado = 'A'; // A: Activo
      
      $alumno->save();

      $persona = Persona::findOrFail($alumno->id_persona);

      $persona->estado = 'A';

      $persona->save();
    }

    //Lista todos los alumnos sin paginarlos
    public function listarAlumnos(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == '') {
          $alumno = Alumno::join('persona', 'alumno.id_persona', '=', 'persona.id_persona')
          ->select(
            'alumno.id_alumno', 
            'alumno.codigo_alumno',

            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido')
            ->where('alumno.estado', '=', 'A')
            ->orderBy('alumno.id_alumno', 'desc')
            ->get();
      } else {
          // usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
          // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
          $alumno = Alumno::join('persona', 'alumno.id_persona', '=', 'persona.id_persona')
          ->select(
            'alumno.id_alumno', 
            'alumno.codigo_alumno',

            'persona.primer_nombre', 
            'persona.segundo_nombre', 
            'persona.primer_apellido', 
            'persona.segundo_apellido')
          ->where([
                  [$criterio, 'like', '%'. $buscar .'%'],
                  ['alumno.estado', '=', 'A']
                ]) // Es necesario el nombre de la tabla + el nombre del campo
          ->orderBy('alumno.id_alumno', 'desc') 
          ->get();
      }
      
      return ['alumnos' => $alumno];
    }

}
