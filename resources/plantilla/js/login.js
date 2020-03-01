//etiqueta1 será la que sea seleccionada
function aplicar(etiqueta1, etiqueta2) {
  $(`#login-${etiqueta2}`).css("display", "none");
  $(`#login-${etiqueta1}`).css("display", "");
  $(`#spn-${etiqueta1}`).css("font-weight", "bold");
  $(`#spn-${etiqueta1}`).css("opacity", "0.95");
  $(`#spn-${etiqueta2}`).css("font-weight", "");
  $(`#spn-${etiqueta2}`).css("opacity", "0.4");
}

aplicar('alumno', 'empleado'); //Se ejecuta al inicio para que Empleado sea seleccionado

$("#login").on("change", function () {
  if($("input:radio[name='login']:checked").val() == "A"){
    aplicar('alumno', 'empleado');
  }else{
    aplicar('empleado', 'alumno');
  }
});
$("#login2").on("change", function () {
  if($("input:radio[name='login']:checked").val() == "E"){
    aplicar('empleado', 'alumno');
  }else{
    aplicar('alumno', 'empleado');
  }
});

$("#btn-login").on("click", function () {  
  if($("#contrasena").val() == ""){
    $("#contrasena").css("border-color", "red");
    $("#contrasena").css("color", "red");
  }else{
    $("#contrasena").css("border-color", "");
    $("#contrasena").css("color", "");
  }
});