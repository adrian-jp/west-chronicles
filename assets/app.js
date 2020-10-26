/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/footer.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

// loads the jquery package from node_modules
    let $ = require('jquery');

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
//     let greet = require('./greet');
//
//  $(document).ready(function() {
//          $('body').prepend('<h1>'+greet('west-chronicles')+'</h1>');
//      });

require('./plugins/jquery.collection.js');

// TODO Problème violation log
require('./plugins/jquery.datetimepicker.full.js');
$.datetimepicker.setLocale('fr');

$('.datetimepicker-input').datetimepicker({
    mask: false,
    format: 'd/m/Y H:i',
});

$('.datepicker-input').datetimepicker({
    mask: false,
    format: 'd/m/Y',
    timepicker: false
});

