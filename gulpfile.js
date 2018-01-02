// Defining base pathes
var basePaths = {
    node: './node_modules/',
    js: './js-src/'
};

// Defining requirements
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var cssnano = require('gulp-cssnano');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var ignore = require('gulp-ignore');
var rimraf = require('gulp-rimraf');
var runSequence = require('run-sequence');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');


// Run: 
// gulp
// Defines gulp default task
gulp.task('default', ['watch'], function () { });



// Run: 
// gulp watch
// Starts watcher. Watcher runs appropriate tasks on file changes
gulp.task('watch', function () {
  gulp.watch('./sass/**/*.scss', ['build-css']);
  gulp.watch('./js-src/**/*.js', ['build-scripts']);
});



// Run: 
// gulp build-css
// Builds css from scss and apply other changes.
gulp.task('build-css', ['cleancss'], function(callback){
  // runSequence('cleancss', 'sass', 'autoprefixer', 'cssnano', callback);
  return gulp.src('./sass/*.scss')
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('./css')) // save .css
    .pipe(cssnano({discardComments: {removeAll: true}}))
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest('./css/')); // save .min.css
});



// Run: 
// gulp cleancss
// Delete generated css files.
gulp.task('cleancss', function() {
  return gulp.src(['./css/*.css'], { read: false }) // much faster
    .pipe(rimraf());
});



// Run: 
// gulp build-scripts. 
// Uglifies and concat all JS files into one
gulp.task('build-scripts', function() {
  
  // gulp.src([
  //     basePaths.node + 'lory.js/dist/lory.js',
  //     basePaths.js + 'products-carousel-init.js'
  //   ])
  //   .pipe(sourcemaps.init())
  //   .pipe(concat('frontend.js'))
  //   .pipe(gulp.dest('./js/')) // save .js
  //   .pipe(uglify())
  //   .pipe(rename({suffix: '.min'}))
  //   .pipe(sourcemaps.write('maps'))
  //   .pipe(gulp.dest('./js/')); // save .min.js



  gulp.src([
      basePaths.js + 'shared/polyfill-closest.js',
      basePaths.js + 'admin/admin-announcements.js',
    ])
    .pipe(sourcemaps.init())
    .pipe(concat('admin-announcements.js'))
    .pipe(gulp.dest('./js/')) // save .js
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest('./js/')); // save .min.js

});
