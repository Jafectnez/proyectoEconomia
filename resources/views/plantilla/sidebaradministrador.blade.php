<div class="sidebar">
  <nav class="sidebar-nav" style="overflow-y:hidden;">
    <ul class="nav">
      <li @click="menu=0" class="nav-item">
        <a class="nav-link active" href="#"><i class="icon-speedometer"></i>Escritorio</a>
      </li>

      <li class="nav-title">
          Mantenimiento
      </li>
      <!--nav-dropdown-toggle-->
      <li class="nav-item nav-dropdown" v-on:click="seccion!=1 ? seccion=1 : seccion=0" v-bind:class="{open: seccion == 1}">
        <a class="nav-link " href="#"><i class="icon-people"></i>Recursos Humanos</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=1" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-people"></i>Empleados</a>
          </li>

          <li @click="menu=2" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-people"></i>Alumnos</a>
          </li>
        </ul>
      </li>

      <li class="nav-item nav-dropdown" v-on:click="seccion!=2 ? seccion=2 : seccion=0" v-bind:class="{open: seccion == 2}">
        <a class="nav-link" @click="menu=4" href="#"><i class="icon-envelope"></i>Mensajes</a>
      </li>
      
      <li class="nav-item nav-dropdown" v-on:click="seccion!=3 ? seccion=3 : seccion=0" v-bind:class="{open: seccion == 3}">
        <a class="nav-link" href="#"><i class="icon-lock"></i>Acceso</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=5" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-user-following"></i>Tipo de Usuario</a>
          </li>

          <li @click="menu=6" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-user-following"></i>Cargos</a>
          </li>
        </ul>
      </li>

      <li class="nav-item nav-dropdown" v-on:click="seccion!=4 ? seccion=4 : seccion=0" v-bind:class="{open: seccion == 4}">
        <a class="nav-link " href="#"><i class="icon-organization"></i>Infraestructura</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=9" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-layers"></i>Edificios</a>
          </li>

          <li @click="menu=10" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-home"></i>Aulas</a>
          </li>
        </ul>
      </li>

      <li class="nav-item nav-dropdown" v-on:click="seccion!=5 ? seccion=5 : seccion=0" v-bind:class="{open: seccion == 5}">
        <a class="nav-link " href="#"><i class="icon-graduation"></i>Académico</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=11" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Grados</a>
          </li>

          <li @click="menu=12" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Asignaturas</a>
          </li>

          <li @click="menu=13" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Clases</a>
          </li>

          <li @click="menu=14" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Parcial</a>
          </li>

          <li @click="menu=15" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Tipo Calificación</a>
          </li>

          <li @click="menu=16" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Archivos</a>
          </li>
        </ul>
      </li>

    </ul>
  </nav>

  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>