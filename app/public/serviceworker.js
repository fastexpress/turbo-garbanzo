self.addEventListener('fetch', function (event) {
    event.respondWith(async function () {
        if (
            event.request.url.startsWith('chrome-extension') ||
            event.request.url.includes('extension') ||
            !(event.request.url.indexOf('http') === 0)
        ) return;
        try {
            var res = await fetch(event.request);
            var cache = await caches.open('cache');
            cache.put(event.request.url, res.clone());
            return res;
        }
        catch (error) {
            return caches.match(event.request);
        }
    }());
});