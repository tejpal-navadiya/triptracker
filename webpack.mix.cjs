const mix = require('laravel-mix');

// Compile your assets here
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

// You can add more mix methods as needed
