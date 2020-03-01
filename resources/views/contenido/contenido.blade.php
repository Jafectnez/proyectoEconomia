@extends('principal')
@section('contenido')

  @if(Auth::check())
    @if(Auth::user()->id_tipo_usuario == 1)
      <template v-if="menu==0">
        <img src="../img/logo-SGE.svg" style="position:relative; transform: translate(40%);">
      </template>

      <template v-if="menu==1">
        <empleado></empleado>
      </template>

      <template v-if="menu==2">
        <alumno></alumno>
      </template>

      <template v-if="menu==4">
        <mensaje></mensaje>
      </template>

      <template v-if="menu==5">
        <tipousuario></tipousuario>
      </template>

      <template v-if="menu==6">
        <cargo></cargo>
      </template>

      <template v-if="menu==7">
        <h1>Contenido del menú 6</h1>
      </template>

      <template v-if="menu==8">
        <h1>Contenido del menú 6</h1>
      </template>

      <template v-if="menu==9">
        <edificio></edificio>
      </template>

      <template v-if="menu==10">
        <aula></aula>
      </template>

      <template v-if="menu==11">
        <grado></grado>
      </template>

      <template v-if="menu==12">
        <asignatura></asignatura>
      </template>

      <template v-if="menu==13">
        <clase></clase>
      </template>

      <template v-if="menu==14">
        <parcial></parcial>
      </template>

      <template v-if="menu==15">
        <tipocalificacion></tipocalificacion>
      </template>

      <template v-if="menu==16">
        <archivo></archivo>
      </template>

    @elseif(Auth::user()->id_tipo_usuario == 3)
      <template v-if="menu==0">
        <img src="../img/logo-SGE.svg" style="position:relative; transform: translate(40%);">
      </template>

      <template v-if="menu==1">
        <clase-maestro></clase-maestro>
      </template>

      <template v-if="menu==2">
        <mensaje></mensaje>
      </template>

      <template v-if="menu==3">
        <edificio-alumno></edificio-alumno>
      </template>

      <template v-if="menu==4">
        <aula-alumno></aula-alumno>
      </template>

      <template v-if="menu==5">
        <grado-alumno></grado-alumno>
      </template>

      <template v-if="menu==6">
        <asignatura></asignatura>
      </template>

    @elseif(Auth::user()->id_tipo_usuario == 4)
      
      <template v-if="menu==0">
        <img src="../img/logo-SGE.svg" style="position:relative; transform: translate(40%);">
      </template>

      <template v-if="menu==1">
        <clase-alumno></clase-alumno>
      </template>

      <template v-if="menu==2">
        <mensaje></mensaje>
      </template>

      <template v-if="menu==3">
        <edificio-alumno></edificio-alumno>
      </template>

      <template v-if="menu==4">
        <aula-alumno></aula-alumno>
      </template>

      <template v-if="menu==5">
        <grado-alumno></grado-alumno>
      </template>

      <template v-if="menu==6">
        <asignatura></asignatura>
      </template>

    @else

    @endif
  @endif

@endsection