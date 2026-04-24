<template>
  <section class="px-6 py-10">
    <div class="mx-auto max-w-5xl">
      <div class="mb-6">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.12em] text-[#1547d1]">ADMIN · BENUTZER</p>
        <h1 class="text-3xl font-bold tracking-tight text-[#111827] sm:text-[2.2rem]">Benutzer bearbeiten</h1>
        <p class="mt-3 max-w-xl text-sm leading-6 text-[#64748b]">
          Aktualisieren Sie Stammdaten, Rollen und optionale Zugangsdaten.
        </p>
      </div>

      <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <button
          type="button"
          class="inline-flex h-10 items-center justify-center gap-2 rounded-md border border-[#dfe5ef] bg-white px-4 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155]"
          @click="$router.push('/admin/users')"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
          </svg>
          Zurück zur Benutzerliste
        </button>

        <div v-if="targetUser" class="flex items-center gap-2">
          <span class="rounded-md border border-[#dfe5ef] bg-white px-3 py-1.5 text-xs font-bold text-[#64748b]">
            {{ targetUser.username }}
          </span>
          <span
            class="rounded-md px-2.5 py-1 text-xs font-semibold"
            :class="targetUser.is_active
              ? 'border border-green-200 bg-green-50 text-green-700'
              : 'border border-slate-200 bg-slate-100 text-slate-600'"
          >
            {{ targetUser.is_active ? 'Aktiv' : 'Inaktiv' }}
          </span>
        </div>
      </div>

      <AppLoadingState v-if="pageLoading" :rows="8" />

      <AppErrorState
        v-else-if="pageState === 'not-found'"
        title="Benutzer nicht gefunden"
        description="Der angeforderte Benutzer konnte nicht gefunden werden oder wurde entfernt."
        action-label="Zurück zur Liste"
        @retry="$router.push('/admin/users')"
      />

      <AppErrorState
        v-else-if="pageState === 'forbidden'"
        title="Zugriff verweigert"
        description="Sie benötigen Admin-Rechte, um diesen Benutzer zu bearbeiten."
        action-label="Zurück zur Liste"
        @retry="$router.push('/admin/users')"
      />

      <div v-else>
        <div
          v-if="globalError"
          class="mb-5 flex items-center justify-between gap-3 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
          role="alert"
        >
          <span class="flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
            </svg>
            {{ globalError }}
          </span>
          <button type="button" class="text-red-700/70 transition hover:text-red-900" @click="globalError = ''">
            <span class="sr-only">Meldung schließen</span>
            ×
          </button>
        </div>

        <div
          v-if="successMessage"
          class="mb-5 flex items-center justify-between gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700"
        >
          <span class="flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
            </svg>
            {{ successMessage }}
          </span>
          <button type="button" class="text-green-700/70 transition hover:text-green-900" @click="successMessage = ''">
            <span class="sr-only">Meldung schließen</span>
            ×
          </button>
        </div>

        <form
          class="rounded-lg border border-[#dfe5ef] bg-white shadow-sm"
          @submit.prevent="handleSubmit"
          novalidate
        >
          <div class="border-b border-[#edf1f6] p-6 sm:p-7">
            <h2 class="text-sm font-bold text-[#111827]">Stammdaten</h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">Vorname</label>
                <input
                  v-model="form.first_name"
                  type="text"
                  placeholder="Max"
                  :disabled="isLoading"
                  :class="fieldClass('first_name')"
                  @blur="touchField('first_name')"
                />
                <p v-if="fieldError('first_name')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('first_name') }}
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">Nachname</label>
                <input
                  v-model="form.last_name"
                  type="text"
                  placeholder="Mustermann"
                  :disabled="isLoading"
                  :class="fieldClass('last_name')"
                  @blur="touchField('last_name')"
                />
                <p v-if="fieldError('last_name')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('last_name') }}
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">Benutzername</label>
                <input
                  v-model="form.username"
                  type="text"
                  placeholder="max.mustermann"
                  :disabled="isLoading"
                  :class="fieldClass('username')"
                  @blur="touchField('username')"
                />
                <p v-if="fieldError('username')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('username') }}
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">E-Mail</label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="max@example.com"
                  :disabled="isLoading"
                  :class="fieldClass('email')"
                  @blur="touchField('email')"
                />
                <p v-if="fieldError('email')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('email') }}
                </p>
              </div>
            </div>
          </div>

          <div class="border-b border-[#edf1f6] p-6 sm:p-7">
            <h2 class="text-sm font-bold text-[#111827]">Passwort ändern</h2>
            <p class="mt-2 text-xs text-[#64748b]">Nur ausfüllen, wenn das Passwort geändert werden soll.</p>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">Neues Passwort</label>
                <div class="relative">
                  <input
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    placeholder=""
                    :disabled="isLoading"
                    :class="fieldClass('password', true)"
                    @blur="touchField('password')"
                  />
                  <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] transition hover:text-[#334155]"
                    :disabled="isLoading"
                    @click="showPassword = !showPassword"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <path v-if="!showPassword" stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path v-if="!showPassword" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      <path v-else stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65" />
                    </svg>
                  </button>
                </div>
                <p v-if="fieldError('password')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('password') }}
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#334155]">Passwort bestätigen</label>
                <div class="relative">
                  <input
                    v-model="form.password_confirmation"
                    :type="showPasswordConfirm ? 'text' : 'password'"
                    placeholder=""
                    :disabled="isLoading"
                    :class="fieldClass('password_confirmation', true)"
                    @blur="touchField('password_confirmation')"
                  />
                  <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] transition hover:text-[#334155]"
                    :disabled="isLoading"
                    @click="showPasswordConfirm = !showPasswordConfirm"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                      <path v-if="!showPasswordConfirm" stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path v-if="!showPasswordConfirm" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      <path v-else stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65" />
                    </svg>
                  </button>
                </div>
                <p v-if="fieldError('password_confirmation')" class="mt-2 text-xs font-semibold text-red-600">
                  {{ fieldError('password_confirmation') }}
                </p>
              </div>
            </div>
          </div>

          <div class="border-b border-[#edf1f6] p-6 sm:p-7">
            <h2 class="text-sm font-bold text-[#111827]">Rollen</h2>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
              <label
                v-for="role in availableRoles"
                :key="role.value"
                :class="[
                  'flex h-11 cursor-pointer items-center gap-3 rounded-md border px-3 text-sm font-semibold transition',
                  form.roles.includes(role.value)
                    ? 'border-[#1547d1] bg-[#eef4ff] text-[#1547d1]'
                    : 'border-[#dfe5ef] bg-white text-[#334155] hover:border-[#b8c7e6]'
                ]"
              >
                <span
                  :class="[
                    'flex h-4 w-4 shrink-0 items-center justify-center rounded border',
                    form.roles.includes(role.value)
                      ? 'border-[#1547d1] bg-[#1547d1]'
                      : 'border-[#cbd5e1] bg-white'
                  ]"
                >
                  <svg v-if="form.roles.includes(role.value)" class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                  </svg>
                </span>
                <input type="checkbox" :value="role.value" v-model="form.roles" class="sr-only" :disabled="isLoading" />
                {{ role.value }}
              </label>
            </div>
            <p v-if="fieldError('roles')" class="mt-2 text-xs font-semibold text-red-600">
              {{ fieldError('roles') }}
            </p>
          </div>

          <div class="border-b border-[#edf1f6] p-6 sm:p-7">
            <h2 class="text-sm font-bold text-[#111827]">Status</h2>
            <div class="mt-4 flex flex-wrap items-center gap-4">
              <span
                class="rounded-md px-2.5 py-1 text-xs font-semibold"
                :class="targetUser?.is_active
                  ? 'border border-green-200 bg-green-50 text-green-700'
                  : 'border border-slate-200 bg-slate-100 text-slate-600'"
              >
                {{ targetUser?.is_active ? 'Aktiv' : 'Inaktiv' }}
              </span>
              <p class="text-sm text-[#64748b]">Der Aktivstatus wird nicht in diesem Formular geändert.</p>
            </div>
          </div>

          <div class="flex flex-col-reverse gap-3 p-6 sm:flex-row sm:items-center sm:justify-end sm:p-7">
            <button
              type="button"
              class="inline-flex h-11 items-center justify-center rounded-md border border-[#dfe5ef] bg-white px-6 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155] disabled:cursor-not-allowed disabled:opacity-50"
              :disabled="isLoading"
              @click="$router.push('/admin/users')"
            >
              Abbrechen
            </button>
            <button
              type="submit"
              class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#1547d1] px-7 text-sm font-semibold text-white transition hover:bg-[#0f3db7] active:translate-y-px disabled:cursor-not-allowed disabled:bg-[#a9bee9]"
              :disabled="isLoading"
            >
              <svg v-if="isLoading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              {{ isLoading ? 'Speichern...' : 'Speichern' }}
            </button>
          </div>
        </form>

        <p class="mt-6 text-sm text-[#64748b]">Deaktivierung erfolgt über separate Aktion.</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { minLength, sameAs, helpers } from '@vuelidate/validators'
