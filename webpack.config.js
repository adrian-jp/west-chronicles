

var Encore = require('@symfony/webpack-encore');
const publicPath = !Encore.isProduction()  ? '/west-chronicles/public/build' : '/build';
const webpack = require('webpack');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath(publicPath)
    // only needed for CDN's or sub-directory deploy
    .setManifestKeyPrefix('public/build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('router', './assets/js/router.js')
    .addEntry('form', './assets/form.js')

    .addEntry('welcome', './assets/js/welcome.js')
    .addEntry('event', './assets/js/events/events.js')
    .addEntry('clips', './assets/js/clips/clips.js')
    .addEntry('edit_artist', './assets/js/artist/edit-artist.js')
    .addEntry('one-artist', './assets/js/artist/one-artist.js')
    .addEntry('list_artist', './assets/js/artist/list-artist.js')
    .addEntry('one_album', './assets/js/album/one-album.js')
    .addEntry('track_player', './assets/js/tracks/track.js')
    .addEntry('media_player', './assets/js/tracks/media-player.js')
    .addEntry('album', './assets/js/album/album.js')
    //.addEntry('page1', './assets/page1.js')
    //.addEntry('page2', './assets/page2.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    //.splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    .enableSassLoader(function(options){

    },{
        resolveUrlLoader: false
    })

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    .addPlugin(
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            'window.jQuery': 'jquery'
        })
    )
    .autoProvideVariables({
        "Routing": "router"
    })
    .addLoader({
        test: /jsrouting-bundle\/Resources\/public\/js\/router.js$/,
        loader: "exports-loader?router=window.Routing"
    })
    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/admin.js')
;

let config = Encore.getWebpackConfig();
config.module.rules.unshift({
    parser: {
        amd: false,
    }
});
config.resolve.alias = {
    'router': __dirname + '/assets/js/router.js'
};
module.exports = config;
