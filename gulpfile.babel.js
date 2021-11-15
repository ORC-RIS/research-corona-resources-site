'use strict';
const { series, parallel, dest, src } = require( 'gulp' );
const watch         = require( 'gulp-watch' );
const cleanCSS      = require( 'gulp-clean-css' );
const rename        = require( 'gulp-rename' );
const batch         = require( 'gulp-batch' );
const newer         = require( 'gulp-newer' );
const gutil         = require( 'gulp-util' );
const less          = require( 'gulp-less' );
const sourcemaps    = require( 'gulp-sourcemaps' );
const duration      = require( 'gulp-duration' );
const size          = require( 'gulp-size' );
const del           = require( 'del' );
const chalk         = require( 'chalk' );

const gulpConfig  = require('./gulpconfig'); // Configuration for server name

const networkPath = gulpConfig.remoteServer; // This requires you to map this server to a network drive
const themePath = "./";
const moveMePath  = [
    themePath + '**',
    '!./{node_modules,node_modules/**}',
    '!/.git/**',
    '!**___jb_tmp___',
    '!./**/*___jb_old___'
];
const stylePath   = themePath + 'style.css';
const cssOptions = {
    compatibility: 'ie8',
    level: 2
};
let timeNow = () => new Date().toLocaleTimeString([], {
    hour12: false,
    hour: '2-digit',
    minute:'2-digit',
    second: '2-digit'
});

let log = ( ...msg ) => { console.log( '[' + chalk.gray( timeNow() ) + ']', ...msg ) };

let deleteCSS = async () => await del(['./style.css','./style.min.css']);
let deleteGeneratedFiles = async () => await parallel( deleteCSS );
let compileLESS = () => {
    let lessTimer = duration('Less');
    let cleanCSSTimer = duration('cleanCSS');

    return src( [ 'style.less' ] )
        .pipe( sourcemaps.init() )
        .pipe( less() )
        .pipe( lessTimer )
        .pipe( sourcemaps.write('./maps') )
        .pipe( dest( themePath ) )
        .pipe( cleanCSS( cssOptions ) )
        .pipe( cleanCSSTimer )
        .pipe( rename({
            extname: '.min.css'
        }))
        .pipe(dest( themePath ));
};
let moveFiles = () => {
    log(
        chalk.yellow('Starting Move')
    );

    function onEnd( obj ){
        log(
            chalk.yellow('Files moved')
        );
    }

    let timerMoved = duration('Moved files' );

    return src( moveMePath )
        .pipe( newer( networkPath ) )
        .pipe( size({ showFiles: true } ) )
        .pipe( dest( networkPath ) )
        .on( 'end', onEnd);
};
let watchForLESSChanges = () => {
    log( 'Watching for LESS changes' );
    return watch( ['./style.less', './less/**/*.less'], compileLESS );
};
let watchForChanges = () => {
    log( 'Watching for changes' );
    function onError( err ) {
        log( 'FROM TASK: watch-changes-for-move', err.toString() );
        this.emit('end');
    }
    function onEnd(){
        log('Moved files');
    }
    function onEndWatch(){
        log('Watch task ended');
    }
    return watch( moveMePath, moveFiles );
};

exports.deleteGeneratedFiles = deleteGeneratedFiles;
exports.deleteCSS = deleteCSS;
exports.compileLESS = compileLESS;
exports.watchForLESSChanges = watchForLESSChanges;
exports.default = series( deleteGeneratedFiles, compileLESS, moveFiles, parallel( watchForChanges, watchForLESSChanges ) );