var gulp = require("gulp");
var jade = require('gulp-jade');
var babel = require("gulp-babel");
var sass = require("gulp-sass");
var browserSync = require("browser-sync").create();

gulp.task('browser-sync', function() {
  browserSync.init(null, {
    server: {
      baseDir: './',
      files: ['./src/**/*']
    }
  });
});

gulp.task('templates', function() {
  var YOUR_LOCALS = {};
 
  gulp.src('./src/jade/*.jade')
    .pipe(jade({
      locals: YOUR_LOCALS,
      pretty: true
    }))
    .pipe(gulp.dest('./'));
});

gulp.task('babel', function () {
  return gulp.src('./src/js/main.js')
    .pipe(babel())
    .pipe(gulp.dest('./js'));
});

gulp.task('sass', function () {
  gulp.src('./src/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css'));
});

gulp.task('build', ['templates', 'babel', 'sass']);

gulp.task('watch', function () {
  gulp.watch('src/jade/**/*.jade', ['templates']);
  gulp.watch('src/js/**/*.js', ['babel']);
  gulp.watch('src/sass/**/*.scss', ['sass']);
});

gulp.task('bs-reload', function() {
  browserSync.reload();
});

gulp.task('serve', ['build', 'watch'], function() {

  browserSync.init(null, {
    server: {
      baseDir: './',
      files: ['./src/**/*']
    }
  });

  gulp.watch(['./*.html', 'js/**/*', 'css/**/*']).on('change', browserSync.reload);
});

gulp.task('default', ['build']);