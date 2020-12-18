/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import $ from 'jquery';
global.$ = global.jQuery = $;

import "../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js";

//import 'cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js';
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

// loads the jquery package from node_modules

import greet from './greet';

require('bootstrap');
import 'bootstrap-select';
import 'bootstrap-select/dist/js/i18n/defaults-es_ES.min';

$(document).ready(function() {
     $('select').selectpicker({language: 'ES', liveSearch: true});
});