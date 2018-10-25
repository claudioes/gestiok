var gulp = require('gulp'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	cleanCSS = require('gulp-clean-css'),
	rename = require('gulp-rename'),
	uglify = require('gulp-uglify'),
	del = require('del');

var paths = {
	'assets'    : './resources/assets',
	'public'    : './public',
	'components': './node_modules',
};

gulp.task('styles', function () {
	return gulp.src([
		paths.components + '/datatables.net-bs/css/dataTables.bootstrap.css',
		paths.components + '/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
		paths.components + '/selectize/dist/css/selectize.bootstrap3.css',
		paths.assets + '/styles/app.scss'
	])
	.pipe(sass({
		includePaths: [
			paths.components + '/bootstrap-sass/assets/stylesheets',
		]
	}))
	.pipe(concat('app.css'))
	.pipe(gulp.dest('./public/css'))
	.pipe(cleanCSS())
	.pipe(rename({suffix: '.min'}))
	.pipe(gulp.dest('./public/css'));
});

		
gulp.task('vendors', function () {
    return gulp.src([
		paths.components + '/jquery/dist/jquery.js',
        paths.components + '/bootstrap-sass/assets/javascripts/bootstrap.js',
		paths.components + '/datatables.net/js/jquery.dataTables.js',
		paths.components + '/datatables.net-bs/js/dataTables.bootstrap.js',
		paths.components + '/bootstrap-datepicker/js/bootstrap-datepicker.js',
		paths.components + '/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js',
		paths.components + '/dropzone/dist/dropzone.js',
		paths.components + '/selectize/dist/js/standalone/selectize.js',
	])
	.pipe(concat('vendor.js'))
	.pipe(gulp.dest('./public/js'))
	.pipe(uglify())
	.pipe(rename({suffix: '.min'}))
	.pipe(gulp.dest('./public/js'));
});
	
gulp.task('scripts', function () {
    return gulp.src([
		paths.assets + '/scripts/*.js',
	])
	.pipe(concat('app.js'))
	.pipe(gulp.dest('./public/js'))
	.pipe(uglify())
	.pipe(rename({suffix: '.min'}))
	.pipe(gulp.dest('./public/js'));
});

gulp.task('clean', function () {
	return del([
		'./public/css/app.css',
		'./public/js/app.js',
		'./public/js/vendor.js'
	]);
});

gulp.task('watch-builder', function () {
	gulp.watch(paths.assets + '/styles/*.scss', ['styles']);
	gulp.watch(paths.assets + '/scripts/*.js', ['scripts']);
});

gulp.task('default', ['styles', 'vendors', 'scripts']);
gulp.task('watch', ['default', 'watch-builder']);
