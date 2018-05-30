var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var browserSync = require('browser-sync').create();

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
		'*/*.png',
		'**/*.css',
		'*.html',
		'*/*.js'
	];
	browserSync.init(files, {
		server: {
			baseDir: './'
		},
		notify: false
	});
});

//gulp.task('autoprefixer', () =>
//    gulp.src('./app/css/main.css')
//        .pipe(autoprefixer({
//            browsers: ['last 2 versions'],
//            cascade: false
//        }))
//        .pipe(gulp.dest('./app'))
//);

/*--- Gulp ---*/
gulp.task('default', ['browser-sync']);
