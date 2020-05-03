<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">Recursos Humanos</li>
      <li class="breadcrumb-item active">Alumnos</li>
    </ol>

    <div class="container-fluid">
      <!-- Tabla Listado -->
      <div class="card">
        <div class="card-header">
          <button
            type="button"
            class="btn btn-secondary mt-1"
            @click="abrirModal('alumno','registrar')"
            data-toggle="modal"
            data-target="#modalNuevo"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo Registro
          </button>
        </div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-4" v-model="criterio" id="opcion" name="opcion">
                  <option value="primer_nombre">Nombre</option>
                  <option value="primer_apellido">Apellido</option>
                  <option value="codigo_alumno">Cuenta</option>
                  <option value="numero_identidad">Número de Identidad</option>
                  <!--<option value="usuario">Usuario</option>-->
                  <!--<option value="telefono">Teléfono</option>-->
                </select>

                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarAlumno(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Buscar"
                />
                <button
                  type="submit"
                  @click="listarAlumno(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i>Buscar
                </button>
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
                  <th>Código</th>
                  <th>Teléfono</th>
                  <th>Estado</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="alumno in arrayAlumno" :key="alumno.id_alumno">
                  <td>
                    <button
                      type="button"
                      @click="abrirModal('alumno', 'actualizar', alumno)"
                      class="btn btn-warning btn-sm"
                      data-toggle="modal"
                      data-target="#modalNuevo"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                    <!--&nbsp; deja espacio -->

                    <!-- Si el estado del alumno es activo, se muestra este botón -->
                    <template v-if="alumno.estado == 'A'">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="desactivarAlumno(alumno.id_alumno)"
                      >
                        <i class="icon-trash"></i>
                      </button>
                    </template>

                    <!-- De lo contrario si el estado del alumno es Inactivo, se muestra este botón -->
                    <template v-else>
                      <button
                        type="button"
                        class="btn btn-info btn-sm"
                        @click="activarAlumno(alumno.id_alumno)"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </template>
                  </td>

                  <td v-text="alumno.primer_nombre"></td>

                  <td v-text="alumno.primer_apellido"></td>

                  <td v-text="alumno.numero_identidad"></td>

                  <td v-text="alumno.correo_electronico"></td>

                  <td v-text="alumno.codigo_alumno"></td>

                  <td v-text="alumno.telefono"></td>

                  <td>
                    <div v-if="alumno.estado=='A'">
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
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)"
                >Ant</a>
              </li>

              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page, buscar, criterio)"
                  v-text="page"
                ></a>
              </li>

              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin Tabla Listado -->
    </div>

    <!--Inicio del modal Agregar/actualizar-->
    <div
      class="modal fade"
      id="modalNuevo"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 v-text="tituloModal" class="modal-title"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Primer Nombre (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="primer_nombre"
                    class="form-control"
                    placeholder="Primer Nombre"
                  />
                  <span class="text-error" v-if="errores.primer_nombre.error == 1">
                    <span v-text="errores.primer_nombre.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="segundo-nombre">Segundo Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="segundo_nombre"
                    class="form-control"
                    placeholder="Segundo Nombre"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="primer-apellido">Primer Apellido (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="primer_apellido"
                    class="form-control"
                    placeholder="Primer Apellido"
                  />
                  <span class="text-error" v-if="errores.primer_apellido.error == 1">
                    <span v-text="errores.primer_apellido.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="segundo-apellido">Segundo Apellido</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="segundo_apellido"
                    class="form-control"
                    placeholder="Segundo Apellido"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero-foto_miniatura">Foto Personal</label>
                <div class="col-md-9">
                  <img id="foto_miniatura" v-if="foto_url != ''" />
                  <input type="file" class="form-control" />
                </div>
              </div>

              <div class="form-group row">
                <label
                  class="col-md-3 form-control-label"
                  for="numero-identidad"
                >Número de Identidad (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="numero_identidad"
                    class="form-control"
                    placeholder="Número de Identidad"
                  />
                  <span class="text-error" v-if="errores.numero_identidad.error == 1">
                    <span v-text="errores.numero_identidad.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha-nacimiento">Fecha Nacimiento</label>
                <div class="col-md-9">
                  <input
                    type="date"
                    v-model="fecha_nacimiento"
                    class="form-control"
                    placeholder="Fecha Nacimiento"
                  />
                  <span class="text-error" v-if="errores.fecha_nacimiento.error == 1">
                    <span v-text="errores.fecha_nacimiento.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="correo">Correo (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="correo_electronico"
                    class="form-control"
                    placeholder="Correo"
                  />
                  <span class="text-error" v-if="errores.correo_electronico.error ==  1">
                    <span v-text="errores.correo_electronico.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="telefono">Teléfono (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Teléfono del contacto"
                  />
                  <span class="text-error" v-if="errores.telefono.error == 1">
                    <span v-text="errores.telefono.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="contrasena">Contraseña (*)</label>
                <div class="col-md-9">
                  <input
                    type="password"
                    v-model="contrasena"
                    class="form-control"
                    placeholder="Contraseña"
                  />
                  <span class="text-error" v-if="errores.contrasena.error == 1">
                    <span v-text="errores.contrasena.mensaje"></span>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="sexo">Sexo</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="sexo">
                    <option disabled value>--Seleccione una opción--</option>
                    <option selected value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="direccion">Dirección</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="direccion"
                    class="form-control"
                    placeholder="Dirección"
                  />
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal()"
              data-dismiss="modal"
            >Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              @click="registrarAlumno()"
              class="btn btn-primary"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              @click="actualizarAlumno()"
              class="btn btn-primary"
            >Actualizar</button>
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
  data() {
    return {
      // Variables de Tabla Persona
      primer_nombre: "",
      segundo_nombre: "",
      primer_apellido: "",
      segundo_apellido: "",
      numero_identidad: "",
      fecha_nacimiento: "",
      correo_electronico: "",
      sexo: "",
      direccion: "",

      // Variable de Tabla Telefono
      telefono: "",

      // Variables de Tabla Alumno
      id_alumno: 0,
      codigo_alumno: "",
      contrasena: "",
      promedio: 0,
      fecha_ingreso: "",
      foto_url: "",
      sesion: "",

      // Variables auxiliares
      arrayAlumno: [], // almacena los datos del objeto response
      tituloModal: "",
      tipoAccion: 0, // 1: Registrar 2: Actualizar
      errorAlumno: 0, //  0: No hay Error, 1: Existe un error
      errores: {
        primer_nombre: { error: 0, mensaje: "" },
        primer_apellido: { error: 0, mensaje: "" },
        numero_identidad: { error: 0, mensaje: "" },
        correo_electronico: { error: 0, mensaje: "" },
        fecha_nacimiento: { error: 0, mensaje: "" },

        contrasena: { error: 0, mensaje: "" },

        telefono: { error: 0, mensaje: "" },

        total_errores: 0
      },
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 2, // Cantidad de páginas a la izq y der de la actual
      criterio: "primer_nombre", // Indica cual es el campo de busqueda
      buscar: "" // Cual es el texto de busqueda para realizar el filtro de todos los registros
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    // Calcula los elementos de la paginación
    pagesNumber: function() {
      // Es diferente del ultimo elemento de la página actual
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      // Mientras la página actual sea menor que la última página
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    trackAnalytics(categoria, accion, label, value) {
      this.$ga.event(categoria, accion, label, value);
    },
    listarAlumno(page, buscar, criterio) {
      let me = this;
      var url =
        "/alumno?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;

      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayAlumno = respuesta.alumno.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    selectTipoUsuario() {
      let me = this;
      var url = "/tipousuario/selectTipo";

      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayTipoUsuario = respuesta.tipoUsuario;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarAlumno(page, buscar, criterio);
    },
    registrarAlumno() {
      // Si el método validarAlumno devuelve 1 significa que hay un error.
      if (this.validarAlumno()) {
        return; // Si hay un error, se ejecuta el return;
      }

      let me = this;
      //Primer parame: ruta, Segundo param: datos
      axios
        .post("/alumno/registrar", {
          primer_nombre: this.primer_nombre,
          segundo_nombre: this.segundo_nombre,
          primer_apellido: this.primer_apellido,
          segundo_apellido: this.segundo_apellido,
          numero_identidad: this.numero_identidad,
          fecha_nacimiento: this.fecha_nacimiento,
          correo_electronico: this.correo_electronico,
          sexo: this.sexo,
          direccion: this.direccion,

          telefono: this.telefono,

          //'codigo_alumno': this.codigo_alumno,
          contrasena: this.contrasena,
          //'promerio': this.promerio,
          fecha_ingreso: this.fecha_ingreso,
          foto_url: this.foto_url
        })
        .then(function(response) {
          if (response.data.error == 1) {
            Swal.fire({
              type: "error",
              title: "Error interno.",
              text: response.data.mensaje
            });

            trackAnalytics("registro", "click", "registro-alumno", "failure");
          } else {
            me.cerrarModal();
            me.listarAlumno(1, "", "primer_nombre");

            Swal.fire({
              type: "success",
              title: "Registrado.",
              text: "El registro ha sido guardado con éxito."
            });

            trackAnalytics("registro", "click", "registro-alumno", "success");
          }
        })
        .catch(function(error) {
          var ArrayErrores = Object.assign({}, error).response.data.errors;

          // console.log('Error:', ArrayErrores);

          if (ArrayErrores["primer_nombre"]) {
            me.errores.primer_nombre = {
              error: 1,
              mensaje: ArrayErrores["primer_nombre"][0]
            };
          }

          if (ArrayErrores["primer_apellido"]) {
            me.errores.primer_apellido = {
              error: 1,
              mensaje: ArrayErrores["primer_apellido"][0]
            };
          }

          if (ArrayErrores["numero_identidad"]) {
            me.errores.numero_identidad = {
              error: 1,
              mensaje: ArrayErrores["numero_identidad"][0]
            };
          }

          if (ArrayErrores["correo_electronico"]) {
            me.errores.correo_electronico = {
              error: 1,
              mensaje: ArrayErrores["correo_electronico"][0]
            };
          }

          if (ArrayErrores["telefono"]) {
            me.errores.telefono = {
              error: 1,
              mensaje: ArrayErrores["telefono"][0]
            };
          }

          if (ArrayErrores["fecha_nacimiento"]) {
            me.errores.fecha_nacimiento = {
              error: 1,
              mensaje: ArrayErrores["fecha_nacimiento"][0]
            };
          }
        });
    },
    actualizarAlumno() {
      // Si el método validarAlumno devuelve 1 significa que hay un error.
      if (this.validarAlumno()) {
        return; // Si hay un error, se ejecuta el return;
      }

      let me = this;
      //Primer parame: ruta, Segundo param: datos
      axios
        .put("/alumno/actualizar", {
          id_alumno: this.id_alumno,
          primer_nombre: this.primer_nombre,
          segundo_nombre: this.segundo_nombre,
          primer_apellido: this.primer_apellido,
          segundo_apellido: this.segundo_apellido,
          numero_identidad: this.numero_identidad,
          fecha_nacimiento: this.fecha_nacimiento,
          correo_electronico: this.correo_electronico,
          sexo: this.sexo,
          direccion: this.direccion,

          telefono: this.telefono,

          //'codigo_alumno': this.codigo_alumno,
          // 'contrasena': this.contrasena,
          //'promerio': this.promerio,
          fecha_ingreso: this.fecha_ingreso,
          foto_url: this.foto_url
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarAlumno(1, "", "primer_nombre");

          Swal.fire({
            type: "success",
            title: "Actulizado.",
            text: "El registro ha sido actualizado con éxito."
          });
        })
        .catch(function(error) {
          var ArrayErrores = Object.assign({}, error).response.data.errors;

          // console.log('Error:', ArrayErrores);

          if (ArrayErrores["primer_nombre"]) {
            me.errores.primer_nombre = {
              error: 1,
              mensaje: ArrayErrores["primer_nombre"][0]
            };
          }

          if (ArrayErrores["primer_apellido"]) {
            me.errores.primer_apellido = {
              error: 1,
              mensaje: ArrayErrores["primer_apellido"][0]
            };
          }

          if (ArrayErrores["numero_identidad"]) {
            me.errores.numero_identidad = {
              error: 1,
              mensaje: ArrayErrores["numero_identidad"][0]
            };
          }

          if (ArrayErrores["correo_electronico"]) {
            me.errores.correo_electronico = {
              error: 1,
              mensaje: ArrayErrores["correo_electronico"][0]
            };
          }

          if (ArrayErrores["telefono"]) {
            me.errores.telefono = {
              error: 1,
              mensaje: ArrayErrores["telefono"][0]
            };
          }

          if (ArrayErrores["fecha_nacimiento"]) {
            me.errores.fecha_nacimiento = {
              error: 1,
              mensaje: ArrayErrores["fecha_nacimiento"][0]
            };
          }
        });
    },
    desactivarAlumno(id_alumno) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Está Seguro de desactivar este Alumno?",
          //text: "No se puede revertir esta acción!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            //Primer parame: ruta, Segundo param: datos
            axios
              .put("/alumno/desactivar", {
                id_alumno: id_alumno
              })
              .then(function(response) {
                me.listarAlumno(1, "", "primer_nombre");
                swalWithBootstrapButtons.fire(
                  "Desactivado!",
                  "El registro ha sido desactivado con éxito.",
                  "success"
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
              "Cancelado",
              "El registro no ha sido desactivado.",
              "error"
            );
          }
        });
    },
    activarAlumno(id_alumno) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Está Seguro de activar este Alumno?",
          //text: "No se puede revertir esta acción!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            //Primer parame: ruta, Segundo param: datos
            axios
              .put("/alumno/activar", {
                id_alumno: id_alumno
              })
              .then(function(response) {
                me.listarAlumno(1, "", "primer_nombre");
                swalWithBootstrapButtons.fire(
                  "Activado!",
                  "El registro ha sido activado con éxito.",
                  "success"
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
              "Cancelado",
              "El registro no ha sido activado.",
              "error"
            );
          }
        });
    },
    validarAlumno() {
      this.errorAlumno = 0;
      this.errores = {
        primer_nombre: { error: 0, mensaje: "" },
        primer_apellido: { error: 0, mensaje: "" },
        numero_identidad: { error: 0, mensaje: "" },
        correo_electronico: { error: 0, mensaje: "" },
        fecha_nacimiento: { error: 0, mensaje: "" },

        contrasena: { error: 0, mensaje: "" },

        telefono: { error: 0, mensaje: "" },

        total_errores: 0
      };

      //Se verifican que los datos esten en formato correcto
      var regex = {
        nombres: /^[A-Z][a-zà-ÿ]+/,
        correo: /^[a-zA-Z0-9\._-]+@([_a-zA-Z0-9])+(\.[a-zA-Z]+)+$/,
        identidad: /^(0[1-9][0-9]{2}|1[0-8][0-9]{2})(19[4-9][0-9]|20[0-1][0-9])[0-9]{5}$/,
        telefono: /(^[504](2|3|8|9)[0-9]{3}[0-9]{4})|(^(2|3|8|9)[0-9]{3}[0-9]{4})$/
      };

      if (!regex.nombres.test(this.primer_nombre)) {
        this.errores.primer_nombre = {
          error: 1,
          mensaje:
            "El nombre solo debe contener letras e iniciar en Mayúsculas."
        };
        this.errores.total_errores = 1;
      }
      if (!regex.nombres.test(this.primer_apellido)) {
        this.errores.primer_apellido = {
          error: 1,
          mensaje:
            "El apellido solo debe contener letras e iniciar en Mayúsculas."
        };
        this.errores.total_errores = 1;
      }
      if (!regex.identidad.test(this.numero_identidad)) {
        this.errores.numero_identidad = {
          error: 1,
          mensaje:
            "El número de identidad debe estar en el formato 0101-1991-00001 (sin guiones)."
        };
        this.errores.total_errores = 1;
      }
      if (!regex.correo.test(this.correo_electronico)) {
        this.errores.correo_electronico = {
          error: 1,
          mensaje:
            "El correo electrónico debe estar en formato micorreo@miservidor.com"
        };
        this.errores.total_errores = 1;
      }
      if (!regex.telefono.test(this.telefono)) {
        this.errores.telefono = {
          error: 1,
          mensaje: "El teléfono debe estar en formato 0000-0000 (sin el guion)."
        };
        this.errores.total_errores = 1;
      }

      //Se verifican campos vacíos
      if (!this.primer_nombre) {
        this.errores.primer_nombre = {
          error: 1,
          mensaje: "El primer nombre no puede ir vacío."
        };
        this.errores.total_errores = 1;
      }
      if (!this.primer_apellido) {
        this.errores.primer_apellido = {
          error: 1,
          mensaje: "El primer apellido no puede ir vacío."
        };
        this.errores.total_errores = 1;
      }
      if (!this.numero_identidad) {
        this.errores.numero_identidad = {
          error: 1,
          mensaje: "El número de identidad no puede ir vacío."
        };
        this.errores.total_errores = 1;
      }
      if (!this.correo_electronico) {
        this.errores.correo_electronico = {
          error: 1,
          mensaje: "El correo electrónico no puede ir vacío."
        };
        this.errores.total_errores = 1;
      }
      if (!this.contrasena) {
        this.errores.contrasena = {
          error: 1,
          mensaje: "La contraseña no puede ir vacía."
        };
        this.errores.total_errores = 1;
      }
      if (!this.telefono) {
        this.errores.telefono = {
          error: 1,
          mensaje: "El teléfono no puede ir vacío."
        };
        this.errores.total_errores = 1;
      }

      if (this.errores.total_errores != 0) this.errorAlumno = 1;

      return this.errorAlumno;
    },
    cerrarModal() {
      this.tituloModal = "";

      this.primer_nombre = "";
      this.segundo_nombre = "";
      this.primer_apellido = "";
      this.segundo_apellido = "";
      this.numero_identidad = "";
      this.fecha_nacimiento = "";
      this.correo_electronico = "";
      this.sexo = "";
      this.direccion = "";

      (this.telefono = ""), (this.fecha_ingreso = "");
      this.codigo_alumno = "";
      this.foto_url = "";
      this.contrasena = "";
      this.promedio = 0;
      (this.sesion = ""), (this.errorAlumno = 0); // 0: No Existe un error
      this.errores = {
        primer_nombre: { error: 0, mensaje: "" },
        primer_apellido: { error: 0, mensaje: "" },
        numero_identidad: { error: 0, mensaje: "" },
        correo_electronico: { error: 0, mensaje: "" },
        fecha_nacimiento: { error: 0, mensaje: "" },

        contrasena: { error: 0, mensaje: "" },

        telefono: { error: 0, mensaje: "" },

        total_errores: 0
      };
      $("#modalNuevo").modal("hide"); // Cierra el Modal
    },
    abrirModal(modelo, accion, data = []) {
      this.selectTipoUsuario();

      this.errores = {
        primer_nombre: { error: 0, mensaje: "" },
        primer_apellido: { error: 0, mensaje: "" },
        numero_identidad: { error: 0, mensaje: "" },
        correo_electronico: { error: 0, mensaje: "" },
        fecha_nacimiento: { error: 0, mensaje: "" },

        contrasena: { error: 0, mensaje: "" },

        telefono: { error: 0, mensaje: "" },

        total_errores: 0
      };

      switch (modelo) {
        case "alumno": {
          switch (accion) {
            case "registrar": {
              this.tituloModal = "Registrar Alumno";
              this.tipoAccion = 1; // 1: Registrar

              this.primer_nombre = "";
              this.segundo_nombre = "";
              this.primer_apellido = "";
              this.segundo_apellido = "";
              this.numero_identidad = "";
              this.fecha_nacimiento = "";
              this.correo_electronico = "";
              this.sexo = "";
              this.direccion = "";

              (this.telefono = ""), (this.contrasena = "");
              this.promedio = 0;
              (this.fecha_ingreso = ""),
                (this.foto_url = ""),
                (this.sesion = "");

              break;
            }
            case "actualizar": {
              this.tituloModal = "Actualizar Alumno";
              this.tipoAccion = 2; // Actualizar

              this.id_alumno = data["id_alumno"];

              this.primer_nombre = data["primer_nombre"];
              this.segundo_nombre = data["segundo_nombre"];
              this.primer_apellido = data["primer_apellido"];
              this.segundo_apellido = data["segundo_apellido"];
              this.numero_identidad = data["numero_identidad"];
              this.fecha_nacimiento = data["fecha_nacimiento"];
              this.correo_electronico = data["correo_electronico"];
              this.sexo = data["sexo"];
              this.direccion = data["direccion"];

              this.telefono = data["telefono"];

              this.contrasena = "asd.456";
              this.fecha_ingreso = data["fecha_ingreso"];
              this.foto_url = data["foto_url"];
              this.sesion = data["sesion"];

              $("#foto_miniatura").attr("src", data["foto_url"]);

              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarAlumno(1, this.buscar, this.criterio);
    this.$ga.page("/Alumno");
  }
};
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