import AppErrorState from '@/components/common/AppErrorState.vue'
import AppLoadingState from '@/components/common/AppLoadingState.vue'
import { useUsersStore } from '@/stores/users.store'

const router = useRouter()
const route = useRoute()
const usersStore = useUsersStore()

const userId = Number(route.params.id)

const pageLoading = ref(true)
const pageState = ref('initial')
const isLoading = ref(false)
const globalError = ref('')
const successMessage = ref('')
const serverErrors = ref({})
const targetUser = ref(null)
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

const availableRoles = ref([
  { value: 'admin' },
  { value: 'support_agent' },
  { value: 'inbound_reviewer' },
  { value: 'contract_manager' },
])

const form = reactive({
  first_name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [],
})

const rules = computed(() => ({
  password: form.password
    ? { minLength: helpers.withMessage('Das Passwort muss mindestens 8 Zeichen lang sein.', minLength(8)) }
    : {},
  password_confirmation: form.password
    ? { sameAs: helpers.withMessage('Die Passwörter stimmen nicht überein.', sameAs(() => form.password)) }
    : {},
}))

const v$ = useVuelidate(rules, form)

function fieldClass(field, hasRightIcon = false) {
  const hasError = v$.value[field]?.$error || serverErrors.value[field]
  return [
    'h-11 w-full rounded-md border bg-white text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-[#f1f5f9] disabled:text-[#64748b]',
    hasRightIcon ? 'pl-4 pr-10' : 'px-4',
    hasError
      ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
      : 'border-[#cbd5e1] focus:border-[#1547d1] focus:ring-blue-100',
  ]
}

