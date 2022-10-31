/**
 * Required Modules.
 *
 * @type {Gulp}
 */
const gulp = require('gulp')
    , concat = require('gulp-concat')
    , terser = require('gulp-terser')
    , cleanCSS = require('gulp-clean-css')
    , fontMin = require('gulp-fontmin');

/**
 * Source and Destiny directories.
 *
 * @type {string}
 */
const jsDest = 'public/dist/js'
    , cssDest = 'public/dist/css';


/**
 * Compressing *.css files
 */
gulp.task('css', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css',
            'vendor/almasaeed2010/adminlte/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css',
        ])
        .pipe(concat('template.min.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(cssDest));
});
gulp.task('css_select2', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/select2/css/select2.min.css',
            'vendor/almasaeed2010/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
        ])
        .pipe(concat('css_select2.min.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(cssDest));
});
gulp.task('layout_adminlte_datatable_css', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
            'vendor/almasaeed2010/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
        ])
        .pipe(concat('layout_adminlte_datatable_css.css'))
        .pipe(gulp.dest(cssDest));
});
gulp.task('layout_login_css', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css',
        ])
        .pipe(concat('layout_login_css.css'))
        .pipe(gulp.dest(cssDest));
});
gulp.task('adminlte_toastr_css', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
            'vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.css',
        ])
        .pipe(concat('adminlte_toastr_css.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(cssDest));
});

/**
 * Compressing *.js files
 */
gulp.task('js', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
            'vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js',
            'vendor/almasaeed2010/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.js',
        ])
        .pipe(concat('template.min.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});
gulp.task('js_select2', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/select2/js/select2.full.min.js',

        ])
        .pipe(concat('js_select2.min.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});
gulp.task('layout_adminlte_datatable_js', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/datatables/jquery.dataTables.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jszip/jszip.min.js',
            'vendor/almasaeed2010/adminlte/plugins/pdfmake/pdfmake.min.js',
            'vendor/almasaeed2010/adminlte/plugins/pdfmake/vfs_fonts.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/js/buttons.print.min.js',
            'vendor/almasaeed2010/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js',
        ])
        .pipe(concat('layout_adminlte_datatable_js.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});
gulp.task('layout_login_js', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
            'vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js',
        ])
        .pipe(concat('layout_login_js.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});
gulp.task('adminlte_toastr_js', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
            'vendor/almasaeed2010/adminlte/plugins/toastr/toastr.min.js',
        ])
        .pipe(concat('adminlte_toastr_js.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});

/**
 * Compressing fonts
 */
gulp.task('fonts', function () {
    return gulp.src(
        [
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-solid-900.woff2',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-regular-400.woff2',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-solid-900.woff',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-regular-400.woff',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-solid-900.ttf',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/webfonts/fa-regular-400.ttf',
        ])
        .pipe(fontMin({text: 'text'}))
        .pipe(gulp.dest('public/dist/webfonts'));
});

/**
 * Runs all tasks
 */
gulp.task('run', gulp.parallel(
    'css',
    'js',
    'fonts',
    'layout_adminlte_datatable_js',
    'layout_adminlte_datatable_css',
    'layout_login_css',
    'layout_login_js',
    'adminlte_toastr_js',
    'adminlte_toastr_css',
    'css_select2',
    'js_select2',
));