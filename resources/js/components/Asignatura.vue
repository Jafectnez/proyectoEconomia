<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Académico</li>
          <li class="breadcrumb-item active">Asignaturas</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('asignatura','registrar')" data-toggle="modal" data-target="#modalNuevo">
                      <i class="icon-plus"></i>&nbsp;Nuevo Registro
                  </button>
              </div>

              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-8">
                          <div class="input-group">
                              <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                                <option value="nombre_asignatura">Nombre</option>
                                <option value="codigo_asignatura">Código</option>
                              </select>

                              <input type="text" v-model="buscar" @keyup.enter="listarAsignatura(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                              <button type="submit" @click="listarAsignatura(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                          </div>
                      </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Dias Impartidos</th>
                                <th>Grado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="asignatura in arrayAsignatura" :key="asignatura.id">
                                <td>
                                  <button type="button" @click="abrirModal('asignatura', 'actualizar', asignatura)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp; <!--&nbsp; deja espacio -->
                                  
                                  <!-- Si el estado del asignatura es activo, se muestra este botón -->
                                  <template v-if="asignatura.estado == 'A'">
                                    <button type="button"  class="btn btn-danger btn-sm" @click="desactivarAsignatura(asignatura.id_asignatura)">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>

                                  <!-- De lo contrario si el estado del asignatura es Inactivo, se muestra este botón -->
                                  <template v-else>
                                    <button type="button"  class="btn btn-info btn-sm" @click="activarAsignatura(asignatura.id_asignatura)">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>
                                </td>

                                <td v-text="asignatura.nombre_asignatura"></td>

                                <td v-text="asignatura.codigo_asignatura"></td>
                                
                                <td v-text="asignatura.dias"></td>

                                <td v-text="asignatura.nombre_grado"></td>
                                
                                <td>
                                    <div v-if="asignatura.estado=='A'">
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
        <!-- Fin Tabla Listado -->
      </div>
      
      <!--Inicio del modal agregar/actualizar-->
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
                              <label class="col-md-3 form-control-label" for="id_grado">Grado</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="id_grado">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option v-for="grado in arrayGrado" :key="grado.id_grado" v-bind:value="grado.id_grado" v-text="grado.nombre_grado"></option>
                                  </select>
                                  <span class="text-error" v-if="errores.id_grado==1">El grado no puede ir vacio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="nombre_asignatura" class="form-control" placeholder="Nombre">
                                  <span class="text-error" v-if="errores.nombre_asignatura==1">El nombre no puede ir vacio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="codigo_asignatura">Código</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="codigo_asignatura" class="form-control" placeholder="Código">
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="codigo_asignatura">Días impartidos</label>
                              <div class="col-md-9">
                                  <label><input type="checkbox" value="Lu" v-model="dias">Lunes</label>
                                  <label><input type="checkbox" value="Ma" v-model="dias">Martes</label>
                                  <label><input type="checkbox" value="Mi" v-model="dias">Miércoles</label>
                                  <label><input type="checkbox" value="Ju" v-model="dias">Jueves</label>
                                  <label><input type="checkbox" value="Vi" v-model="dias">Viernes</label>
                                  <label><input type="checkbox" value="Sa" v-model="dias">Sábado</label>
                                  <label><input type="checkbox" value="Do" v-model="dias">Domingo</label>
                                  <br>
                                  <span>Días seleccionados: {{ dias }}</span>
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" @click="registrarAsignatura()" class="btn btn-primary">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" @click="actualizarAsignatura()" class="btn btn-primary">Actualizar</button>

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
        nombre_asignatura: '',
        codigo_asignatura: '',
        id_grado: '',
        id_relacion: '',
        dias: [], //Almacena los dias en que se imparte la asignatura

        // Variables auxiliares
        arrayAsignatura : [], // almacena los datos del objeto response
        arrayGrado : [], // almacena los datos del objeto response
        modal: 0,
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorAsignatura: 0, // 1: Existe un error
        errores : {
          "id_grado": 0,
          "nombre_asignatura": 0,
          "dias": 0,
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
        criterio: 'nombre_asignatura', // Indica cual es el campo de busqueda
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
      listarAsignatura(page, buscar, criterio){
        let me = this;
        var url = '/asignatura?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayAsignatura = respuesta.asignatura.data;
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
        me.listarAsignatura(page, buscar, criterio);
      },
      registrarAsignatura(){
        // Si el método validarAsignatura devuelve 1 significa que hay un error.
        if(this.validarAsignatura()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/asignatura/registrar', {
          'id_grado': this.id_grado,
          'nombre_asignatura': this.nombre_asignatura,
          'codigo_asignatura': this.codigo_asignatura,
          'dias': this.dias
        }).then(function(response){
          me.cerrarModal();
          me.listarAsignatura(1, '', 'nombre_asignatura');

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
      actualizarAsignatura(){
        // Si el método validarAsignatura devuelve 1 significa que hay un error.
        if(this.validarAsignatura()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        
        //Primer parame: ruta, Segundo param: datos
        axios.put('/asignatura/actualizar', {
          'id_asignatura': this.id_asignatura, 
          'id_grado': this.id_grado,
          'id_relacion': this.id_relacion,
          'nombre_asignatura': this.nombre_asignatura,
          'codigo_asignatura': this.codigo_asignatura,
          'dias': this.dias
        }).then(function(response){
          me.cerrarModal();
          me.listarAsignatura(1, ' ', 'nombre_asignatura');

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
      desactivarAsignatura(id_asignatura){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Asignatura?',
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
            axios.put('/asignatura/desactivar', {
              'id_asignatura': id_asignatura
            })
            .then(function(response){
              me.listarAsignatura(1, '', 'nombre_asignatura');
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
      activarAsignatura(id_asignatura){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Asignatura?',
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
            axios.put('/asignatura/activar', {
              'id_asignatura': id_asignatura
            })
            .then(function(response){
              me.listarAsignatura(1, '', 'nombre_asignatura');
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
      validarAsignatura(){
        this.errorAsignatura = 0;
        this.errores = {
          "nombre_asignatura" : 0,
          "dias" : 0,
          "total_errores" : 0
        };
        if(!this.id_grado){
          this.errores.id_grado = 1;
          this.errores.total_errores = 1;
        }
        if(!this.nombre_asignatura){
          this.errores.nombre_asignatura = 1;
          this.errores.total_errores = 1;
        }
        if(!this.dias){
          this.errores.dias = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorAsignatura = 1;

        return this.errorAsignatura;
      },
      cerrarModal(){
        this.tituloModal = '';

        this.id_grado = '';
        this.nombre_asignatura = '';
        this.codigo_asignatura = '';   
        this.dias = [];     

        $('#modalNuevo').modal('hide'); // Cierra el Modal
      },
      selectGrado(){
        let me = this;
        axios.get('/grado/selectGrado').then(function(response){
          var respuesta = response.data;
          me.arrayGrado = respuesta.grado;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      abrirModal (modelo, accion, data = []){
        this.selectGrado();
        
        switch (modelo) {
          case "asignatura": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Asignatura';
                this.modal = 1;
                this.tipoAccion = 1; // 1: Registrar

                this.id_grado = 0;
                this.nombre_asignatura = '';
                this.codigo_asignatura = '';   
                this.dias = [];     
                
                break;
              }
              case "actualizar": {
                this.modal = 1;
                this.tituloModal = "Actualizar Asignatura";
                this.tipoAccion = 2; // Actualizar

                this.id_asignatura = data['id_asignatura'];
                this.id_grado = data['id_grado'];
                this.id_relacion = data['id'];

                this.nombre_asignatura = data['nombre_asignatura'];
                this.codigo_asignatura = data['codigo_asignatura'];   

                var dias = data['dias'];
                var arregloTemporal = [];
                var contador = 0;
                var posicion = 0;

                while(posicion < dias.length){
                  arregloTemporal[contador] = dias.slice(posicion, posicion+2);
                  contador++;
                  posicion+=2;
                }

                this.dias = arregloTemporal;     

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarAsignatura(1, this.buscar, this.criterio);
      this.$ga.page('/Asignatura');
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
