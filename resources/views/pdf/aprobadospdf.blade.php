<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reporte de Aprobados</title>
  <style>
      body {
          margin: 0;
          font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
          font-size: 0.875rem;
          font-weight: normal;
          line-height: 1.5;
          color: #151b1e;           
      }
      .table {
          display: table;
          width: 100%;
          max-width: 100%;
          margin-bottom: 1rem;
          background-color: transparent;
          border-collapse: collapse;
      }
      .table-bordered {
          border: 1px solid #c2cfd6;
      }
      thead {
          display: table-header-group;
          vertical-align: middle;
          border-color: inherit;
      }
      tr {
          display: table-row;
          vertical-align: inherit;
          border-color: inherit;
      }
      .table th, .table td {
          padding: 0.75rem;
          vertical-align: top;
          border-top: 1px solid #c2cfd6;
      }
      .table thead th {
          vertical-align: bottom;
          border-bottom: 2px solid #c2cfd6;
      }
      .table-bordered thead th, .table-bordered thead td {
          border-bottom-width: 2px;
      }
      .table-bordered th, .table-bordered td {
          border: 1px solid #c2cfd6;
      }
      th, td {
          display: table-cell;
          vertical-align: inherit;
      }
      th {
          font-weight: bold;
          text-align: -internal-center;
          text-align: left;
      }
      tbody {
          display: table-row-group;
          vertical-align: middle;
          border-color: inherit;
      }
      tr {
          display: table-row;
          vertical-align: inherit;
          border-color: inherit;
      }
      .table-striped tbody tr:nth-of-type(odd) {
          background-color: rgba(0, 0, 0, 0.05);
      }
      .izquierda{
          float:left;
      }
      .derecha{
          float:right;
      }
  </style>
</head>
<body>
  <div>
      <h3>Reporte de aprobados <span class="derecha">{{now()}}</span></h3>
      <div class="table_responsive">
        <table class="table table-bordered table-striped table-sm">
            <tr>
                <th colspan="2">Código Asignatura</th>
                <th colspan="2">Asignatura</th>
            </tr>
            <tr>
                <td colspan="2">{{$clase[0]->codigo_asignatura}}</td>
                <td colspan="2">{{$clase[0]->nombre_asignatura}}</td>
            </tr>
            <tr>
                <th>Código Aula</th>
                <th colspan="2">Aula</th>
                <th>Sección</th>
            </tr>
            <tr>
                <td>{{$clase[0]->codigo_aula}}</td>
                <td colspan="2">{{$clase[0]->nombre_aula}}</td>
                <td>{{$clase[0]->seccion}}</td>
            </tr>
            <tr>
                <th colspan="2">Maestro Asignado</th>
                <th>Horario</th>
                <th>Días</th>
            </tr>
            <tr>
                <td colspan="2">{{$maestro[0]->primer_nombre.' '.$maestro[0]->segundo_nombre.' '.$maestro[0]->primer_apellido.' '.$maestro[0]->segundo_apellido}}</td>
                <td>{{$clase[0]->hora_inicio.' - '.$clase[0]->hora_fin}}</td>
                <td>{{$clase[0]->dias}}</td>
            </tr>
            <tr>
                <th colspan="3">Observación</th>
                <th>Estado</th>
            </tr>
            <tr>
                <td colspan="3">{{$clase[0]->observacion}}</td>
                <td>{{($clase[0]->estado == 'A')?'Activa':'Inactiva'}}</td>
            </tr>
        </table>
      </div>
  </div>

  <div>
    <table class="table table-bordered table-striped table-sm">
      <tr><th><p><strong>Alumnos: </strong>{{$count}}</p></th></tr>
      <tr><th><p><strong>Total de aprobados: </strong>{{$aprobados}}</p></th></tr>
      <tr><th><p><strong>Total de reprobados: </strong>{{$reprobados}}</p></th></tr>
      @if ($count <= 0)
        <tr><th><p><strong>Porcentaje de aprobación: </strong>{{'N/A'}}</p></th></tr>\
      @elseif($aprobados == 0)
        <tr><th><p><strong>Porcentaje de aprobación: </strong>{{'0%'}}</p></th></tr>
      @else
        <tr><th><p><strong>Porcentaje de aprobación: </strong>{{(int)(($aprobados/$count)*100).'%'}}</p></th></tr>
      @endif
    </table>
  </div> 

  <h3>Listado de Alumnos</h3>
  <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm">
          <thead>
              <tr>
                  <th>Cuenta</th>
                  <th>Nombre</th>
                  <th>Promedio</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($alumnos as $alumno)
              <tr>
                  <td>{{$alumno->codigo_alumno}}</td>
                  <td>{{$alumno->primer_nombre.' '.$alumno->segundo_nombre.' '.$alumno->primer_apellido.' '.$alumno->segundo_apellido}}</td>
                  @if ($alumno->indice_global < 70)
                    <td style="color:red">{{$alumno->indice_global}}</td>
                  @else
                    <td>{{$alumno->indice_global}}</td>
                  @endif
              </tr>
              @endforeach                               
          </tbody>
      </table>
  </div>
</body>
</html>