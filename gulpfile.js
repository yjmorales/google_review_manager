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
gulp.task('landing_css', function () {
    return gulp.src(
        [
            'public/vendor/landing_page/BizLand/assets/vendor/aos/aos.css',
            'public/vendor/landing_page/BizLand/assets/vendor/bootstrap/css/bootstrap.min.css',
            'public/vendor/landing_page/BizLand/assets/vendor/bootstrap-icons/bootstrap-icons.css',
            'public/vendor/landing_page/BizLand/assets/vendor/boxicons/css/boxicons.min.css',
            'public/vendor/landing_page/BizLand/assets/vendor/glightbox/css/glightbox.min.css',
            'public/vendor/landing_page/BizLand/assets/vendor/swiper/swiper-bundle.min.css',
            'public/vendor/landing_page/BizLand/assets/css/style.css',
            'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'node_modules/smartwizard/dist/css/smart_wizard_all.min.css',
            'node_modules/smartwizard/dist/css/smart_wizard.min.css',
        ])
        .pipe(concat('landing_css.css'))
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
            'vendor/almasaeed2010/adminlte/plugins/jquery-validation/jquery.validate.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jquery-validation/additional-methods.min.js',

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
            'vendor/almasaeed2010/adminlte/plugins/jquery-validation/jquery.validate.min.js',
            'vendor/almasaeed2010/adminlte/plugins/jquery-validation/additional-methods.min.js',
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
gulp.task('cleave_js', function () {
    return gulp.src(
        [
            'node_modules/cleave.js/dist/cleave.min.js',
        ])
        .pipe(concat('cleave_js.js'))
        .pipe(terser())
        .pipe(gulp.dest(jsDest));
});
gulp.task('landing_js', function () {
    return gulp.src(
        [
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/jquery-ui/dist/jquery-ui.min.js',
            'public/vendor/landing_page/BizLand/assets/vendor/purecounter/purecounter_vanilla.js',
            'public/vendor/landing_page/BizLand/assets/vendor/aos/aos.js',
            'public/vendor/landing_page/BizLand/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
            'public/vendor/landing_page/BizLand/assets/vendor/glightbox/js/glightbox.min.js',
            'public/vendor/landing_page/BizLand/assets/vendor/isotope-layout/isotope.pkgd.min.js',
            'public/vendor/landing_page/BizLand/assets/vendor/swiper/swiper-bundle.min.js',
            'public/vendor/landing_page/BizLand/assets/vendor/waypoints/noframework.waypoints.js',
            'public/vendor/landing_page/BizLand/assets/vendor/php-email-form/validate.js',
            'node_modules/jquery-validation/dist/jquery.validate.min.js',
            'node_modules/smartwizard/dist/js/jquery.smartWizard.min.js',
            'public/vendor/landing_page/BizLand/assets/js/main.js',
        ])
        .pipe(concat('landing_js.js'))
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
gulp.task('landing_fonts', function () {
    return gulp.src(
        [
            'public/vendor/landing_page/BizLand/assets/vendor/boxicons/fonts/boxicons.woff2',
            'public/vendor/landing_page/BizLand/assets/vendor/boxicons/fonts/boxicons.woff',
        ])
        .pipe(fontMin({text: 'text'}))
        .pipe(gulp.dest('public/dist/fonts'));
});
gulp.task('landing_bootstrap_icons_fonts', function () {
    return gulp.src(
        [
            'public/vendor/landing_page/BizLand/assets/vendor/bootstrap-icons/fonts/bootstrap-icons.woff2',
            'public/vendor/landing_page/BizLand/assets/vendor/bootstrap-icons/fonts/bootstrap-icons.woff',
        ])
        .pipe(fontMin({text: 'text'}))
        .pipe(gulp.dest('public/dist/css/fonts'));
});
gulp.task('landing_img', function () {
    return gulp.src(['public/vendor/landing_page/BizLand/assets/img/**/*']).pipe(gulp.dest('public/dist/img'));
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
    'cleave_js',
    'landing_css',
    'landing_js',
    'landing_fonts',
    'landing_bootstrap_icons_fonts',
    'landing_img',
));