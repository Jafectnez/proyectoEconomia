<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Se importa en caso de trabajar con transacciones
use Illuminate\Support\Facades\Hash;

use App\Persona;
use App\Empleado;
use App\Telefono;

class EmpleadoController extends Controller
{
    /**
     * Listar todos los registros de la tabla empleado
     */
    public function index(Request $request){
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;
      
      if ($buscar == '') {
          // Instancia del modelo persona
          $empleado = Empleado::join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
          ->join('telefono', 'telefono.id_persona', '=', 'persona.id_persona')
          ->join('tipo_usuario', 'tipo_usuario.id_tipo_usuario', '=', 'empleado.id_tipo_usuario')
          ->join('cargo', 'cargo.id_cargo', '=', 'empleado.id_cargo')
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

            'empleado.id_empleado',
            'empleado.id_cargo',
            'empleado.id_tipo_usuario',
            'empleado.fecha_ingreso',
            'empleado.usuario',
            'empleado.contrasena',
            'empleado.foto_url',
            'empleado.sesion',
            'empleado.estado',
            
            'tipo_usuario.tipo_usuario',
            'cargo.nombre_cargo')
          ->orderBy('empleado.id_persona', 'desc')
          ->paginate(10);
      } else {
          // usando el método where: Buscar por el campo criterio, el texto que se está obteniendo en el parametro buscar
          // que se está recibiendo mediante GET de ajax. like, % bucasn en cualquier parte de la cadena de text
          $empleado = Empleado::join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
          ->join('telefono', 'telefono.id_persona', '=', 'persona.id_persona')
          ->join('tipo_usuario', 'tipo_usuario.id_tipo_usuario', '=', 'empleado.id_tipo_usuario')
          ->join('cargo', 'cargo.id_cargo', '=', 'empleado.id_cargo')
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
            
            'empleado.id_empleado',
            'empleado.id_cargo',
            'empleado.id_tipo_usuario',
            'empleado.fecha_ingreso',
            'empleado.usuario',
            'empleado.contrasena',
            'empleado.foto_url',
            'empleado.sesion',
            'empleado.estado',

            'tipo_usuario.tipo_usuario',
            'cargo.nombre_cargo')
          ->where('persona.'.$criterio, 'like', '%'. $buscar . '%') // Es necesario el nombre de la tabla + el nombre del campo
          ->orderBy('empleado.id_persona', 'desc')
          ->paginate(10);
      }
      
      return [
          'pagination' => [
              'total'        => $empleado->total(),
              'current_page' => $empleado->currentPage(),
              'per_page'     => $empleado->perPage(),
              'last_page'    => $empleado->lastPage(),
              'from'         => $empleado->firstItem(),
              'to'           => $empleado->lastItem(),
          ],
          'empleado' => $empleado
      ];
    }

    /**
     * Guarda un registro nuevo en la base
     */
    public function store(Request $request){
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
          'fecha_nacimiento' => 'date|before_or_equal:'.date('Y-m-d', strtotime("-18 years")),
          'telefono' => 'required|unique:telefono,telefono',

          'usuario' => 'required|unique:empleado,usuario'
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
          'fecha_nacimiento.before_or_equal' => 'El empleado debe tener 18 años o más para ser aceptado.',

          'usuario.required' => 'El usuario no debe ir vacío.',
          'usuario.unique' => 'El usuario no está disponible.'
        ];

        //Se validan los datos recibidos
        $this->validate($request, $reglasPersona, $erroresPersona);

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


        $empleado = new Empleado();
        // Se va a almacenar lo que se guardó en la columna id_persona de la tabla persona
        $empleado->id_persona = $persona->id_persona;
        $empleado->id_cargo = $request->id_cargo;
        $empleado->id_tipo_usuario = $request->id_tipo_usuario;
        
        $empleado->fecha_ingreso = date("Y").'-'.date("m").'-'.date("d"); // $request->fecha_ingreso;
        $empleado->usuario = $request->usuario;
        //$empleado->contrasena = bcrypt($request->contrasena);

        $empleado->contrasena = Hash::make($request->contrasena);

        $empleado->foto_url = "url.com"; //$request->foto_url;
        $empleado->sesion = date("Y").'-'.date("m").'-'.date("d"); // $request->sesion;
        $empleado->estado = 'A'; // A: Activo
        
        $empleado->save();
        
        DB::commit();

      } catch (exception $e) {
        DB::rollBack();
      }
    }

    /**
     * Realiza una comproabación antes de salvar los datos mediante el
     * método find o el método findOrFail
     */
    public function update(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      try {
        DB::beginTransaction();
        // Buscar el Empleado a modificar
        $empleado = Empleado::findOrFail($request->id_empleado); // Contiene toda la tabla empleado
        $persona = Persona::findOrFail($empleado->id_persona);
        $telefono = Telefono::where('id_persona', '=', $persona->id_persona)->first();
        
        //Reglas y mensajes de errores para validación de persona
        $reglasPersona = [
          'primer_nombre' => 'required|max:50',
          'primer_apellido' => 'required|max:50',
          'numero_identidad' => 'required|unique:persona,numero_identidad,'.$empleado->id_persona.',id_persona',
          'correo_electronico' => 'required|unique:persona,correo_electronico,'.$empleado->id_persona.',id_persona',
          'fecha_nacimiento' => 'date|before_or_equal:'.date('Y-m-d', strtotime("-4 years")),
          'telefono' => 'required|unique:telefono,telefono,'.$empleado->id_persona.',id_persona',
          'usuario' => 'required|unique:empleado,usuario,'.$empleado->id_empleado.',id_empleado',
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
          'fecha_nacimiento.before_or_equal' => 'El empleado debe tener 18 años o más para ser aceptado.',

          'usuario.required' => 'El usuario no debe ir vacío.',
          'usuario.unique' => 'El usuario no está disponible.'
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
        $persona->estado = 'A'; // A: Activo
        $persona->save();

        $telefono->telefono = $request->telefono;
        $telefono->save();

        $empleado->id_tipo_usuario = $request->id_tipo_usuario;
        $empleado->id_cargo = $request->id_cargo;
        //$empleado->fecha_ingreso = $request->fecha_ingreso; // No deberia ser posible cambiarlo una vez creado
        $empleado->usuario = $request->usuario;
        
        // $empleado->contrasena = Hash::make($request->contrasena);
        
        $empleado->foto_url = "url.com"; // $request->foto_url;
        $empleado->sesion = date("Y").'-'.date("m").'-'.date("d"); //$request->sesion;
        $empleado->estado = 'A'; // A: Activo
        $empleado->save();

        DB::commit();

      } catch (exception $e) {
        DB::rollBack();
      }
    }

    public function selectMaestro(Request $request){
      // Determina si la petición que se hace es diferente de ajax
      // De ser lo contrario, se va a a redirigir a la ruta raiz
      if (!$request->ajax()) return redirect('/');

      $maestro = Empleado::where('empleado.id_tipo_usuario', '=', 3)
      ->join('persona', 'empleado.id_persona', '=', 'persona.id_persona')
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
        
        'empleado.id_empleado',
        'empleado.id_cargo',
        'empleado.id_tipo_usuario',
        'empleado.fecha_ingreso',
        'empleado.usuario',
        'empleado.contrasena',
        'empleado.foto_url',
        'empleado.sesion',
        'empleado.estado')
      ->orderBy('empleado.id_persona', 'desc')
      ->get();

      return ['maestro' => $maestro]; 
  }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $empleado = Empleado::findOrFail($request->id_empleado);

      $empleado->estado = 'I'; // I: Inactivo
      
      $empleado->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $empleado = Empleado::findOrFail($request->id_empleado);

      $empleado->estado = 'A'; // A: Activo
      
      $empleado->save();
    }
}
