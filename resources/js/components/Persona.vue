<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Recursos Humanos</li>
          <li class="breadcrumb-item active">Personas</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('persona','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nuevo Registro
              </button>
              <!--<i class="fa fa-align-justify"></i> Personas-->
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio">
                      <option value="primer_nombre">Nombre</option>
                      <option value="primer_apellido">Apellido</option>
                      <option value="correo_electronico">correo</option>
                      <option value="numero_identidad">Número de Identidad</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarPersona(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    <button type="submit" @click="listarPersona(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Correo Electrónico</th>
                      <th>Estado</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="persona in arrayPersona" :key="persona.id_persona">
                      <td>
                        <button type="button" @click="abrirModal('persona', 'actualizar', persona)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                          <i class="icon-pencil"></i>
                        </button> &nbsp;
                        
                        <template v-if="persona.estado == 'A'">
                          <!-- Si el estado de la persona es Activo, se muestra este botón -->
                          <button type="button"  class="btn btn-danger btn-sm" @click="desactivarPersona(persona.id_persona)">
                            <i class="icon-trash"></i>
                          </button>
                        </template>

                        <template v-else>
                          <!-- De lo contrario si el estado de la persona es Inactivo, se muestra este botón -->
                          <button type="button"  class="btn btn-info btn-sm" @click="activarPersona(persona.id_persona)">
                            <i class="icon-check"></i>
                          </button>
                        </template>
                      </td>

                      <td v-text="persona.primer_nombre"></td>

                      <td v-text="persona.primer_apellido"></td>

                      <td v-text="persona.correo_electronico"></td>

                      <td>
                          <div v-if="persona.estado=='A'">
                            <span class="badge badge-success">Activo</span>
                          </div>
                          <div v-else>
                            <span class="badge badge-danger">Inactivo</span>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <nav>
                <ul class="pagination">
                  <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                  </li>
                  
                  <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                  </li>

                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                  </li>
                </ul>
              </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
      </div>
      
      <!--Inicio del modal Agregar/Actualizar-->
      <!--:class={'mostrar' : modal}-->
      <div class="modal fade" id="modalNuevo" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h4 v-text="tituloModal" class="modal-title"></h4>
                <button type="button" class="close" @click="cerrarModal()" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Primer Nombre</label>
                    <div class="col-md-9">
                      <input type="text" v-model="primer_nombre" class="form-control" placeholder="Primer Nombre">
                      <span class="text-error" v-if="errores.primer_nombre==1">El primer nombre no puede ir vacio</span>
                      <!--<span class="help-block">(*) Ingrese el nombre de la Persona</span>-->
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="segundo-nombre">Segundo Nombre</label>
                    <div class="col-md-9">
                      <input type="text" v-model="segundo_nombre" class="form-control" placeholder="Segundo Nombre">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="primer-apellido">Primer Apellido</label>
                    <div class="col-md-9">
                      <input type="text" v-model="primer_apellido" class="form-control" placeholder="Primer Apellido">
                      <span class="text-error" v-if="errores.primer_apellido==1">El primer apellido no puede ir vacio</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="segundo-apellido">Segundo Apellido</label>
                    <div class="col-md-9">
                      <input type="text" v-model="segundo_apellido" class="form-control" placeholder="Segundo Apellido">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="numero-identidad">Número de Identidad</label>
                    <div class="col-md-9">
                      <input type="text" v-model="numero_identidad" class="form-control" placeholder="Número de Identidad">
                      <span class="text-error" v-if="errores.numero_identidad==1">El número de identidad no puede ir vacio</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="fecha-nacimiento">Fecha Nacimiento</label>
                    <div class="col-md-9">
                      <input type="date" v-model="fecha_nacimiento" class="form-control" placeholder="Fecha Nacimiento">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="correo">Correo</label>
                    <div class="col-md-9">
                      <input type="text" v-model="correo_electronico" class="form-control" placeholder="Correo">
                      <span class="text-error" v-if="errores.correo_electronico==1">El correo electrónico no puede ir vacio</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="sexo">Sexo</label>
                    <div class="col-md-9">
                      <select v-model="sexo" class="form-control">
                        <option disabled value>--Seleccione una opción--</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="direccion">Dirección</label>
                    <div class="col-md-9">
                      <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                    </div>
                  </div>

                  <div class="form-group row" v-show="errorPersona">
                    <div class="col-12 text-error">
                      <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                      </div>
                    </div>
                  </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                <button type="button" v-if="tipoAccion==1" @click="registrarPersona()" class="btn btn-primary">Guardar</button>
                <button type="button" v-if="tipoAccion==2" @click="actualizarPersona()" class="btn btn-primary">actualizar</button>

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

  </main>
</template>

<script>
  export default {
    data () {
      return {
        // Variables de Tabla
        id_persona: 0,
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        numero_identidad: '',
        fecha_nacimiento: '',
        correo_electronico: '',
        sexo: '',
        direccion: '',

        // Variables auxiliares
        arrayPersona : [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar,s 2: Actualizar
        errorPersona: 0, // 0: No hay Error, 1: Existe un error
        errorMostrarMsjPersona: [],
        errores : {
          "primer_nombre" : 0,
          "primer_apellido" : 0,
          "numero_identidad" : 0,
          "correo_electronico" : 0,
          "total_errores" : 0
        },
        pagination: {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
        },
        offset : 2, // Cantidad de páginas a la izq y der de la actual
        criterio : 'primer_nombre', // Indica cual es el campo de busqueda
        buscar : '' // Cual es el texto de busqueda para realizar el filtro de todos los registros
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
      listarPersona(page, buscar, criterio){
          let me = this;
          var url = '/persona?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayPersona = respuesta.persona.data;
            me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      cambiarPagina(page, buscar, criterio){
          let me = this;
          //Actualiza la página actual
          me.pagination.current_page = page;
          //Envia la petición para visualizar la data de esa página
          me.listarPersona(page, buscar, criterio);
      },
      registrarPersona(){
        // Si el método validarPersona devuelve 1 significa que hay un error.
        if(this.validarPersona()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/persona/registrar', { 
          'primer_nombre': this.primer_nombre,
          'segundo_nombre': this.segundo_nombre,
          'primer_apellido': this.primer_apellido,
          'segundo_apellido': this.segundo_apellido,
          'numero_identidad': this.numero_identidad,
          'fecha_nacimiento': this.fecha_nacimiento,
          'correo_electronico': this.correo_electronico,
          'sexo': this.sexo,
          'direccion': this.direccion
        }).then(function(response){
          me.cerrarModal();
          me.listarPersona(1, '', 'primer_nombre');

          Swal.fire({
            type: 'success',
            title: 'Registrado.',
            text: 'El registro ha sido guardado con éxito.',
          });
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      actualizarPersona(){
        // Si el método validarPersona devuelve 1 significa que hay un error.
        if(this.validarPersona()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/persona/actualizar', {
          'id_persona': this.id_persona, 
          'primer_nombre': this.primer_nombre,
          'segundo_nombre': this.segundo_nombre,
          'primer_apellido': this.primer_apellido,
          'segundo_apellido': this.segundo_apellido,
          'numero_identidad': this.numero_identidad,
          'fecha_nacimiento': this.fecha_nacimiento,
          'correo_electronico': this.correo_electronico,
          'sexo': this.sexo,
          'direccion': this.direccion
        }).then(function(response){
          me.cerrarModal();
          me.listarPersona(1, '', 'primer_nombre');

          Swal.fire({
            type: 'success',
            title: 'Actulizado.',
            text: 'El registro ha sido actualizado con éxito.',
          });
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      desactivarPersona(id_persona){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar esta Persona?',
          //text: "No se puede revertir esta acción!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {

            let me = this;
            //Primer parame: ruta, Segundo param: datos
            axios.put('/persona/desactivar', {
              'id_persona': id_persona
            })
            .then(function(response){
              me.listarPersona(1, '', 'primer_nombre');
              swalWithBootstrapButtons.fire(
                'Desactivado!',
                'El registro ha sido desactivado con éxito.',
                'success'
              );
            })
            .catch(function(error) {
              console.log("Error Axios:" + error);
            });

          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              'Cancelado',
              'El registro no ha sido desactivado.',
              'error'
            )
          }
        });
      },
      activarPersona(id_persona){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar esta Persona?',
          //text: "No se puede revertir esta acción!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Aceptar',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {

            let me = this;
            //Primer parame: ruta, Segundo param: datos
            axios.put('/persona/activar', {
              'id_persona': id_persona
            })
            .then(function(response){
              me.listarPersona(1, '', 'primer_nombre');
              swalWithBootstrapButtons.fire(
                'Activado!',
                'El registro ha sido activado con éxito.',
                'success'
              );
            })
            .catch(function(error) {
              console.log("Error Axios:" + error);
            });

          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              'Cancelado',
              'El registro no ha sido activado.',
              'error'
            )
          }
        });

      },
      validarPersona(){
        this.errorPersona = 0;
        this.errorMostrarMsjPersona = [];
        this.errores = {
          "primer_nombre" : 0,
          "primer_apellido" : 0,
          "numero_identidad" : 0,
          "correo_electronico" : 0,
          "total_errores" : 0
        };

        if(!this.primer_nombre){
          this.errores.primer_nombre = 1;
          this.errores.total_errores = 1;
        }
        if(!this.primer_apellido){
          this.errores.primer_apellido = 1;
          this.errores.total_errores = 1;
        }
        if(!this.numero_identidad){
          this.errores.numero_identidad = 1;
          this.errores.total_errores = 1;
        }
        if(!this.correo_electronico){
          this.errores.correo_electronico = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorPersona = 1;

        return this.errorPersona;

        /*
        if (!this.primer_nombre) this.errorMostrarMsjPersona.push();
        if (this.errorMostrarMsjPersona.length) this.errorPersona = 1; //  1: Existe un error
        return this.errorPersona;
        */
      },
      cerrarModal(){
        this.tituloModal = '';

        this.primer_nombre = '';
        this.segundo_nombre = '';
        this.primer_apellido = '';
        this.segundo_apellido = '';
        this.numero_identidad = '';
        this.fecha_nacimiento = '';
        this.correo_electronico = '';
        this.sexo = '';
        this.direccion = '';

        this.errorPersona = 0; // 0: No Existe un error
        $('#modalNuevo').modal('hide');
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "persona": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Persona';
                this.tipoAccion = 1; // Registrar

                this.primer_nombre = '';
                this.segundo_nombre = '';
                this.primer_apellido = '';
                this.segundo_apellido = '';
                this.numero_identidad = '';
                this.fecha_nacimiento = '';
                this.correo_electronico = '';
                this.sexo = '';
                this.direccion = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Persona";
                this.tipoAccion = 2; // Actualizar

                this.id_persona = data['id_persona'];
                this.primer_nombre = data['primer_nombre']
                this.segundo_nombre = data['segundo_nombre'];
                this.primer_apellido = data['primer_apellido'];
                this.segundo_apellido = data['segundo_apellido'];
                this.numero_identidad = data['numero_identidad'];
                this.fecha_nacimiento = data['fecha_nacimiento'];
                this.correo_electronico = data['correo_electronico'];
                this.sexo = data['sexo'];
                this.direccion = data['direccion'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarPersona(1, this.buscar, this.criterio);
      this.$ga.page('/Persona');
    }
  }
</script>

<style>
.div-error {
  display: flex;
  justify-content: center;
}

.text-error {
  color: red !important;
  font-weight: bold;
}
</style>
