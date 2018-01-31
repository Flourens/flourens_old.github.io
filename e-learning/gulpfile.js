var gulp				= require('gulp')
	, sass				= require('gulp-sass')
	, csso 				= require('gulp-csso')
	, cssbeautify = require('gulp-cssbeautify')
	, svgSprite 	= require('gulp-svg-sprite')
	, svgmin 			= require('gulp-svgmin')
	, rename 			= require('gulp-rename')
	// https://github.com/olegskl/gulp-stylelint
	, jshint 			= require('jshint')
	, browserSync = require('browser-sync').create()
	;

// server + watching
gulp.task('server', function() {

  browserSync.init({
    server: "./",
		ui: false,
		notify: false,
		logLevel: 'debug',
		// reloadOnRestart: false,
		open: false
  });

});

// style
gulp.task('style', function () {
	gulp.src('./style/general.scss')
		.pipe(sass({
			outputStyle: 'compressed'
			// use: rupture()
		}).on('error', sass.logError))
		.pipe(cssbeautify())
		// .pipe(csso())
		.pipe(gulp.dest('./style/'))
		.pipe(browserSync.stream());
});

// markup
gulp.task('markup', function () {
	gulp.src('./*.html')
		.pipe(browserSync.stream());
});

// vector icons sprite
gulp.task('icons-vector', function () {
  return gulp.src('./images/icons-vector/*.svg')
    // minify svg
    .pipe(svgmin({
      js2svg: { pretty: true }
    }))
  	.pipe(rename(function (path) {
		    path.basename = path.basename
		    	.replace(/\s/g, '-')
		    	.replace(/-icon/, '');
		  }))
    .pipe(svgSprite({
			mode: {
    		symbol: {
    			dest: '',
    			example: true,
    			sprite: 'icons-vector.svg'
    		},
    		inline: false
    	}
    }))
    .pipe(gulp.dest('./images/'));
});

// watch
gulp.task('watch', function(){
  gulp.watch(['./style/**/*.scss', './style/**/*.css'], ['style']);
  gulp.watch('./*.html').on('change', browserSync.reload);
  gulp.watch('./images/icons-vector/*.svg', ['icons-vector']);
  gulp.watch('./behavior/*.js').on('change', browserSync.reload);
});

// default
gulp.task('default', ['server','watch', 'style']);
