<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Infraestructura</li>
          <li class="breadcrumb-item active">Aulas</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Tabla Listado -->
          <div class="card">
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-8">
                          <div class="input-group">
                              <select class="form-control col-md-4"  v-model="criterio" id="opcion" name="opcion">
                                <option value="nombre_aula">Nombre</option>
                                <option value="codigo_aula">Código</option>
                              </select>

                              <input type="text" v-model="buscar" @keyup.enter="listarAula(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                              <button type="submit" @click="listarAula(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                          </div>
                      </div>
                  </div>

                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Edificio</th>
                              <th>Nombre</th>
                              <th>Código</th>
                              <th>Estado</th>
                          </tr>
                      </thead>

                      <tbody>
                          <tr v-for="aula in arrayAula" :key="aula.id_aula">
                              <td v-text="aula.nombre_edificio"></td>

                              <td v-text="aula.nombre_aula"></td>
                              
                              <td v-text="aula.codigo_aula"></td>
                              
                              <td>
                                  <div v-if="aula.estado=='A'">
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
  </main>
</template>

<script>
  export default {
    data () {
      return {
        id_edificio: '',
        nombre_aula: '',
        codigo_aula: '',

        // Variables auxiliares
        arrayAula : [], // almacena los datos del objeto response
        arrayEdificio : [], // almacena los datos del objeto response
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
        criterio: 'nombre_aula', // Indica cual es el campo de busqueda
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
      listarAula(page, buscar, criterio){
        let me = this;
        var url = '/aula?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayAula = respuesta.aula.data;
          me.pagination = respuesta.pagination;
          axios.get('/edificio').then(function(response){
            me.arrayEdificio = response.data.edificio.data;
          })
          .catch(function(error) {
            console.log(error);
          });
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
        me.listarAula(page, buscar, criterio);
      }
    },
    mounted() {
      this.listarAula(1, this.buscar, this.criterio);
      this.$ga.page('/Aula/Alumno');
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
