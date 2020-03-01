@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card-group mb-0">
      <div class="card p-4">

        <form class="form-horizontal was-validated" method="POST" action="{{ route('login') }}">

          <!-- Proteger la aplicación de solicitudes de falsificación a través de sitios cross site -->
          {{ csrf_field() }}

          <div class="card-body">
            <h1 class="text-center">Acceder</h1>
            <p class="text-muted text-center">Control de acceso al sistema</p>

            <div class="botones-login mb-3">
              <label for="login">
                <input style="display:none" type="radio" name="login" id="login" value="E" class="btn btn-primary px-4"></button>
                <span class="btn-success" id="spn-empleado">Empleado</span>
              </label>
              
              <label for="login2">
                <input style="display:none" type="radio" name="login" id="login2" value="A" class="btn btn-primary px-4" checked></button>
                <span class="btn-primary" id="spn-alumno">Alumno</span>
              </label>
            </div>

            <!-- Si se lanza una excepción en el campo codigo_empleado, se agrega la clase, is-invalid a todo el div -->
            <div id="login-empleado" style="display:none" class="mb-3 {{ $errors->has('codigo_empleado' ? 'is-invalid' : '') }}">
              <span style="border-right:1px solid #c2cfd6" class="input-group-addon"><i class="icon-user"></i>&nbsp;&nbsp;Usuario</span>
              <input type="text" value="{{old('codigo_empleado')}}" name="codigo_empleado" id="codigo_empleado" class="form-control" placeholder="Código Empleado">
              <div class="invalid-feedback">  
                {!! $errors->first('codigo_empleado', '<span >:message</span>') !!}
              </div>
            </div>

            <!-- Si se lanza una excepción en el campo cuenta_alumno, se agrega la clase, is-invalid a todo el div -->
            <div id="login-alumno" class="mb-3 {{ $errors->has('cuenta_alumno' ? 'is-invalid' : '') }}">
              <span style="border-right:1px solid #c2cfd6" class="input-group-addon"><i class="icon-user"></i>&nbsp;&nbsp;Usuario</span>
              <input type="text" value="{{old('cuenta_alumno')}}" name="cuenta_alumno" id="cuenta_alumno" class="form-control" placeholder="Cuenta Alumno">
              <div class="invalid-feedback">
                {!! $errors->first('cuenta_alumno', '<span >:message</span>') !!}
              </div>
            </div>

            <div class="mb-3 {{ $errors->has('contrasena' ? 'is-invalid' : '') }}">
              <span style="border-right:1px solid #c2cfd6" class="input-group-addon"><i class="icon-lock"></i>&nbsp;&nbsp;Contraseña</span>
              <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña">
            </div>

            <div class="row">
              <div class="col-6">
                <button id="btn-login" type="submit" class="btn btn-primary px-4">Acceder</button>
              </div>
              <!--
              <div class="col-6 text-right">
                <button type="button" class="btn btn-link px-0">Olvidaste tu contraseña?</button>
              </div>
              -->
            </div>

          </div>
        </form>
      </div>

      <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
        <div class="card-body text-center">
          <div>
            <img src="/img/logo-SGE.svg" style="width:250px;">
            <h2>Sistema de Gestión Educativa</h2>
            <p>Plataforma desarrollada para brindar asistencia a las instituciones educativas.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
