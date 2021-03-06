<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Gestión Educativa Laravel Vue Js">
    <meta name="author" content="Ariel Guardado y Allan Martínez">
    <meta name="keyword" content="Sistema educación Laravel Vue Js, Sistema gestión educación Laravel Vue Js">
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
    <link rel="shortcut icon" href="img/favicon.svg">

    <title>Sistema de Gestión Educativa (SGE)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Contiene todo el CSS -->
    <link href="css/all.css" rel="stylesheet">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <!-- Inicio App -->
    <div id="app">
      <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand" href="#"></a>

        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav ml-auto">
          <!--Notificaciones-->
          <notification :notifications="notifications"></notification>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <img src="img/avatars/6.jpg" class="img-avatar" alt="avatar">
              <!-- Clase:Auth, Método:user, propiedad: usuario donde esta almacenado el nombre del usuario -->
              <span class="d-md-down-none">{{ Auth::user()->usuario }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-header text-center">
                <strong>Cuenta</strong>
              </div>

              <a class="dropdown-item" href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-lock"></i> Cerrar sesión
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </div>
          </li>
        </ul>
      </header>

      <div class="app-body">
        <!-- Sidebar -->
        @if(Auth::check())
            @if(Auth::user()->id_tipo_usuario == 1)
                @include('plantilla.sidebaradministrador')

            @elseif(Auth::user()->id_tipo_usuario == 2)
                @include('plantilla.sidebaradministrador')

            @elseif(Auth::user()->id_tipo_usuario == 3)
                @include('plantilla.sidebarmaestro')

            @elseif(Auth::user()->id_tipo_usuario == 4)
                @include('plantilla.sidebaralumno')
            @else

            @endif
        @endif
        <!-- Fin Sidebar -->

        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
      </div>

    </div>
    <!-- Fin App -->

    <footer class="app-footer" style="position: fixed;left: 0px;bottom: 0px;height: 35px;width: 100%;">
      <span><a href="#">Sistema Gestión Educación</a> &copy; 2019</span>
      <span class="ml-auto">Desarrollado por <a href="#">Ariel Guardado y Allan Martínez</a></span>
    </footer>

    <!-- Contiene todo el Javascript -->
    <script src="js/app.js"></script>
    <script src="js/all.js"></script>

</body>

</html>