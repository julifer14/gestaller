/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

// loads the jquery package from node_modules
import $ from 'jquery';
import greet from './greet';

require('bootstrap');

/*$(document).ready(function() {
     $('body').prepend('<h1>'+greet('jill')+'</h1>');
});*/