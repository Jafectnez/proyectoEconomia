<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Académico</li>
          <li class="breadcrumb-item active">Grados</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('grado','registrar')" data-toggle="modal" data-target="#modalNuevo">
                      <i class="icon-plus"></i>&nbsp;Nuevo Registro
                  </button>
              </div>

              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-8">
                          <div class="input-group">
                              <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                                <option value="nombre_grado">Nombre</option>
                                <option value="codigo_grado">Código</option>
                              </select>

                              <input type="text" v-model="buscar" @keyup.enter="listarGrado(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                              <button type="submit" @click="listarGrado(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                          </div>
                      </div>
                  </div>

                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Nombre</th>
                              <th>Código</th>
                              <th>Total Asignaturas</th>
                              <th>Estado</th>
                          </tr>
                      </thead>

                      <tbody>
                          <tr v-for="grado in arrayGrado" :key="grado.id_grado">
                              <td>
                                  <button type="button" @click="abrirModal('grado', 'actualizar', grado)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp; <!--&nbsp; deja espacio -->
                                  
                                  <!-- Si el estado del grado es activo, se muestra este botón -->
                                  <template v-if="grado.estado == 'A'">
                                    <button type="button"  class="btn btn-danger btn-sm" @click="desactivarGrado(grado.id_grado)">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>

                                  <!-- De lo contrario si el estado del grado es Inactivo, se muestra este botón -->
                                  <template v-else>
                                    <button type="button"  class="btn btn-info btn-sm" @click="activarGrado(grado.id_grado)">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>

                              </td>
                              <td v-text="grado.nombre_grado"></td>
                              
                              <td v-text="grado.codigo_grado"></td>
                              
                              <td v-text="grado.cantidad_asignatura"></td>
                              
                              <td>
                                  <div v-if="grado.estado=='A'">
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
                                  <input type="text" v-model="nombre_grado" class="form-control" placeholder="Nombre">
                                  <span class="text-error" v-if="errores.nombre_grado==1">El nombre no puede ir vacio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="codigo_grado">Código</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="codigo_grado" class="form-control" placeholder="Código">
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" @click="registrarGrado()" class="btn btn-primary">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" @click="actualizarGrado()" class="btn btn-primary">Actualizar</button>

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
        nombre_grado: '',
        codigo_grado: '',
        cantidad_asignatura: '',

        // Variables auxiliares
        arrayGrado : [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorGrado: 0, // 1: Existe un error
        errores : {
          "nombre_grado" : 0,
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
        criterio: 'nombre_grado', // Indica cual es el campo de busqueda
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
      listarGrado(page, buscar, criterio){
        let me = this;
        var url = '/grado?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayGrado = respuesta.grado.data;
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
        me.listarGrado(page, buscar, criterio);
      },
      registrarGrado(){
        // Si el método validarGrado devuelve 1 significa que hay un error.
        if(this.validarGrado()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/grado/registrar', {
          'nombre_grado': this.nombre_grado,
          'codigo_grado': this.codigo_grado
        }).then(function(response){
          me.cerrarModal();
          me.listarGrado(1, ' ', 'nombre_grado');

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
      actualizarGrado(){
        // Si el método validarGrado devuelve 1 significa que hay un error.
        if(this.validarGrado()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/grado/actualizar', {
          'id_grado': this.id_grado, 
          'nombre_grado': this.nombre_grado,
          'codigo_grado': this.codigo_grado
        }).then(function(response){
          me.cerrarModal();
          me.listarGrado(1, ' ', 'nombre_grado');

          Swal.fire({
            type: 'success',
            title: 'Actulizado.',
            text: 'El registro ha sido actualizado con éxito.',
          });

          me.cerrarModal();
          me.listarGrado(1, '', 'nombre_grado');
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      desactivarGrado(id_grado){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Grado?',
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
            axios.put('/grado/desactivar', {
              'id_grado': id_grado
            })
            .then(function(response){
              me.listarGrado(1, '', 'nombre_grado');
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
      activarGrado(id_grado){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Grado?',
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
            axios.put('/grado/activar', {
              'id_grado': id_grado
            })
            .then(function(response){
              me.listarGrado(1, '', 'nombre_grado');
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
      validarGrado(){
        this.errorGrado = 0;
        this.errores = {
          "nombre_grado" : 0,
          "total_errores" : 0
        };

        if(!this.nombre_grado){
          this.errores.nombre_grado = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorGrado = 1;

        return this.errorGrado;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.nombre_grado = '';
        this.codigo_grado = '';

        $('#modalNuevo').modal('hide');
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "grado": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Grado';
                this.tipoAccion = 1; // 1: Registrar

                this.nombre_grado = '';
                this.codigo_grado = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Grado";
                this.tipoAccion = 2; // Actualizar

                this.id_grado = data['id_grado'];

                this.nombre_grado = data['nombre_grado'];
                this.codigo_grado = data['codigo_grado'];    

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarGrado(1, this.buscar, this.criterio);
      this.$ga.page('/Grado');
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
