var elixir = require('laravel-elixir');
require('laravel-elixir-sass-compass');
require('laravel-elixir-webpack-official');

elixir.config.sourcemaps = false;

elixir((mix) => {
    mix.copy('node_modules/jquery/dist/jquery.min.js','public/js/jquery.min.js');

    mix.sass(['app.scss'], 'public/css/')
		.webpack('app.js', 'public/js/app.min.js');
});
