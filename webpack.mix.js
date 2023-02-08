const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.setPublicPath('public')
        .setResourceRoot('../') // Turns assets paths in css relative to css 
        .styles([
            'public/css/app.css',
            'public/css/bootstrap.min.css', 
            'public/css/jquery-ui.css',
            'public/css/all.min.css', 
            'public/css/owl.carousel.min.css' ,
            'public/plugins/jquery-confirm-v3.3.4/css/jquery-confirm.css', 
            'public/css/icon.css',  
            'public/css/style.css',  
            'public/css/responsive.css',   
            'public/css/developer.css'
       ],  'public/css/minfied.css')
    
        .js([
            'public/js/app.js',
            /*'public/plugins/jquery/jquery.min.js',
            'public/js/jquery-3.5.1.min.js',
            'public/js/jquery-ui.min.js',*/
            'public/plugins/jquery-confirm-v3.3.4/js/jquery-confirm.js',
            'public/js/chart.min.js',
            'public/js/popper.min.js',
            'public/js/owl.carousel.min.js',
            'public/js/common.js',
            'public/js/filter-menu.js',
            'public/js/bootstrap-datetimepicker.min.js'
    ], 'public/js/minfied.js')
    .extract([
        'jquery',
        'alpinejs',
        'bootstrap',
        'popper.js'
    ])
    .sourceMaps(); 

if (mix.inProduction()) {
    mix.version();
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
