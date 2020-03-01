<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB; - No es necesaria usando Paginación de Eloquent
use App\Persona;

class PersonaController extends Controller {

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
        $persona = Persona::orderBy('id_persona', 'desc')->paginate(10);
      }
      else {
        // '%' significa que el texto puede estar al inicio, centro o al final, del campo criterio
        $persona = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_persona', 'desc')->paginate(10);
      }

      return [
          'pagination' => [
              'total'        => $persona->total(), // Indica el total de registros
              'current_page' => $persona->currentPage(),
              'per_page'     => $persona->perPage(), // Cuantos registros de deben mostrar por página
              'last_page'    => $persona->lastPage(), // Registros por página
              'from'         => $persona->firstItem(), // Primera página
              'to'           => $persona->lastItem(), // Cuantos registros se están viasualizando
          ],
          'persona' => $persona
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

      $persona = Persona::findOrFail($request->id_persona);

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
    }

    public function desactivar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');

      $persona = Persona::findOrFail($request->id_persona);

      $persona->estado = 'I'; // I: Inactivo
      
      $persona->save();
    }

    public function activar(Request $request) {
      // Determinar si la petición que se hace es diferente de ajax
      // De lo contrario va a a redirigir a la ruta raiz
      if(!$request->ajax()) return redirect('/');
      
      $persona = Persona::findOrFail($request->id_persona);

      $persona->estado = 'A'; // A: Activo
      
      $persona->save();
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
