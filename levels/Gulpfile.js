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


// Watch
gulp.task('watch', function() {
    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch('./asset/css/*.css').on('change', browserSync.reload);
    gulp.watch('./asset/js/*.js').on('change', browserSync.reload);
});

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
gulp.task('default', ['server', 'watch']);