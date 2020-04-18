<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">Recursos Humanos</li>
          <li class="breadcrumb-item active">Empleados</li>
      </ol>
      
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
          <div class="card-header">
            <button type="button" class="btn btn-secondary mt-1" @click="abrirModal('empleado', 'registrar')" data-toggle="modal" data-target="#modalNuevo">
              <i class="icon-plus"></i>&nbsp;Nuevo Registro
            </button>
            <!--<i class="fa fa-align-justify"></i>Empleados-->
          </div>

          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-8">
                <div class="input-group">
                  <select class="form-control col-md-4" v-model="criterio">
                    <option value="primer_nombre">Nombre</option>
                    <option value="primer_apellido">Apellido</option>
                    <option value="correo_electronico">correo</option>
                    <option value="numero_identidad">Número de Identidad</option>
                    <!--<option value="usuario">Usuario</option>-->
                    <!--<option value="telefono">Teléfono</option>-->
                  </select>
                  <input type="text" v-model="buscar" @keyup.enter="listarEmpleado(1, buscar, criterio)" class="form-control" placeholder="Buscar">
                  <button type="submit" @click="listarEmpleado(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Número Identidad</th>
                    <th>Correo Electrónico</th>
                    <th>Usuario</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Cargo</th>
                    <th>Estado</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="empleado in arrayEmpleado" :key="empleado.id_empleado">
                    <td>
                      <button type="button" @click="abrirModal('empleado', 'actualizar', empleado)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalNuevo">
                        <i class="icon-pencil"></i>
                      </button> &nbsp; <!--&nbsp; deja espacio -->

                      <template v-if="empleado.estado == 'A'">
                        <!-- Si el estado del empleado es activo, se muestra este botón -->
                        <button type="button"  class="btn btn-danger btn-sm" @click="desactivarEmpleado(empleado.id_empleado)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>

                      <template v-else>
                        <!-- De lo contrario si el estado del empleado es Inactivo, se muestra este botón -->
                        <button type="button"  class="btn btn-info btn-sm" @click="activarEmpleado(empleado.id_empleado)">
                          <i class="icon-check"></i>
                        </button>
                      </template>
                    </td>

                    <td v-text="empleado.primer_nombre"></td>

                    <td v-text="empleado.primer_apellido"></td>

                    <td v-text="empleado.numero_identidad"></td>

                    <td v-text="empleado.correo_electronico"></td>

                    <td v-text="empleado.usuario"></td>

                    <td v-text="empleado.telefono"></td>

                    <td v-text="empleado.tipo_usuario"></td>

                    <td v-text="empleado.nombre_cargo"></td>

                    <td>
                      <div v-if="empleado.estado=='A'">
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
        <!-- Fin ejemplo de tabla Listado -->
      </div>
      
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg modal-dialog-centered" role="document">
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
                        <label class="col-md-3 form-control-label" for="text-input">Primer Nombre (*)</label>
                        <div class="col-md-9">
                          <input type="text" v-model="primer_nombre" class="form-control" placeholder="Primer Nombre">
                          <span class="text-error" v-if="errores.primer_nombre.error == 1"><span v-text="errores.primer_nombre.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="segundo-nombre">Segundo Nombre</label>
                        <div class="col-md-9">
                          <input type="text" v-model="segundo_nombre" class="form-control" placeholder="Segundo Nombre">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="primer-apellido">Primer Apellido (*)</label>
                        <div class="col-md-9">
                          <input type="text" v-model="primer_apellido" class="form-control" placeholder="Primer Apellido">
                          <span class="text-error" v-if="errores.primer_apellido.error == 1"><span v-text="errores.primer_apellido.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="segundo-apellido">Segundo Apellido</label>
                        <div class="col-md-9">
                          <input type="text" v-model="segundo_apellido" class="form-control" placeholder="Segundo Apellido">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="numero-identidad">Número de Identidad (*)</label>
                        <div class="col-md-9">
                          <input type="text" v-model="numero_identidad" class="form-control" placeholder="Número de Identidad">
                          <span class="text-error" v-if="errores.numero_identidad.error == 1"><span v-text="errores.numero_identidad.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="correo">Correo (*)</label>
                        <div class="col-md-9">
                          <input type="email" v-model="correo_electronico" class="form-control" placeholder="Correo">
                          <span class="text-error" v-if="errores.correo_electronico.error ==  1"><span v-text="errores.correo_electronico.mensaje"></span></span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="telefono">Teléfono</label>
                        <div class="col-md-9">
                          <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono del contacto">
                          <span class="text-error" v-if="errores.telefono.error == 1"><span v-text="errores.telefono.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="usuario">Usuario (*)</label>
                        <div class="col-md-9">
                          <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">
                          <span class="text-error" v-if="errores.usuario.error == 1"><span v-text="errores.usuario.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="contrasena">Contraseña (*)</label>
                        <div class="col-md-9">
                          <input type="password" v-model="contrasena" class="form-control" placeholder="Contraseña">
                          <span class="text-error" v-if="errores.contrasena.error == 1"><span v-text="errores.contrasena.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="id_cargo">Cargo (*)</label>
                        <div class="col-md-9">
                          <select v-model="id_cargo" class="form-control">
                            <option disabled value="0">--Seleccione una opción--</option>
                            <option v-for="cargo in arrayCargo" :key="cargo.id_cargo" :value="cargo.id_cargo" v-text="cargo.nombre_cargo"></option>
                          </select>
                          <span class="text-error" v-if="errores.id_cargo.error == 1"><span v-text="errores.id_cargo.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="id_tipo_usuario">Tipo Usuario (*)</label>
                        <div class="col-md-9">
                          <select  v-model="id_tipo_usuario" class="form-control">
                            <option disabled value="0">--Seleccione una opción--</option>
                            <option v-for="tipoUsuario in arrayTipoUsuario" :key="tipoUsuario.id_tipo_usuario" :value="tipoUsuario.id_tipo_usuario" v-text="tipoUsuario.tipo_usuario"></option>
                          </select>
                          <span class="text-error" v-if="errores.id_tipo_usuario.error == 1"><span v-text="errores.id_tipo_usuario.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="numero-foto_miniatura">Foto Personal</label>
                        <div class="col-md-9">
                          <img id="foto_miniatura" v-if="foto_url != ''">
                          <input type="file" class="form-control">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="fecha_ingreso">Fecha Ingreso</label>
                        <div class="col-md-9">
                          <input type="date" v-model="fecha_ingreso" class="form-control" placeholder="Fecha Ingreso">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="fecha-nacimiento">Fecha Nacimiento</label>
                        <div class="col-md-9">
                          <input type="date" v-model="fecha_nacimiento" class="form-control" placeholder="Fecha Nacimiento">
                          <span class="text-error" v-if="errores.fecha_nacimiento.error == 1"><span v-text="errores.fecha_nacimiento.mensaje"></span></span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="sexo">Sexo</label>
                        <div class="col-md-9">
                          <select v-model="sexo" class="form-control">
                            <option disabled value="0">--Seleccione una opción--</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="direccion">Dirección</label>
                        <div class="col-md-9">
                          <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                        </div>
                      </div>

                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" @click="registrarEmpleado()" class="btn btn-primary">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" @click="actualizarEmpleado()" class="btn btn-primary">Actualizar</button>
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
        // Variables de Tabla Persona
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        numero_identidad: '',
        fecha_nacimiento: '',
        correo_electronico: '',
        sexo: '',
        direccion: '',

        // Variable de Tabla Telefono
        telefono: '',

        // Variables de Tabla Empleado
        id_empleado: 0,
        id_tipo_usuario: 0,
        id_cargo: 0,
        fecha_ingreso: '',
        usuario: '',
        contrasena: '',
        foto_url: '',
        sesion: '',

        arrayTipoUsuario: [],
        arrayCargo: [],

        // Variables auxiliares
        arrayEmpleado: [], // almacena los datos del objeto response
        tituloModal: '',
        tipoAccion: 0, // 1: Registrar 2: Actualizar
        errorEmpleado: 0, // 1: Existe un error
        errores : {
          "primer_nombre" : {'error': 0, 'mensaje': ''},
          "primer_apellido" : {'error': 0, 'mensaje': ''},
          "numero_identidad" : {'error': 0, 'mensaje': ''},
          "correo_electronico" : {'error': 0, 'mensaje': ''},
          "fecha_nacimiento" : {'error': 0, 'mensaje': ''},

          "usuario" : {'error': 0, 'mensaje': ''},
          "contrasena" : {'error': 0, 'mensaje': ''},
          "id_cargo" : {'error': 0, 'mensaje': ''},
          "id_tipo_usuario" : {'error': 0, 'mensaje': ''},
          "telefono": {'error': 0, 'mensaje': ''},

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
        criterio: 'primer_nombre', // Indica cual es el campo de busqueda
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
      listarEmpleado(page, buscar, criterio){
          let me = this;
          var url = '/empleado?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;

          axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayEmpleado = respuesta.empleado.data;
            me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      selectTipoUsuario(){
        let me = this;
        var url = '/tipousuario/selectTipo';

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayTipoUsuario = respuesta.tipoUsuario;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      selectCargo(){
        let me = this;
        var url = '/cargo/selectCargo';

        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayCargo = respuesta.cargo;
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
          me.listarEmpleado(page, buscar, criterio);
      },
      registrarEmpleado(){
        // Si el método validarEmpleado devuelve 1 significa que hay un error.
        if(this.validarEmpleado()){
          return; // Si hay un error, se ejecuta el return;
        }

        let me = this;
        //Primer parame: ruta, Segundo param: datos
        axios.post('/empleado/registrar', { 
          'primer_nombre': this.primer_nombre,
          'segundo_nombre': this.segundo_nombre,
          'primer_apellido': this.primer_apellido,
          'segundo_apellido': this.segundo_apellido,
          'numero_identidad': this.numero_identidad,
          'fecha_nacimiento': this.fecha_nacimiento,
          'correo_electronico': this.correo_electronico,
          'sexo': this.sexo,
          'direccion': this.direccion,

          'telefono':this.telefono,
          
          'id_tipo_usuario': this.id_tipo_usuario,
          'id_cargo': this.id_cargo,
          'fecha_ingreso': this.fecha_ingreso,
          'usuario': this.usuario,
          'contrasena': this.contrasena,
          'foto_url': this.foto_url

        }).then(function(response){
          me.cerrarModal();
          me.listarEmpleado(1, '', 'primer_nombre');

          Swal.fire({
            type: 'success',
            title: 'Registrado.',
            text: 'El registro ha sido guardado con éxito.',
          });

        })
        .catch(function(error) {          
          var ArrayErrores = Object.assign({}, error).response.data.errors;

          // console.log('Error:', ArrayErrores);

          if(ArrayErrores['primer_nombre']){
            me.errores.primer_nombre = {'error': 1, 'mensaje': ArrayErrores['primer_nombre'][0]};
          }

          if(ArrayErrores['primer_apellido']){
            me.errores.primer_apellido = {'error': 1, 'mensaje': ArrayErrores['primer_apellido'][0]};
          }

          if(ArrayErrores['numero_identidad']){
            me.errores.numero_identidad = {'error': 1, 'mensaje': ArrayErrores['numero_identidad'][0]};
          }

          if(ArrayErrores['correo_electronico']){
            me.errores.correo_electronico = {'error': 1, 'mensaje': ArrayErrores['correo_electronico'][0]};
          }

          if(ArrayErrores['telefono']){
            me.errores.telefono = {'error': 1, 'mensaje': ArrayErrores['telefono'][0]};
          }

          if(ArrayErrores['fecha_nacimiento']){
            me.errores.fecha_nacimiento = {'error': 1, 'mensaje': ArrayErrores['fecha_nacimiento'][0]};
          }
        });

      },
      actualizarEmpleado(){
        // Si el método validarEmpleado devuelve 1 significa que hay un error.
        if(this.validarEmpleado()){
          return; // Si hay un error, se ejecuta el return y sale del método
        }

        let me = this;

        //Primer parame: ruta, Segundo param: datos
        axios.put('/empleado/actualizar', {
          'id_empleado': this.id_empleado, 
          'primer_nombre': this.primer_nombre,
          'segundo_nombre': this.segundo_nombre,
          'primer_apellido': this.primer_apellido,
          'segundo_apellido': this.segundo_apellido,
          'numero_identidad': this.numero_identidad,
          'fecha_nacimiento': this.fecha_nacimiento,
          'correo_electronico': this.correo_electronico,
          'sexo': this.sexo,
          'direccion': this.direccion,
          
          'telefono': this.telefono,
          
          'id_tipo_usuario': this.id_tipo_usuario,
          'id_cargo': this.id_cargo,
          'fecha_ingreso': this.fecha_ingreso,
          'usuario': this.usuario,
          'contrasena': this.contrasena,
          'foto_url': this.foto_url

        }).then(function(response){
          me.cerrarModal();
          me.listarEmpleado(1, '', 'primer_nombre');
          
          Swal.fire({
            type: 'success',
            title: 'Actulizado.',
            text: 'El registro ha sido actualizado con éxito.',
          });

        })
        .catch(function(error) {
          var ArrayErrores = Object.assign({}, error).response.data.errors;

          // console.log('Error:', ArrayErrores);

          if(ArrayErrores['primer_nombre']){
            me.errores.primer_nombre = {'error': 1, 'mensaje': ArrayErrores['primer_nombre'][0]};
          }

          if(ArrayErrores['primer_apellido']){
            me.errores.primer_apellido = {'error': 1, 'mensaje': ArrayErrores['primer_apellido'][0]};
          }

          if(ArrayErrores['numero_identidad']){
            me.errores.numero_identidad = {'error': 1, 'mensaje': ArrayErrores['numero_identidad'][0]};
          }

          if(ArrayErrores['correo_electronico']){
            me.errores.correo_electronico = {'error': 1, 'mensaje': ArrayErrores['correo_electronico'][0]};
          }

          if(ArrayErrores['telefono']){
            me.errores.telefono = {'error': 1, 'mensaje': ArrayErrores['telefono'][0]};
          }

          if(ArrayErrores['fecha_nacimiento']){
            me.errores.fecha_nacimiento = {'error': 1, 'mensaje': ArrayErrores['fecha_nacimiento'][0]};
          }

          if(ArrayErrores['usuario']){
            me.errores.usuario = {'error': 1, 'mensaje': ArrayErrores['usuario'][0]};
          }
        });

      },
      desactivarEmpleado(id_empleado){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de desactivar este Empleado?',
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
            axios.put('/empleado/desactivar', {
              'id_empleado': id_empleado
            })
            .then(function(response){
              me.listarEmpleado(1, '', 'primer_nombre');
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
      activarEmpleado(id_empleado){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons.fire({
          title: '¿Está Seguro de activar este Empleado?',
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
            axios.put('/empleado/activar', {
              'id_empleado': id_empleado
            })
            .then(function(response){
              me.listarEmpleado(1, '', 'primer_nombre');
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
      validarEmpleado(){
        this.errorEmpleado = 0;
        this.errores = {
          "primer_nombre" : {'error': 0, 'mensaje': ''},
          "primer_apellido" : {'error': 0, 'mensaje': ''},
          "numero_identidad" : {'error': 0, 'mensaje': ''},
          "correo_electronico" : {'error': 0, 'mensaje': ''},
          "fecha_nacimiento" : {'error': 0, 'mensaje': ''},

          "usuario" : {'error': 0, 'mensaje': ''},
          "contrasena" : {'error': 0, 'mensaje': ''},
          
          "id_tipo_usuario" : {'error': 0, 'mensaje': ''},
          "id_cargo" : {'error': 0, 'mensaje': ''},

          "telefono": {'error': 0, 'mensaje': ''},
          
          "total_errores" : 0
        };

        //Se verifican que los datos esten en formato correcto
        var regex = {
          'nombres': /^[A-Z][a-zà-ÿ]+/,
          'correo': /^[a-zA-Z0-9\._-]+@([_a-zA-Z0-9])+(\.[a-zA-Z]+)+$/,
          'identidad': /^(0[1-9][0-9]{2}|1[0-8][0-9]{2})(19[4-9][0-9]|20[0-1][0-9])[0-9]{5}$/,
          'telefono': /(^[504](2|3|8|9)[0-9]{3}[0-9]{4})|(^(2|3|8|9)[0-9]{3}[0-9]{4})$/
        }

        if(!regex.nombres.test(this.primer_nombre)){
          this.errores.primer_nombre = {'error': 1, 'mensaje': 'El nombre solo debe contener letras e iniciar en Mayúsculas.'};
          this.errores.total_errores = 1;
        }
        if(!regex.nombres.test(this.primer_apellido)){
          this.errores.primer_apellido = {'error': 1, 'mensaje': 'El apellido solo debe contener letras e iniciar en Mayúsculas.'};
          this.errores.total_errores = 1;
        }
        if(!regex.identidad.test(this.numero_identidad)){
          this.errores.numero_identidad = {'error': 1, 'mensaje': 'El número de identidad debe estar en el formato 0101-1991-00001 (sin guiones).'};
          this.errores.total_errores = 1;
        }
        if(!regex.correo.test(this.correo_electronico)){
          this.errores.correo_electronico = {'error': 1, 'mensaje': 'El correo electrónico debe estar en formato micorreo@miservidor.com'};
          this.errores.total_errores = 1;
        }
        if(!regex.telefono.test(this.telefono)){
          this.errores.telefono = {'error': 1, 'mensaje': 'El teléfono debe estar en formato 0000-0000 (sin el guion).'};
          this.errores.total_errores = 1;
        }

        //Validación de campos vacíos

        if(!this.primer_nombre){
          this.errores.primer_nombre = {'error': 1, 'mensaje': 'El primer nombre no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.primer_apellido){
          this.errores.primer_apellido = {'error': 1, 'mensaje': 'El primer apellido no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.numero_identidad){
          this.errores.numero_identidad = {'error': 1, 'mensaje': 'El número de identidad no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.correo_electronico){
          this.errores.correo_electronico = {'error': 1, 'mensaje': 'El correo electrónico no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.usuario){
          this.errores.usuario = {'error': 1, 'mensaje': 'El usuario no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.contrasena){
          this.errores.contrasena = {'error': 1, 'mensaje': 'La contraseña no puede ir vacía.'};
          this.errores.total_errores = 1;
        }
        if(!this.telefono){
          this.errores.telefono = {'error': 1, 'mensaje': 'El télefono no puede ir vacío.'};
          this.errores.total_errores = 1;
        }
        if(!this.id_tipo_usuario){
          this.errores.id_tipo_usuario = {'error': 1, 'mensaje': 'Debe seleccionar un tipo de usuario.'};
          this.errores.total_errores = 1;
        }
        if(!this.id_cargo){
          this.errores.id_cargo = {'error': 1, 'mensaje': 'Debe seleccionar un tipo de cargo.'};
          this.errores.total_errores = 1;
        }

        if(this.errores.total_errores != 0) this.errorEmpleado = 1;

        return this.errorEmpleado;

      },
      cerrarModal(){
        //this.modal = 0;
        this.tituloModal = '';

        this.primer_nombre = '';
        this.segundo_nombre = '';
        this.primer_apellido = '';
        this.segundo_apellido = '';
        this.numero_identidad = '';
        this.fecha_nacimiento = '';
        this.correo_electronico = '';
        this.sexo = '';
        this.direccion = '';

        this.telefono = '',

        this.id_tipo_usuario = '',
        this.id_cargo = '',
        this.fecha_ingreso = '',
        this.usuario = '',
        this.contrasena = '',
        this.foto_url = '',
        this.sesion = '',

        this.errorEmpleado = 0; // 0: No Existe un error
        $('#modalNuevo').modal('hide'); // Cierra el Modal

        this.errores = {
          "primer_nombre" : {'error': 0, 'mensaje': ''},
          "primer_apellido" : {'error': 0, 'mensaje': ''},
          "numero_identidad" : {'error': 0, 'mensaje': ''},
          "correo_electronico" : {'error': 0, 'mensaje': ''},
          "fecha_nacimiento" : {'error': 0, 'mensaje': ''},

          "usuario" : {'error': 0, 'mensaje': ''},
          "contrasena" : {'error': 0, 'mensaje': ''},
          
          "id_tipo_usuario" : {'error': 0, 'mensaje': ''},
          "id_cargo" : {'error': 0, 'mensaje': ''},

          "telefono": {'error': 0, 'mensaje': ''},
          
          "total_errores" : 0
        };
      },
      abrirModal (modelo, accion, data = []){
        this.selectTipoUsuario();
        this.selectCargo();

        this.errores = {
          "primer_nombre" : {'error': 0, 'mensaje': ''},
          "primer_apellido" : {'error': 0, 'mensaje': ''},
          "numero_identidad" : {'error': 0, 'mensaje': ''},
          "correo_electronico" : {'error': 0, 'mensaje': ''},
          "fecha_nacimiento" : {'error': 0, 'mensaje': ''},

          "usuario" : {'error': 0, 'mensaje': ''},
          "contrasena" : {'error': 0, 'mensaje': ''},
          
          "id_tipo_usuario" : {'error': 0, 'mensaje': ''},
          "id_cargo" : {'error': 0, 'mensaje': ''},

          "telefono": {'error': 0, 'mensaje': ''},
          
          "total_errores" : 0
        };
        
        switch (modelo) {
          case "empleado": {
            switch (accion) {
              case "registrar": {
                this.tituloModal = 'Registrar Empleado';
                this.tipoAccion = 1; // 1: Registrar

                this.primer_nombre = '';
                this.segundo_nombre = '';
                this.primer_apellido = '';
                this.segundo_apellido = '';
                this.numero_identidad = '';
                this.fecha_nacimiento = '';
                this.correo_electronico = '';
                this.sexo = 0;
                this.direccion = '';

                this.telefono = '',

                this.id_tipo_usuario = 0,
                this.id_cargo = 0,
                this.fecha_ingreso = '',
                this.usuario = '',
                this.contrasena = '',
                this.foto_url = '',
                this.sesion = ''
                
                break;
              }
              case "actualizar": {
                
                this.tituloModal = "Actualizar Empleado";
                this.tipoAccion = 2; // 2: Actualizar

                this.id_empleado = data['id_empleado'];
                this.primer_nombre = data['primer_nombre']
                this.segundo_nombre = data['segundo_nombre'];
                this.primer_apellido = data['primer_apellido'];
                this.segundo_apellido = data['segundo_apellido'];
                this.numero_identidad = data['numero_identidad'];
                this.fecha_nacimiento = data['fecha_nacimiento'];
                this.correo_electronico = data['correo_electronico'];
                this.sexo = data['sexo'];
                this.direccion = data['direccion'];

                this.telefono = data['telefono'];

                this.id_tipo_usuario = data['id_tipo_usuario'];
                this.id_cargo = data['id_cargo'];
                this.fecha_ingreso = data['fecha_ingreso'];
                this.usuario = data['usuario'];
                this.contrasena = data['contrasena'];
                this.foto_url = data['foto_url'];
                this.sesion = data['sesion'];                
                //,this.estado = data['estado'];

                break;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.listarEmpleado(1, this.buscar, this.criterio);
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
