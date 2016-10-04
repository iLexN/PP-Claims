(function(global) {
  'use strict';

  // Load the sw-tookbox library.
  importScripts('assets/components/sw-toolbox-3.3.0/sw-toolbox.js');

  // Turn on debug logging, visible in the Developer Tools' console.
  global.toolbox.options.debug = true;

  // The route for the images
  toolbox.router.get('/assets/(.*)', global.toolbox.cacheFirst, {
    cache: {
          name: 'v1.1',
          maxEntries: 10,
          maxAgeSeconds: 86400 // cache for a day
        }
  });

  // Boilerplate to ensure our service worker takes control of the page as soon as possible.
  global.addEventListener('install', event => event.waitUntil(global.skipWaiting()));
  global.addEventListener('activate', event => event.waitUntil(global.clients.claim()));
})(self);