var gulp = require('gulp'),
    less = require('gulp-less'),
    sass = require('gulp-sass'),
    usemin = require('gulp-usemin'),
    wrap = require('gulp-wrap'),
    connect = require('gulp-connect'),
    watch = require('gulp-watch'),
    templateCache = require('gulp-angular-templatecache'),
    minifyCss = require('gulp-minify-css'),
    minifyJs = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    minifyHtml = require('gulp-minify-html'),
    replace = require('gulp-replace-task'),
    argv = require('yargs').argv,
    fs = require('fs-utils'),
    gutil = require('gulp-util');
// var es = require('event-stream');

// var uglify = require('gulp-uglify');
// var minifyHtml = require('gulp-minify-html');
// var minifyCss = require('gulp-minify-css');
// var rev = require('gulp-rev');
// require('events').EventEmitter.prototype._maxListeners = 100;



var paths = {
	module: 'src/js/module/pardna/',
	js: 'src/js/module/**/*.js',
	data: 'src/data/**/*.*',
	fonts: 'src/fonts/**.*',
	svg: 'src/svg/**.*',
	images: 'src/img/**/*.*',
	templates: 'src/js/**/*.html',
	styles: ('src/less/**/*.less'),
	sassstyles: ['src/sass/*.scss'],
	index: 'src/index.html',
	config: 'src/js/config/',
	parameter: 'src/js/config/parameters.js',
	// index: 'src/*.html',
	bower_fonts: 'src/bower_components/**/*.{otf,ttf,woff,eof,svg,woff2}',
	bower_components: 'src/bower_components/**/*.*',
};

var outputDir = argv.env || 'dist';
paths.outputDir = outputDir + '/';

gulp.task('usemin', function() {
	return gulp.src(paths.index)
			.pipe(usemin({
				 less: ['concat', less()],
                 sass: ['concat', sass()],
				 // js: [minifyJs(), 'concat'],
                 js: [minifyJs()],
				 css: [minifyCss({keepSpecialComments: 0}), 'concat'],
				 html: [minifyHtml({empty: true})]
			}))
			.pipe(gulp.dest(paths.outputDir));
});

gulp.task('replace', function() {
	// Get the environment from the command line
	var env = argv.env || 'dev';

	// Read the settings from the right file
	var filename = env + '.json';
	var evn = JSON.parse(fs.readFileSync(paths.config + filename, 'utf8'));

// Replace each placeholder with the correct value for the variable.
	gulp.src(paths.parameter)
			.pipe(replace({
				patterns: [
					{
						match: 'env',
						replacement: evn
					}
				]
			})).pipe(gulp.dest(paths.module));
});

/**
 * Copy assets
 */

gulp.task('bootstrap', ['replace']);
gulp.task('copy-assets', ['copy-images', 'copy-templates', 'concatenate-templates', 'copy-fonts', 'copy-svg', 'copy-data', 'copy-bower_fonts']);
gulp.task('build-custom', ['custom-js', 'custom-sass', 'custom-less']);

gulp.task('copy-images', function() {
	return gulp.src(paths.images)
			.pipe(gulp.dest(paths.outputDir + 'img'));
});

gulp.task('copy-templates', function() {
	return gulp.src(paths.templates)
			.pipe(gulp.dest(paths.outputDir + 'js/'));
	;
});

gulp.task('concatenate-templates', function() {
	return gulp.src(paths.templates)
			.pipe(templateCache('pardna-tpl.js', {module: 'Pardna'}))
			.pipe(gulp.dest(paths.outputDir + 'tpls'));
	;
});

gulp.task('copy-fonts', function() {
	return gulp.src(paths.fonts)
			.pipe(gulp.dest(paths.outputDir + 'fonts'));
});

gulp.task('copy-svg', function() {
	return gulp.src(paths.svg)
			.pipe(gulp.dest(paths.outputDir + 'svg'));
});

gulp.task('copy-bower_fonts', function() {
	return gulp.src(paths.bower_fonts)
			.pipe(rename({
				dirname: '/fonts'
			}))
			.pipe(gulp.dest(paths.outputDir + 'lib'));
});



gulp.task('custom-js', function() {
	return gulp.src(paths.js)
			// .pipe(minifyJs())
			.pipe(concat('pardna.min.js'))
			.pipe(gulp.dest(paths.outputDir + 'js'));
});


/**
 * Compile less
 */
gulp.task('custom-less', function() {
	gulp.src(paths.styles)
			.pipe(less())
			.pipe(concat('pardna.css'))
            .pipe(gulp.dest(paths.outputDir + 'css/'))
 			.pipe(minifyCss())
			.pipe(rename('pardna.min.css'))
			.pipe(gulp.dest(paths.outputDir + 'css/'));
});

gulp.task('copy-data', function() {
	return;
	return gulp.src(paths.data)
			.pipe(gulp.dest(paths.outputDir + 'data'));
});

/**
 * Compile less
 */
gulp.task('compile-less', function() {
	return gulp.src(paths.styles)
			.pipe(less())
			.pipe(gulp.dest(paths.outputDir + 'css'));
});



/**********/



/***************** SCSS ******************/
/**
 * Compile scss
 */
gulp.task('custom-sass', function() {
	gulp.src(paths.sassstyles)
			.pipe(sass())
			.pipe(concat('pardna.css'))
            .pipe(gulp.dest(paths.outputDir + 'css/'))
 			.pipe(minifyCss())
			.pipe(rename('pardna.min.css'))
			.pipe(gulp.dest(paths.outputDir + 'css/'));
});

gulp.task('copy-data', function() {
	return;
	return gulp.src(paths.data)
			.pipe(gulp.dest(paths.outputDir + 'data'));
});

/**
 * Compile scss
 */
gulp.task('compile-sass', function() {
	return gulp.src(paths.sassstyles)
			.pipe(sass())
			.pipe(gulp.dest(paths.outputDir + 'css'));
});

/***************** /SCSS ******************/


 
 

/**
 * Watch src
 */
gulp.task('watch', function() {

	gulp.watch([paths.styles], ['custom-less']);
	gulp.watch([paths.styles], ['compile-less']);
	gulp.watch([paths.sassstyles], ['custom-sass']);
	gulp.watch([paths.sassstyles], ['compile-sass']);
	gulp.watch([paths.images], ['copy-images']);
	gulp.watch([paths.templates], ['copy-templates', 'concatenate-templates']);
	// gulp.watch([paths.templates], []);
	gulp.watch([paths.js], ['custom-js']);
	gulp.watch([paths.fonts], ['copy-fonts']);
	gulp.watch([paths.bower_fonts], ['copy-bower_fonts']);
	gulp.watch([paths.data], ['copy-data']);
	gulp.watch(['src/bootstrap-less/*.less'], ['build-bootstrap-less', 'usemin']);
	gulp.watch([paths.index], ['usemin']);

});



gulp.task('webserver', function() {
	connect.server({
		root: outputDir,
		port: 8085,
		livereload: true
	});
});

gulp.task('livereload', function() {
	gulp.src([paths.outputDir + '**/*.*'])
			.pipe(watch())
			.pipe(connect.reload());
});



// gulp.task('build-compile', ['compile-less']);

gulp.task('build', ['bootstrap', 'copy-assets', 'build-custom', 'usemin']);
gulp.task('default', ['build', 'webserver', 'livereload', 'watch']);
