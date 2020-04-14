<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm(){
      return view('auth.login');
    }

    public function login(Request $request){
      $this->validateLogin($request);

      if($request->login == "A"){

        $licencia = DB::table('licencia')
          ->select(DB::raw('estado'))
          ->where([['fecha_vencimiento', '>=', Carbon::now()],['numero_licencia', '=', 'asd.456']])
          ->get();
        $conteo = sizeof($licencia);

        if($conteo == 0 || $licencia[0]->estado == "I") {
          return back()
            ->withErrors(['cuenta_alumno' => 'Error: Licencia Vencida'])
            ->withInput(request(['cuenta_alumno']));
        }
        
        //Login de Alumnos
        if (Auth::attempt([
          'usuario' => $request->cuenta_alumno, 
          'password' => $request->contrasena, 
          'estado' => 'A'])){
            return redirect()->route('main');
          }

        // Si el usuario no es el correcto, se retorna a la página anterior, 
        // y se va a mostrar el error en el identificador usuario de la plantilla blade.
        return back()
          ->withErrors(['cuenta_alumno' => 'Error: Verifique los datos he intente de nuevo'])
          ->withInput(request(['cuenta_alumno'])); // retorna el usuario que se habia escrito en el input

      } else {

        $licencia = DB::table('licencia')
        ->select(DB::raw('estado'))
        ->where([['fecha_vencimiento', '>=', Carbon::now()],['numero_licencia', '=', 'asd.456']])
        ->get();
        $conteo = sizeof($licencia);

        if($conteo == 0 || $licencia[0]->estado == "I") {
          return back()
            ->withErrors(['codigo_empleado' => 'Error: Licencia Vencida'])
            ->withInput(request(['codigo_empleado']));
        }

        //Login de Empleados        
        if (Auth::attempt([
          'usuario' => $request->codigo_empleado, 
          'password' => $request->contrasena, 
          'estado' => 'A'
          ])){
            return redirect()->route('main');
        }
  
        return back()
          ->withErrors(['codigo_empleado' => 'Error: Verifique los datos he intente de nuevo'])
          ->withInput(request(['codigo_empleado']));
      }
    }

    protected function validateLogin(Request $request){
      if ($request->login == "A"){
        // Alumno
        $this->validate($request, [
          'cuenta_alumno' => 'required|string', // campo obligatorio tipo string
          'contrasena' => 'required|string' // campo obligatorio tipo string
        ]);

      } else {
        // Empleado
        $this->validate($request, [
          'codigo_empleado' => 'required|string', // campo obligatorio tipo string
          'contrasena' => 'required|string' // campo obligatorio tipo string
        ]);
        
      }
    }

    public function logout(Request $request){
      Auth::logout();
      $request->session()->invalidate();
      return redirect('/login');
    }
}
