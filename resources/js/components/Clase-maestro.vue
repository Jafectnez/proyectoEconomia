<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">Clases</li>
      </ol>
      
      <div class="container-fluid">
        <div class="card">

          <!-- Tabla Listado de Clases del Maestro -->
          <template v-if="verMas==1">
            <div class="card-body">
              <h3 class="mb-3">Clases de las que eres encargado</h3>
              
              <div class="form-group row">
                <div class="col-md-8">
                  <div class="input-group">
                    <select class="form-control col-md-4"  v-model="criterioClase" id="opcion" name="opcion">
                      <option value="seccion">Sección</option>
                      <option value="anio">Año</option>
                    </select>

                    <input type="text" v-model="buscarClase" @keyup.enter="listarClase(1, buscarClase, criterioClase)" class="form-control" placeholder="Buscar">
                    <button type="submit" @click="listarClase(1, buscarClase, criterioClase)" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                  </div>
                </div>
              </div>
              
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Maestro Encargado</th>
                      <th>Sección</th>
                      <th>Año</th>
                      <th>Asignatura</th>
                      <th>Código Asignatura</th>
                      <th>Hora Inicio</th>
                      <th>Hora Fin</th>
                      <th>Días</th>
                      <th>Observación</th>
                      <th>Ver Alumnos</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="clase in arrayClase" :key="clase.id_clase">
                      <td v-text="clase.primer_nombre + ' ' + clase.segundo_nombre + ' ' + clase.primer_apellido + ' ' + clase.segundo_apellido"></td>

                      <td v-text="clase.seccion"></td>
                      
                      <td v-text="clase.anio_clase"></td>
                      
                      <td v-text="clase.nombre_asignatura"></td>
                      
                      <td v-text="clase.codigo_asignatura"></td>
                      
                      <td v-text="clase.hora_inicio"></td>
                      
                      <td v-text="clase.hora_fin"></td>
                      
                      <td v-text="clase.dias"></td>
                      
                      <td v-text="clase.observacion"></td>
                      
                      <td>
                        <button type="button" @click="verAlumnos(clase.id_clase)" class="btn btn-success btn-sm">
                          <i class="icon-eye"></i>
                        </button> &nbsp;
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
            </div>
          </template>
          <!-- Fin Tabla Listado de Clases del Maestro-->
          
          <!-- Tabla Listado de Alumnos en la Clase -->
          <template v-else-if="verMas==2">
            <div class="card-body">
              <div class="form-group row">
                  <div class="col-md-8">
                      <div class="input-group">
                          <select class="form-control col-md-4" v-model="criterio" id="opcion" name="opcion">
                            <option value="primer_nombre">Nombre</option>
                            <option value="primer_apellido">Apellido</option>
                          </select>

                          <input type="text" v-model="buscar" @keyup.enter="listarAlumno(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                          <button type="submit" @click="listarAlumno(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                      </div>
                  </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Cuenta</th>
                      <th>Nombre</th>
                      <th>Apellido </th>
                      <th>Ver Notas</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="alumno in arrayListarAlumnoClase" :key="alumno.id_alumno">
                      <td v-text="alumno.codigo_alumno"></td>                    
                      
                      <td v-text="alumno.primer_nombre + ' ' + alumno.segundo_nombre"></td>
                      
                      <td v-text="alumno.primer_apellido + ' ' + alumno.segundo_apellido"></td>
                      
                      <td>
                        <button type="button" @click="verNotaAlumno(alumno.id_alumno)" class="btn btn-success btn-sm">
                          <i class="icon-eye"></i>
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

              <div class="form-group row">
                <div class="col-md-12">
                  <button type="button" @click="ocultarAlumnos()" class="btn btn-secondary">Cerrar</button>
                </div>
              </div>
            </div>
          </template>
          <!-- Fin Tabla Listado de Alumnos en la Clase -->
          
          <!-- Tabla Listado de Notas de un Alumno -->
          <template v-else-if="verMas==3">
            <div class="card-body">
              <div class="form-group row">
                
                <div class="input-group col-md-10">
                  <button type="button" class="btn btn-secondary mr-1" @click="abrirModal('alumno', 'registrar')" data-toggle="modal" data-target="#modalNuevo">
                    <i class="icon-plus"></i>&nbsp;Nueva Calificación
                  </button>

                  <div class="input-group">
                    <select class="form-control" v-model="filtro_parcial">
                      <option disabled value="0">--Seleccione una opción--</option>
                      <option v-for="parcial in arrayParcial" :key="parcial.id_parcial" :value="parcial.id_parcial" v-text="parcial.nombre_parcial"></option>
                    </select>
                    <button type="button" @click="verNotaAlumnoParcial()" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Filtrar</button>
                  </div>
                </div>                
              
              </div>

              <div class="form-group row">
                <div class="table-responsive col-md-12">
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>Cuenta Alumno</th>
                        <th>Nombre Alumno</th>
                        <th>Nota</th>
                        <th>Parcial</th>
                        <th>Tipo Calificación</th>
                        <th>Observación</th>
                        <th>Opción</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="alumno in arrayAlumno" :key="alumno.id_calificacion">
                        
                        <td v-text="alumno.codigo_alumno"></td>
                        
                        <td v-text="alumno.primer_nombre + ' ' + alumno.segundo_nombre + ' ' + alumno.primer_apellido + ' ' + alumno.segundo_apellido"></td>
                        
                        <template v-if="alumno.nota">
                          <td v-text="alumno.nota"></td>

                          <td v-text="alumno.nombre_parcial"></td>

                          <td v-text="alumno.tipo_calificacion"></td>

                          <td v-text="alumno.observacion"></td> 

                          <td>
                            <button type="button" class="btn btn-info btn-sm" @click="abrirModal('alumno', 'actualizar', alumno)" data-toggle="modal" data-target="#modalNuevo">
                              <i class="icon-pencil"></i>
                            </button>
                          </td>
                        </template>

                        <template v-else>
                          <td>Sin Nota</td>

                          <td>Parcial</td>              

                          <td>Sin Observación</td> 

                          <td>
                            <button type="button" class="btn btn-success btn-sm" @click="abrirModal('alumno', 'registrar', alumno)" data-toggle="modal" data-target="#modalNuevo">
                              <i class="icon-plus"></i>
                            </button>
                          </td>
                        </template>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <button type="button" @click="ocultarAlumnos()" class="btn btn-secondary">Cerrar</button>
                  <button type="button" @click="ocultarNotas()" class="btn btn-secondary">Atrás</button>
                </div>
              </div>
            </div>
          </template>
          <!-- Fin Tabla Listado de Notas de un Alumno -->
        </div>
        
      </div>
      
      <!--Inicio del modal cargar notas-->
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
                  <label class="col-md-3 form-control-label" for="nota">Nota (*)</label>
                  <div class="col-md-9">
                    <input type="text" v-model="nota" class="form-control" placeholder="Nota">
                    <span class="text-error" v-if="errores.nota==1">Debe introducir una Nota</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="Observación">Observación (*)</label>
                  <div class="col-md-9">
                    <input type="text" v-model="observacion" class="form-control" placeholder="Observación">
                    <span class="text-error" v-if="errores.observacion==1">Debe escribir una Observación</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="id_tipo_calificacion">Tipo Nota (*)</label>
                  <div class="col-md-9">
                    <select  v-model="id_tipo_calificacion" class="form-control">
                      <option disabled value="0">--Seleccione una opción--</option>
                      <option v-for="tipoCalificacion in arrayCalificacion" :key="tipoCalificacion.id_tipo_calificacion" :value="tipoCalificacion.id_tipo_calificacion" v-text="tipoCalificacion.tipo_calificacion"></option>
                    </select>
                    <span class="text-error" v-if="errores.id_tipo_calificacion==1">Debe seleccionar un Tipo de Calificación</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="id_parcial">Parcial (*)</label>
                  <div class="col-md-9">
                    <select  v-model="id_parcial" class="form-control">
                      <option disabled value="0">--Seleccione una opción--</option>
                      <option v-for="parcial in arrayParcial" :key="parcial.id_parcial" :value="parcial.id_parcial" v-text="parcial.nombre_parcial"></option>
                    </select>
                    <span class="text-error" v-if="errores.id_parcial==1">Debe seleccionar un Parcial</span>
                  </div>
                </div>

              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" @click="registrarNota()" class="btn btn-primary">Registrar</button>
              <button type="button" v-if="tipoAccion==2" @click="actualizarNota()" class="btn btn-primary">Actualizar</button>
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
        nombre_clase: '',
        codigo_clase: '',
        id_calificacion: '',

        id_clase: 0,
        id_alumno: 0,

        id_parcial: 0,
        nombre_parcial: '',

        id_tipo_calificacion: 0,
        tipo_calificacion: '',

        id_archivo_tarea: 0,
        nombre_archivo: 0,

        nota: 0,
        observacion: "",
        filtro_parcial: "",

        // Variables auxiliares
        verMas: 1,
        arrayClase: [], // almacena los datos del objeto response
        arrayListarAlumnoClase: [],
        arrayAlumno: [], // almacena los datos del objeto response
        arrayParcial: [], // almacena los datos del objeto response
        arrayCalificacion: [], // almacena los datos del objeto response
        arrayTest:[],
        
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorNota: 0, // 1: Existe un error
        errores: {
          "id_tipo_calificacion" : 0,
          "id_parcial" : 0,
          "nota" : 0,
          "observacion": 0,

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
        criterio: 'primer_nombre', // Indica cual es el campo de busqueda
        buscar: '', // Cual es el texto de busqueda para realizar el filtro de todos los registros

        criterioClase: 'seccion',
        buscarClase: '',

        offset: 2 // Cantidad de páginas a la izq y der de la actual
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
      listarClase(page, buscarClase, criterioClase){
        let me = this;
        var url = '/clase/maestro?page=' + page + '&buscar='+ buscarClase + '&criterio='+ criterioClase;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayClase = respuesta.clase;
        })
        .catch(function(error) {
          console.log(error);
        }); 
      },
      verAlumnos(idClase){
        let me = this;
        me.id_clase = idClase;
        me.listarAlumno(1, '', 'primer_nombre');
        me.verMas = 2;
      },
      listarAlumno(page, buscar, criterio){
        let me = this;
        var url = '/clase/listarAlumnosClase?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.post(url, {
          'id_clase': me.id_clase
        }).then(function (response) {
          var respuesta = response.data;
          me.arrayListarAlumnoClase = respuesta.alumno.data;
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
        me.listarAlumno(page, buscar, criterio);
      },
      ocultarAlumnos(){
        this.verMas = 1;
      },
      ocultarNotas(){
        this.verMas = 2;
      },
      verNotaAlumno(id_alumno){
        let me = this;
        me.id_alumno = id_alumno;
        me.selectParcial();
        me.filtro_parcial = 0;
        me.arrayAlumno = [];

        axios.post('/clase/verNotaAlumno', {
          'id_clase': me.id_clase,
          'id_alumno': me.id_alumno
        }).then(function(response){
          me.arrayAlumno = response.data;
          me.verMas = 3;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      verNotaAlumnoParcial(id_alumno){
        let me = this;
        me.id_alumno = id_alumno;
        me.selectParcial();
        me.arrayAlumno = [];

        axios.post('/clase/verNotaAlumno', {
          'id_clase': me.id_clase,
          'id_alumno': me.id_alumno,
          'filtro_parcial': me.filtro_parcial
        }).then(function(response){
          me.arrayAlumno = response.data;
          me.verMas = 3;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      registrarNota(){
        // Si el método validarNota devuelve 1 significa que hay un error.
        if(this.validarNota()){
          return; // Si hay un error, se ejecuta el return;
        }
        let me = this;
        axios.post('/clase/registrarNota', {
          'id_clase': this.id_clase,
          'id_alumno': this.id_alumno,

          'id_parcial': this.id_parcial,
          'id_tipo_calificacion': this.id_tipo_calificacion,
          'id_archivo_tarea': this.id_archivo_tarea,
          'nota': this.nota,
          'observacion': this.observacion
        }).then(function(response){
          me.cerrarModal();
          me.verNotaAlumno(me.id_alumno);

          me.arrayTest = response.data.conteo;
          
          if(response.data.conteo){
            Swal.fire({
              type: 'warning',
              title: 'La nota Final de ese parcial ya existe.',
              text: 'El registro No ha sido guardado.',
            });
          } else {
            Swal.fire({
              type: 'success',
              title: 'Nota Registrada.',
              text: 'El registro ha sido guardado con éxito.',
            });
          }
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      actualizarNota(){
        // Si el método validarNota devuelve 1 significa que hay un error.
        if(this.validarNota()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        axios.put('/clase/actualizarNota', {
          'id_calificacion': this.id_calificacion,
          'id_clase': this.id_clase,
          'id_alumno': this.id_alumno,
          'id_parcial': this.id_parcial,
          'id_tipo_calificacion': this.id_tipo_calificacion,
          'id_archivo_tarea': this.id_archivo_tarea,
          'nota': this.nota,
          'observacion': this.observacion
        }).then(function(response){
          me.cerrarModal();
          me.verNotaAlumno(me.id_alumno);

          Swal.fire({
            type: 'success',
            title: 'Nota Actualizada.',
            text: 'El registro ha sido actualizado con éxito.',
          });
        })
        .catch(function(error) {
          console.log(error);
        });

      },
      validarNota(){
        this.errorNota = 0;
        this.errores = {
          "id_tipo_calificacion" : 0,
          "id_parcial" : 0,
          "nota" : 0,
          "observacion": 0,

          "total_errores" : 0
        };

        if(!this.id_tipo_calificacion){
          this.errores.id_tipo_calificacion = 1;
          this.errores.total_errores = 1;
        }
        if(!this.id_parcial){
          this.errores.id_parcial = 1;
          this.errores.total_errores = 1;
        }
        if(!this.nota){
          this.errores.nota = 1;
          this.errores.total_errores = 1;
        }
        if(!this.observacion){
          this.errores.observacion = 1;
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorNota = 1;

        return this.errorNota;
      },
      selectCalificacion(){
        let me = this;
        var url = '/tipocalificacion/selectTipoCalificacion';

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayCalificacion = respuesta.tipoCalificacion;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      selectParcial(){
        let me = this;
        var url = '/parcial/selectParcial';

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayParcial = respuesta.parcial;
        })
        .catch(function (error) {
          console.log(error);
        });
      },
      cerrarModal(){
        this.tituloModal = '';

        this.nota = '';
        this.observacion = '';
        this.id_parcial = '';
        this.id_tipo_calificacion = '';
        this.id_archivo_tarea = '';

        this.errorNota = 0; // 0: No Existe un error
        $('#modalNuevo').modal('hide');
      },
      abrirModal (modelo, accion, data = []){
        this.selectCalificacion();
        this.selectParcial();

        this.errores = {
          "id_tipo_calificacion" : 0,
          "id_parcial" : 0,
          "nota" : 0,
          "observacion": 0,
          "total_errores" : 0
        };

        switch (modelo) {
          case "alumno": {
            switch (accion) {
              case "actualizar": {
                let me = this;
                me.tituloModal = 'Actualizar Nota';
                me.tipoAccion = 2; // 2: Actualizar

                this.id_calificacion = data['id_calificacion'];
                this.id_clase = data['id_clase'];
                this.id_alumno = data['id_alumno'];
                this.id_parcial = data['id_parcial'];
                this.id_tipo_calificacion = data['id_tipo_calificacion'];
                this.id_archivo_tarea = data['id_archivo_tarea'];
                this.nota = data['nota'];
                this.observacion = data['observacion'];
                
                break;
              }
            }
          }
          case "alumno": {
            switch (accion) {
              case "registrar": {
                let me = this;
                me.tituloModal = 'Registrar Nota';
                me.tipoAccion = 1; // 1: Registrar
                
                //me.id_clase = me.id_clase;
                //me.id_alumno = data.id_alumno;
                me.id_parcial = 0;
                me.id_tipo_calificacion = 0;
                me.id_archivo_tarea = '';
                me.nota = '';
                me.observacion = '';
                
                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarClase(1, this.buscarClase, this.criterioClase);
      this.$ga.page('/Clase/Maestro');
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
