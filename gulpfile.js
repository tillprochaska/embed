var gulp          = require('gulp');
var autoprefixer  = require('gulp-autoprefixer');
var sass          = require('gulp-sass');
var cssmin        = require('gulp-cssmin');
var rename        = require('gulp-rename');
var uglify        = require('gulp-uglify');

gulp.task('css', function() {
  return gulp.src('assets/scss/oembed.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(rename('oembed.css'))
    .pipe(cssmin())
    .pipe(gulp.dest('assets/css'))
    .pipe(gulp.dest('field/assets/css'));
});

gulp.task('js', function() {
  return gulp.src('assets/js/src/oembed.js')
    .pipe(uglify())
    .pipe(gulp.dest('assets/js'))
    .pipe(gulp.dest('field/assets/js'));
});

gulp.task('watch', function() {
  gulp.watch('assets/scss/**/*.scss', ['css']);
  gulp.watch('assets/js/src/*.js', ['js']);
});
