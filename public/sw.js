/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("/web/pwa/workbox-v4.3.1/workbox-sw.js");
workbox.setConfig({modulePathPrefix: "/web/pwa/workbox-v4.3.1"});

importScripts(
  "/web/pwa/precache-manifest.9df3a01d3cdb21573b1e1e6523d1b930.js"
);

workbox.core.skipWaiting();

workbox.core.clientsClaim();

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

workbox.routing.registerNavigationRoute(workbox.precaching.getCacheKeyForURL("/index.html"), {
  whitelist: [/^\/web/,/sw\.js$/,/index\.html/,/^\/auth/,/^\/requests/,/^\/users/,/^\/equipments/,/^\/roles/,/^\/settings/],
  
});

workbox.routing.registerRoute(/\.json$|api\/settings/, new workbox.strategies.StaleWhileRevalidate({ "cacheName":"settings", plugins: [] }), 'GET');
workbox.routing.registerRoute(/storage/, new workbox.strategies.StaleWhileRevalidate({ "cacheName":"storage", plugins: [] }), 'GET');
workbox.routing.registerRoute(/api\/users\/images/, new workbox.strategies.NetworkOnly(), 'GET');
workbox.routing.registerRoute(/api/, new workbox.strategies.NetworkFirst({ "cacheName":"api", plugins: [] }), 'GET');
