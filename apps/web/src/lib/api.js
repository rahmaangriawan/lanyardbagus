const API_BASE = import.meta.env.PUBLIC_API_BASE_URL || '/api';
const LARAVEL_ROOT = (import.meta.env.PUBLIC_API_BASE_URL
  ? import.meta.env.PUBLIC_API_BASE_URL.replace(/\/api\/?$/, '')
  : '');

export async function initCsrf() {
  await fetch(`${LARAVEL_ROOT}/sanctum/csrf-cookie`, {
    credentials: 'include'
  });
}

async function parseResponse(res) {
  const text = await res.text();
  return text ? JSON.parse(text) : null;
}

function getCookie(name) {
  if (typeof document === 'undefined') return null;
  const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
  return match ? decodeURIComponent(match[1]) : null;
}

export async function apiFetch(path, options = {}) {
  const hasBody = options.body !== undefined && options.body !== null;
  const isFormData =
    typeof FormData !== 'undefined' && options.body instanceof FormData;

  const headers = { ...(options.headers || {}) };

  if (hasBody && !isFormData && !headers['Content-Type'] && !headers['content-type']) {
    headers['Content-Type'] = 'application/json';
  }

  const xsrfToken = getCookie('XSRF-TOKEN');
  if (xsrfToken) {
    headers['X-XSRF-TOKEN'] = xsrfToken;
  }

  headers['Accept'] = 'application/json';

  const res = await fetch(`${API_BASE}${path}`, {
    credentials: 'include',
    headers,
    ...options
  });

  if (res.status === 401 && path !== '/auth/login') {
    if (typeof window !== 'undefined' && window.location.pathname !== '/login') {
      window.location.href = '/login';
      throw new Error('Sesi login berakhir. Silakan login lagi.');
    }
  }

  if (!res.ok) {
    const err = await res.json().catch(() => ({ message: res.statusText }));
    throw new Error(err.message || 'Request failed');
  }

  return parseResponse(res);
}

export async function serverApiFetch(path, cookie = '') {
  const WEB_ORIGIN = import.meta.env.PUBLIC_WEB_ORIGIN || 'http://localhost:4321';
  const response = await fetch(`${API_BASE}${path}`, {
    headers: {
      cookie,
      Accept: 'application/json',
      Origin: WEB_ORIGIN,
      Referer: WEB_ORIGIN,
    }
  });

  if (!response.ok) {
    throw new Error(`API request failed: ${response.status}`);
  }

  const text = await response.text();
  return text ? JSON.parse(text) : null;
}
