<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Acceso</li>
          <li class="breadcrumb-item active">Archivo</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('archivo','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nuevo Registro
              </button>
              <!--<i class="fa fa-align-justify"></i> Tipo Archivos-->
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio">
                      <option value="nombre_archivo">Nombre</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarArchivo(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    
                    <button type="submit" @click="listarArchivo(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>

              <div class="table-responsive">

              </div>

              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Identificador</th>
                    <th>Archivo</th>
                    <th>Fecha Subido</th>
                    <th>Url Archivo</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="archivo in arrayArchivo" :key="archivo.id_archivo">
                    <td>
                      <button type="button" @click="abrirModal('archivo', 'actualizar', archivo)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      
                      <!-- Si el estado del archivo es Activo, se muestra este botón -->
                      <!--<template v-if="archivo.estado == 'A'">
                        <button type="button"  class="btn btn-danger btn-sm" @click="desactivarArchivo(archivo.id_archivo)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>-->

                      <!-- De lo contrario si el estado del archivo es Inactivo, se muestra este botón -->
                      <!--<template v-else>
                        <button type="button"  class="btn btn-info btn-sm" @click="activarArchivo(archivo.id_archivo)">
                          <i class="icon-check"></i>
                        </button>
                      </template>-->
                    </td>

                    <td v-text="archivo.id_archivo"></td>

                    <td v-text="archivo.nombre_archivo"></td>

                    <td v-text="archivo.fecha_subido"></td>

                    <td v-text="archivo.url_archivo"></td>
                    <!--
                    <td>
                      <div v-if="archivo.estado=='A'">
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
                    <label class="col-md-3 form-control-label" for="nombre_archivo">Nombre del Archivo (*)</label>
                    <div class="col-md-9">
                      <input type="text" v-model="nombre_archivo" class="form-control" placeholder="Nombre del Archivo">
                      <span class="text-error" v-if="errores.nombre_archivo==1">El nombre del archivo no puede ir vacio</span>
                    </div>
                  </div>
                  <!--
                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="nombre_archivo">Nombre del Archivo (*)</label>
                    <div class="col-md-9">
                      <input type="text" v-model="nombre_archivo" class="form-control" placeholder="Nombre del Archivo">
                      <span class="text-error" v-if="errores.nombre_archivo==1">El nombre del archivo no puede ir vacio</span>
                    </div>
                  </div>-->
                </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" @click="registrarArchivo()" class="btn btn-primary">Guardar</button>
              <button type="button" v-if="tipoAccion==2" @click="actualizarArchivo()" class="btn btn-primary">actualizar</button>
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
        id_archivo: 0,
        nombre_archivo: '',
        fecha_subido: '',
        url_archivo: '',

        // Variables auxiliares
        arrayArchivo: [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar, 2: Actualizar
        errorArchivo: 0, // 0: No hay Error, 1: Existe un error
        errores: {
          "nombre_archivo": 0,
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
        criterio: 'nombre_archivo', // Indica cual es el campo de busqueda
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
      listarArchivo(page, buscar, criterio){
          let me = this;
          var url = '/archivo?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayArchivo = respuesta.archivo.data;
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
          me.listarArchivo(page, buscar, criterio);
      },
      registrarArchivo(){
        // Si el método validarArchivo devuelve 1 significa que hay un error.
        if(this.validarArchivo()){
          return; // Si hay un error, se ejecuta el return;
        }
        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/archivo/registrar', { 
          'nombre_archivo': this.nombre_archivo
        
        }).then(function(response){
          me.cerrarModal();
          me.listarArchivo(1, '', 'nombre_archivo');

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
      actualizarArchivo(){
        // Si el método validarArchivo devuelve 1 significa que hay un error.
        if(this.validarArchivo()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/archivo/actualizar', {
          'id_archivo': this.id_archivo, 
          'nombre_archivo': this.nombre_archivo
        
        }).then(function(response){
          me.cerrarModal();
          me.listarArchivo(1, '', 'nombre_archivo');

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
      desactivarArchivo(id_archivo){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Archivo?',
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
            axios.put('/archivo/desactivar', {
              'id_archivo': id_archivo
            })
            .then(function(response){
              me.listarArchivo(1, '', 'nombre_archivo');
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
      activarArchivo(id_archivo){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Archivo?',
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
            axios.put('/archivo/activar', {
              'id_archivo': id_archivo
            })
            .then(function(response){
              me.listarArchivo(1, '', 'nombre_archivo');
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
      validarArchivo(){
        this.errorArchivo = 0;
        this.errores = {
          "nombre_archivo": 0,
          "total_errores": 0
        };

        if(!this.nombre_archivo){
          this.errores.nombre_archivo = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorArchivo = 1;

        return this.errorArchivo;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.nombre_archivo = '';
        this.errorArchivo = 0; // 0: No Existe un error

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      abrirModal (modelo, accion, data = []){
        switch (modelo) {
          case "archivo": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Archivo';
                this.tipoAccion = 1; // 1: Registrar
                this.nombre_archivo = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Archivo";
                this.tipoAccion = 2; // Actualizar

                this.id_archivo = data['id_archivo'];
                this.nombre_archivo = data['nombre_archivo'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarArchivo(1, this.buscar, this.criterio);
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
