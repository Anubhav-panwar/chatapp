import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false, // Localhost doesn't use TLS
    wsHost: window.location.hostname,
    wsPort: 6001,    // Make sure this matches the port you configured in your Echo server
    wssPort: 6001,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],  // For local development
});
