(function (global) {
    'use strict';

    // Load the sw-tookbox library.
    importScripts('components/sw-toolbox/sw-toolbox.js');

    // Turn on debug logging, visible in the Developer Tools' console.
    global.toolbox.options.debug = true;

    // The route for the images
    global.toolbox.router.get('/assets/(.*)', global.toolbox.cacheFirst, {
        cache: {
            name: 'assets2017-1b',
            //maxAgeSeconds: 60 * 60 * 24 * 7 // cache for a week
            maxAgeSeconds: 60 * 60  // cache for 1 hr
        }
    });
    global.toolbox.router.get('/components/(.*)', global.toolbox.cacheFirst, {
        cache: {
            name: 'component2017-1',
            maxAgeSeconds: 60 * 60 * 24 * 31 // cache for a month
        }
    });
    
    global.toolbox.router.get('/(.*)', global.toolbox.cacheFirst, {origin: 'https://fonts.gstatic.com'});
    global.toolbox.router.get('/(.*)', global.toolbox.cacheFirst, {origin: 'https://fonts.googleapis.com'});
    
    global.toolbox.router.post('/(.*)', global.toolbox.networkOnly);

    // By default, all requests that don't match our custom handler will use the toolbox.networkFirst
    // cache strategy, and their responses will be stored in the default cache.
    global.toolbox.router.default = global.toolbox.networkFirst;

    // Boilerplate to ensure our service worker takes control of the page as soon as possible.
    global.addEventListener('install', event => event.waitUntil(global.skipWaiting()));
    global.addEventListener('activate', event => event.waitUntil(global.clients.claim()));
})(self);