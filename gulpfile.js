var gulp = require("gulp");
var babel = require("gulp-babel");
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

gulp.task('babel', function () {
  return gulp.src('src/main.js')
    .pipe(plumber({
      errorHandler: notify.onError("Error: <%= error.message %>") //<-
    }))
    .pipe(babel())
    .pipe(gulp.dest('js'));
});

gulp.task("watch", function () {
  gulp.watch('src/main.js', ['babel']);
});

gulp.task('default', ['babel']);