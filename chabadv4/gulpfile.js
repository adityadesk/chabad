'use strict';

/*============================= Dependencies =============================*/

const gulp = require('gulp'),
	browserSync = require('browser-sync'),
	plumber = require('gulp-plumber'),
	concat = require('gulp-concat'),
	rename = require('gulp-rename');

/* --- Dependencies: styles --- */
const sass = require('gulp-sass'),
	cleanCSS = require('gulp-clean-css'),
	autoprefixer = require('gulp-autoprefixer');

/* --- Dependencies: scripts --- */

const uglify = require('gulp-uglify');

/* --- Dependencies: images --- */
const imagemin = require('gulp-imagemin');

/* --- Dependencies: i18n --- */
const wpPot = require('gulp-wp-pot'),
	sort = require('gulp-sort');

var reload = browserSync.reload;
sass.compiler = require('node-sass');

/*================================= Configuration =================================*/

const NODE_ENV = process.env.NODE_ENV || 'development';
const SASS_DIR = 'sass';
const SCRIPTS_DIR = 'js';
const SCRIPTS_V_DIR = 'js/vendors';
const ADMIN_SCRIPTS_DIR = SCRIPTS_DIR + '/admin';
const ASSETS_DIR = 'assets';

let config = {
	debug: NODE_ENV === 'development' ? true : false,
	style: {
		src: SASS_DIR + '/main.scss',
		dest: ASSETS_DIR + '/css/'
	},
	block_style: {
		src: SASS_DIR + '/sub//blocks/*',
		dest: ASSETS_DIR + '/css/blocks/'
	},
	scripts: {
		public: {
			src: [
				SCRIPTS_DIR + '/vendors/*.js',
				SCRIPTS_DIR + '/main.js'
			],
			dest: ASSETS_DIR + '/js/',
			outputName: 'app'
		},
		/*public: {
			src: [
				//SCRIPTS_V_DIR + '/bootstrap.bundle.min.js',
				SCRIPTS_V_DIR + '/slick.min.js',
				SCRIPTS_V_DIR + '/viewportchecker.js',
				SCRIPTS_V_DIR + '/jquery.magnific-popup.min.js',
				SCRIPTS_V_DIR + '/stickySidebar.js',
				SCRIPTS_DIR + '/main.js'
			],
			dest: ASSETS_DIR + '/js/',
			outputName: 'app'
		},*/
	},
	fonts: {
		src: [ SASS_DIR + '/fonts/*' ],
		dest: ASSETS_DIR + '/css/fonts/'
	},
	images: {
		src: [ 'images/**/*.**' ],
		dest: ASSETS_DIR + '/images/'
	},
	translation: {
		domain: 'addverb',
		package: 'addverb',
		team: 'addverb',
		dest: './languages/addverb.pot'
	}
};

/*================================= Tasks =================================*/

for (let type in config.scripts) {
	gulp.task(`${type}-scripts`, function() {
		return gulp
			.src(config.scripts[type].src, { sourcemaps: config.debug ? true : false })
			.pipe(concat(config.scripts[type].outputName + '.js'))
			.pipe(gulp.dest(config.scripts[type].dest))
			.pipe(rename(config.scripts[type].outputName + '.min.js'))
			.pipe(uglify())
			.pipe(gulp.dest(config.scripts[type].dest, { sourcemaps: config.debug ? '.' : false }))
			.pipe(reload({ stream: true }));
	});
}

gulp.task('sass', function() {
	return gulp
		.src(config.style.src, { sourcemaps: config.debug ? true : false })
		.pipe(plumber())
		.pipe(
			sass({
				includePaths: [ 'node_modules' ],
				outputStyle: 'expanded'
			})
		)
		.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
		.pipe(rename('app.css'))
		.pipe(gulp.dest(config.style.dest))
		.pipe(rename('app.min.css'))
		.pipe(cleanCSS())
		.pipe(gulp.dest(config.style.dest, { sourcemaps: config.debug ? '.' : false }));
});
gulp.task('block_sass', function() {
	return gulp
		.src(config.block_style.src, { sourcemaps: config.debug ? true : false })
		.pipe(plumber())
		.pipe(
			sass({
				includePaths: [ 'node_modules' ],
				outputStyle: 'expanded'
			})
		)
		.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
		.pipe(gulp.dest(config.block_style.dest));
});

gulp.task('fonts', function() {
	return gulp.src(config.fonts.src).pipe(gulp.dest(config.fonts.dest));
});

gulp.task('images', function() {
	return gulp.src(config.images.src).pipe(gulp.dest(config.images.dest));
});

gulp.task('imagemin', () =>
	gulp
		.src(config.images.src)
		.pipe(
			imagemin({
				progressive: true,
				optimizationLevel: 7,
				svgoPlugins: [ { removeViewBox: false } ]
				/*use: [pngquant(), jpegtran(), optipng(), gifsicle()]*/
			})
		)
		.pipe(gulp.dest(config.images.dest))
);

// Generates pot file for plugin localization.
let i18n = () => {
	return gulp
		.src([ './**/*.php', '!./build/**/*.php', '!./vendor/**/*.php' ])
		.pipe(sort())
		.pipe(
			wpPot({
				domain: config.translation.domain,
				package: config.translation.package,
				team: config.translation.team
			})
		)
		.pipe(gulp.dest(config.translation.dest));
};
i18n.description = 'Generates pot file for plugin localization';
gulp.task('translate', i18n);

// watch files for any changes.
let watchFiles = () => {
	gulp.watch(SCRIPTS_DIR + '/**/*.js', gulp.series([ 'public-scripts' ]));
	gulp.watch(config.images.src, gulp.series([ 'images' ]));
	gulp.watch(config.fonts.src, gulp.series([ 'fonts' ]));
	gulp.watch(SASS_DIR + '/sub/blocks/*.scss', gulp.series([ 'block_sass' ]));
	gulp.watch(SASS_DIR + '/**/*.scss', gulp.series([ 'sass' ]));
	
};

gulp.task('watch', gulp.series(gulp.parallel('public-scripts', 'sass', 'block_sass'), watchFiles));

// build tasks.
gulp.task(
	'build',
	gulp.parallel('public-scripts', 'fonts', 'sass', 'block_sass', gulp.series('images', 'imagemin'))
);

// default tasks.
gulp.task(
	'default',
	gulp.parallel('public-scripts', 'fonts', 'sass', 'block_sass', gulp.series('images'), watchFiles)
);
