var gulp = require('gulp'),
	sass = require('gulp-sass'),
    fileinclude = require('gulp-file-include'),
	autoprefixer = require('gulp-autoprefixer'),
	uglify = require('gulp-uglify'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
    cache = require('gulp-cache'),
  browserSync = require('browser-sync').create(),
	notify = require('gulp-notify');

// super easy include system to be able to get template files
// usage: @@include('filename.html') in source file ("src" folder)
// gulp task will generate final file at the root folder
//gulp.task('include', function() {
//		gulp.src(['src/index.html', 'src/sexual-strength.html', 'src/decline.html', 'src/strength-score.html', 'src/provarin.html', 'src/faq.html', 'src/healthcare.html', 'src/starter-kit-a.html', 'src/contact.html', 'src/starter-kit-b.html', 'src/important-safety.html', 'src/contact-thanks.html'])
//    .pipe(fileinclude({
//        prefix: '@@',
//        basepath: '@file'
//    }))
//    .pipe(gulp.dest('./'))
//    .pipe(notify({ message: 'Inlude task complete.' }))
//	.pipe(browserSync.stream());
//});

// Compiles SCSS to CSS
//gulp.task('styles', function() {
//    return gulp.src('assets/scss/styles.scss')
//        .pipe(sass({ style: 'expanded' }))
//        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
//        .pipe(gulp.dest('assets/css'))
//        .pipe(notify({ message: 'Styles task complete.' }))
//				.pipe(browserSync.stream());
//});

// Watch
//gulp.task('watch', function() {
////    gulp.watch('src/**/*.html', ['include']);
//    gulp.watch('./*.html').on('change', browserSync.reload);
//    gulp.watch('assets/scss/**/*.scss', ['styles']).on('change', browserSync.reload);
//    gulp.watch('assets/js/**/*.js').on('change', browserSync.reload);
//});

gulp.task('server', function() {
	
  browserSync.init({
    server: "./",
		ui: false,
		notify: false,
		logLevel: 'debug',
		// reloadOnRestart: false,
		open: true
  });
	
});

// Default task
gulp.task('default', ['server']);