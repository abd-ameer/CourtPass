/**
 * CourtPass shared JS (vanilla, no libraries).
 * api() wraps fetch() for calls to our own /api/... routes and
 * sends the CSRF token automatically.
 */
const CourtPass = (() => {
    const base = document.querySelector('meta[name="base-url"]')?.content || '';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    async function api(path, { method = 'GET', data = null } = {}) {
        const options = {
            method,
            headers: { 'Accept': 'application/json', 'X-CSRF-Token': csrf },
            credentials: 'same-origin',
        };
        if (data !== null) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }
        const response = await fetch(base + path, options);
        const body = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw Object.assign(new Error(body.error || 'Request failed'), { status: response.status, body });
        }
        return body;
    }

    return { api, base };
})();
