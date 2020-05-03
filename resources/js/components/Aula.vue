<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Infraestructura</li>
          <li class="breadcrumb-item active">Aulas</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('aula','registrar')" data-toggle="modal" data-target="#modalNuevo">
                      <i class="icon-plus"></i>&nbsp;Nuevo Registro
                  </button>
              </div>

              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-8">
                          <div class="input-group">
                              <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                                <option value="nombre_aula">Nombre</option>
                                <option value="codigo_aula">Código</option>
                              </select>

                              <input type="text" v-model="buscar" @keyup.enter="listarAula(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                              <button type="submit" @click="listarAula(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                          </div>
                      </div>
                  </div>

                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Edificio</th>
                              <th>Nombre</th>
                              <th>Código</th>
                              <th>Estado</th>
                          </tr>
                      </thead>

                      <tbody>
                          <tr v-for="aula in arrayAula" :key="aula.id_aula">
                              <td>
                                  <button type="button" @click="abrirModal('aula', 'actualizar', aula)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp; <!--&nbsp; deja espacio -->
                                  
                                  <!-- Si el estado del aula es activo, se muestra este botón -->
                                  <template v-if="aula.estado == 'A'">
                                    <button type="button"  class="btn btn-danger btn-sm" @click="desactivarAula(aula.id_aula)">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>

                                  <!-- De lo contrario si el estado del aula es Inactivo, se muestra este botón -->
                                  <template v-else>
                                    <button type="button"  class="btn btn-info btn-sm" @click="activarAula(aula.id_aula)">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>

                              </td>
                              <td v-text="aula.nombre_edificio"></td>

                              <td v-text="aula.nombre_aula"></td>
                              
                              <td v-text="aula.codigo_aula"></td>
                              
                              <td>
                                  <div v-if="aula.estado=='A'">
                                      <span class="badge badge-success">Activo</span>
                                  </div>
                                  <div v-else>
                                      <span class="badge badge-danger">Inactivo</span>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>

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
        <!-- Fin Tabla Listado -->
      </div>
      
      <!--Inicio del modal agregar/actualizar-->
      <!--:class={'mostrar' : modal}-->
      <div class="modal fade" id="modalNuevo" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg" role="document">
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
                              <label class="col-md-3 form-control-label" for="id_edificio">Edificio</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="id_edificio">
                                    <option v-for="edificio in arrayEdificio" :key="edificio.id_edificio" v-bind:value="edificio.id_edificio" v-text="edificio.nombre_edificio"></option>
                                  </select>
                                  <span class="text-error" v-if="errores.id_edificio==1">Debe seleccionar un edificio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="nombre_aula" class="form-control" placeholder="Nombre">
                                  <span class="text-error" v-if="errores.nombre_aula==1">El nombre no puede ir vacio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="codigo_aula">Código</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="codigo_aula" class="form-control" placeholder="Código">
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" @click="registrarAula()" class="btn btn-primary">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" @click="actualizarAula()" class="btn btn-primary">Actualizar</button>

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
        id_edificio: '',
        nombre_aula: '',
        codigo_aula: '',

        // Variables auxiliares
        arrayAula : [], // almacena los datos del objeto response
        arrayEdificio : [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorAula: 0, // 1: Existe un error
        errores : {
          "id_edificio" : 0,
          "nombre_aula" : 0,
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
        offset: 2, // Cantidad de páginas a la izq y der de la actual
        criterio: 'nombre_aula', // Indica cual es el campo de busqueda
        buscar: '' // Cual es el texto de busqueda para realizar el filtro de todos los registros
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
      listarAula(page, buscar, criterio){
        let me = this;
        var url = '/aula?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayAula = respuesta.aula.data;
          me.pagination = respuesta.pagination;
          axios.get('/edificio').then(function(response){
            me.arrayEdificio = response.data.edificio.data;
          })
          .catch(function(error) {
            console.log(error);
          });
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
        me.listarAula(page, buscar, criterio);
      },
      registrarAula(){
        // Si el método validarAula devuelve 1 significa que hay un error.
        if(this.validarAula()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/aula/registrar', {
          'id_edificio': this.id_edificio,
          'nombre_aula': this.nombre_aula,
          'codigo_aula': this.codigo_aula
        }).then(function(response){
          me.cerrarModal();
          me.listarAula(1, ' ', 'nombre_aula');

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
      actualizarAula(){
        // Si el método validarAula devuelve 1 significa que hay un error.
        if(this.validarAula()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/aula/actualizar', {
          'id_aula': this.id_aula, 
          'id_edificio': this.id_edificio,
          'nombre_aula': this.nombre_aula,
          'codigo_aula': this.codigo_aula
        }).then(function(response){
          me.cerrarModal();
          me.listarAula(1, ' ', 'nombre_aula');

          Swal.fire({
            type: 'success',
            title: 'Actulizado.',
            text: 'El registro ha sido actualizado con éxito.',
          });

          me.cerrarModal();
          me.listarAula(1, '', 'nombre_aula');
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      desactivarAula(id_aula){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Aula?',
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
            axios.put('/aula/desactivar', {
              'id_aula': id_aula
            })
            .then(function(response){
              me.listarAula(1, '', 'nombre_aula');
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
      activarAula(id_aula){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Aula?',
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
            axios.put('/aula/activar', {
              'id_aula': id_aula
            })
            .then(function(response){
              me.listarAula(1, '', 'nombre_aula');
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
      validarAula(){
        this.errorAula = 0;
        this.errores = {
          "id_edificio" : 0,
          "nombre_aula" : 0,
          "total_errores" : 0
        };
        //Se verifican campos vacios
        if(!this.id_edificio){
          this.errores.id_edificio = 1;
          this.errores.total_errores = 1;
        }

        if(!this.nombre_aula){
          this.errores.nombre_aula = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorAula = 1;

        return this.errorAula;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.id_edificio = '';
        this.nombre_aula = '';
        this.codigo_aula = '';

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "aula": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Aula';
                this.tipoAccion = 1; // 1: Registrar

                this.id_edificio = '';
                this.nombre_aula = '';
                this.codigo_aula = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Aula";
                this.tipoAccion = 2; // Actualizar

                this.id_aula = data['id_aula'];
                this.id_edificio = data['id_edificio'];

                this.nombre_aula = data['nombre_aula'];
                this.codigo_aula = data['codigo_aula'];    

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarAula(1, this.buscar, this.criterio);
      this.$ga.page('/Aula');
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
