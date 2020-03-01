<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Acceso</li>
          <li class="breadcrumb-item active">Parcial</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('parcial','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nuevo Registro
              </button>
              <!--<i class="fa fa-align-justify"></i> Tipo Parcials-->
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio">
                      <option value="nombre_parcial">Nombre</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarParcial(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    
                    <button type="submit" @click="listarParcial(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>

              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Identificador</th>
                    <th>Parcial</th>
                    <!--<th>Estado</th>-->
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="parcial in arrayParcial" :key="parcial.id_parcial">
                    <td>
                      <button type="button" @click="abrirModal('parcial', 'actualizar', parcial)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      
                      <!-- Si el estado del parcial es Activo, se muestra este botón -->
                      <!--<template v-if="parcial.estado == 'A'">
                        <button type="button"  class="btn btn-danger btn-sm" @click="desactivarParcial(parcial.id_parcial)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>-->

                      <!-- De lo contrario si el estado del parcial es Inactivo, se muestra este botón -->
                      <!--<template v-else>
                        <button type="button"  class="btn btn-info btn-sm" @click="activarParcial(parcial.id_parcial)">
                          <i class="icon-check"></i>
                        </button>
                      </template>-->
                    </td>

                    <td v-text="parcial.id_parcial"></td>

                    <td v-text="parcial.nombre_parcial"></td>
                    <!--
                    <td>
                      <div v-if="parcial.estado=='A'">
                        <span class="badge badge-success">Activo</span>
                      </div>

                      <div v-else>
                        <span class="badge badge-danger">Inactivo</span>
                      </div>
                    </td>
                    -->
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
                    <label class="col-md-3 form-control-label" for="nombre_parcial">Nombre del Parcial (*)</label>
                    <div class="col-md-9">
                      <input type="text" v-model="nombre_parcial" class="form-control" placeholder="Nombre del Tipo de Parcial">
                      <span class="text-error" v-if="errores.nombre_parcial==1">El nombre del parcial no puede ir vacio</span>
                    </div>
                  </div>
                </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" @click="registrarParcial()" class="btn btn-primary">Guardar</button>
              <button type="button" v-if="tipoAccion==2" @click="actualizarParcial()" class="btn btn-primary">actualizar</button>
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
        id_parcial: 0,
        nombre_parcial: '',

        // Variables auxiliares
        arrayParcial: [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar, 2: Actualizar
        errorParcial: 0, // 0: No hay Error, 1: Existe un error
        errores: {
          "nombre_parcial": 0,
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
        criterio: 'nombre_parcial', // Indica cual es el campo de busqueda
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
      listarParcial(page, buscar, criterio){
          let me = this;
          var url = '/parcial?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayParcial = respuesta.parcial.data;
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
          me.listarParcial(page, buscar, criterio);
      },
      registrarParcial(){
        // Si el método validarParcial devuelve 1 significa que hay un error.
        if(this.validarParcial()){
          return; // Si hay un error, se ejecuta el return;
        }
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/parcial/registrar', { 
          'nombre_parcial': this.nombre_parcial
        
        }).then(function(response){
          me.cerrarModal();
          me.listarParcial(1, '', 'nombre_parcial');

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
      actualizarParcial(){
        // Si el método validarParcial devuelve 1 significa que hay un error.
        if(this.validarParcial()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/parcial/actualizar', {
          'id_parcial': this.id_parcial, 
          'nombre_parcial': this.nombre_parcial
        
        }).then(function(response){
          me.cerrarModal();
          me.listarParcial(1, '', 'nombre_parcial');

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
      desactivarParcial(id_parcial){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Parcial?',
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
            axios.put('/parcial/desactivar', {
              'id_parcial': id_parcial
            })
            .then(function(response){
              me.listarParcial(1, '', 'nombre_parcial');
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
      activarParcial(id_parcial){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Parcial?',
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
            axios.put('/parcial/activar', {
              'id_parcial': id_parcial
            })
            .then(function(response){
              me.listarParcial(1, '', 'nombre_parcial');
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
      validarParcial(){
        this.errorParcial = 0;
        this.errores = {
          "nombre_parcial": 0,
          "total_errores": 0
        };

        if(!this.nombre_parcial){
          this.errores.nombre_parcial = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorParcial = 1;

        return this.errorParcial;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.nombre_parcial = '';
        this.errorParcial = 0; // 0: No Existe un error

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "parcial": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Parcial';
                this.tipoAccion = 1; // 1: Registrar
                this.nombre_parcial = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Parcial";
                this.tipoAccion = 2; // Actualizar

                this.id_parcial = data['id_parcial'];
                this.nombre_parcial = data['nombre_parcial'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarParcial(1, this.buscar, this.criterio);
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