function fieldError(field) {
  if (serverErrors.value[field]) return serverErrors.value[field][0]
  if (v$.value[field]?.$error) return v$.value[field].$errors[0]?.$message
  return null
}

function touchField(field) {
  v$.value[field]?.$touch()
}

async function handleSubmit() {
  serverErrors.value = {}
  globalError.value = ''
  successMessage.value = ''
  const valid = await v$.value.$validate()
  if (!valid) return

  const payload = {}
  if (form.first_name !== targetUser.value?.first_name) payload.first_name = form.first_name
  if (form.last_name !== targetUser.value?.last_name) payload.last_name = form.last_name
  if (form.username !== targetUser.value?.username) payload.username = form.username
  if (form.email !== targetUser.value?.email) payload.email = form.email
  if (form.password) {
    payload.password = form.password
    payload.password_confirmation = form.password_confirmation
  }
  const currentRoles = [...(targetUser.value?.roles ?? [])].sort().join(',')
  const newRoles = [...form.roles].sort().join(',')
  if (currentRoles !== newRoles) payload.roles = form.roles

  if (!Object.keys(payload).length) {
    successMessage.value = 'Keine Änderungen vorgenommen.'
    return
  }

  isLoading.value = true
  try {
    const result = await usersStore.updateInternalUser(userId, payload)
    targetUser.value = result.data
    form.password = ''
    form.password_confirmation = ''
    successMessage.value = 'Interner Benutzer erfolgreich aktualisiert.'
  } catch (err) {
    if (err?.response?.status === 422 && err?.data?.errors) {
      serverErrors.value = err.data.errors
      globalError.value = 'Benutzername oder E-Mail ist bereits vergeben.'
    } else {
      globalError.value = err?.data?.message ?? 'Fehler beim Aktualisieren des Benutzers.'
    }
  } finally {
    isLoading.value = false
  }
}

onMounted(async () => {
  try {
    await usersStore.fetchRoles()
    if (usersStore.roles.length) {
      availableRoles.value = usersStore.roles.map((role) => ({ value: role.name }))
    }
    const user = await usersStore.fetchInternalUser(userId)
    if (!user) {
      pageState.value = 'not-found'
      return
    }
    targetUser.value = user
    form.first_name = user.first_name ?? ''
    form.last_name = user.last_name ?? ''
    form.username = user.username ?? ''
    form.email = user.email ?? ''
    form.roles = Array.isArray(user.roles) ? [...user.roles] : []
  } catch (err) {
    if (err?.response?.status === 403 || err?.status === 403) {
      pageState.value = 'forbidden'
    } else if (err?.response?.status === 404 || err?.status === 404) {
      pageState.value = 'not-found'
    } else {
      globalError.value = 'Fehler beim Laden des Benutzers.'
    }
  } finally {
    pageLoading.value = false
  }
})
</script>
