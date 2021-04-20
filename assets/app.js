/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss'

import $ from 'jquery'
global.$ = global.jQuery = $

import '../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'

//import 'cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js';
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

//console.log('Hello Webpack Encore! Edit me in assets/app.js')

// loads the jquery package from node_modules

import greet from './greet'

import 'bootstrap'
import 'bootstrap/dist/js/bootstrap.min'
//require('bootstrap')
import 'bootstrap-select'
import 'bootstrap-datepicker'
import 'bootstrap-datepicker/'

import 'bootstrap-select/dist/js/i18n/defaults-es_ES.min'

var total_linia_pre = 0
var total_linia_fac = 0

$(document).ready(function () {
  $('select').selectpicker({ language: 'ES', liveSearch: true })
  $(".form_datetime").datetimepicker();

 // $(".datetimepicker").datepicker($.datepicker.regional['es']);

 /* $('.datetimepicker').datepicker({'language' : 'ES'});
  $('.datetimepicker').datepicker({
    uiLibrary: 'bootstrap4',
    locale: 'es-es',
  });*/

  //add_linia
  total_linia_pre = $('#pressupost_totalLinies').val()

  $('.add_linia').click(function (e) {
    e.preventDefault()
    e.stopPropagation()

    var id = $(this).attr('data-id-pressupost')

    $.ajax({
      // la URL para la petición
      url: '/pressupostos/afegirLinia',
      data: { id: id, total_linia: total_linia_pre },
      type: 'POST',
      dataType: 'html',
      success: function (result) {
        $('#linias').append(result)
        total_linia_pre = total_linia_pre + 1
      },
    })
  })

  total_linia_fac = $('#factura_totalLinies').val()

  $('.add_linia_fac').click(function (e) {
    e.preventDefault()
    e.stopPropagation()

    var id = $(this).attr('data-id-factura')

    $.ajax({
      // la URL para la petición
      url: '/factures/afegirLinia',
      data: { id: id, total_linia: total_linia_fac },
      type: 'POST',
      dataType: 'html',
      success: function (result) {
        $('#linias').append(result)
        total_linia_fac = total_linia_fac + 1
      },
    })
  })

  $('.pagarFactura').click(function (e) {
    console.log('pagar')
    e.preventDefault()
    e.stopPropagation()

    var id = $(this).attr('data-id-factura')
    var tipusPagament = $('#tipusPagament :selected').val()
    var url = '/factures/' + id + '/pagar'
    $.ajax({
      // la URL para la petición
      url: url,
      data: { tipusPagament: tipusPagament },
      type: 'POST',
      dataType: 'html',
      success: function (data, textStatus, xhr) {
        console.log(xhr.status)

        if (xhr.status == 200) {
          $('#modalPagar').modal('hide')
          window.location.href = '/factures/' + id + '/pdf'
        }
      },
    })
  })

  if ( window.location.pathname.indexOf('?pagar') >= 0 )
  {
    console.log("patata");
   alert("invt");
  }

})
