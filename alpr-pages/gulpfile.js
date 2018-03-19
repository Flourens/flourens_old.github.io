var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var imagemin = require('gulp-imagemin');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var browserSync = require('browser-sync').create();

//gulp.task('css', function () {
//	gulp.src(['css/*.css'])
//		.pipe(plumber(plumberErrorHandler))
//		.pipe(autoprefixer({
//            browsers: ['last 2 versions'],
//            cascade: false
//        }))
//		.pipe(concat('main.css'))
//		.pipe(gulp.dest('./css'));
//});

/*--- JavaScript compile ---*/
//gulp.task('js', function () {
//	gulp.src('assets/js/*.js')
//		.pipe(plumber(plumberErrorHandler))
//		.pipe(jshint())
//		.pipe(jshint.reporter('fail'))
//		.pipe(concat('theme.js'))
//		.pipe(gulp.dest('../js'));
//});

/*--- Image min ---*/
//gulp.task('img', function () {
//	gulp.src('img/src/*.{png,jpg,gif}')
//		.pipe(plumber(plumberErrorHandler))
//		.pipe(imagemin({
//			optimizationLevel: 7,
//			progressive: true
//		}))
//		.pipe(gulp.dest('img'))
//});

/*--- Errors ---*/
var plumberErrorHandler = {
	errorHandler: notify.onError({
		title: 'Gulp',
		message: 'Error: <%= error.message %>'
	})
};

/*--- Browser-sync ---*/
gulp.task('browser-sync', function () {
	var files =[
		'static/css/*.css',
		'./*.html',
		'static/js/*.js'
	];
	browserSync.init(files, {
		server: {
			baseDir: ''
		},
		notify: false
	});
});

/*--- Gulp ---*/
gulp.task('default', ['browser-sync']);
