// gulp sigue la filosofía de npm:
// módulos pequeños con funcionalidades específicas.
// Lo primero que hacemos, es incluir los módulos
// que queremos usar.
// El nombre de cada variable, desde luego,
// es arbitrario, y lo inventamos nosotros.
var gulp = require('gulp');
var gutil = require('gulp-util');
var bower = require('bower');
var concat = require('gulp-concat');
//var sass = require('gulp-sass');
//var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');
var sh = require('shelljs');

// Definimos rutas donde buscar o guardar archivos.
var paths = {
  //sass: ['./scss/**/*.scss'],
  //js: ['./www/js/*.js', './www/js/controllers/*.js', './www/js/services/*.js']
  // El primer strings busca todos los js
  // dentro de la carpeta 'js'.
  // El segundo, todos los js dentro de todas
  // las subcarpetas dentro de 'js'.
  js: ['./www/js/*.js', './www/js/**/*.js'],
  // Definimos la ruta para guardar todos los
  // archivos finales listos para distibuir.
  dist: './www/dist'
};

// Definimos las tareas que queremos tener.
// gulp.task es el comando para definir una tarea.
//gulp.task('default', ['sass']);

// Creamos nuestra tarea para unir todos los
// JavaScripts en uno solo.
gulp.task('js', function(done) {
  // Primero, vamos a leer todos los archivos
  // de JavaScript, y levantarlos en memoria.
  // gulp.src lee las rutas que le indiquemos.
  // Esas rutas, las podemos indicar o con un
  // string, o con un array de strings.
  gulp.src(paths.js)
      // Le pasamos la salida del src al
      // módulo concat. Muy al estilo Linux.
      // El concat une todos los archivos
      // abiertos previamente en un único archivo.
  // De parámetro, le pasamos el nombre.
      .pipe(concat('bundle.js'))
      // Finalmente, vamos a grabar ese archivo
      // // en disco.
      // gulp.dest permite grabar en disco.
      // Por parámetro, le pasamos la ruta.
      .pipe(gulp.dest(paths.dist))
      // Evento para consola.
      .on('end', done);
});

// Vamos ahora a crear una tarea para definir
// un "watcher" para las carpetas de js.
// Un watcher es un programita que queda
// corriendo, y cuando alguno de los archivos
// que "observa" cambia, llama a la tarea
// asociada.
gulp.task('js:watch', ['js'], function() {
  // Llamamos al gulp.watch
  // Le indicamos que observe las rutas de los
  // js, y cuando cambien, que ejecute la tarea
  // 'js'.
  gulp.watch(paths.js, ['js']);
});



/*gulp.task('sass', function(done) {
  gulp.src('./scss/ionic.app.scss')
    .pipe(sass())
    .on('error', sass.logError)
    .pipe(gulp.dest('./www/css/'))
    .pipe(minifyCss({
      keepSpecialComments: 0
    }))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(gulp.dest('./www/css/'))
    .on('end', done);
});

gulp.task('watch', ['sass'], function() {
  gulp.watch(paths.sass, ['sass']);
});*/

gulp.task('install', ['git-check'], function() {
  return bower.commands.install()
    .on('log', function(data) {
      gutil.log('bower', gutil.colors.cyan(data.id), data.message);
    });
});

gulp.task('git-check', function(done) {
  if (!sh.which('git')) {
    console.log(
      '  ' + gutil.colors.red('Git is not installed.'),
      '\n  Git, the version control system, is required to download Ionic.',
      '\n  Download git here:', gutil.colors.cyan('http://git-scm.com/downloads') + '.',
      '\n  Once git is installed, run \'' + gutil.colors.cyan('gulp install') + '\' again.'
    );
    process.exit(1);
  }
  done();
});
