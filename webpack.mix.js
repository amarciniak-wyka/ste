const mix = require('laravel-mix');




mix.js('resources/js/app.js','public/js')
    .js('resources/js/delete.js','public/js')
    .js('resources/js/welcome.js','public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .postCss('resources/css/cart.css', 'public/css')

mix.browserSync('shop.test');
