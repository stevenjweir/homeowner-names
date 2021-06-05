var mix = require('laravel-mix');

/**
 * Options
 */
mix.options({
    postCss: [
        require('autoprefixer')({
            overrideBrowserslist: ['last 20 versions'],
            cascade: false
        })
    ]
});

/**
 * Ensure that scss source maps files are loaded within dev tools.
 */
mix.webpackConfig({ devtool: "inline-source-map"});

/**
 * Stylesheet
 */
mix.sass('./assets/scss/app.scss', './dist/app.css').options({
    processCssUrls: false
});

/**
 * Javascript
 */
mix.js('./assets/scripts/app.js', './dist/app.js').vue();