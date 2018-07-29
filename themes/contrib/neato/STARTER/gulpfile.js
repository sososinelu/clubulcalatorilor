const gulp = require('gulp'),
  gutil = require('gulp-util'),
  sass = require('gulp-sass'),
  watch = require('gulp-watch'),
  shell = require('gulp-shell'),
  notify = require('gulp-notify'),
  browserSync = require('browser-sync').create(),
  sourcemaps = require('gulp-sourcemaps'),
  uglify = require('gulp-uglify'),
  runSequence = require('run-sequence'),
  replace = require('gulp-replace');

// Only include config if exists.
let config;
try {
  config = require("./config");
} catch (error) {
  config = require("./example.config");
}

/**
 * This task generates CSS from all SCSS files and compresses them down.
 */
gulp.task('sass', function () {
  return gulp.src('./scss/**/*.scss')
  .pipe(sourcemaps.init())
  .pipe(sass({
    noCache: true,
    outputStyle: "compressed",
    lineNumbers: false,
    loadPath: './css/*',
    sourceMap: true
  })).on('error', function (error) {
    gutil.log(error);
    this.emit('end');
  })
  .pipe(sourcemaps.write('./maps'))
  .pipe(gulp.dest('./css'))
  .pipe(notify({
    title: "SASS Compiled",
    message: "All SASS files have been recompiled to CSS.",
    onLast: true
  }));
});

/**
 * This task minifies javascript in the js/js-src folder and places them in the js directory.
 */
gulp.task('compress', function () {
  return gulp.src('./js/js-src/*.js')
  .pipe(sourcemaps.init())
  .pipe(uglify())
  .pipe(sourcemaps.write('./maps'))
  .pipe(gulp.dest('./js'))
  .pipe(notify({
    title: "JS Minified",
    message: "All JS files in the theme have been minified.",
    onLast: true
  }));
});

/**
 * Defines a task that triggers a Drush cache clear (css-js).
 */
gulp.task('drush:cc', function () {
  if (!config.drush.enabled) {
    return;
  }

  return gulp.src('', {read: false})
  .pipe(shell([
    config.drush.alias.css_js
  ]))
  .pipe(notify({
    title: "Caches cleared",
    message: "Drupal CSS/JS caches cleared.",
    onLast: true
  }));
});

/**
 * Defines a task that triggers a Drush cache rebuild.
 */
gulp.task('drush:cr', function () {
  if (!config.drush.enabled) {
    return;
  }

  return gulp.src('', {read: false})
  .pipe(shell([
    config.drush.alias.cr
  ]))
  .pipe(notify({
    title: "Cache rebuilt",
    message: "Drupal cache rebuilt.",
    onLast: true
  }));
});

/**
 * Define a task to spawn Browser Sync.
 * Options are defaulted, but can be overridden within your config.js file.
 */
gulp.task('browser-sync', function () {
  browserSync.init({
    files: ['css/**/*.css', 'js/*.js'],
    port: config.browserSync.port,
    proxy: config.browserSync.hostname,
    open: config.browserSync.openAutomatically,
    reloadDelay: config.browserSync.reloadDelay,
    injectChanges: config.browserSync.injectChanges
  });
});

/**
 * Define a task to be called to instruct browser sync to reload.
 */
gulp.task('reload', function () {
  browserSync.reload();
});

/**
 * Combined tasks that are run synchronously specifically for twig template changes.
 */
gulp.task('flush', function () {
  runSequence('drush:cr', 'reload');
});

/**
 * Defines the watcher task.
 */
gulp.task('watch', function () {
  // watch scss for changes and clear drupal theme cache on change
  gulp.watch(['scss/**/*.scss'], ['sass', 'drush:cc']);

  // watch js for changes and clear drupal theme cache on change
  gulp.watch(['js/js-src/**/*.js'], ['compress', 'drush:cc']);

  // If user has specified an override, rebuild Drupal cache
  if (!config.twig.useCache) {
    gulp.watch(['templates/**/*.html.twig'], ['flush']);
  }
});

// Copy Bitters to the subtheme folder.
gulp.task('bitters-copy', function () {
  gulp.src(['node_modules/bourbon-bitters/app/assets/stylesheets/**/*'])
  .pipe(gulp.dest('scss/base', {overwrite: false}));
});

// Fix Bitters Neat import.
gulp.task('bitters-fix', function () {
  gulp.src(['scss/base/_grid-settings.scss'])
  .pipe(replace('@import "neat-helpers";', '@import "../../node_modules/bourbon-neat/app/assets/stylesheets/neat-helpers";'))
  .pipe(gulp.dest('scss/base/'));
});

gulp.task('default', ['watch', 'browser-sync']);
