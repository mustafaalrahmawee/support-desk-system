<template>
  <section class="px-6 py-10">
    <div class="mx-auto max-w-7xl">
      <div class="mb-7 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="mb-3 text-xs font-bold uppercase tracking-[.12em] text-[#1547d1]">ADMIN · BENUTZER</p>
          <h1 class="text-3xl font-bold tracking-tight text-[#111827] sm:text-[2.2rem]">Interne Benutzer</h1>
          <p class="mt-3 max-w-xl text-sm leading-6 text-[#64748b]">
            Verwalten Sie interne Benutzer, Rollen und Aktivstatus.
          </p>
        </div>

        <button
          type="button"
          class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#1547d1] px-5 text-sm font-semibold text-white transition hover:bg-[#0f3db7] active:translate-y-px"
          @click="$router.push('/admin/users/create')"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
          </svg>
          Neuen Benutzer anlegen
        </button>
      </div>

      <div class="mb-5">
        <span class="inline-flex rounded-md border border-[#dfe5ef] bg-white px-4 py-2 text-sm font-semibold text-[#64748b] shadow-sm">
          {{ meta?.total ?? 0 }} Einträge
        </span>
      </div>

      <div class="rounded-lg border border-[#dfe5ef] bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-[#edf1f6] p-4 lg:flex-row lg:items-center">
          <div class="relative min-w-0 flex-1">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z" />
            </svg>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Name, Benutzername oder E-Mail suchen"
              class="h-10 w-full rounded-md border border-[#dfe5ef] bg-white pl-10 pr-3 text-sm text-[#111827] outline-none transition placeholder:text-[#94a3b8] focus:border-[#1547d1] focus:ring-2 focus:ring-blue-100"
              @input="onFilterChange"
            />
          </div>

          <select
            v-model="filters.is_active"
            class="h-10 rounded-md border border-[#dfe5ef] bg-white px-3 text-sm text-[#475569] outline-none transition focus:border-[#1547d1] focus:ring-2 focus:ring-blue-100 lg:w-44"
            @change="onFilterChange"
          >
            <option value="">Alle Status</option>
            <option value="true">Aktiv</option>
            <option value="false">Inaktiv</option>
          </select>

          <select
            v-model="filters.role"
            class="h-10 rounded-md border border-[#dfe5ef] bg-white px-3 text-sm text-[#475569] outline-none transition focus:border-[#1547d1] focus:ring-2 focus:ring-blue-100 lg:w-44"
            @change="onFilterChange"
          >
            <option value="">Alle Rollen</option>
            <option value="admin">admin</option>
            <option value="support_agent">support_agent</option>
            <option value="inbound_reviewer">inbound_reviewer</option>
            <option value="contract_manager">contract_manager</option>
          </select>

          <button
            type="button"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-md border border-[#dfe5ef] px-4 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155]"
            @click="resetFilters"
          >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 1 1-1.108-4.805.75.75 0 0 0 1.061-1.06 7 7 0 1 0 1.407 6.113.75.75 0 0 0-1.36-.248Z" clip-rule="evenodd" />
              <path fill-rule="evenodd" d="M14.25 3.75a.75.75 0 0 1 .75.75V8h-3.5a.75.75 0 0 1 0-1.5h2V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
            </svg>
            Zurücksetzen
          </button>
        </div>

        <div v-if="uiState === 'loading'" class="hidden md:block">
          <div class="grid grid-cols-[2fr_1.4fr_2fr_1.4fr_1fr_auto] border-b border-[#edf1f6] bg-[#fbfdff] text-xs font-bold text-[#334155]">
            <div class="px-4 py-3">Name</div>
            <div class="px-4 py-3">Benutzername</div>
            <div class="px-4 py-3">E-Mail</div>
            <div class="px-4 py-3">Rollen</div>
            <div class="px-4 py-3">Status</div>
            <div class="px-4 py-3 text-right">Aktionen</div>
          </div>
          <div
            v-for="item in 5"
            :key="item"
            class="grid animate-pulse grid-cols-[2fr_1.4fr_2fr_1.4fr_1fr_auto] border-b border-[#edf1f6] last:border-b-0"
          >
            <div class="flex items-center gap-3 px-4 py-4">
              <div class="h-9 w-9 rounded-full bg-[#e6ebf2]"></div>
              <div class="h-3 w-32 rounded bg-[#e6ebf2]"></div>
            </div>
            <div class="px-4 py-5"><div class="h-3 w-28 rounded bg-[#eef2f7]"></div></div>
            <div class="px-4 py-5"><div class="h-3 w-40 rounded bg-[#eef2f7]"></div></div>
            <div class="px-4 py-5"><div class="h-5 w-24 rounded bg-[#e6ebf2]"></div></div>
            <div class="px-4 py-5"><div class="h-5 w-16 rounded bg-[#e6ebf2]"></div></div>
            <div class="px-4 py-4"><div class="h-8 w-24 rounded bg-[#eef2f7]"></div></div>
          </div>
        </div>

        <div v-else-if="uiState === 'error' || uiState === 'forbidden'" class="px-4 py-8">
          <AppErrorState
            :title="uiState === 'forbidden' ? 'Zugriff verweigert' : 'Benutzer konnten nicht geladen werden'"
            :description="uiState === 'forbidden'
              ? 'Sie benötigen Admin-Rechte, um diese Seite zu öffnen.'
              : errorMessage"
            :action-label="uiState === 'forbidden' ? '' : 'Erneut versuchen'"
            @retry="loadUsers"
          />
        </div>

        <div v-else-if="uiState === 'empty'" class="flex flex-col items-center justify-center px-6 py-20 text-center">
          <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-full bg-[#eef4ff] text-[#7aa2f7]">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
          </div>
          <h2 class="text-lg font-bold text-[#111827]">Keine Benutzer gefunden</h2>
          <p class="mt-2 text-sm text-[#64748b]">Passen Sie Suche oder Filter an.</p>
        </div>

        <template v-else-if="uiState === 'success'">
          <div class="hidden md:block">
            <div class="grid grid-cols-[2fr_1.4fr_2fr_1.4fr_1fr_auto] border-b border-[#edf1f6] bg-[#fbfdff] text-xs font-bold text-[#334155]">
              <div class="px-4 py-3">Name</div>
              <div class="px-4 py-3">Benutzername</div>
              <div class="px-4 py-3">E-Mail</div>
              <div class="px-4 py-3">Rollen</div>
              <div class="px-4 py-3">Status</div>
              <div class="px-4 py-3 text-right">Aktionen</div>
            </div>

            <div
              v-for="user in users"
              :key="user.id"
              class="grid grid-cols-[2fr_1.4fr_2fr_1.4fr_1fr_auto] border-b border-[#edf1f6] transition last:border-b-0 hover:bg-[#f8fbff]"
            >
              <button
                type="button"
                class="flex min-w-0 items-center gap-3 px-4 py-4 text-left"
                @click="$router.push(`/admin/users/${user.id}/edit`)"
              >
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold" :class="avatarClass(user)">
                  {{ initials(user) }}
                </span>
                <span class="truncate text-sm font-bold text-[#111827]">{{ user.first_name }} {{ user.last_name }}</span>
              </button>
              <div class="flex items-center px-4 py-4 text-sm text-[#475569]">{{ user.username }}</div>
              <div class="flex min-w-0 items-center px-4 py-4 text-sm text-[#475569]">
                <span class="truncate">{{ user.email }}</span>
              </div>
              <div class="flex flex-wrap items-center gap-1.5 px-4 py-4">
                <span
                  v-for="role in user.roles"
                  :key="role"
                  class="rounded-md bg-[#eaf1ff] px-2.5 py-1 text-xs font-semibold text-[#1547d1]"
                >
                  {{ role }}
                </span>
                <span v-if="!user.roles || user.roles.length === 0" class="text-sm text-[#94a3b8]">–</span>
              </div>
              <div class="flex items-center px-4 py-4">
                <span
                  class="rounded-md px-2.5 py-1 text-xs font-semibold"
                  :class="user.is_active
                    ? 'border border-green-200 bg-green-50 text-green-700'
                    : 'border border-slate-200 bg-slate-100 text-slate-600'"
                >
                  {{ user.is_active ? 'Aktiv' : 'Inaktiv' }}
                </span>
              </div>
              <div class="flex items-center justify-end gap-2 px-4 py-4">
                <button
                  type="button"
                  class="inline-flex h-9 items-center gap-2 rounded-md border border-[#dfe5ef] px-3 text-sm font-semibold text-[#475569] transition hover:border-[#1547d1] hover:text-[#1547d1]"
                  @click="$router.push(`/admin/users/${user.id}/edit`)"
                >
                  <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M13.586 3.586a2 2 0 1 1 2.828 2.828l-.793.793-2.828-2.828.793-.793ZM11.379 5.793 3 14.172V17h2.828l8.38-8.379-2.83-2.828Z" />
                  </svg>
                  Bearbeiten
                </button>
                <button
                  v-if="canDeactivate(user)"
                  type="button"
                  class="inline-flex h-9 items-center gap-2 rounded-md border border-red-200 px-3 text-sm font-semibold text-red-600 transition hover:border-red-400 hover:bg-red-50"
                  @click="openDeactivateModal(user)"
                >
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728 1.414 1.414M5.636 5.636 4.222 4.222" />
                  </svg>
                  Deaktivieren
                </button>
              </div>
            </div>
          </div>

          <div class="grid gap-3 p-4 md:hidden">
            <article
              v-for="user in users"
              :key="user.id"
              class="rounded-lg border border-[#edf1f6] bg-white p-4 shadow-sm"
            >
              <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold" :class="avatarClass(user)">
                  {{ initials(user) }}
                </div>
                <div class="min-w-0 flex-1">
                  <div class="flex items-center justify-between gap-3">
                    <h2 class="truncate text-sm font-bold text-[#111827]">{{ user.first_name }} {{ user.last_name }}</h2>
                    <span
                      class="shrink-0 rounded-md px-2 py-0.5 text-[.65rem] font-semibold"
                      :class="user.is_active
                        ? 'border border-green-200 bg-green-50 text-green-700'
                        : 'border border-slate-200 bg-slate-100 text-slate-600'"
                    >
                      {{ user.is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                  </div>
                  <p class="mt-1 text-xs text-[#64748b]">{{ user.username }}</p>
                  <p class="mt-1 truncate text-xs text-[#64748b]">{{ user.email }}</p>
                  <div class="mt-2 flex flex-wrap gap-1.5">
                    <span
                      v-for="role in user.roles"
                      :key="role"
                      class="rounded-md bg-[#eaf1ff] px-2 py-0.5 text-[.65rem] font-semibold text-[#1547d1]"
                    >
                      {{ role }}
                    </span>
                  </div>
                </div>
              </div>

              <button
                type="button"
                class="mt-3 inline-flex h-9 w-full items-center justify-center gap-2 rounded-md border border-[#dfe5ef] text-sm font-semibold text-[#475569]"
                @click="$router.push(`/admin/users/${user.id}/edit`)"
              >
                Bearbeiten
              </button>
              <button
                v-if="canDeactivate(user)"
                type="button"
                class="mt-2 inline-flex h-9 w-full items-center justify-center gap-2 rounded-md border border-red-200 text-sm font-semibold text-red-600"
                @click="openDeactivateModal(user)"
              >
                Deaktivieren
              </button>
            </article>
          </div>
        </template>
      </div>

      <div v-if="meta" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <span class="text-xs text-[#64748b]">
          Seite {{ meta.current_page }} von {{ totalPages }} · {{ meta.total }} Einträge gesamt
        </span>
        <div class="flex items-center gap-2">
          <button
            type="button"
            class="h-9 rounded-md border border-[#dfe5ef] px-4 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155] disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage <= 1"
            @click="changePage(currentPage - 1)"
          >
            Zurück
          </button>
          <button
            v-for="page in visiblePages"
            :key="page"
            type="button"
            class="h-9 min-w-9 rounded-md border px-3 text-sm font-semibold transition"
            :class="page === currentPage
              ? 'border-[#1547d1] bg-[#1547d1] text-white'
              : 'border-[#dfe5ef] text-[#64748b] hover:border-[#b8c7e6] hover:text-[#334155]'"
            @click="changePage(page)"
          >
            {{ page }}
          </button>
          <button
            type="button"
            class="h-9 rounded-md border border-[#dfe5ef] px-4 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155] disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="!hasNextPage"
            @click="changePage(currentPage + 1)"
          >
            Weiter
          </button>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <div
        v-if="deactivateModal.open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm"
        @click.self="closeDeactivateModal"
      >
        <div class="w-full max-w-md rounded-lg border border-[#dfe5ef] bg-white shadow-xl">
          <div class="border-b border-[#edf1f6] px-6 py-5">
            <h2 class="text-lg font-bold text-[#111827]">Benutzer deaktivieren</h2>
            <p class="mt-1 text-sm text-[#64748b]">Diese Aktion kann nicht rückgängig gemacht werden.</p>
          </div>

          <div class="space-y-4 px-6 py-5">
            <p class="text-sm leading-6 text-[#475569]">
              Benutzer
              <span class="font-bold text-[#111827]">{{ deactivateModal.user?.first_name }} {{ deactivateModal.user?.last_name }}</span>
              wird deaktiviert.
            </p>
            <div class="rounded-md border border-red-100 bg-red-50/60 px-4 py-3">
              <p class="text-xs font-bold uppercase tracking-[.08em] text-red-700">Konsequenzen</p>
              <ul class="mt-2 space-y-1.5 text-sm text-red-700">
                <li>Zugehöriger Actor wird deaktiviert.</li>
                <li>Bestehende Ticket-Zuweisungen werden aufgehoben.</li>
              </ul>
            </div>

            <div v-if="deactivateModal.error" class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
              {{ deactivateModal.error }}
            </div>
          </div>

          <div class="flex justify-end gap-3 border-t border-[#edf1f6] px-6 py-4">
            <button
              type="button"
              class="h-10 rounded-md border border-[#dfe5ef] px-4 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155] disabled:opacity-50"
              :disabled="deactivateModal.loading"
              @click="closeDeactivateModal"
            >
              Abbrechen
            </button>
            <button
              type="button"
              class="inline-flex h-10 items-center gap-2 rounded-md bg-red-600 px-4 text-sm font-semibold text-white transition hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-50"
              :disabled="deactivateModal.loading"
              @click="confirmDeactivate"
            >
              <svg v-if="deactivateModal.loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              Deaktivieren
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AppErrorState from '@/components/common/AppErrorState.vue'
import { useUsersStore } from '@/stores/users.store'
import { useAuthStore } from '@/stores/auth.store'

const router = useRouter()
const usersStore = useUsersStore()
const authStore = useAuthStore()

const users = ref([])
const meta = ref(null)
const currentPage = ref(1)
const uiState = ref('loading') // loading | success | empty | error | forbidden
const errorMessage = ref('')

const filters = ref({
  search: '',
  is_active: '',
  role: '',
})

const deactivateModal = ref({
  open: false,
  user: null,
  loading: false,
  error: '',
})

const hasNextPage = computed(() => {
  if (!meta.value) return false
  return currentPage.value * meta.value.per_page < meta.value.total
})

const totalPages = computed(() => {
  if (!meta.value?.per_page) return 1
  return Math.max(1, Math.ceil(meta.value.total / meta.value.per_page))
})

const visiblePages = computed(() => {
  const pages = []
  const max = Math.min(totalPages.value, 3)
  for (let page = 1; page <= max; page += 1) pages.push(page)
  return pages
})

function initials(user) {
  return ((user.first_name?.[0] ?? '') + (user.last_name?.[0] ?? '')).toUpperCase() || '?'
}

function avatarClass(user) {
  const palette = [
    'bg-[#eef4ff] text-[#1547d1]',
    'bg-green-50 text-green-700',
    'bg-violet-50 text-violet-700',
    'bg-amber-50 text-amber-700',
  ]
  return palette[user.id % palette.length]
}

function canDeactivate(user) {
  return user.is_active && authStore.user?.id !== user.id
}

async function loadUsers() {
  uiState.value = 'loading'
  errorMessage.value = ''
  try {
    const params = {
      page: currentPage.value,
      per_page: 15,
      ...(filters.value.search ? { search: filters.value.search } : {}),
      ...(filters.value.is_active !== '' ? { is_active: filters.value.is_active } : {}),
      ...(filters.value.role ? { role: filters.value.role } : {}),
    }
    await usersStore.fetchInternalUsers(params)
    users.value = usersStore.users
    meta.value = usersStore.usersMeta
    uiState.value = users.value.length === 0 ? 'empty' : 'success'
  } catch (err) {
    if (err?.response?.status === 403 || err?.status === 403) {
      uiState.value = 'forbidden'
      return
    }
    uiState.value = 'error'
    errorMessage.value = err?.data?.message ?? 'Beim Laden der Benutzerliste ist ein Fehler aufgetreten.'
  }
}

let searchTimeout = null
function onFilterChange() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    currentPage.value = 1
    loadUsers()
  }, 300)
}

function resetFilters() {
  filters.value = { search: '', is_active: '', role: '' }
  currentPage.value = 1
  loadUsers()
}

function changePage(page) {
  if (page < 1 || page > totalPages.value || page === currentPage.value) return
  currentPage.value = page
  loadUsers()
}

function openDeactivateModal(user) {
  deactivateModal.value = { open: true, user, loading: false, error: '' }
}

function closeDeactivateModal() {
  if (deactivateModal.value.loading) return
  deactivateModal.value.open = false
}

async function confirmDeactivate() {
  deactivateModal.value.loading = true
  deactivateModal.value.error = ''
  try {
    await usersStore.deactivateInternalUser(deactivateModal.value.user.id)
    deactivateModal.value.open = false
    await loadUsers()
  } catch (err) {
    deactivateModal.value.error = err?.data?.message ?? 'Deaktivierung fehlgeschlagen.'
  } finally {
    deactivateModal.value.loading = false
  }
}

onMounted(loadUsers)
</script>
