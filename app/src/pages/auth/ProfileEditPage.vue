<template>
  <div class="min-h-screen bg-[#0c0e14] font-[Sora,sans-serif] text-slate-200">

    <!-- Page header -->
    <header class="border-b border-[#1e2130] bg-[#0f1117]">
      <div class="mx-auto max-w-2xl px-6 pb-6 pt-8">
        <p class="mb-2 font-mono text-[.65rem] font-semibold uppercase tracking-[.2em] text-amber-500">SYSTEM · KONTO</p>
        <h1 class="text-2xl font-bold tracking-tight text-slate-100">Profil bearbeiten</h1>
      </div>
    </header>

    <div class="mx-auto max-w-2xl px-6 py-8">
      <div class="rounded-xl border border-[#1e2130] bg-[#0f1117] p-7">

        <!-- Global success -->
        <div
          v-if="uiState === 'success'"
          class="mb-6 flex items-center gap-2.5 rounded-md border border-green-500/25 bg-green-500/8 px-3.5 py-3 text-sm text-green-300"
        >
          <svg class="h-4 w-4 shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          Profil erfolgreich gespeichert.
        </div>

        <!-- Global conflict/error -->
        <div
          v-if="errorMessage"
          class="mb-6 flex items-start gap-2.5 rounded-md border border-red-500/25 bg-red-500/8 px-3.5 py-3 text-sm text-red-300"
          role="alert"
        >
          <svg class="mt-px h-4 w-4 shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          <span>{{ errorMessage }}</span>
        </div>

        <!-- Form -->
        <form class="space-y-5" @submit.prevent="handleSubmit" novalidate>

          <!-- First name -->
          <div>
            <label class="mb-1.5 block text-[.72rem] font-semibold uppercase tracking-[.08em] text-slate-400" for="first_name">
              Vorname
            </label>
            <input
              id="first_name"
              v-model="form.first_name"
              type="text"
              autocomplete="given-name"
              :disabled="isLoading"
              class="w-full rounded-md border bg-[#131620] px-3.5 py-3 text-sm text-slate-200 placeholder-slate-700 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="v$.first_name.$error || serverErrors.first_name
                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                : 'border-[#1e2535] focus:border-amber-500 focus:ring-amber-500/15'"
            />
            <p v-if="v$.first_name.$error" class="mt-1.5 font-mono text-xs text-red-400">Vorname ist erforderlich.</p>
            <p v-else-if="serverErrors.first_name" class="mt-1.5 font-mono text-xs text-red-400">{{ serverErrors.first_name }}</p>
          </div>

          <!-- Last name -->
          <div>
            <label class="mb-1.5 block text-[.72rem] font-semibold uppercase tracking-[.08em] text-slate-400" for="last_name">
              Nachname
            </label>
            <input
              id="last_name"
              v-model="form.last_name"
              type="text"
              autocomplete="family-name"
              :disabled="isLoading"
              class="w-full rounded-md border bg-[#131620] px-3.5 py-3 text-sm text-slate-200 placeholder-slate-700 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="v$.last_name.$error || serverErrors.last_name
                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                : 'border-[#1e2535] focus:border-amber-500 focus:ring-amber-500/15'"
            />
            <p v-if="v$.last_name.$error" class="mt-1.5 font-mono text-xs text-red-400">Nachname ist erforderlich.</p>
            <p v-else-if="serverErrors.last_name" class="mt-1.5 font-mono text-xs text-red-400">{{ serverErrors.last_name }}</p>
          </div>

          <!-- Username -->
          <div>
            <label class="mb-1.5 block text-[.72rem] font-semibold uppercase tracking-[.08em] text-slate-400" for="username">
              Benutzername
            </label>
            <input
              id="username"
              v-model="form.username"
              type="text"
              autocomplete="username"
              :disabled="isLoading"
              class="w-full rounded-md border bg-[#131620] px-3.5 py-3 font-mono text-sm text-slate-200 placeholder-slate-700 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="v$.username.$error || serverErrors.username
                ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                : 'border-[#1e2535] focus:border-amber-500 focus:ring-amber-500/15'"
            />
            <p v-if="v$.username.$error" class="mt-1.5 font-mono text-xs text-red-400">Benutzername ist erforderlich.</p>
            <p v-else-if="serverErrors.username" class="mt-1.5 font-mono text-xs text-red-400">{{ serverErrors.username }}</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-2">
            <button
              type="submit"
              :disabled="isLoading"
              class="flex items-center gap-2 rounded-md bg-amber-500 px-5 py-2.5 text-sm font-semibold text-[#0c0e14] transition hover:bg-amber-400 active:translate-y-px disabled:cursor-not-allowed disabled:opacity-70"
            >
              <svg v-if="isLoading" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
              </svg>
              {{ isLoading ? 'Speichern…' : 'Speichern' }}
            </button>

            <router-link
              to="/profile"
              class="rounded-md border border-[#1e2535] px-5 py-2.5 text-sm text-slate-400 transition hover:border-slate-500 hover:text-slate-300"
            >
              Abbrechen
            </router-link>
          </div>

        </form>
      </div>
    </div>
  </div>
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

// API-Feldfehler (z.B. Konflikt vom Server)
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
