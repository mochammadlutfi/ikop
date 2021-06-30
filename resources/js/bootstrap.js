window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import jQuery from 'jquery';
// import AutoNumeric from '../../node_modules/autonumeric/src/main';
import SimpleBar from 'simplebar';
import Cookies from 'js-cookie';
import 'bootstrap';
import 'popper.js';
import 'jquery.appear';
import 'jquery-scroll-lock';
import 'jquery-countto';
import 'sharer.js';

// requiresel
// ..and assign to window the ones that need it
 window.$ = window.jQuery    = jQuery;
 window.SimpleBar            = SimpleBar;
 window.Cookies              = Cookies;
 window.lozad                = require('lozad');
 window.Swal                 = require('sweetalert2');
 window.AutoNumeric          = require('autonumeric');
 window.select2              = require('select2');
 window.Tools                = require('./modules/tools');
 window.Helpers              = require('./modules/helpers');

 
//  $('#field-simla').each(function() {
//     new AutoNumeric(this);
// });
// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
