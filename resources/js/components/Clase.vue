<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Académico</li>
          <li class="breadcrumb-item active">Clases</li>
      </ol>
      
      <div class="container-fluid">
        <div class="card">
          <!-- Tabla Listado -->
          <template v-if="verMas==1">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('clase','registrar')" data-toggle="modal" data-target="#modalNuevo">
                <i class="icon-plus"></i>&nbsp;Nueva Clase
              </button>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4" v-model="criterio" id="opcion" name="opcion">
                      <option value="seccion">Sección</option>
                      <option value="anio">Año</option>
                    </select>

                    <input type="text" v-model="buscar" @keyup.enter="listarClase(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                    <button type="submit" @click="listarClase(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                  </div>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <th>Sección</th>
                      <th>Año</th>
                      <th>Maestro Encargado</th>
                      <th>Asignatura</th>
                      <th>Código</th>
                      <th>H. Inicio</th>
                      <th>H. Fin</th>
                      <th>Días</th>
                      <th>Observación</th>
                      <th>Estado</th>
                      <th>Matricular</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="clase in arrayClase" :key="clase.id_clase">
                      <td>
                        <button type="button" @click="abrirModal('clase', 'actualizar', clase)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                          <i class="icon-pencil"></i>
                        </button>

                        <button type="button" @click="cargarPDF(clase.id_clase)" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Descargar PDF">
                          <i class="icon-cloud-download"></i>
                        </button>
                        
                        <!-- Si el estado del clase es activo, se muestra este botón -->
                        <template v-if="clase.estado == 'A'">
                          <button type="button"  class="btn btn-danger btn-sm" @click="desactivarClase(clase.id_clase)">
                            <i class="icon-trash"></i>
                          </button>
                        </template>

                        <!-- De lo contrario si el estado del clase es Inactivo, se muestra este botón -->
                        <template v-else>
                          <button type="button"  class="btn btn-info btn-sm" @click="activarClase(clase.id_clase)">
                            <i class="icon-check"></i>
                          </button>
                        </template>
                      </td>

                      <td v-text="clase.seccion"></td>

                      <td v-text="clase.anio_clase"></td>
                      
                      <td v-text="clase.primer_nombre + ' ' + clase.segundo_nombre + ' ' + clase.primer_apellido + ' ' + clase.segundo_apellido"></td>
                      
                      <td v-text="clase.nombre_asignatura"></td>
                      
                      <td v-text="clase.codigo_asignatura"></td>
                      
                      <td v-text="clase.hora_inicio"></td>
                      
                      <td v-text="clase.hora_fin"></td>
                      
                      <td v-text="clase.dias"></td>
                      
                      <td v-text="clase.observacion"></td>
                      
                      <td>
                        <div v-if="clase.estado=='A'">
                          <span class="badge badge-success">Activo</span>
                        </div>
                        <div v-else>
                          <span class="badge badge-danger">Inactivo</span>
                        </div>
                      </td>
                
                      <td v-if="clase.estado=='A'">
                        <button type="button" @click="verAlumnos(clase.id_clase); id_clase = clase.id_clase" class="btn btn-success btn-sm">
                          <i class="icon-pencil"></i>
                        </button> &nbsp;
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
          </template> 
          <!-- Fin Tabla Listado -->
            
          <template v-else-if="verMas==2">
            <div class="card-header">
              <button type="button" class="btn btn-secondary mt-1" @click="registrarMatricula()">
                <i class="icon-cloud-upload"></i>&nbsp;Cargar Matrícula 
              </button>
            </div>

            <div class="card-body">
              <div class="form-group row">
                  
                  <!-- Tabla Alumnos --> 
                  <div class="col-sm-12 col-md-6">
                    <h3>Alumnos</h3>
                    <div class="input-group mb-3">
                      <select class="form-control col-md-4"  v-model="criterio_alumno" id="opcion" name="opcion">
                        <option value="primer_nombre">Nombre</option>
                        <option value="primer_apellido">Apellido</option>
                        <option value="codigo_alumno">Cuenta</option>
                        <option value="numero_identidad">Número de Identidad</option>
                      </select>

                      <input type="text" v-model="buscar_alumno" @keyup.enter="listarAlumno(buscar_alumno, criterio_alumno)" class="form-control" placeholder="Buscar">
                      <button type="submit" @click="listarAlumno(buscar_alumno, criterio_alumno)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-sm">
                        <thead>
                          <tr>
                            <th>Cuenta Alumno</th>
                            <th>Nombre Alumno</th>
                            <th>Opción</th>
                          </tr>
                        </thead>

                        <tbody>
                          <tr v-for="alumno in arrayAlumnos" :key="alumno.id_alumno">
                            <td v-text="alumno.codigo_alumno"></td>
                            
                            <td v-text="alumno.primer_nombre + ' ' + alumno.segundo_nombre + ' ' + alumno.primer_apellido + ' ' + alumno.segundo_apellido"></td>
                                      
                            <td>
                              <button type="button" class="btn btn-info btn-sm" @click="agregarAlumnos(alumno.id_alumno)">
                                <i class="icon-plus"></i>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- Fin Tabla Alumnos --> 
                  
                  <!-- Tabla Alumnos Matriculados --> 
                  <div class="table-responsive col-sm-12 col-md-6">
                    <h3>Matriculados</h3>
                    <table class="table table-bordered table-striped table-sm">
                      <thead>
                        <tr>
                          <th>Cuenta Alumno</th>
                          <th>Nombre Alumno</th>
                          <th>Opción</th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr v-for="alumno in arrayAlumnosMatriculados" :key="alumno.id_alumno">
                          <td v-text="alumno.codigo_alumno"></td>
                          
                          <td v-text="alumno.primer_nombre + ' ' + alumno.segundo_nombre + ' ' + alumno.primer_apellido + ' ' + alumno.segundo_apellido"></td>
                          
                          <td>
                            <button type="button" class="btn btn-info btn-sm" @click="quitarAlumnos(alumno.id_alumno)">
                              <i class="icon-minus"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- Fin Tabla Alumnos Matriculados -->
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <button type="button" @click="ocultarAlumnos()" class="btn btn-secondary">Cerrar</button>
                </div>
              </div>
            </div>
          </template>
        </div>
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
                              <label class="col-md-3 form-control-label" for="id_maestro_encargado">Maestro Encargado (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="id_maestro_encargado">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option v-for="maestro in arrayEmpleado" :key="maestro.id_empleado" v-bind:value="maestro.id_empleado" v-text="maestro.primer_nombre + ' ' + maestro.primer_apellido"></option>
                                  </select>
                                  <span class="text-error" v-if="errores.id_maestro_encargado==1">Debe seleccionar un Maestro</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="id_asignatura">Asignatura (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="id_asignatura">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option v-for="asignatura in arrayAsignatura" :key="asignatura.id_asignatura" v-bind:value="asignatura.id_asignatura" v-text="asignatura.codigo_asignatura + ' - ' + asignatura.nombre_asignatura"></option>
                                  </select>
                                  <span class="text-error" v-if="errores.id_asignatura==1">Debe seleccionar una Asignatura</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="id_aula">Aula (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="id_aula">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option v-for="aula in arrayAula" :key="aula.id_aula" v-bind:value="aula.id_aula" v-text="aula.codigo_aula + ' - ' + aula.nombre_aula"></option>
                                  </select>
                                  <span class="text-error" v-if="errores.id_aula==1">Debe seleccionar una Aula</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="hora_inicio">Hora Inicio (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="hora_inicio">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option value="06:00">06:00</option>
                                    <option value="07:00">07:00</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                  </select>
                                  <span class="text-error" v-if="errores.hora_inicio==1">Debe asignar una Hora de Inicio</span>
                                  <span class="text-error" v-if="errores.hora_inicio==2">La Hora de Inicio esta después de la Hora de Finalización</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="hora_fin">Hora Finalización (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="hora_fin">
                                    <option disabled value="0">--Seleccione una opción--</option>
                                    <option value="07:00">07:00</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:00">21:00</option>
                                  </select>
                                  <span class="text-error" v-if="errores.hora_fin==1">Debe asignar una Hora de Finalización</span>
                                  <span class="text-error" v-if="errores.hora_fin==2">La Hora de Finalización esta antes de la Hora de Inicio</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="anio">Año de la Clase (*)</label>
                              <div class="col-md-9">
                                  <input type="date" v-model="anio" class="form-control" placeholder="Fecha Ingreso">
                                  <span class="text-error" v-if="errores.anio==1">Debe asignar un año de sección</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="seccion">Sección (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="seccion" class="form-control" placeholder="Sección">
                                  <span class="text-error" v-if="errores.seccion==1">Debe asignar nombre de Sección</span>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="observacion">Observación</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="observacion" class="form-control" placeholder="Observación">
                              </div>
                          </div>
                      </form>
                      <span class="text-error" style="font-size:18px" v-for="item in errorGeneral" :key="item.error"><span v-text="item.mensaje"></span><br></span>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" @click="registrarClase()" class="btn btn-primary">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" @click="actualizarClase()" class="btn btn-primary">Actualizar</button>

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
        id_aula: 0,
        id_maestro_encargado: 0,
        id_asignatura: 0,
        hora_inicio: '',
        hora_fin: '',
        anio: '',
        seccion: '',
        observacion: '',
        estado: '',

        // Variables auxiliares
        verMas: 1,
        id_clase: 0,
        arrayClase : [], // almacena los datos del objeto response
        arrayEmpleado : [], // almacena los datos del objeto response
        arrayAula : [], // almacena los datos del objeto response
        arrayAsignatura : [], // almacena los datos del objeto response
        arrayAlumnos: [], // almacena los datos de los alumnos no matriculados en la clase actual
        arrayAlumnosMatriculados: [], // almacena los datos de los alumnos matriculados en la clase actual
        id_alumno: 0, //almacena el id del alumno seleccionado para matricular

        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorClase: 0, // 1: Existe un error
        errores : {
          "seccion" : 0,
          "hora_inicio" : 0,
          "hora_fin" : 0,
          "total_errores" : 0
        },
        errorGeneral: '',
        pagination: {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
        },
        offset: 2, // Cantidad de páginas a la izq y der de la actual
        criterio: 'seccion', // Indica cual es el campo de busqueda
        buscar: '', // Cual es el texto de busqueda para realizar el filtro de todos los registros
        criterio_alumno: 'primer_nombre', // Indica cual es el campo de busqueda
        buscar_alumno: '' // Cual es el texto de busqueda para realizar el filtro de todos los registros
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
      listarClase(page, buscar, criterio){
        let me = this;
        var url = '/clase?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayClase = respuesta.clase.data;
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
        me.listarClase(page, buscar, criterio);
      },
      registrarClase(){
        // Si el método validarClase devuelve 1 significa que hay un error.
        if(this.validarClase()){
          return; // Si hay un error, se ejecuta el return;
        }
        console.log(this.validarClase());

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/clase/registrar', {
          'id_aula': this.id_aula,
          'id_maestro_encargado': this.id_maestro_encargado,
          'id_asignatura': this.id_asignatura,
          'seccion': this.seccion,
          'hora_inicio': this.hora_inicio,
          'hora_fin': this.hora_fin,
          'anio': this.anio,
          'observacion': this.observacion
        }).then(function(response){
          if(response.data.error){
            me.errorGeneral = response.data.errores;
          }else{
            me.errorGeneral = '';
            me.cerrarModal();
            me.listarClase(1, ' ', 'id_seccion');

            Swal.fire({
              type: 'success',
              title: 'Registrado.',
              text: 'El registro ha sido guardado con éxito.',
            });
          }
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      actualizarClase(){
        // Si el método validarClase devuelve 1 significa que hay un error.
        if(this.validarClase()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.put('/clase/actualizar', {
          'id_clase': this.id_clase, 
          'id_aula': this.id_aula,
          'id_maestro_encargado': this.id_maestro_encargado,
          'id_asignatura': this.id_asignatura,
          'seccion': this.seccion,
          'hora_inicio': this.hora_inicio,
          'hora_fin': this.hora_fin,
          'anio': this.anio,
          'observacion': this.observacion
        }).then(function(response){
          if(response.data.error){
            me.errorGeneral = response.data.errores;
          }else{
            me.errorGeneral = '';
            me.cerrarModal();
            me.listarClase(1, '', 'nombre_clase');

            Swal.fire({
              type: 'success',
              title: 'Actulizado.',
              text: 'El registro ha sido actualizado con éxito.',
            });
          }
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      desactivarClase(id_clase){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar esta Clase?',
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
            axios.put('/clase/desactivar', {
              'id_clase': id_clase
            })
            .then(function(response){
              me.listarClase(1, '', 'seccion');
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
      activarClase(id_clase){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar esta Clase?',
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
            axios.put('/clase/activar', {
              'id_clase': id_clase
            })
            .then(function(response){
              me.listarClase(1, '', 'nombre_clase');
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
      validarClase(){
        this.errorClase = 0;
        this.errores = {
          "id_aula": 0,
          "id_asignatura": 0,
          "id_maestro_encargado": 0,
          "hora_inicio": 0,
          "hora_fin": 0,
          "anio": 0,
          "seccion": 0,

          "total_errores": 0
        };
        //Se verifican campos vacios
        if(!this.id_maestro_encargado){
          this.errores.id_maestro_encargado = 1;
          this.errores.total_errores = 1;
        }
        if(!this.id_aula){
          this.errores.id_aula = 1;
          this.errores.total_errores = 1;
        }
        if(!this.id_asignatura){
          this.errores.id_asignatura = 1;
          this.errores.total_errores = 1;
        }
        if(!this.seccion){
          this.errores.seccion = 1;
          this.errores.total_errores = 1;
        }
        if(!this.hora_inicio){
          this.errores.hora_inicio = 1;
          this.errores.total_errores = 1;
        }
        if(!this.hora_fin){
          this.errores.hora_fin = 1;
          this.errores.total_errores = 1;
        }
        //Se verifican errores de inserción
        if((parseInt(this.hora_fin) - parseInt(this.hora_inicio)) <= 0){
          this.errores.hora_inicio = 2;
          this.errores.hora_fin = 2; 
          this.errores.total_errores = 1;
        }
        if(!this.anio){
          this.errores.anio = 1;
          this.errores.anio = 1;
        }

        if(this.errores.total_errores != 0) this.errorClase = 1;

        return this.errorClase;
      },
      registrarMatricula(){
        let me = this; 
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está seguro de cargar la matrícula de esta clase?',
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
            axios.post('/clase/matricularAlumno', {
              'id_clase': this.id_clase,
              'data': this.arrayAlumnosMatriculados
            }).then(function (response) {
                swalWithBootstrapButtons.fire(
                  'Cargado!',
                  'Se cargó la matrícula con éxito.',
                  'success'
                );
            }).catch(function (error) {
                console.log(error);
            });

          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              'Cancelado',
              'La matrícula no se cargó.',
              'error'
            )
          }
        });
      },
      agregarAlumnos(id_alumno){
        let me = this;
        
        for( var i = 0; i < me.arrayAlumnos.length; i++){ 
          if (me.arrayAlumnos[i].id_alumno === id_alumno) {
            me.arrayAlumnosMatriculados.push(me.arrayAlumnos[i]);
            me.arrayAlumnos.splice(i, 1);
          }
        }
      },
      quitarAlumnos(id_alumno){
        let me = this;

        for( var i = 0; i < me.arrayAlumnosMatriculados.length; i++){ 
          if ( me.arrayAlumnosMatriculados[i].id_alumno === id_alumno) {
            me.arrayAlumnos.push(me.arrayAlumnosMatriculados[i]);
            me.arrayAlumnosMatriculados.splice(i, 1);
          }
        }
      },
      ocultarAlumnos(){
        let me = this;

        me.verMas = 1;
        me.arrayAlumnos = [];
        me.arrayAlumnosMatriculados = [];
      },
      listarAlumno(buscar, criterio){
        let me = this;
        var url = '/alumno/listar?buscar='+ buscar + '&criterio='+ criterio;
        
        axios.get(url).then(function (response) {
          me.arrayAlumnos = response.data.alumnos;
          
          for( var i = 0; i < me.arrayAlumnos.length; i++){ 
            for( var j = 0; j < me.arrayAlumnosMatriculados.length; j++){
              if ( me.arrayAlumnos[i].id_alumno === me.arrayAlumnosMatriculados[j].id_alumno) {
                me.arrayAlumnos.splice(i, 1);
              }
            }
          }
        }).catch(function (error) {
            console.log(error);
        });
      },
      verAlumnos(id_clase){
        let me = this;
        me.verMas = 2;

        // Obtener los datos de los Alumnos matriculados
        axios.post('/clase/alumnos/matriculados', {
            'id_clase': id_clase
        }).then(function (response) {
            me.arrayAlumnosMatriculados = response.data;

        }).catch(function (error) {
            console.log(error);
        });

        // Obtener los datos de los Alumnos no matriculados
        me.listarAlumno('', 'primer_nombre');
      },
      cerrarModal(){
        this.tituloModal = '';

        this.id_aula = '';
        this.id_maestro_encargado = '';
        this.id_asignatura = '';
        this.seccion = '';
        this.hora_inicio = '';
        this.hora_fin = '';
        this.anio = '';
        this.observacion = '';

        this.errorClase = 0;
        this.errorGeneral = '';
        $('#modalNuevo').modal('hide');

        this.errores = {
          "id_aula": 0,
          "id_asignatura": 0,
          "id_maestro_encargado": 0,
          "hora_inicio": 0,
          "hora_fin": 0,
          "anio": 0,
          "seccion": 0,

          "total_errores": 0
        };
      },
      selectMaestro(){
        let me = this;
        axios.get('/empleado/selectMaestro').then(function(response){
          var respuesta = response.data;
          me.arrayEmpleado = respuesta.maestro;
        })
        .catch(function(error) {
          console.log(error);
        });
      }, 
      selectAsignatura(){
        let me = this;
        axios.get('/asignatura/selectAsignatura').then(function(response){
          var respuesta = response.data;
          me.arrayAsignatura = respuesta.asignatura;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      selectAula(){
        let me = this;
        axios.get('/aula/selectAula').then(function(response){
          var respuesta = response.data;
          me.arrayAula = respuesta.aula;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      abrirModal (modelo, accion, data = []){
        this.errores = {
          "id_aula": 0,
          "id_asignatura": 0,
          "id_maestro_encargado": 0,
          "hora_inicio": 0,
          "hora_fin": 0,
          "anio": 0,
          "seccion": 0,

          "total_errores": 0
        };

        this.selectMaestro();
        this.selectAsignatura();
        this.selectAula();

        switch (modelo) {
          case "clase": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Clase';
                this.tipoAccion = 1; // 1: Registrar

                this.id_aula = 0;
                this.id_maestro_encargado = 0;
                this.id_asignatura = 0;
                this.seccion = '';
                this.hora_inicio = '';
                this.hora_fin = '';
                this.anio = '';
                this.observacion = '';
                this.errorGeneral = '';
                
                break;
              }
              case "actualizar": {
                this.tituloModal = "Actualizar Clase";
                this.tipoAccion = 2; // Actualizar

                this.id_clase = data['id_clase'];

                this.id_aula = data['id_aula'];
                this.id_maestro_encargado = data['id_empleado'];
                this.id_asignatura = data['id_asignatura'];
                this.seccion = data['seccion'];
                this.hora_inicio = data['hora_inicio'];
                this.hora_fin = data['hora_fin'];
                this.anio = data['anio'];
                this.observacion = data['observacion'];

                break;
              }
            }
          }
        }
      },
      cargarPDF(id_clase){
        window.open('/clase/listarPDF?id_clase='+id_clase, '_blank');
      }
    },
    mounted() {
      this.listarClase(1, this.buscar, this.criterio);
      
      $(document).ready(function() {
        $(function () {
          $('[data-toggle="tooltip"]').tooltip({delay: { "show": 100, "hide": 100 }});
          // console.log("Se cargo el documento");
        });
      });

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
