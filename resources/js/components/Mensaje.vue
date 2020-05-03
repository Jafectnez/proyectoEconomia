<template>
  
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Mensajería</li>
    </ol>

    <link href="css/chat.css" rel="stylesheet">
    <div class="container" style="margin-left:0; max-width:100%">
      <div class="messaging mt-3">
        <div class="inbox_msg">

          <div class="inbox_people">
            <div class="headind_srch">
              <div class="recent_heading" v-if="atras == true" style="cursor:pointer">
                <span @click="listar()"><i class="btn btn-primary icon-arrow-left-circle">Atrás</i></span>
              </div>
              <div class="srch_bar">
                <div class="input-group">
                    <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                      <option value="primer_nombre">Nombre</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="buscarContacto(buscar, criterio)" class="form-control" placeholder="Buscar">
                    <button type="submit" @click="buscarContacto(buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                </div>
              </div>
            </div>
            <div class="inbox_chat"> 

              <div class="chat_list" style="cursor:pointer;" v-for="contacto in arrayContactos" :key="contacto.id_persona" @click="id_contacto = contacto.id_persona;filtro(contacto.id_persona);" v-bind:id="contacto.id_persona">
                <div class="chat_people">
                  <div class="chat_ib">
                    <h5 v-text="contacto.primer_nombre + ' ' + contacto.segundo_nombre + ' ' + primer_apellido + ' ' + contacto.segundo_apellido"></h5>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="mesgs" style="background: rgb(255, 255, 255);" v-if="id_contacto != ''">
            <div class="msg_history" style="height: 560px;">
              <div v-for="mensaje in arrayMensajes" :key="mensaje.id_mensaje">

                <div class="incoming_msg" v-if="mensaje.id_emisor == id_contacto">
                  <div class="received_msg">
                    <div class="received_withd_msg">
                      <p v-text="mensaje.mensaje"></p>
                      <span class="time_date" v-text="mensaje.fecha_envio"></span></div>
                  </div>
                </div>
                
                <div class="outgoing_msg" v-if="mensaje.id_emisor == id_usuario && mensaje.id_receptor == id_contacto">
                  <div class="sent_msg">
                    <p v-text="mensaje.mensaje"></p>
                    <span class="time_date" v-text="mensaje.fecha_envio"></span> </div>
                </div>
              </div>
              
            </div>
            <div class="type_msg">
              <div class="input_msg_write">
                <input type="text" v-model="contenido_mensaje" class="write_msg pl-2" placeholder="Escribir" @keyup.enter="enviarMensaje();">
                <button style="top:8px;" class="msg_send_btn mr-2" type="button" v-if="contenido_mensaje != ''" @click="enviarMensaje();"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
        </div>        
      </div>
    </div>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        // Variables de Tabla Mensaje
        id_mensaje: '',
        id_receptor: '',
        id_emisor: '',
        mensaje: '',
        fecha_envio: '',
        estado: '',
        
        // Variables de Tabla Persona
        id_persona: '',
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        
        // Variables auxiliares
        contenido_mensaje: '',
        id_contacto: '',
        id_usuario: '',
        arrayMensajes: [], // Almacena los datos del objeto response de mensajes
        arrayContactos : [], // Almacena los datos del objeto response de contactos
        tituloModal: '',
        tipoAccion: 0, // 1: Enviar 2: Actualizar
        offset: 2, // Cantidad de páginas a la izq y der de la actual
        criterio: 'primer_nombre', // Indica cual es el campo de busqueda
        buscar: '', // Cual es el texto de busqueda para realizar el filtro de todos los registros
        isActive: 0, // Ayuda a saber cuál chat esta seleccionado en este momento
        atras: false //Ayuda a saber si se puede regresar o no
      }
    },
    computed: {
      isActived: function(){
        return this.pagination.current_page;
      },
      // Calcula los elementos de la paginación
      pagesNumber: function() {
        // Es diferente del ultimo elemento de la página actual
        if(!this.pagination.to) {
          return [];
        }
        
        var from = this.pagination.current_page - this.offset; 
        if(from < 1) {
          from = 1;
        }

        var to = from + (this.offset * 2); 
        if(to >= this.pagination.last_page){
          to = this.pagination.last_page;
        }  

        var pagesArray = [];
        // Mientras la página actual sea menor que la última página
        while(from <= to) {
          pagesArray.push(from);
          from++;
        }
        return pagesArray;             
      }
    },
    methods: {
      filtro(id_persona){
        if(this.isActive != 0){
          document.getElementById(this.isActive).classList.toggle("active_chat");
          document.getElementById(id_persona).classList.toggle("active_chat");
        }else{
          document.getElementById(id_persona).classList.toggle("active_chat");
        }
        this.isActive = id_persona
      },
      listar(){
        let me = this;
        var url = '/mensaje';
  
        axios.get(url).then(function (response) {
          var respuesta = response;
          me.arrayMensajes = respuesta.data.mensajes;
          me.arrayContactos = respuesta.data.contactos;
          me.id_usuario = respuesta.data.usuario;
          me.pagination = respuesta.pagination;
          me.atras = false;
        })
        .catch(function(error) {
          console.log(error);
        }); 
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listar(page, buscar, criterio);
      },
      enviarMensaje(){
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/mensaje/enviar', {
          'contenido_mensaje': this.contenido_mensaje,
          'id_contacto': this.id_contacto
        }).then(function(response){
          me.listar();
          me.arrayMensajes = response.data.mensajes;
          me.id_usuario = response.data.usuario;
          me.contenido_mensaje = '';
          
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      buscarContacto(buscar, criterio){
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.get('/mensaje?buscar='+buscar+'&criterio='+criterio).then(function(response){
          me.atras = true;
          me.arrayContactos = response.data.personas;
          me.id_usuario = response.data.usuario;
        })
        .catch(function(error) {
          console.log(error);
        });
      }
    },
    mounted() {
      this.listar();
      this.$ga.page('/Mensaje');
    }
  }
</script>

<style>
  .chat_list:hover{
    background: rgba(0, 0, 0, 0.1);
  }
</style>