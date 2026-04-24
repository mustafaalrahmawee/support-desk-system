<template>
  <section class="px-6 py-12">
    <div class="mx-auto max-w-5xl">
      <div class="mb-10">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.12em] text-[#1547d1]">SYSTEM · KONTO</p>
        <h1 class="text-4xl font-bold tracking-tight text-[#111827] sm:text-[2.6rem]">Mein Profil</h1>
        <p class="mt-4 max-w-xl text-base leading-7 text-[#64748b]">
          Ihre persönlichen Kontodaten und Rollen im System.
        </p>
      </div>

      <AppLoadingState v-if="uiState === 'loading'" :rows="7" />

      <AppErrorState
        v-else-if="uiState === 'error'"
        title="Profil konnte nicht geladen werden"
        description="Beim Laden Ihrer Profilinformationen ist ein Fehler aufgetreten."
        action-label="Erneut versuchen"
        @retry="loadProfile"
      />

      <article v-else-if="uiState === 'success'" class="overflow-hidden rounded-lg border border-[#dfe5ef] bg-white shadow-sm">
        <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
          <div class="flex min-w-0 items-center gap-6">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-[#eef4ff] text-2xl font-bold text-[#1547d1]">
              {{ initials }}
            </div>

            <div class="min-w-0">
              <div class="flex flex-wrap items-center gap-3">
                <h2 class="truncate text-2xl font-bold tracking-tight text-[#111827]">
                  {{ fullName }}
                </h2>
                <span
                  class="rounded-md px-2.5 py-1 text-xs font-semibold"
                  :class="profileData.is_active
                    ? 'border border-green-200 bg-green-50 text-green-700'
                    : 'border border-slate-200 bg-slate-100 text-slate-600'"
                >
                  {{ profileData.is_active ? 'Aktiv' : 'Deaktiviert' }}
                </span>
              </div>
              <p class="mt-1 text-base text-[#64748b]">{{ profileData.username }}</p>
            </div>
          </div>

          <router-link
            to="/profile/edit"
            class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-[#b8c7e6] bg-white px-5 text-sm font-semibold text-[#1547d1] transition hover:border-[#1547d1] hover:bg-[#f8fbff] sm:w-auto"
          >
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
              <path d="M13.586 3.586a2 2 0 1 1 2.828 2.828l-.793.793-2.828-2.828.793-.793ZM11.379 5.793 3 14.172V17h2.828l8.38-8.379-2.83-2.828Z" />
            </svg>
            Profil bearbeiten
          </router-link>
        </div>

        <dl class="border-t border-[#edf1f6] px-6 sm:px-8">
          <div
            v-for="row in profileRows"
            :key="row.label"
            class="grid gap-2 border-b border-[#edf1f6] py-4 last:border-b-0 sm:grid-cols-[14rem_1fr] sm:gap-6"
          >
            <dt class="text-sm font-bold text-[#334155]">{{ row.label }}</dt>
            <dd class="text-sm text-[#475569]">
              <template v-if="row.type === 'status'">
                <span
                  class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold"
                  :class="profileData.is_active
                    ? 'border border-green-200 bg-green-50 text-green-700'
                    : 'border border-slate-200 bg-slate-100 text-slate-600'"
                >
                  {{ profileData.is_active ? 'Aktiv' : 'Deaktiviert' }}
                </span>
              </template>

              <template v-else-if="row.type === 'roles'">
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="role in profileData.roles"
                    :key="role"
                    class="inline-flex rounded-md bg-[#eaf1ff] px-2.5 py-1 text-xs font-semibold text-[#1547d1]"
                  >
                    {{ role }}
                  </span>
                  <span v-if="!profileData.roles?.length" class="text-[#94a3b8]">Keine Rollen</span>
                </div>
              </template>

              <template v-else>
                {{ row.value }}
              </template>
            </dd>
          </div>
        </dl>
      </article>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppErrorState from '@/components/common/AppErrorState.vue'
import AppLoadingState from '@/components/common/AppLoadingState.vue'
import { useAuthStore } from '../../stores/auth.store.js'

const authStore = useAuthStore()

const uiState = ref('loading')
const profileData = ref(null)

const fullName = computed(() => {
  if (!profileData.value) return ''
  return [profileData.value.first_name, profileData.value.last_name].filter(Boolean).join(' ')
})

const initials = computed(() => {
  if (!profileData.value) return '?'
  const first = profileData.value.first_name?.[0] ?? ''
  const last = profileData.value.last_name?.[0] ?? ''
  return `${first}${last}`.toUpperCase() || '?'
})

const profileRows = computed(() => {
  if (!profileData.value) return []
  return [
    { label: 'Name', value: fullName.value },
    { label: 'Benutzername', value: profileData.value.username },
    { label: 'E-Mail', value: profileData.value.email },
    { label: 'Status', type: 'status' },
    { label: 'Rollen', type: 'roles' },
    { label: 'Erstellt', value: formatDate(profileData.value.created_at) },
    { label: 'Zuletzt geändert', value: formatDate(profileData.value.updated_at) },
  ]
})

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('de-DE', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
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
