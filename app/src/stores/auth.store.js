import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useApiFetch } from '@/composables/useApiFetch'


export const useAuthStore = defineStore('auth', () => {
  const { apiFetch } = useApiFetch()
  const user = ref(JSON.parse(localStorage.getItem('auth_user') ?? 'null'))
  const token = ref(localStorage.getItem('auth_token') ?? null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  function persistAuth(userData, authToken) {
    user.value = userData
    token.value = authToken
    localStorage.setItem('auth_user', JSON.stringify(userData))
    localStorage.setItem('auth_token', authToken)
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }

  async function login(payload) {
    const data = await apiFetch('/api/login', {
      method: 'POST',
      body: payload,
    })
    persistAuth(data.data.internal_user, data.data.token)
    return data
  }

  async function logout() {
    try {
      await apiFetch('/api/logout', { method: 'POST' })
    } finally {
      clearAuth()
    }
  }

  async function fetchMe() {
    const data = await apiFetch('/api/me')
    user.value = data.data
    localStorage.setItem('auth_user', JSON.stringify(data.data))
    return data.data
  }

  async function updateProfile(payload) {
    const data = await apiFetch('/api/me', {
      method: 'PATCH',
      body: payload,
    })
    user.value = data.data
    localStorage.setItem('auth_user', JSON.stringify(data.data))
    return data.data
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    logout,
    fetchMe,
    updateProfile,
  }
})
