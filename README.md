# Dependencias

Extensiones a instalar de Vue para autocompletar el código:
Vue 2 Snippets - hollowtree
Vue TypeScript Snippets - Matt Levy


Extensiones a instalar de Vue para Chrome
Vue.js devtools de google chrome
*Configurar para que funcione con url de archivos


Una vez se tenga la instancia de Vue, es posible probar la reactividad de Vue
a través de la consola de chrome de la siguiente forma:
app.mensaje="Hola mundo!"


Instalación de XAMPP Apache + MariaDB + PHP + Perl
Versión 7.2.20


Laravel:
- Eloquent-ORM (nada de sql) -> accede a los registros de la base de datos como si fueran 
objetos de PHP y no tener que ejecutar codigo sql
- Motor de Plantillas (Blade)
- Versión 5.6 de Laravel


Instalar Composer para luego instalar Laravel
Comando de composer:
composer global require "laravel/installer"
laravel new educacion


Instalar JSON viewer para ver el formato de un JSON en el navegador


Para la paginación se utiliza Eloquent

## Comandos a conocer

para correr el servidor artisan que viene incluido con Laravel usando la terminal y :
```bash
php artisan serve
```

Página principal del proyecto:
views/contenido/contenido.blade.php


Instalar la Versión LTS (estable) de Node.js
```
npm install - instala todas las dependencias de laravel
```
```
npm run dev - compila el js y css
```

migrar las tablas a la base de datos:
```
php artisan migrate
```

Los modelos se guardan en: App


Crear un Modelo:
```
php artisan make:model Flight
```

Los archivos DDL para llenar las tablas estan ubicados en: tests/SQL tests/02.SQLFiles/05.TestData


Los controladores se guardan en: App/Http/Controllers/controlador


Crear un controlador:
```
php artisan make:controller NombreController --resource
```

Se debe importar el model al controlador


Es importante poner las rutas en:
routes/web.php


Para la creación de middlewares:
```
php artisan make:middleware NombreMiddleware
```

## Tipos de usuarios
Administrador podra:
  * Crear Clases para los maestros
  * Matricular los alumnos en las clases


Al Logear un maestro:
  * Mostrar las clases que tiene asignada
  * Mostrar los alumnos en esa clase


Alumno al Logearse:
  * Ver las clases en la que está matriculado
  * Ver su nota de la clase
  * Ver información del edificio


Usuarios registrados en la base
  * griseborough0 - Maestro, idempleado:1
  * 20174352632 - Alumno, idAlumno:1
  

Listar las clases de un maestro
  * Los Alumnos matriculados en una clase
  * Las notas de ese alumno
  * Actualizar y agregar notas de ese alumno


Listar las clases de un alumno
  * Las notas de una clase en especifico


### Tablas de la base
* Persona
* Telefono
* Alumno
* Empleado
* Edificio
* Asignatura
* Asignatura_Grado
* Aula
* Clase
* Alumno_Clase


Calificaciones:


Se tiene un historial de notas del alumno 

* Indice Parcial: Nota Final del Parcial

* Indice Clase: Nota Final (Notas Finales del Parciales)/(Numero de Parciales)

* Indice Global: Suma de todas los "Indice de Clase"


### En duro:
tipo_calificacion:
* 1 Nota Final Parcial (asumimos 2 parciales)
* 2 Tarea
* 3 Examen


parcial: 
* Parcial I
* Parcial II