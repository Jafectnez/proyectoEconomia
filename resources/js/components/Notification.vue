<template>
  <li class="nav-item d-md-down-none">
    <a class="nav-link" href="#" data-toggle="dropdown" @click="marcarLeidas()">
      <i class="icon-bell"></i>
      <span class="badge badge-pill badge-danger">{{calcular > 0 ? '!':''}}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-header text-center">
        <strong>Notificaciones</strong>
      </div>
      <div v-if="calcular != 0">
        <li v-for="item in notifications" :key="item.id">
          <a class="dropdown-item" href="#">
            <i class="fa fa-envelope-o"></i>{{item.data.mensaje}}
          </a>
        </li>
      </div>
      <div v-else>
          <a class="dropdown-item" href="#">
            No hay notificaciones
          </a>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  props: ['notifications'],
  data (){
    return {
      totalNotificaciones: 0,
      leidas: false
    }
  },
  computed: {
    calcular: function(){
      //Verifica si hay nuevas notificaciones y si no han sido leidas
      if((this.totalNotificaciones != this.notifications.length)){
        this.leidas = false;
        return this.totalNotificaciones = this.notifications.length;
      }

      //Verifica que las notificaciones aun no han sido leidas
      // if(!this.leidas)
      //   return this.totalNotificaciones = this.notifications.length;
      
      //No hay notificaciones por leer
      return 0;
    }
  },
  methods: {
    marcarLeidas: function (){
      let me = this;
      
      me.leidas = true;

      axios.get('notificacion/leidas').then(function(response) {
        // console.log('Notificaciones Leídas');
        
      })
      .catch(function(error) {
        console.log("Error Axios:" + error);
      });
    }
  }
}
</script>