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
        <a class="nav-link" @click="menu=1" href="#"><i class="icon-notebook"></i>Clases</a>
      </li>

      <li class="nav-item nav-dropdown" v-on:click="seccion!=2 ? seccion=2 : seccion=0" v-bind:class="{open: seccion == 2}">
        <a class="nav-link" @click="menu=2" href="#"><i class="icon-envelope"></i>Mensajes</a>
      </li>

      <li class="nav-item nav-dropdown" v-on:click="seccion!=3 ? seccion=3 : seccion=0" v-bind:class="{open: seccion == 3}">
        <a class="nav-link " href="#"><i class="icon-organization"></i>Infraestructura</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=3" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-layers"></i>Edificios</a>
          </li>

          <li @click="menu=4" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-home"></i>Aulas</a>
          </li>
        </ul>
      </li>
      <!--        
      <li class="nav-item nav-dropdown" v-on:click="seccion!=4 ? seccion=4 : seccion=0" v-bind:class="{open: seccion == 4}">
        <a class="nav-link " href="#"><i class="icon-graduation"></i>Académico</a>
        <ul class="nav-dropdown-items">
          <li @click="menu=5" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Grados</a>
          </li>

          <li @click="menu=6" class="nav-item">
            <a class="nav-link" href="#"><i class="icon-chart"></i>Asignaturas</a>
          </li>
        </ul>
      </li> 
      -->

    </ul>
  </nav>

  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>