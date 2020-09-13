// require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery-ui/ui/widgets/datepicker.js';

import Vue from 'vue';
Vue.config.productionTip = false;
Vue.config.devtools = true;

import VueResource from 'vue-resource'
Vue.use(VueResource);

var _ = require('lodash');
Vue.prototype._ = window._;

var BasicForm = Vue.component('basic-form', require('./components/BasicForm.vue').default);

var app = new Vue({
    el: '#app'
})


$(document).ready(function(){

    var dateFormat = "yy-mm-dd";

    var startDate = $('#start_date').datepicker({
        dateFormat: dateFormat,
        maxDate: 0,
        onSelect: function(dateText) {
            $(this)[0].dispatchEvent(new Event('input', { 'bubbles': true }))
            endDate.datepicker( "option", "minDate", getDate( this ) );
        }
    });

    var endDate = $('#end_date').datepicker({
        dateFormat: dateFormat,
        maxDate: 0,
        onSelect: function(dateText) {
            $(this)[0].dispatchEvent(new Event('input', { 'bubbles': true }))
            startDate.datepicker( "option", "maxDate", getDate( this ) );
        }
    });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }

});
