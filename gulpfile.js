var gulp         = require('gulp');
var sass         = require('gulp-sass');
var rename       = require('gulp-rename');
var watch        = require('gulp-watch');
var notify       = require('gulp-notify');
var concat       = require('gulp-concat');
var livereload   = require('gulp-livereload');

gulp.task('php', function() {
  gulp.src('index.php')
  .pipe(livereload())
  .pipe(notify({ message : "html"}));
})



gulp.task('sass', function () {
 return gulp.src('./css/style.s*ss')
   .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
   .pipe(gulp.dest('./'))
   .pipe(livereload())
   .pipe(notify({ message : "sass"}));
});

/* WATCH */
gulp.task('watch', function () {
  livereload.listen({ start:true });
  gulp.watch(['./*.php'], ['php']);
  gulp.watch(['css/**/*.scss', 'src/bootstrap*/**/*.s*ss'], ['sass']);
});

/* Default task */
gulp.task('default', ['watch']);
