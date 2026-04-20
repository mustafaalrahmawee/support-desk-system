import { $fetch } from 'ofetch'

const API_BASE = import.meta.env.VITE_API_URL ?? 'http://localhost:8000'

export function useApiFetch() {
  function apiFetch(path, options = {}) {
    const token = localStorage.getItem('auth_token')
    return $fetch(`${API_BASE}${path}`, {
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
        ...options.headers,
      },
      ...options,
    })
  }

  return { apiFetch }
}
