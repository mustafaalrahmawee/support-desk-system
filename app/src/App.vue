<template>
  <div>
    <!-- Logout-Button in geschützten Bereichen -->
    <div v-if="authStore.isAuthenticated" class="fixed right-4 top-4 z-50">
      <button
        class="flex items-center gap-1.5 rounded-md border border-[#1e2535] bg-[#0f1117] px-3 py-1.5 font-[Sora,sans-serif] text-xs text-slate-400 transition hover:border-slate-500 hover:text-slate-300 disabled:opacity-50"
        :disabled="loggingOut"
        @click="handleLogout"
      >
        <svg v-if="loggingOut" class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
        </svg>
        <svg v-else viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
          <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
        </svg>
        {{ loggingOut ? 'Abmelden…' : 'Abmelden' }}
      </button>
    </div>

    <RouterView />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth.store.js'

const router = useRouter()
const authStore = useAuthStore()
const loggingOut = ref(false)

async function handleLogout() {
  loggingOut.value = true
  try {
    await authStore.logout()
  } finally {
    loggingOut.value = false
  }
  router.push('/login')
}
</script>
