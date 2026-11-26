// Laundry Driver Portal - Service Worker
// Version: 1.0.0

const CACHE_NAME = 'laundry-driver-v1.0.0';
const STATIC_CACHE = 'laundry-driver-static-v1.0.0';
const DYNAMIC_CACHE = 'laundry-driver-dynamic-v1.0.0';
const OFFLINE_CACHE = 'laundry-driver-offline-v1.0.0';

// Files to cache immediately (app shell)
const STATIC_ASSETS = [
  '/driver/dashboard',
  '/driver/orders',
  '/driver/earnings',
  '/driver/profile',
  '/driver/schedule',
  '/css/driver-design-system.css',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css',
  'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
  'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
  'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css',
  'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js',
  'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
  'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js'
];

// Pages that should work offline
const OFFLINE_PAGES = [
  '/driver/dashboard',
  '/driver/orders',
  '/driver/earnings',
  '/driver/profile',
  '/driver/schedule',
  '/offline.html'
];

// API endpoints that can be cached for offline viewing
const CACHEABLE_API_PATTERNS = [
  /^\/api\/driver\/orders/,
  /^\/api\/driver\/earnings/,
  /^\/api\/driver\/profile/,
  /^\/api\/driver\/schedule/,
  /^\/api\/driver\/availability/,
  /^\/api\/driver\/notifications/
];

// API endpoints that should be queued when offline
const QUEUEABLE_API_PATTERNS = [
  /^\/api\/driver\/orders\/.*\/accept/,
  /^\/api\/driver\/orders\/.*\/collected/,
  /^\/api\/driver\/orders\/.*\/out_for_delivery/,
  /^\/api\/driver\/orders\/.*\/delivered/,
  /^\/api\/driver\/availability/,
  /^\/api\/driver\/profile/,
  /^\/api\/driver\/earnings\/withdraw/,
  /^\/api\/driver\/documents\/upload/
];

// Install event - cache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    Promise.all([
      caches.open(STATIC_CACHE).then(cache => {
        console.log('[SW] Caching static assets');
        return cache.addAll(STATIC_ASSETS.map(url => {
          return new Request(url, { credentials: 'same-origin' });
        })).catch(err => {
          console.warn('[SW] Some static assets failed to cache:', err);
        });
      }),
      caches.open(OFFLINE_CACHE).then(cache => {
        console.log('[SW] Caching offline pages');
        return cache.addAll(OFFLINE_PAGES.map(url => {
          return new Request(url, { credentials: 'same-origin' });
        }));
      })
    ]).then(() => {
      console.log('[SW] Installation complete');
      return self.skipWaiting();
    })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== STATIC_CACHE && name !== DYNAMIC_CACHE && name !== OFFLINE_CACHE)
          .map(name => {
            console.log('[SW] Deleting old cache:', name);
            return caches.delete(name);
          })
      );
    }).then(() => {
      console.log('[SW] Activation complete');
      return self.clients.claim();
    })
  );
});

// Fetch event - network first with cache fallback for pages, cache first for static assets
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    // Queue POST/PUT/DELETE requests for background sync
    if (['POST', 'PUT', 'DELETE', 'PATCH'].includes(request.method)) {
      event.respondWith(handleMutationRequest(request));
    }
    return;
  }
  
  // Skip cross-origin requests (except known CDNs)
  const isKnownCDN = url.hostname.includes('cdn.jsdelivr.net') || 
                     url.hostname.includes('fonts.googleapis.com') ||
                     url.hostname.includes('fonts.gstatic.com');
  
  if (url.origin !== location.origin && !isKnownCDN) {
    return;
  }
  
  // Handle API requests
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(handleApiRequest(request));
    return;
  }
  
  // Handle navigation requests (pages)
  if (request.mode === 'navigate' || request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(handleNavigationRequest(request));
    return;
  }
  
  // Handle static assets (CSS, JS, images, fonts)
  event.respondWith(handleStaticRequest(request));
});

