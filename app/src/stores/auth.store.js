import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useApiFetch } from '@/composables/useApiFetch'

export const useAuthStore = defineStore('auth', () => {
  const { apiFetch } = useApiFetch()

  // ***************** initial State *******************
  const user = ref(JSON.parse(localStorage.getItem('auth_user') ?? 'null'))
  const token = ref(localStorage.getItem('auth_token') ?? null)

  // ***************** initial Getter *******************
  const isAuthenticated = computed(() => !!token.value && !!user.value)

  // ***************** initial Helpers *******************
  const persistAuth = (userData, authToken) => {
    user.value = userData
    token.value = authToken

    localStorage.setItem('auth_user', JSON.stringify(userData))
    localStorage.setItem('auth_token', authToken)
  }

  const clearAuth = () => {
    user.value = null
    token.value = null

    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }

  // ***************** Auth Actions *******************
  const login = async (payload) => {
    const response = await apiFetch('/api/login', {
      method: 'POST',
      body: payload,
    })

    persistAuth(response.data.internal_user, response.data.token)

    return response
  }

  const logout = async () => {
    try {
      await apiFetch('/api/logout', {
        method: 'POST',
      })
    } finally {
      clearAuth()
    }
  }

  const fetchMe = async () => {
    const response = await apiFetch('/api/me')

    user.value = response.data
    localStorage.setItem('auth_user', JSON.stringify(response.data))

    return response.data
  }

  const updateProfile = async (payload) => {
    const response = await apiFetch('/api/me', {
      method: 'PATCH',
      body: payload,
    })

    user.value = response.data
    localStorage.setItem('auth_user', JSON.stringify(response.data))

    return response.data
  }

  return {
    user,
    token,
    isAuthenticated,
    persistAuth,
    clearAuth,
    login,
    logout,
    fetchMe,
    updateProfile,
  }
})
