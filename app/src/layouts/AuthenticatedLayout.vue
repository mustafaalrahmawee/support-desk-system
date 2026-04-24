<template>
  <div class="flex min-h-screen flex-col bg-[#f8fafc] font-[Sora,sans-serif] text-[#111827]">
    <header class="sticky top-0 z-40 border-b border-[#dfe5ef] bg-white">
      <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-5 px-6">
        <div class="flex min-w-0 items-center gap-10">
          <RouterLink to="/profile" class="flex shrink-0 items-center gap-4">
            <svg class="h-10 w-10" viewBox="0 0 44 44" fill="none" aria-hidden="true">
              <rect x="2" y="2" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="17" y="2" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="32" y="2" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="2" y="17" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="17" y="17" width="10" height="10" rx="1.5" fill="#1547d1" opacity="0.6" />
              <rect x="32" y="17" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="2" y="32" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="17" y="32" width="10" height="10" rx="1.5" fill="#1547d1" />
              <rect x="32" y="32" width="10" height="10" rx="1.5" fill="#1547d1" opacity="0.6" />
            </svg>
            <span class="text-xl font-bold tracking-tight text-[#0f172a]">Support Desk</span>
          </RouterLink>

          <nav class="hidden h-[72px] items-stretch md:flex" aria-label="Hauptnavigation">
            <RouterLink
              v-for="item in navItems"
              :key="item.to"
              :to="item.to"
              class="relative flex items-center px-7 text-sm font-semibold transition"
              :class="isActive(item) ? 'text-[#1547d1]' : 'text-[#374151] hover:text-[#1547d1]'"
              :aria-current="isActive(item) ? 'page' : undefined"
            >
              {{ item.label }}
              <span
                v-if="isActive(item)"
                class="absolute inset-x-5 bottom-0 h-0.5 rounded-full bg-[#1547d1]"
              ></span>
            </RouterLink>
          </nav>
        </div>

        <div class="hidden shrink-0 items-center gap-5 md:flex">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#eef4ff] text-xs font-bold text-[#1547d1]">
              {{ userInitials }}
            </div>
            <div class="leading-tight">
              <p class="text-sm font-bold text-[#111827]">{{ displayName }}</p>
              <p v-if="authStore.user?.username" class="mt-0.5 text-xs text-[#64748b]">{{ authStore.user.username }}</p>
            </div>
          </div>

          <button
            type="button"
            class="inline-flex h-10 items-center gap-2 rounded-md border border-[#cbd5e1] bg-white px-4 text-sm font-semibold text-[#334155] transition hover:border-[#1547d1] hover:text-[#1547d1] disabled:cursor-not-allowed disabled:opacity-70"
            :disabled="isLoggingOut"
            @click="handleLogout"
          >
            <svg v-if="isLoggingOut" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            <svg v-else viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
              <path fill-rule="evenodd" d="M3 3a1 1 0 0 0-1 1v12a1 1 0 1 0 2 0V4a1 1 0 0 0-1-1Zm10.293 9.293a1 1 0 0 0 1.414 1.414l3-3a1 1 0 0 0 0-1.414l-3-3a1 1 0 1 0-1.414 1.414L14.586 9H7a1 1 0 1 0 0 2h7.586l-1.293 1.293Z" clip-rule="evenodd" />
            </svg>
            {{ isLoggingOut ? 'Abmelden...' : 'Abmelden' }}
          </button>
        </div>

        <button
          type="button"
          class="inline-flex h-10 w-10 items-center justify-center rounded-md text-[#334155] transition hover:bg-[#eef4ff] hover:text-[#1547d1] md:hidden"
          :aria-expanded="mobileMenuOpen"
          aria-label="Navigation öffnen"
          @click="mobileMenuOpen = !mobileMenuOpen"
        >
          <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
            <path fill-rule="evenodd" d="M3 5.75A.75.75 0 0 1 3.75 5h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.75ZM3 10a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 10Zm0 4.25a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <div v-if="mobileMenuOpen" class="border-t border-[#dfe5ef] bg-white px-6 py-4 md:hidden">
        <nav class="grid gap-2" aria-label="Mobile Hauptnavigation">
          <RouterLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="rounded-md px-3 py-2 text-sm font-semibold transition"
            :class="isActive(item) ? 'bg-[#eef4ff] text-[#1547d1]' : 'text-[#334155] hover:bg-[#f1f5f9]'"
            @click="mobileMenuOpen = false"
          >
            {{ item.label }}
          </RouterLink>
        </nav>

        <button
          type="button"
          class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-md border border-[#cbd5e1] px-4 py-2.5 text-sm font-semibold text-[#334155]"
          :disabled="isLoggingOut"
          @click="handleLogout"
        >
          {{ isLoggingOut ? 'Abmelden...' : 'Abmelden' }}
        </button>
      </div>
    </header>

    <main class="flex-1">
      <RouterView />
    </main>

    <footer class="border-t border-[#dfe5ef] bg-white">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 text-xs text-[#64748b]">
        <span>© 2026 Support Desk. Alle Rechte vorbehalten.</span>
        <span class="hidden sm:inline">Version 1.0.0</span>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const isLoggingOut = ref(false)
const mobileMenuOpen = ref(false)

const navItems = [
  { label: 'Profil', to: '/profile', match: /^\/profile/ },
  { label: 'Benutzer', to: '/admin/users', match: /^\/admin\/users/ },
]

const displayName = computed(() => {
  const user = authStore.user
  if (!user) return 'Benutzer'
  return [user.first_name, user.last_name].filter(Boolean).join(' ') || user.username || 'Benutzer'
})

const userInitials = computed(() => {
  const user = authStore.user
  if (!user) return 'U'
  const first = user.first_name?.[0] ?? ''
  const last = user.last_name?.[0] ?? ''
  const fallback = user.username?.[0] ?? 'U'
  return `${first}${last}`.trim().toUpperCase() || fallback.toUpperCase()
})

function isActive(item) {
  return item.match.test(route.path)
}

async function handleLogout() {
  isLoggingOut.value = true
  try {
    await authStore.logout()
    mobileMenuOpen.value = false
    router.push('/login')
  } finally {
    isLoggingOut.value = false
  }
}
</script>
