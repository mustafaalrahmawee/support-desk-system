<template>
  <section class="px-6 py-10">
    <div class="mx-auto max-w-5xl">
      <div class="mb-6">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.12em] text-[#1547d1]">SYSTEM · KONTO</p>
        <h1 class="text-3xl font-bold tracking-tight text-[#111827] sm:text-[2.2rem]">Profil bearbeiten</h1>
        <p class="mt-3 max-w-xl text-sm leading-6 text-[#64748b]">
          Aktualisieren Sie Ihre persönlichen Profildaten.
        </p>
      </div>

      <p class="mb-7 flex items-start gap-2 text-sm leading-6 text-[#64748b]">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#64748b]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0ZM9 8a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V8Zm1-3a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" clip-rule="evenodd" />
        </svg>
        Nur freigegebene Profilfelder können geändert werden.
      </p>

      <div
        v-if="uiState === 'success'"
        class="mb-5 flex items-center justify-between gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700"
      >
        <span class="flex items-center gap-2">
          <svg class="h-4 w-4 shrink-0 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
          </svg>
          Profil erfolgreich gespeichert.
        </span>
        <button type="button" class="text-green-700/70 transition hover:text-green-900" @click="uiState = 'initial'">
          <span class="sr-only">Meldung schließen</span>
          ×
        </button>
      </div>

      <div
        v-if="errorMessage"
        class="mb-5 flex items-center justify-between gap-3 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
        role="alert"
      >
        <span class="flex items-center gap-2">
          <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
          </svg>
          {{ errorMessage }}
        </span>
        <button type="button" class="text-red-700/70 transition hover:text-red-900" @click="errorMessage = ''">
          <span class="sr-only">Meldung schließen</span>
          ×
        </button>
      </div>

      <form
        class="rounded-lg border border-[#dfe5ef] bg-white p-6 shadow-sm sm:p-7"
        @submit.prevent="handleSubmit"
        novalidate
      >
        <div class="space-y-5">
          <div>
            <label class="mb-2 block text-sm font-bold text-[#334155]" for="first_name">
              Vorname
            </label>
            <input
              id="first_name"
              v-model="form.first_name"
              type="text"
              autocomplete="given-name"
              :disabled="isLoading"
              class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-[#f1f5f9] disabled:text-[#64748b]"
              :class="v$.first_name.$error || serverErrors.first_name
                ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                : 'border-[#cbd5e1] focus:border-[#1547d1] focus:ring-blue-100'"
            />
            <p v-if="v$.first_name.$error" class="mt-2 text-xs font-semibold text-red-600">Vorname ist erforderlich.</p>
            <p v-else-if="serverErrors.first_name" class="mt-2 text-xs font-semibold text-red-600">{{ serverErrors.first_name }}</p>
          </div>

          <div>
            <label class="mb-2 block text-sm font-bold text-[#334155]" for="last_name">
              Nachname
            </label>
            <input
              id="last_name"
              v-model="form.last_name"
              type="text"
              autocomplete="family-name"
              :disabled="isLoading"
              class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-[#f1f5f9] disabled:text-[#64748b]"
              :class="v$.last_name.$error || serverErrors.last_name
                ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                : 'border-[#cbd5e1] focus:border-[#1547d1] focus:ring-blue-100'"
            />
            <p v-if="v$.last_name.$error" class="mt-2 text-xs font-semibold text-red-600">Nachname ist erforderlich.</p>
            <p v-else-if="serverErrors.last_name" class="mt-2 text-xs font-semibold text-red-600">{{ serverErrors.last_name }}</p>
          </div>

          <div>
            <label class="mb-2 block text-sm font-bold text-[#334155]" for="username">
              Benutzername
            </label>
            <input
              id="username"
              v-model="form.username"
              type="text"
              autocomplete="username"
              :disabled="isLoading"
              class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-[#f1f5f9] disabled:text-[#64748b]"
              :class="v$.username.$error || serverErrors.username
                ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                : 'border-[#cbd5e1] focus:border-[#1547d1] focus:ring-blue-100'"
            />
            <p v-if="v$.username.$error" class="mt-2 text-xs font-semibold text-red-600">Benutzername ist erforderlich.</p>
            <p v-else-if="serverErrors.username" class="mt-2 text-xs font-semibold text-red-600">{{ serverErrors.username }}</p>
          </div>
        </div>

        <div class="mt-7 flex flex-col-reverse gap-3 border-t border-[#edf1f6] pt-5 sm:flex-row sm:justify-end">
          <router-link
            to="/profile"
            class="inline-flex h-11 items-center justify-center rounded-md border border-[#dfe5ef] bg-white px-6 text-sm font-semibold text-[#64748b] transition hover:border-[#b8c7e6] hover:text-[#334155]"
          >
            Abbrechen
          </router-link>

          <button
            type="submit"
            :disabled="isLoading"
            class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#1547d1] px-7 text-sm font-semibold text-white transition hover:bg-[#0f3db7] active:translate-y-px disabled:cursor-not-allowed disabled:bg-[#a9bee9]"
          >
            <svg v-if="isLoading" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v8H4z"/>
            </svg>
            {{ isLoading ? 'Speichern...' : 'Speichern' }}
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { useAuthStore } from '../../stores/auth.store.js'

const authStore = useAuthStore()

const isLoading = ref(false)
const uiState = ref('initial')
const errorMessage = ref('')

const form = reactive({ first_name: '', last_name: '', username: '' })

const rules = {
  first_name: { required },
  last_name:  { required },
  username:   { required },
}

const v$ = useVuelidate(rules, form)

const serverErrors = reactive({ first_name: '', last_name: '', username: '' })

onMounted(() => {
  if (authStore.user) {
    form.first_name = authStore.user.first_name ?? ''
    form.last_name  = authStore.user.last_name  ?? ''
    form.username   = authStore.user.username   ?? ''
  }
})

async function handleSubmit() {
  serverErrors.first_name = ''
  serverErrors.last_name  = ''
  serverErrors.username   = ''
  errorMessage.value = ''

  const valid = await v$.value.$validate()
  if (!valid) return

  isLoading.value = true
  uiState.value   = 'initial'

  try {
    await authStore.updateProfile({
      first_name: form.first_name,
      last_name:  form.last_name,
      username:   form.username,
    })
    uiState.value = 'success'
  } catch (err) {
    const data = err?.data ?? err?.response?._data
    if (err?.response?.status === 422 && data?.errors) {
      Object.entries(data.errors).forEach(([key, msgs]) => {
        if (key in serverErrors) serverErrors[key] = msgs[0]
      })
    } else {
      errorMessage.value = 'Ein Fehler ist aufgetreten. Bitte erneut versuchen.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>
