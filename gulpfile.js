// including plugins
var gulp = require('gulp')
    , minifyCss = require("gulp-minify-css")
    , uglify = require("gulp-uglify")
    , rename = require("gulp-rename");

// task
gulp.task('minify-css', function () {
    gulp.src(['css/*.css', '!css/*.min.css'])
        .pipe(minifyCss())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('css/'));
});

gulp.task('minify-js', function () {
    gulp.src(['js/*.js', '!js/*.min.js'])
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('js/'));
});