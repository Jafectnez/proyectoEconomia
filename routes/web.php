<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login'); // Muestra el formulario de Login

// Grupo de Rutas para un Usuario No Autenticado
Route::group(['middleware'=>['guest']], function(){
  Route::get('/', 'Auth\LoginController@showLoginForm'); // Muestra el formulario de Login
  Route::post('/login', 'Auth\LoginController@login')->name('login'); // name() - es una alias
});

// Grupo de Rutas para un Usuario Autenticado
Route::group(['middleware'=>['auth']], function(){

  Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

  Route::get('/notificacion/get', 'NotificationController@get');
  Route::get('/notificacion/leidas', 'NotificationController@leidas');

  Route::get('/main', function () {
    return view('contenido/contenido');
  })->name('main'); // name() - es una alias

  // Gurpo de Rutas para un Alumno - solamente puede ver, no editar
  Route::group(['middleware'=>['Alumno']], function(){
    
    Route::get('/clase', 'claseController@index');
    Route::get('/clase/alumno', 'ClaseController@clasesAlumno');
    Route::post('/clase/verNotasDeAlumno', 'ClaseController@verNotasDeAlumno');
    Route::post('/clase/calcularIndiceClase', 'ClaseController@calcularIndiceClase');
    Route::post('/clase/calcularIndiceGlobal', 'ClaseController@calcularIndiceGlobal');

    Route::get('/grado', 'GradoController@index');
    
    Route::get('/asignatura', 'AsignaturaController@index');

    Route::get('/edificio', 'EdificioController@index');
    
    Route::get('/aula', 'AulaController@index');

    Route::get('/mensaje', 'MensajeController@index');
  });

  // Grupo de Rutas para un Empleado
  Route::group(['middleware'=>['Empleado']], function(){

    Route::get('/clase', 'ClaseController@index');
    Route::post('/clase/registrar', 'ClaseController@store');
    Route::put('/clase/actualizar', 'ClaseController@update');
    Route::put('/clase/desactivar', 'ClaseController@desactivar');
    Route::put('/clase/activar', 'ClaseController@activar');
    Route::get('/clase/maestro', 'ClaseController@clasesMaestro');
    Route::post('/clase/alumnos', 'ClaseController@alumnosClase');
    Route::post('/clase/verNotaAlumno', 'ClaseController@verNotasAlumno');
    Route::post('/clase/listarAlumnosClase', 'ClaseController@listarAlumnosClase'); // Determina los alumnos de una clase especifica
    Route::post('/clase/verNotasDeAlumno', 'ClaseController@verNotasDeAlumno');

    Route::get('/grado', 'GradoController@index');
    Route::post('/grado/registrar', 'GradoController@store');
    Route::put('/grado/actualizar', 'GradoController@update');
    Route::put('/grado/desactivar', 'GradoController@desactivar');
    Route::put('/grado/activar', 'GradoController@activar');
    
    Route::get('/asignatura', 'AsignaturaController@index');
    Route::post('/asignatura/registrar', 'AsignaturaController@store');
    Route::put('/asignatura/actualizar', 'AsignaturaController@update');
    Route::put('/asignatura/desactivar', 'AsignaturaController@desactivar');
    Route::put('/asignatura/activar', 'AsignaturaController@activar');

    Route::get('/edificio', 'EdificioController@index');
    Route::post('/edificio/registrar', 'EdificioController@store');
    Route::put('/edificio/actualizar', 'EdificioController@update');
    Route::put('/edificio/desactivar', 'EdificioController@desactivar');
    Route::put('/edificio/activar', 'EdificioController@activar');
    
    Route::get('/aula', 'AulaController@index');
    Route::post('/aula/registrar', 'AulaController@store');
    Route::put('/aula/actualizar', 'AulaController@update');
    Route::put('/aula/desactivar', 'AulaController@desactivar');
    Route::put('/aula/activar', 'AulaController@activar');

    Route::get('/mensaje', 'MensajeController@index');
  });

  // Grupo de Rutas para un Administrador
  Route::group(['middleware'=>['Administrador']], function(){

    Route::get('/clase', 'ClaseController@index');
    Route::post('/clase/registrar', 'ClaseController@store');
    Route::put('/clase/actualizar', 'ClaseController@update');
    Route::put('/clase/desactivar', 'ClaseController@desactivar');
    Route::put('/clase/activar', 'ClaseController@activar');
    
    Route::get('/clase/maestro', 'ClaseController@clasesMaestro'); // Determina las clases que tiene un maestro
    Route::get('/clase/alumno', 'ClaseController@clasesAlumno'); // Determina las clases en las que está matriculado un alumno
    Route::post('/clase/alumnos', 'ClaseController@alumnosClase'); //Determina los alumnos matriculados en una clase (Con y sin calificación de sus notas)
    Route::post('/clase/alumnos/matriculados', 'ClaseController@alumnosClaseMatriculados'); //Determina los alumnos matriculados en una clase
    Route::post('/clase/registrarNota', 'ClaseController@registrarNota');
    Route::put('/clase/actualizarNota', 'ClaseController@actualizarNota');
    Route::post('/clase/matricularAlumno', 'ClaseController@matricularAlumno');
    Route::post('/clase/listarAlumnosClase', 'ClaseController@listarAlumnosClase'); // Determina los alumnos de una clase especifica
    Route::post('/clase/verNotaAlumno', 'ClaseController@verNotasAlumno');
    Route::post('/clase/verNotasDeAlumno', 'ClaseController@verNotasDeAlumno');

    Route::get('/clase/listarPDF', 'ClaseController@listarPDF')->name('aprobados_pdf'); // Para los reportes

    Route::get('/persona', 'PersonaController@index');
    Route::post('/persona/registrar', 'PersonaController@store');
    Route::put('/persona/actualizar', 'PersonaController@update');
    Route::put('/persona/desactivar', 'PersonaController@desactivar');
    Route::put('/persona/activar', 'PersonaController@activar');
    
    Route::get('/cargo', 'CargoController@index');
    Route::post('/cargo/registrar', 'CargoController@store');
    Route::put('/cargo/actualizar', 'CargoController@update');
    Route::put('/cargo/desactivar', 'CargoController@desactivar');
    Route::put('/cargo/activar', 'CargoController@activar');
    Route::get('/cargo/selectCargo', 'CargoController@selectCargo');
    
    Route::get('/tipousuario', 'TipoUsuarioController@index');
    Route::post('/tipousuario/registrar', 'TipoUsuarioController@store');
    Route::put('/tipousuario/actualizar', 'TipoUsuarioController@update');
    Route::put('/tipousuario/desactivar', 'TipoUsuarioController@desactivar');
    Route::put('/tipousuario/activar', 'TipoUsuarioController@activar');
    Route::get('/tipousuario/selectTipo', 'TipoUsuarioController@selectTipo');

    Route::get('/tipocalificacion', 'TipoCalificacionController@index');
    Route::post('/tipocalificacion/registrar', 'TipoCalificacionController@store');
    Route::put('/tipocalificacion/actualizar', 'TipoCalificacionController@update');
    Route::put('/tipocalificacion/desactivar', 'TipoCalificacionController@desactivar');
    Route::put('/tipocalificacion/activar', 'TipoCalificacionController@activar');
    Route::get('/tipocalificacion/selectTipoCalificacion', 'TipoCalificacionController@selectTipoCalificacion');

    Route::get('/parcial', 'ParcialController@index');
    Route::post('/parcial/registrar', 'ParcialController@store');
    Route::put('/parcial/actualizar', 'ParcialController@update');
    Route::put('/parcial/desactivar', 'ParcialController@desactivar');
    Route::put('/parcial/activar', 'ParcialController@activar');
    Route::get('/parcial/selectParcial', 'ParcialController@selectParcial');

    Route::get('/archivo', 'ArchivoController@index');
    Route::post('/archivo/registrar', 'ArchivoController@store');
    Route::put('/archivo/actualizar', 'ArchivoController@update');
    Route::put('/archivo/desactivar', 'ArchivoController@desactivar');
    Route::put('/archivo/activar', 'ArchivoController@activar');
    Route::get('/archivo/selectArchivo', 'ArchivoController@selectArchivo');
    
    Route::get('/empleado', 'EmpleadoController@index');
    Route::post('/empleado/registrar', 'EmpleadoController@store');
    Route::put('/empleado/actualizar', 'EmpleadoController@update');
    Route::put('/empleado/desactivar', 'EmpleadoController@desactivar');
    Route::put('/empleado/activar', 'EmpleadoController@activar');
    Route::get('/empleado/selectMaestro', 'EmpleadoController@selectMaestro');
    
    Route::get('/alumno', 'AlumnoController@index');
    Route::post('/alumno/registrar', 'AlumnoController@store');
    Route::put('/alumno/actualizar', 'AlumnoController@update');
    Route::put('/alumno/desactivar', 'AlumnoController@desactivar');
    Route::put('/alumno/activar', 'AlumnoController@activar');
    Route::get('/alumno/listar', 'AlumnoController@listarAlumnos');
    Route::get('/alumno/ultimo', 'AlumnoController@ultimoAlumno');

    Route::get('/grado', 'GradoController@index');
    Route::post('/grado/registrar', 'GradoController@store');
    Route::put('/grado/actualizar', 'GradoController@update');
    Route::put('/grado/desactivar', 'GradoController@desactivar');
    Route::put('/grado/activar', 'GradoController@activar');
    Route::get('/grado/selectGrado', 'GradoController@selectGrado');
    
    Route::get('/asignatura', 'AsignaturaController@index');
    Route::post('/asignatura/registrar', 'AsignaturaController@store');
    Route::put('/asignatura/actualizar', 'AsignaturaController@update');
    Route::put('/asignatura/desactivar', 'AsignaturaController@desactivar');
    Route::put('/asignatura/activar', 'AsignaturaController@activar');
    Route::get('/asignatura/selectAsignatura', 'AsignaturaController@selectAsignatura');

    Route::get('/edificio', 'EdificioController@index');
    Route::post('/edificio/registrar', 'EdificioController@store');
    Route::put('/edificio/actualizar', 'EdificioController@update');
    Route::put('/edificio/desactivar', 'EdificioController@desactivar');
    Route::put('/edificio/activar', 'EdificioController@activar');
    
    Route::get('/aula', 'AulaController@index');
    Route::post('/aula/registrar', 'AulaController@store');
    Route::put('/aula/actualizar', 'AulaController@update');
    Route::put('/aula/desactivar', 'AulaController@desactivar');
    Route::put('/aula/activar', 'AulaController@activar');
    Route::get('/aula/selectAula', 'AulaController@selectAula');

    Route::get('/mensaje', 'MensajeController@index');
    Route::post('/mensaje/enviar', 'MensajeController@store');

  });
  
});

//Route::get('/home', 'HomeController@index')->name('home');