// Handle API requests - network first, cache for offline viewing
async function handleApiRequest(request) {
  const url = new URL(request.url);
  const cacheName = CACHEABLE_API_PATTERNS.some(p => p.test(url.pathname)) ? DYNAMIC_CACHE : null;
  
  try {
    // Try network first
    const networkResponse = await fetch(request);
    
    // Cache successful GET responses for offline viewing
    if (cacheName && networkResponse.ok && request.method === 'GET') {
      const cache = await caches.open(cacheName);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    // Network failed - try cache
    if (cacheName) {
      const cache = await caches.open(cacheName);
      const cachedResponse = await cache.match(request);
      
      if (cachedResponse) {
        // Add offline indicator header
        const response = cachedResponse.clone();
        response.headers.set('X-Offline', 'true');
        return response;
      }
    }
    
    // Return offline response for queueable requests
    if (QUEUEABLE_API_PATTERNS.some(p => p.test(url.pathname))) {
      return queueForBackgroundSync(request);
    }
    
    // Return generic offline response
    return new Response(
      JSON.stringify({ 
        error: 'Offline', 
        message: 'This action requires an internet connection. It will be synced when you\'re back online.' 
      }),
      { 
        status: 503, 
        headers: { 'Content-Type': 'application/json', 'X-Offline': 'true' } 
      }
    );
  }
}

// Handle navigation requests - network first, offline page fallback
async function handleNavigationRequest(request) {
  try {
    const networkResponse = await fetch(request);
    
    // Cache successful page responses
    if (networkResponse.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    // Try cache
    const cache = await caches.open(DYNAMIC_CACHE);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
      const response = cachedResponse.clone();
      response.headers.set('X-Offline', 'true');
      return response;
    }
    
    // Fallback to offline page
    const offlineCache = await caches.open(OFFLINE_CACHE);
    const offlinePage = await offlineCache.match('/offline.html');
    
    if (offlinePage) {
      return offlinePage;
    }
    
    // Last resort - minimal offline page
    return new Response(`
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Offline - Laundry Driver Portal</title>
        <style>
          body { font-family: system-ui; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f3f4f6; }
          .container { text-align: center; padding: 2rem; background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 400px; }
          .icon { font-size: 4rem; color: #10b981; margin-bottom: 1rem; }
          h1 { color: #1f2937; margin-bottom: 0.5rem; }
          p { color: #6b7280; margin-bottom: 1.5rem; }
          button { background: #10b981; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-size: 1rem; cursor: pointer; }
          button:hover { background: #059669; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="icon">📱</div>
          <h1>You're Offline</h1>
          <p>Please check your internet connection and try again. Some features may be limited while offline.</p>
          <button onclick="window.location.reload()">Retry</button>
        </div>
      </body>
      </html>
    `, {
      headers: { 'Content-Type': 'text/html' }
    });
  }
}

// Handle static assets - cache first
async function handleStaticRequest(request) {
  const cache = await caches.open(STATIC_CACHE);
  const cachedResponse = await cache.match(request);
  
  if (cachedResponse) {
    // Serve from cache, update in background
    fetch(request).then(networkResponse => {
      if (networkResponse.ok) {
        cache.put(request, networkResponse);
      }
    }).catch(() => {});
    
    return cachedResponse;
  }
  
  // Not in cache - fetch and cache
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    // Return placeholder for images
    if (request.destination === 'image') {
      return new Response(
        '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect fill="#e5e7eb" width="200" height="200"/><text x="100" y="100" text-anchor="middle" fill="#9ca3af" font-family="system-ui" font-size="14">Image unavailable offline</text></svg>',
        { headers: { 'Content-Type': 'image/svg+xml' } }
      );
    }
    
    throw error;
  }
}

// Handle mutation requests (POST, PUT, DELETE) - queue for background sync
async function handleMutationRequest(request) {
  const url = new URL(request.url);
  
  // Check if this is a queueable request
  if (QUEUEABLE_API_PATTERNS.some(p => p.test(url.pathname))) {
    return queueForBackgroundSync(request);
  }
  
  // For non-queueable mutations, try network
  try {
    return await fetch(request);
  } catch (error) {
    return new Response(
      JSON.stringify({ error: 'Offline', message: 'This action requires an internet connection.' }),
      { status: 503, headers: { 'Content-Type': 'application/json' } }
    );
  }
}

// Queue request for background sync
async function queueForBackgroundSync(request) {
  try {
    const clone = request.clone();
    const body = await clone.text();
    
    const queueItem = {
      url: request.url,
      method: request.method,
      headers: Object.fromEntries(request.headers.entries()),
      body: body,
      timestamp: Date.now()
    };
    
    // Store in IndexedDB
    await storeInQueue(queueItem);
    
    // Register background sync
    if ('serviceWorker' in navigator && 'sync' in registration) {
      await registration.sync.register('background-sync');
    }
    
    return new Response(
      JSON.stringify({ 
        success: true, 
        queued: true, 
        message: 'Action queued for sync when online' 
      }),
      { status: 202, headers: { 'Content-Type': 'application/json' } }
    );
  } catch (error) {
    console.error('[SW] Failed to queue request:', error);
    return new Response(
      JSON.stringify({ error: 'Failed to queue', message: 'Could not save action for later sync.' }),
      { status: 500, headers: { 'Content-Type': 'application/json' } }
    );
  }
}

