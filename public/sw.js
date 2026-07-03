"use strict";

const CACHE_NAME = "offline-cache-v2";
const IMAGE_CACHE_NAME = "image-cache-v1";
const OFFLINE_URL = "/offline";
const API_CACHE_NAME = "api-cache-v1";
const apiEndpoints = ['/api/', '/webauthn/'];

// Files to cache for offline access
const filesToCache = [
    OFFLINE_URL,
    "/",
    "/manifest.json",
    "/css/app.css",
    "/js/app.js",
    "/assets/Logo.svg"
];


// Install event - cache essential files
self.addEventListener("install", (event) => {
    console.log("[Service Worker] Installing...");
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log("[Service Worker] Caching files");
                return cache.addAll(filesToCache);
            })
            .then(() => {
                return self.skipWaiting();
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log("[Service Worker] Activating...");
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME && cacheName !== API_CACHE_NAME && cacheName !== IMAGE_CACHE_NAME) {
                        console.log("[Service Worker] Deleting old cache:", cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log("[Service Worker] Claiming clients");
            return self.clients.claim();
        })
    );
});

// Fetch event - handle offline and caching strategies
self.addEventListener("fetch", (event) => {
    const url = new URL(event.request.url);
    
    // Handle navigation requests (HTML pages)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    // Cache the fetched page
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // Return offline page when offline
                    return caches.match(OFFLINE_URL);
                })
        );
    } 
    // Handle image requests with cache-first strategy
    else if (event.request.destination === 'image' || /\.(png|jpe?g|webp|avif|svg|gif)$/.test(url.pathname)) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then((response) => {
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(IMAGE_CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return response;
                }).catch(() => {
                    return caches.match(OFFLINE_URL);
                });
            })
        );
    }
    // Handle API requests (optional)
    else if (apiEndpoints.some(endpoint => url.pathname.includes(endpoint))) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    const responseClone = response.clone();
                    caches.open(API_CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    }
    // Handle static assets (CSS, JS, images)
    else {
        event.respondWith(
            caches.match(event.request)
                .then((response) => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request).then((response) => {
                        // Cache new assets
                        if (response.status === 200) {
                            const responseClone = response.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, responseClone);
                            });
                        }
                        return response;
                    });
                })
        );
    }
});

// Handle push notifications (optional)
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'Portofolio Mahasiswa';
    const options = {
        body: data.body || 'Ada update terbaru di portofolio Anda',
        icon: '/assets/Logo-rounded.svg',
        badge: '/assets/Logo-rounded.svg',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/'
        }
    };
    
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});

// Handle background sync (optional)
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-data') {
        event.waitUntil(syncData());
    }
});

async function syncData() {
    // Implement your sync logic here
    console.log('[Service Worker] Syncing data...');
}