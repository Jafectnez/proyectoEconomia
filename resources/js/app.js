/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('persona', require('./components/Persona.vue').default);
Vue.component('cargo', require('./components/Cargo.vue').default);
Vue.component('tipousuario', require('./components/TipoUsuario.vue').default);
Vue.component('empleado', require('./components/Empleado.vue').default);
Vue.component('alumno', require('./components/Alumno.vue').default);
Vue.component('mensaje', require('./components/Mensaje.vue').default);
Vue.component('edificio', require('./components/Edificio.vue').default);
Vue.component('aula', require('./components/Aula.vue').default);
Vue.component('clase', require('./components/Clase.vue').default);
Vue.component('grado', require('./components/Grado.vue').default);
Vue.component('asignatura', require('./components/Asignatura.vue').default);
Vue.component('parcial', require('./components/Parcial.vue').default);
Vue.component('tipocalificacion', require('./components/TipoCalificacion.vue').default);
Vue.component('archivo', require('./components/Archivo.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);

//Componentes para alumno
Vue.component('edificio-alumno', require('./components/Edificio-alumno.vue').default);
Vue.component('aula-alumno', require('./components/Aula-alumno.vue').default);
Vue.component('clase-alumno', require('./components/Clase-alumno.vue').default);
Vue.component('grado-alumno', require('./components/Grado-alumno.vue').default);

//Componentes para maestro
Vue.component('clase-maestro', require('./components/Clase-maestro.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
      menu: 0,
      seccion: 0,
      notifications: []
    },
    created() {
      let me = this;

      axios.get('notificacion/get').then(function(response) {
        me.notifications = response.data.slice(0, 10);
      })
      .catch(function(error) {
        console.log("Error Axios:" + error);
      });

      var userId = $('meta[name="userId"]').attr('content');

      Echo.private('App.User.'+userId)
      .notification((notification) => {
        me.notifications.unshift(notification);
      });
    }
});
