<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Acceso</li>
          <li class="breadcrumb-item active">Tipo de Calificación</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('tipoCalificacion','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nuevo Registro
              </button>
              <!--<i class="fa fa-align-justify"></i> Tipo Calificación-->
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio">
                      <option value="tipo_calificacion">Nombre</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarTipoCalificacion(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    
                    <button type="submit" @click="listarTipoCalificacion(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>

              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Identificador</th>
                    <th>Tipo de Calificación</th>
                    <!--<th>Estado</th>-->
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="tipoCalificacion in arrayTipoCalificacion" :key="tipoCalificacion.id_tipo_calificacion">
                    <td>
                      <button type="button" @click="abrirModal('tipoCalificacion', 'actualizar', tipoCalificacion)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      
                      <!-- Si el estado de el tipo Calificacion es Activo, se muestra este botón -->
                      <!--<template v-if="tipoCalificacion.estado == 'A'">
                        <button type="button"  class="btn btn-danger btn-sm" @click="desactivarTipoCalificacion(tipoCalificacion.id_tipo_calificacion)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>-->

                      <!-- De lo contrario si el estado de el tipo Calificacion es Inactivo, se muestra este botón -->
                      <!--<template v-else>
                        <button type="button"  class="btn btn-info btn-sm" @click="activarTipoCalificacion(tipoCalificacion.id_tipo_calificacion)">
                          <i class="icon-check"></i>
                        </button>
                      </template>-->
                    </td>

                    <td v-text="tipoCalificacion.id_tipo_calificacion"></td>

                    <td v-text="tipoCalificacion.tipo_calificacion"></td>
                    
                    <!--<td>
                      <div v-if="tipoCalificacion.estado=='A'">
                        <span class="badge badge-success">Activo</span>
                      </div>

                      <div v-else>
                        <span class="badge badge-danger">Inactivo</span>
                      </div>
                    </td>-->
                    
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
        <!-- Fin ejemplo de tabla Listado -->
      </div>
      
      <!--Inicio del modal Agregar/Actualizar-->
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
                    <label class="col-md-3 form-control-label" for="tipo_calificacion">Tipo de Calificación (*)</label>
                    <div class="col-md-9">
                      <input type="text" v-model="tipo_calificacion" class="form-control" placeholder="Nombre del Tipo de Calificación">
                      <span class="text-error" v-if="errores.tipo_calificacion==1">El nombre del tipo de Calificacion no puede ir vacio</span>
                    </div>
                  </div>
                </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" @click="registrarTipoCalificacion()" class="btn btn-primary">Guardar</button>
              <button type="button" v-if="tipoAccion==2" @click="actualizarTipoCalificacion()" class="btn btn-primary">actualizar</button>
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
        id_tipo_calificacion: 0,
        tipo_calificacion: '',

        // Variables auxiliares
        arrayTipoCalificacion: [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar, 2: Actualizar
        errorTipoCalificacion: 0, // 0: No hay Error, 1: Existe un error
        errores: {
          "tipo_calificacion": 0,
          "total_errores": 0
        },
        pagination: {
          'total': 0,
          'current_page': 0,
          'per_page': 0,
          'last_page': 0,
          'from': 0,
          'to': 0,
        },
        offset: 2, // Cantidad de páginas a la izq y der de la actual
        criterio: 'tipo_calificacion', // Indica cual es el campo de busqueda
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
      listarTipoCalificacion(page, buscar, criterio){
          let me = this;
          var url = '/tipocalificacion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayTipoCalificacion = respuesta.tipoCalificacion.data;
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
          me.listarTipoCalificacion(page, buscar, criterio);
      },
      registrarTipoCalificacion(){
        // Si el método validarTipoCalificacion devuelve 1 significa que hay un error.
        if(this.validarTipoCalificacion()){
          return; // Si hay un error, se ejecuta el return;
        }
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/tipocalificacion/registrar', { 
          'tipo_calificacion': this.tipo_calificacion
        
        }).then(function(response){
          me.cerrarModal();
          me.listarTipoCalificacion(1, '', 'tipo_calificacion');

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
      actualizarTipoCalificacion(){
        // Si el método validarTipoCalificacion devuelve 1 significa que hay un error.
        if(this.validarTipoCalificacion()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/tipocalificacion/actualizar', {
          'id_tipo_calificacion': this.id_tipo_calificacion, 
          'tipo_calificacion': this.tipo_calificacion
        
        }).then(function(response){
          me.cerrarModal();
          me.listarTipoCalificacion(1, '', 'tipo_calificacion');

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
      desactivarTipoCalificacion(id_tipo_calificacion){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Tipo de Calificación?',
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
            axios.put('/tipocalificacion/desactivar', {
              'id_tipo_calificacion': id_tipo_calificacion
            })
            .then(function(response){
              me.listarTipoCalificacion(1, '', 'tipo_calificacion');
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
      activarTipoCalificacion(id_tipo_calificacion){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Tipo de Calificación?',
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
            axios.put('/tipocalificacion/activar', {
              'id_tipo_calificacion': id_tipo_calificacion
            })
            .then(function(response){
              me.listarTipoCalificacion(1, '', 'tipo_calificacion');
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
      validarTipoCalificacion(){
        this.errorTipoCalificacion = 0;
        this.errores = {
          "tipo_calificacion": 0,
          "total_errores": 0
        };

        if(!this.tipo_calificacion){
          this.errores.tipo_calificacion = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorTipoCalificacion = 1;

        return this.errorTipoCalificacion;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.tipo_calificacion = '';
        this.errorTipoCalificacion = 0; // 0: No Existe un error

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "tipoCalificacion": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Tipo Calificación';
                this.tipoAccion = 1; // 1: Registrar
                this.tipo_calificacion = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Tipo Calificación";
                this.tipoAccion = 2; // Actualizar

                this.id_tipo_calificacion = data['id_tipo_calificacion'];
                this.tipo_calificacion = data['tipo_calificacion'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarTipoCalificacion(1, this.buscar, this.criterio);
      this.$ga.page('/TipoCalificacion');
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
