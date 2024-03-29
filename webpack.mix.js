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
//  mix.js('resources/js/venta.js','public/js')
//  .version();

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/login.js','public/js')
    .js('resources/js/busqueda.js','public/js')
    .js('resources/js/proveedor.js','public/js/component')
    .js('resources/js/venta.js','public/js')
    .js('resources/js/articulo.js','public/js/component')
    .js('resources/js/funciones.js','public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
if(mix.inProduction())
{
	mix.version();
}


//mix.browserSync('http://softsystem.test:8080');