<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Acceso</li>
          <li class="breadcrumb-item active">Tipos de Usuarios</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('tipoUsuario','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nuevo Registro
              </button>
              <!--<i class="fa fa-align-justify"></i> Tipo Usuarios-->
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio">
                      <option value="tipo_usuario">Nombre</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarTipoUsuario(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    
                    <button type="submit" @click="listarTipoUsuario(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>

              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Identificador</th>
                    <th>Tipo de Usuario</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="tipoUsuario in arrayTipoUsuario" :key="tipoUsuario.id_tipo_usuario">
                    <td>
                      <button type="button" @click="abrirModal('tipoUsuario', 'actualizar', tipoUsuario)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      
                      <!-- Si el estado de la tipo usuario es Activo, se muestra este botón -->
                      <template v-if="tipoUsuario.estado == 'A'">
                        <button type="button"  class="btn btn-danger btn-sm" @click="desactivarTipoUsuario(tipoUsuario.id_tipo_usuario)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>

                      <!-- De lo contrario si el estado de la tipo usuario es Inactivo, se muestra este botón -->
                      <template v-else>
                        <button type="button"  class="btn btn-info btn-sm" @click="activarTipoUsuario(tipoUsuario.id_tipo_usuario)">
                          <i class="icon-check"></i>
                        </button>
                      </template>
                    </td>

                    <td v-text="tipoUsuario.id_tipo_usuario"></td>

                    <td v-text="tipoUsuario.tipo_usuario"></td>

                    <td v-text="tipoUsuario.descripcion"></td>
                    
                    <td>
                      <div v-if="tipoUsuario.estado=='A'">
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
                    <label class="col-md-3 form-control-label" for="tipo_usuario">Tipo de Usuario (*)</label>
                    <div class="col-md-9">
                      <input type="text" v-model="tipo_usuario" class="form-control" placeholder="Nombre del Tipo de Usuario">
                      <span class="text-error" v-if="errores.tipo_usuario==1">El nombre del tipo usuario no puede ir vacio</span>
                    </div>
                  </div>
                </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" @click="registrarTipoUsuario()" class="btn btn-primary">Guardar</button>
              <button type="button" v-if="tipoAccion==2" @click="actualizarTipoUsuario()" class="btn btn-primary">actualizar</button>
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
        id_tipo_usuario: 0,
        tipo_usuario: '',
        descripcion:'',

        // Variables auxiliares
        arrayTipoUsuario: [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar, 2: Actualizar
        errorTipoUsuario: 0, // 0: No hay Error, 1: Existe un error
        errores: {
          "tipo_usuario": 0,
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
        criterio: 'tipo_usuario', // Indica cual es el campo de busqueda
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
      listarTipoUsuario(page, buscar, criterio){
          let me = this;
          var url = '/tipousuario?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayTipoUsuario = respuesta.tipoUsuario.data;
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
          me.listarTipoUsuario(page, buscar, criterio);
      },
      registrarTipoUsuario(){
        // Si el método validarTipoUsuario devuelve 1 significa que hay un error.
        if(this.validarTipoUsuario()){
          return; // Si hay un error, se ejecuta el return;
        }
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/tipousuario/registrar', { 
          'tipo_usuario': this.tipo_usuario
        
        }).then(function(response){
          me.cerrarModal();
          me.listarTipoUsuario(1, '', 'tipo_usuario');

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
      actualizarTipoUsuario(){
        // Si el método validarTipoUsuario devuelve 1 significa que hay un error.
        if(this.validarTipoUsuario()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/tipousuario/actualizar', {
          'id_tipo_usuario': this.id_tipo_usuario, 
          'tipo_usuario': this.tipo_usuario
        
        }).then(function(response){
          me.cerrarModal();
          me.listarTipoUsuario(1, '', 'tipo_usuario');

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
      desactivarTipoUsuario(id_tipo_usuario){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Tipo de Usuario?',
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
            axios.put('/tipousuario/desactivar', {
              'id_tipo_usuario': id_tipo_usuario
            })
            .then(function(response){
              me.listarTipoUsuario(1, '', 'tipo_usuario');
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
      activarTipoUsuario(id_tipo_usuario){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Tipo de Usuario?',
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
            axios.put('/tipousuario/activar', {
              'id_tipo_usuario': id_tipo_usuario
            })
            .then(function(response){
              me.listarTipoUsuario(1, '', 'tipo_usuario');
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
      validarTipoUsuario(){
        this.errorTipoUsuario = 0;
        this.errores = {
          "tipo_usuario": 0,
          "total_errores": 0
        };

        if(!this.tipo_usuario){
          this.errores.tipo_usuario = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorTipoUsuario = 1;

        return this.errorTipoUsuario;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.tipo_usuario = '';
        this.errorTipoUsuario = 0; // 0: No Existe un error

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "tipoUsuario": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Tipo Usuario';
                this.tipoAccion = 1; // 1: Registrar
                this.tipo_usuario = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Tipo Usuario";
                this.tipoAccion = 2; // Actualizar

                this.id_tipo_usuario = data['id_tipo_usuario'];
                this.tipo_usuario = data['tipo_usuario'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarTipoUsuario(1, this.buscar, this.criterio);
      this.$ga.page('/TipoUsuario');
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
