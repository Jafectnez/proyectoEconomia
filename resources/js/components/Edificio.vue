<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Infraestructura</li>
          <li class="breadcrumb-item active">Edificios</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('edificio','registrar')" data-toggle="modal" data-target="#modalNuevo">
                      <i class="icon-plus"></i>&nbsp;Nuevo Registro
                  </button>
              </div>

              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-8">
                          <div class="input-group">
                              <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                                <option value="nombre_edificio">Nombre</option>
                                <option value="codigo_edificio">Código</option>
                              </select>

                              <input type="text" v-model="buscar" @keyup.enter="listarEdificio(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                              <button type="submit" @click="listarEdificio(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                          </div>
                      </div>
                  </div>

                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Nombre</th>
                              <th>Código</th>
                              <th>Estado</th>
                          </tr>
                      </thead>

                      <tbody>
                          <tr v-for="edificio in arrayEdificio" :key="edificio.id_edificio">
                              <td>
                                  <button type="button" @click="abrirModal('edificio', 'actualizar', edificio)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp; <!--&nbsp; deja espacio -->
                                  
                                  <!-- Si el estado del edificio es activo, se muestra este botón -->
                                  <template v-if="edificio.estado == 'A'">
                                    <button type="button"  class="btn btn-danger btn-sm" @click="desactivarEdificio(edificio.id_edificio)">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>

                                  <!-- De lo contrario si el estado del edificio es Inactivo, se muestra este botón -->
                                  <template v-else>
                                    <button type="button"  class="btn btn-info btn-sm" @click="activarEdificio(edificio.id_edificio)">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>

                              </td>
                              <td v-text="edificio.nombre_edificio"></td>
                              
                              <td v-text="edificio.codigo_edificio"></td>
                              
                              <td>
                                  <div v-if="edificio.estado=='A'">
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
                              <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="nombre_edificio" class="form-control" placeholder="Nombre">
                                  <span class="text-error" v-if="errores.nombre_edificio==1">El nombre no puede ir vacio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="codigo_edificio">Código</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="codigo_edificio" class="form-control" placeholder="Código">
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" @click="registrarEdificio()" class="btn btn-primary">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" @click="actualizarEdificio()" class="btn btn-primary">Actualizar</button>

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
        nombre_edificio: '',
        codigo_edificio: '',

        // Variables auxiliares
        arrayEdificio : [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorEdificio: 0, // 1: Existe un error
        errores : {
          "nombre_edificio" : 0,
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
        criterio: 'nombre_edificio', // Indica cual es el campo de busqueda
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
      listarEdificio(page, buscar, criterio){
        let me = this;
        var url = '/edificio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayEdificio = respuesta.edificio.data;
          me.pagination = respuesta.pagination;
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
        me.listarEdificio(page, buscar, criterio);
      },
      registrarEdificio(){
        // Si el método validarEdificio devuelve 1 significa que hay un error.
        if(this.validarEdificio()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/edificio/registrar', {
          'nombre_edificio': this.nombre_edificio,
          'codigo_edificio': this.codigo_edificio
        }).then(function(response){
          me.cerrarModal();
          me.listarEdificio(1, ' ', 'nombre_edificio');

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
      actualizarEdificio(){
        // Si el método validarEdificio devuelve 1 significa que hay un error.
        if(this.validarEdificio()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/edificio/actualizar', {
          'id_edificio': this.id_edificio, 
          'nombre_edificio': this.nombre_edificio,
          'codigo_edificio': this.codigo_edificio
        }).then(function(response){
          me.cerrarModal();
          me.listarEdificio(1, ' ', 'nombre_edificio');

          Swal.fire({
            type: 'success',
            title: 'Actulizado.',
            text: 'El registro ha sido actualizado con éxito.',
          });

          me.cerrarModal();
          me.listarEdificio(1, '', 'nombre_edificio');
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      desactivarEdificio(id_edificio){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Edificio?',
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
            axios.put('/edificio/desactivar', {
              'id_edificio': id_edificio
            })
            .then(function(response){
              me.listarEdificio(1, '', 'nombre_edificio');
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
      activarEdificio(id_edificio){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Edificio?',
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
            axios.put('/edificio/activar', {
              'id_edificio': id_edificio
            })
            .then(function(response){
              me.listarEdificio(1, '', 'nombre_edificio');
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
      validarEdificio(){
        this.errorEdificio = 0;
        this.errores = {
          "nombre_edificio" : 0,
          "total_errores" : 0
        };

        if(!this.nombre_edificio){
          this.errores.nombre_edificio = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorEdificio = 1;

        return this.errorEdificio;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.nombre_edificio = '';
        this.codigo_edificio = '';

        $('#modalNuevo').modal('hide');
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "edificio": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Edificio';
                this.tipoAccion = 1; // 1: Registrar

                this.nombre_edificio = '';
                this.codigo_edificio = '';
                
                break;
              }
              case "actualizar": {
                
                this.tituloModal = "Actualizar Edificio";
                this.tipoAccion = 2; // Actualizar

                this.id_edificio = data['id_edificio'];

                this.nombre_edificio = data['nombre_edificio'];
                this.codigo_edificio = data['codigo_edificio'];    

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarEdificio(1, this.buscar, this.criterio);
      this.$ga.page('/Edificio');
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
