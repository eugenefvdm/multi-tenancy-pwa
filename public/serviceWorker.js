const staticCacheName = "pwa-v" + new Date().getTime();
// const staticCacheName = "pwa-v1"
// If any of the files below do not exist, you may see `Failed to execute 'Cache' on 'addAll'`
// This is particularly acute with the /offline page which is a Laravel web route
const filesToCache = [
    '/offline',
    // '/css/app.css',
    // '/js/app.js',
    // '/img/pwa/icons/icon-72x72.png',
    // '/img/pwa/icons/icon-96x96.png',
    // '/img/pwa/icons/icon-128x128.png',
    // '/img/pwa/icons/icon-144x144.png',
    // '/img/pwa/icons/icon-152x152.png',
    // '/img/pwa/icons/icon-192x192.png',
    // '/img/pwa/icons/icon-384x384.png',
    // '/img/pwa/icons/icon-512x512.png',
]


/**
 * Push notification event listener.
 *
 * The push event listener was intentionally moved to the top, because it's
 * the most valuable aspect of a web app masquerading as a mobile app.
 *
 * It's also one of the more complex parts of the application to get working.
 *
 * @param {PushEvent} event
 * @returns {Promise<void>}
 */
self.addEventListener('push', function (event) {
    console.log('Push event received');

    if (!event.data) {
        console.warn('Push event but no data payload!');
        return;
    }

    try {
        const payload = event.data.json();
        const title = payload.title || 'Default Title';
        const options = {
            body: payload.body || 'Default Body',
            icon: payload.icon || '/default-icon.png',
            data: payload.data || {}, // Any additional data to pass
            actions: payload.actions || [], // For notification buttons
        };

        event.waitUntil(
            self.registration.showNotification(title, options)
        );
    } catch (error) {
        console.error('Error processing push event data:', error);
    }
});

// Cache on the 'install' event listener
// https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/install_event#examples
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear the cache on the 'activate' event listener
// https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/activate_event#examples
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Fetch - Serve from the cache - default method as per original package
 self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                console.error('Fetch failed; returning offline page instead.', error);
                return caches.match('offline');
            })
    )
});

// service-worker.js from https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/message_event#examples
addEventListener("message", (event) => {
    // event is an ExtendableMessageEvent object
    console.log(`The client sent me a message: ${event.data}`);

    event.source.postMessage("Hi client");
});

// Add an event handler for clicks in the notification to go to a URL
self.addEventListener('notificationclick', function(event) {
    event.notification.close(); // Close the notification

    // Get the URL from the notification data
    const url = event.notification.data.url;

    // Open the URL in a new tab or focus the tab if already open
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(windowClients => {
            // Check if a window tab is already open with the URL
            for (const client of windowClients) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise, open a new tab
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});

