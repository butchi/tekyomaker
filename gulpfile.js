var gulp = require("gulp");
var jade = require('gulp-jade');
var babel = require("gulp-babel");
var sass = require("gulp-sass");
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var webserver = require('gulp-webserver');

gulp.task('serve', function() {
  gulp.src('.')
    .pipe(webserver({
      livereload: true,
      directoryListing: false,
      open: true,
    }));
  gulp.task('watch');
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
    .pipe(plumber({
      errorHandler: notify.onError("Error: <%= error.message %>") //<-
    }))
    .pipe(babel())
    .pipe(gulp.dest('./js'));
});

gulp.task('sass', function () {
  gulp.src('./sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css'));
});

gulp.task("watch", function () {
  gulp.watch('src/main.js', ['babel']);
});

gulp.task('default', ['babel']);