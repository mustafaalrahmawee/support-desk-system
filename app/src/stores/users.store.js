import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useApiFetch } from '@/composables/useApiFetch'

export const useUsersStore = defineStore('users', () => {
  const { apiFetch } = useApiFetch()

  const users = ref([])
  const usersMeta = ref(null)
  const currentUser = ref(null)
  const roles = ref([])
  const isLoading = ref(false)

  async function fetchInternalUsers(params = {}) {
    isLoading.value = true
    try {
      const query = new URLSearchParams(
        Object.fromEntries(Object.entries(params).filter(([, v]) => v !== null && v !== undefined && v !== ''))
      ).toString()
      const data = await apiFetch(`/api/admin/internal-users${query ? '?' + query : ''}`)
      users.value = data.data
      usersMeta.value = data.meta
      return data
    } finally {
      isLoading.value = false
    }
  }

  async function fetchInternalUser(id) {
    const data = await apiFetch(`/api/admin/internal-users?page=1&per_page=1000`)
    const user = data.data.find((u) => u.id === Number(id))
    currentUser.value = user ?? null
    return user ?? null
  }

  async function fetchRoles() {
    const data = await apiFetch('/api/admin/roles')
    roles.value = data.data
    return data.data
  }

  async function createInternalUser(payload) {
    const data = await apiFetch('/api/admin/internal-users', {
      method: 'POST',
      body: payload,
    })
    return data
  }

  async function updateInternalUser(id, payload) {
    const data = await apiFetch(`/api/admin/internal-users/${id}`, {
      method: 'PATCH',
      body: payload,
    })
    return data
  }

  async function deactivateInternalUser(id) {
    const data = await apiFetch(`/api/admin/internal-users/${id}`, {
      method: 'DELETE',
    })
    return data
  }

  return {
    users,
    usersMeta,
    currentUser,
    roles,
    isLoading,
    fetchInternalUsers,
    fetchInternalUser,
    fetchRoles,
    createInternalUser,
    updateInternalUser,
    deactivateInternalUser,
  }
})