// IndexedDB for offline queue
function storeInQueue(item) {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('laundry-driver-offline', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => {
      const db = request.result;
      const transaction = db.transaction('queue', 'readwrite');
      const store = transaction.objectStore('queue');
      store.add(item);
      transaction.oncomplete = () => resolve();
      transaction.onerror = () => reject(transaction.error);
    };
    
    request.onupgradeneeded = event => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('queue')) {
        db.createObjectStore('queue', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

// Background sync event
self.addEventListener('sync', event => {
  if (event.tag === 'background-sync') {
    event.waitUntil(processQueue());
  }
});

// Process queued requests
async function processQueue() {
  const db = await openDB();
  const transaction = db.transaction('queue', 'readwrite');
  const store = transaction.objectStore('queue');
  const items = await getAll(store);
  
  for (const item of items) {
    try {
      const response = await fetch(item.url, {
        method: item.method,
        headers: item.headers,
        body: item.body || undefined
      });
      
      if (response.ok) {
        await deleteFromQueue(db, item.id);
        
        // Notify client
        const clients = await self.clients.matchAll();
        clients.forEach(client => {
          client.postMessage({
            type: 'SYNC_COMPLETE',
            itemId: item.id,
            url: item.url
          });
        });
      }
    } catch (error) {
      console.warn('[SW] Sync failed for:', item.url, error);
      // Keep in queue for next sync
    }
  }
}

// Helper functions for IndexedDB
function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('laundry-driver-offline', 1);
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    request.onupgradeneeded = event => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('queue')) {
        db.createObjectStore('queue', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

function getAll(store) {
  return new Promise((resolve, reject) => {
    const request = store.getAll();
    request.onsuccess = () => resolve(request.result);
    request.onerror = () => reject(request.error);
  });
}

function deleteFromQueue(db, id) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction('queue', 'readwrite');
    const store = transaction.objectStore('queue');
    const request = store.delete(id);
    request.onsuccess = () => resolve();
    request.onerror = () => reject(request.error);
  });
}

// Push notification handling
self.addEventListener('push', event => {
  if (!event.data) return;
  
  const data = event.data.json();
  const options = {
    body: data.body || 'You have a new notification',
    icon: '/icons/icon-192x192.png',
    badge: '/icons/badge-72x72.png',
    vibrate: [200, 100, 200],
    data: data.data || {},
    actions: data.actions || [],
    requireInteraction: data.requireInteraction || false,
    tag: data.tag || 'default',
    renotify: true
  };
  
  event.waitUntil(
    self.registration.showNotification(data.title || 'Laundry Driver', options)
  );
});

// Notification click handling
self.addEventListener('notificationclick', event => {
  event.notification.close();
  
  const data = event.notification.data;
  const action = event.action;
  
  let url = '/driver/dashboard';
  
  if (action === 'view_order' && data.orderId) {
    url = `/driver/orders/${data.orderId}`;
  } else if (action === 'navigate' && data.address) {
    url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(data.address)}`;
  } else if (data.url) {
    url = data.url;
  }
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      // Try to focus existing window
      for (const client of clientList) {
        if (client.url.includes(url) && 'focus' in client) {
          return client.focus();
        }
      }
      // Open new window
      return clients.openWindow(url);
    })
  );
});

// Message handling from clients
self.addEventListener('message', event => {
  if (event.data?.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data?.type === 'GET_VERSION') {
    event.ports[0].postMessage({ version: CACHE_NAME });
  }
  
  if (event.data?.type === 'CLEAR_CACHE') {
    caches.keys().then(names => {
      names.forEach(name => caches.delete(name));
    });
  }
  
  if (event.data?.type === 'PREFETCH') {
    const urls = event.data.urls || [];
    event.waitUntil(
      caches.open(DYNAMIC_CACHE).then(cache => {
        return cache.addAll(urls.map(u => new Request(u, { credentials: 'same-origin' })));
      })
    );
  }
});

// Periodic background sync (if supported)
self.addEventListener('periodicsync', event => {
  if (event.tag === 'refresh-schedule') {
    event.waitUntil(refreshScheduleData());
  }
});

async function refreshScheduleData() {
  try {
    const response = await fetch('/api/driver/schedule/today', { credentials: 'same-origin' });
    if (response.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put('/api/driver/schedule/today', response);
    }
  } catch (error) {
    console.warn('[SW] Failed to refresh schedule:', error);
  }
}

console.log('[SW] Service Worker loaded:', CACHE_NAME);