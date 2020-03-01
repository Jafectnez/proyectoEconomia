<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Clases</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
            <div class="card-body">
              <div class="form-group row">
                <!-- <h3 class="col-md-12 mb-3">Información General</h3>
                Columna Derecha 
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4">
                      <label class="form-control-label" for="nota">Cuenta:</label>
                    </div>
                    <div class="col-md-8">
                      <input type="text" class="form-control" placeholder="Cuenta">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <label class="form-control-label" for="nota">Nombre:</label>
                    </div>
                    <div class="col-md-8">
                      <input type="text" class="form-control" placeholder="Nombre">
                    </div>
                  </div>
                </div>-->

                <!-- Columna Izquierda -->
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4">
                      <label class="form-control-label" for="nota">Indice Global:</label>
                    </div>
                    <div class="col-md-8">
                      <div v-text="indice_global"></div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <button type="submit" @click="calcularIndiceGlobal()" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Calcular Indice Global</button>
                    </div>
                  </div>
                </div>

              </div>           
            </div>

            <template v-if="verMas==1">
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-8">
                    <div class="input-group">
                      <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                        <option value="nombre_asignatura">Asignatura</option>
                        <option value="codigo_asignatura">Código Asignatura</option>
                      </select>

                      <input type="text" v-model="buscar" @keyup.enter="listarClase(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                      <button type="submit" @click="listarClase(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                    </div>
                  </div>
                </div>
                
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>Maestro Encargado</th>
                        <th>Código</th>
                        <th>Asignatura</th>
                        <th>Indice Parcial</th>
                        <th>Año</th>
                        <th>Sección</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Días</th>
                        <th>Aula</th>
                        <th>Observación</th>
                        <th>Opciones</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="clase in arrayClase" :key="clase.id">
                          <td v-text="clase.primer_nombre + ' ' + clase.segundo_nombre + ' ' + clase.primer_apellido + ' ' + clase.segundo_apellido"></td>

                          <td v-text="clase.codigo_asignatura"></td>

                          <td v-text="clase.nombre_asignatura"></td>

                          <td v-text="clase.indice_clase"></td>
                          
                          <td v-text="clase.anio_clase"></td>

                          <td v-text="clase.seccion"></td>
                          
                          <td v-text="clase.hora_inicio"></td>
                          
                          <td v-text="clase.hora_fin"></td>
                          
                          <td v-text="clase.dias"></td>

                          <td v-text="clase.nombre_aula"></td>
                          
                          <td v-text="clase.observacion"></td>

                          <td>
                            <button type="button" @click="verNotasClase(clase.id_clase)" class="btn btn-success btn-sm mb-1">
                              <i class="icon-eye"></i>
                            </button> &nbsp;

                            <button type="button" @click="calcularIndiceClase(clase.id_clase)" class="btn btn-info btn-sm mb-1">
                              <i class="icon-pencil"></i>
                            </button> &nbsp;
                          </td>
                          <!--
                          <td>
                              <div v-if="clase.estado=='A'">
                                  <span class="badge badge-success">Activo</span>
                              </div>
                              <div v-else>
                                  <span class="badge badge-danger">Inactivo</span>
                              </div>
                          </td>-->
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

              <!-- Tabla Listado de Notas del Alumno -->
            <template v-else-if="verMas==2">
              <div class="card-body">
                <div class="form-group row">
                  <div class="table-responsive col-md-12">
                    <table class="table table-bordered table-striped table-sm">
                      <thead>
                        <tr>
                          <th>Cuenta Alumno</th>
                          <th>Nombre Alumno</th>
                          <th>Nota</th>
                          <th>Parcial</th>
                          <th>Año</th>
                          <th>Tipo Calificación</th>
                          <th>Observación</th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr v-for="notas in arrayCalificacion" :key="notas.id_calificacion">
                          
                          <td v-text="notas.codigo_alumno"></td>
                          
                          <td v-text="notas.primer_nombre + ' ' + notas.segundo_nombre + ' ' + notas.primer_apellido + ' ' + notas.segundo_apellido"></td>
                          
                          <template v-if="notas.nota">
                            <td v-text="notas.nota"></td>

                            <td v-text="notas.nombre_parcial"></td>

                            <td v-text="notas.anio_clase"></td>

                            <td v-text="notas.tipo_calificacion"></td>

                            <td v-text="notas.observacion"></td> 
                          </template>

                          <template v-else>
                            <td>Sin Nota</td>

                            <td>Parcial</td>

                            <td>Año</td>           

                            <td>Sin Observación</td> 
                          </template>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-12">
                    <button type="button" @click="ocultarNotas()" class="btn btn-secondary">Atrás</button>
                  </div>
                </div>
              </div>
            </template>
            <!-- Fin Tabla Listado de Notas del Alumno -->
          </div>
        <!-- Fin Tabla Listado -->
      </div>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        nombre_clase: '',
        codigo_clase: '',
        id_clase: 0,
        id_parcial: 0,
        nombre_parcial: '',
        indice_global: '',

        id_tipo_calificacion: 0,
        tipo_calificacion: '',

        id_archivo_tarea: 0,
        nombre_archivo: 0,

        nota: 0,
        observacion: '',

        // Variables auxiliares
        verMas: 1,
        arrayClase : [], // almacena los datos del objeto response
        arrayCalificacion: [], // almacena los datos del objeto response
        arrayTest:[],
        arrayTest2:[],
        
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        pagination: {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
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
      listarClase(page, buscar, criterio){
        let me = this;
        var url = '/clase/alumno?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayClase = respuesta.clase.data;
          me.pagination = respuesta.pagination;

          me.indice_global = respuesta.clase.data[0].indice_global;
        })
        .catch(function(error) {
          console.log(error);
        }); 
      },
      listarInformacion(){

      },
      calcularIndiceClase(id_clase){
        let me = this;
        me.id_clase = id_clase;

        axios.post('/clase/calcularIndiceClase', {
          'id_clase': me.id_clase
        }).then(function(response){
          me.listarClase(1, "", 'nombre_asignatura');
          me.arrayTest = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      calcularIndiceGlobal(){
        let me = this;

        axios.post('/clase/calcularIndiceGlobal')
        .then(function(response){
          me.listarClase(1, "", 'nombre_asignatura');
          me.indice_global = response.data.alumno.indice_global;
          me.arrayTest2 = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      verNotasClase(id_clase){
        //console.log(id_clase);
        let me = this;
        me.id_clase = id_clase;

        axios.post('/clase/verNotasDeAlumno', {
          'id_clase': me.id_clase
        }).then(function(response){
          me.arrayCalificacion = response.data;
          me.verMas = 2;
          //console.log(me.id_clase);
        })
        .catch(function(error) {
          console.log(error);
        });
      },
      ocultarNotas(){
        this.verMas = 1;
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarClase(page, buscar, criterio);
      }
    },
    mounted() {
      this.listarClase(1, this.buscar, this.criterio);
      this.listarInformacion();
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
