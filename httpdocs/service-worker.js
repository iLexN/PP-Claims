(function (global) {
    'use strict';

    // Load the sw-tookbox library.
    importScripts('components/sw-toolbox/sw-toolbox.js');

    // Turn on debug logging, visible in the Developer Tools' console.
    global.toolbox.options.debug = true;

    // The route for the images
    toolbox.router.get('/assets/(.*)', global.toolbox.cacheFirst, {
        cache: {
            name: 'assets20161013',
            maxAgeSeconds: 60 * 60 * 24 * 7 // cache for a week
        }
    });
    toolbox.router.get('/components/(.*)', global.toolbox.cacheFirst, {
        cache: {
            name: 'components-v0.2',
            maxAgeSeconds: 60 * 60 * 24 * 31 // cache for a month
        }
    });

    // By default, all requests that don't match our custom handler will use the toolbox.networkFirst
    // cache strategy, and their responses will be stored in the default cache.
    //global.toolbox.router.default = global.toolbox.networkFirst;

    // Boilerplate to ensure our service worker takes control of the page as soon as possible.
    global.addEventListener('install', event => event.waitUntil(global.skipWaiting()));
    global.addEventListener('activate', event => event.waitUntil(global.clients.claim()));
})(self);