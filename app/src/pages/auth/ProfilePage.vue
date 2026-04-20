<template>
  <div class="min-h-screen bg-[#0c0e14] font-[Sora,sans-serif] text-slate-200">

    <!-- Page header -->
    <header class="border-b border-[#1e2130] bg-[#0f1117]">
      <div class="mx-auto max-w-2xl px-6 pb-6 pt-8">
        <p class="mb-2 font-mono text-[.65rem] font-semibold uppercase tracking-[.2em] text-amber-500">SYSTEM · KONTO</p>
        <h1 class="text-2xl font-bold tracking-tight text-slate-100">Mein Profil</h1>
      </div>
    </header>

    <div class="mx-auto max-w-2xl px-6 py-8">

      <!-- Loading skeleton -->
      <div v-if="uiState === 'loading'" class="rounded-xl border border-[#1e2130] bg-[#0f1117] p-7">
        <div class="mb-8 flex items-center gap-5">
          <div class="h-16 w-16 shrink-0 animate-pulse rounded-full bg-[#1e2535]"></div>
          <div class="flex flex-col gap-2.5">
            <div class="h-3.5 w-48 animate-pulse rounded bg-[#1e2535]"></div>
            <div class="h-3 w-32 animate-pulse rounded bg-[#1e2535]"></div>
          </div>
        </div>
        <div class="space-y-5">
          <div v-for="n in 4" :key="n" class="flex flex-col gap-2">
            <div class="h-2.5 w-20 animate-pulse rounded bg-[#1e2535]"></div>
            <div class="h-3.5 w-56 animate-pulse rounded bg-[#1e2535]"></div>
          </div>
        </div>
      </div>

      <!-- Error state -->
      <div v-else-if="uiState === 'error'" class="flex flex-col items-center rounded-xl border border-[#1e2130] bg-[#0f1117] px-7 py-12 text-center">
        <svg class="mb-4 h-12 w-12 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
        <h3 class="mb-1 text-base font-semibold text-slate-100">Profil konnte nicht geladen werden</h3>
        <p class="mb-5 text-sm text-slate-500">Verbindungsfehler oder Authentifizierungsproblem.</p>
        <button
          class="rounded-md border border-[#1e2535] px-5 py-2 text-sm text-slate-400 transition hover:border-amber-500 hover:text-amber-500"
          @click="loadProfile"
        >Erneut versuchen</button>
      </div>

      <!-- Profile card -->
      <div v-else-if="uiState === 'success'" class="rounded-xl border border-[#1e2130] bg-[#0f1117] overflow-hidden">

        <!-- Identity row -->
        <div class="flex flex-wrap items-center gap-5 p-7">
          <!-- Avatar -->
          <div class="relative shrink-0">
            <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-[#1e2535] bg-gradient-to-br from-[#1e2535] to-[#2a3348]">
              <span class="font-bold text-xl tracking-tight text-amber-500">{{ initials }}</span>
            </div>
            <span
              class="absolute bottom-0.5 right-0.5 h-3 w-3 rounded-full border-2 border-[#0f1117]"
              :class="profileData.is_active ? 'bg-green-500' : 'bg-slate-600'"
            ></span>
          </div>

          <!-- Name / username -->
          <div class="flex-1 min-w-0">
            <h2 class="truncate text-xl font-bold tracking-tight text-slate-100">
              {{ profileData.first_name }} {{ profileData.last_name }}
            </h2>
            <p class="font-mono text-sm text-slate-500">@{{ profileData.username }}</p>
          </div>

          <!-- Edit button -->
          <router-link
            to="/profile/edit"
            class="flex shrink-0 items-center gap-1.5 rounded-md border border-amber-500/30 bg-amber-500/10 px-4 py-2 text-xs font-semibold text-amber-500 transition hover:border-amber-500/50 hover:bg-amber-500/18"
          >
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
            </svg>
            Profil bearbeiten
          </router-link>
        </div>

        <div class="mx-7 h-px bg-[#1e2130]"></div>

        <!-- Data fields -->
        <dl class="px-7 pb-6">
          <div
            v-for="field in fields"
            :key="field.label"
            class="flex items-start gap-4 border-b border-[#131620] py-3.5 last:border-0"
          >
            <dt class="w-44 shrink-0 pt-0.5 font-mono text-[.62rem] font-medium uppercase tracking-[.15em] text-slate-600">
              {{ field.label }}
            </dt>
            <dd class="text-sm text-slate-300">

              <!-- Roles -->
              <template v-if="field.key === 'roles'">
                <div class="flex flex-wrap gap-1.5">
                  <span
                    v-for="role in profileData.roles"
                    :key="role"
                    class="rounded px-2 py-0.5 font-mono text-[.65rem] uppercase tracking-wide border border-amber-500/20 bg-amber-500/10 text-amber-300"
                  >{{ role }}</span>
                  <span v-if="!profileData.roles?.length" class="font-mono text-xs text-slate-600">Keine Rollen</span>
                </div>
              </template>

              <!-- Active status -->
              <template v-else-if="field.key === 'is_active'">
                <span
                  class="rounded px-2 py-0.5 font-mono text-[.65rem] uppercase tracking-wide border"
                  :class="profileData.is_active
                    ? 'border-green-500/20 bg-green-500/10 text-green-400'
                    : 'border-slate-600/30 bg-slate-600/10 text-slate-500'"
                >{{ profileData.is_active ? 'Aktiv' : 'Deaktiviert' }}</span>
              </template>

              <!-- Date -->
              <template v-else-if="field.key === 'created_at' || field.key === 'updated_at'">
                <span class="font-mono text-xs text-slate-400">{{ formatDate(profileData[field.key]) }}</span>
              </template>

              <!-- Plain text -->
              <template v-else>{{ profileData[field.key] }}</template>

            </dd>
          </div>
        </dl>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth.store.js'

const authStore = useAuthStore()

const uiState = ref('loading')
const profileData = ref(null)

const fields = [
  { label: 'E-Mail',           key: 'email' },
  { label: 'Benutzername',     key: 'username' },
  { label: 'Status',           key: 'is_active' },
  { label: 'Rollen',           key: 'roles' },
  { label: 'Erstellt',         key: 'created_at' },
  { label: 'Zuletzt geändert', key: 'updated_at' },
]

const initials = computed(() => {
  if (!profileData.value) return '?'
  return `${profileData.value.first_name?.[0] ?? ''}${profileData.value.last_name?.[0] ?? ''}`.toUpperCase()
})

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('de-DE', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

async function loadProfile() {
  uiState.value = 'loading'
  try {
    profileData.value = await authStore.fetchMe()
    uiState.value = 'success'
  } catch {
    uiState.value = 'error'
  }
}

onMounted(loadProfile)
</script>
