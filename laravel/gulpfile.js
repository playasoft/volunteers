var gulp = require('gulp');
var sass = require('gulp-sass');

var scss =
{
    // Compile SCSS into CSS
    compile: function()
    {
        gulp.src('./resources/css/main.scss')
            .pipe(sass().on('error', sass.logError))
            .pipe(gulp.dest('./public/css'));
    },

    // Watch SCSS for changes
    watch: function()
    {
        gulp.watch('./resources/css/**/*.scss', ['scss']);
    }
}

gulp.task('default', ['scss', 'scss:watch']);
gulp.task('scss', scss.compile);
gulp.task('scss:watch', scss.watch);
