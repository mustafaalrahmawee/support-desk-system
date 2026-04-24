<template>
  <div class="flex min-h-screen bg-[#f8fafc] font-[Sora,sans-serif] text-[#0f172a]">
    <!-- Left: Brand panel -->
    <aside
      class="relative hidden w-[470px] shrink-0 overflow-hidden bg-[#edf5ff] px-16 py-16 lg:flex lg:flex-col lg:justify-center"
    >
      <div class="pointer-events-none absolute bottom-8 left-8 grid grid-cols-2 gap-4 opacity-45" aria-hidden="true">
        <div class="h-16 w-16 bg-[#dbeafe]"></div>
        <div class="h-16 w-16 bg-[#dbeafe]"></div>
        <div class="h-16 w-16 bg-[#dbeafe]"></div>
        <div class="h-16 w-16 bg-[#dbeafe]"></div>
      </div>

      <div class="relative z-10 max-w-[360px]">
        <div class="mb-10 flex items-center gap-5">
          <svg class="h-14 w-14 shrink-0" viewBox="0 0 44 44" fill="none" aria-hidden="true">
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
          <h1 class="text-[1.7rem] font-bold tracking-tight text-[#0f172a]">Support Desk</h1>
        </div>

        <div class="mb-10 h-px w-28 bg-[#cbd5e1]"></div>

        <ul class="space-y-8">
          <li
            v-for="feat in features"
            :key="feat.title"
            class="flex items-start gap-5"
          >
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white text-[#2563eb] shadow-[0_10px_24px_rgba(15,23,42,0.08)] ring-1 ring-[#dbeafe]">
              <svg v-if="feat.icon === 'ticket'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6.75 17.25 13.5m-10.5 0 6.75-6.75m-9 5.25a2.25 2.25 0 0 0 0 3.182l4.318 4.318a2.25 2.25 0 0 0 3.182 0l7.5-7.5a2.25 2.25 0 0 0 0-3.182L15.182 4.5a2.25 2.25 0 0 0-3.182 0l-7.5 7.5Z" />
              </svg>
              <svg v-else-if="feat.icon === 'users'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
              </svg>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-7.5-6h9A2.25 2.25 0 0 1 18.75 6v4.5c0 5.25-3.75 8.25-6.75 9.75-3-1.5-6.75-4.5-6.75-9.75V6A2.25 2.25 0 0 1 7.5 3.75Z" />
              </svg>
            </span>
            <span>
              <span class="block text-sm font-bold tracking-tight text-[#0f172a]">{{ feat.title }}</span>
              <span class="mt-1 block text-sm leading-6 text-[#475569]">{{ feat.description }}</span>
            </span>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Right: Form panel -->
    <main class="flex flex-1 items-center justify-center px-5 py-8 sm:px-8 lg:bg-[#fbfdff]">
      <div class="w-full max-w-[440px]">
        <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
          <svg class="h-10 w-10 shrink-0" viewBox="0 0 44 44" fill="none" aria-hidden="true">
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
          <span class="text-lg font-bold tracking-tight text-[#0f172a]">Support Desk</span>
        </div>

        <section class="rounded-xl border border-[#dbe3ef] bg-white px-8 py-9 shadow-[0_18px_45px_rgba(15,23,42,0.08)] sm:px-10">

        <!-- Header -->
        <p class="mb-4 text-[.68rem] font-bold uppercase tracking-[.12em] text-[#1d4ed8]">
          ANMELDUNG
        </p>
        <h2 class="mb-3 text-2xl font-bold tracking-tight text-[#111827]">Willkommen zurück</h2>
        <p class="mb-7 max-w-[280px] text-sm leading-6 text-[#475569]">
          Melden Sie sich mit Ihren Zugangsdaten an.
        </p>

        <!-- Global error -->
        <div
          v-if="uiState === 'error'"
          class="mb-5 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
          role="alert"
        >
          <svg class="mt-px h-4 w-4 shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
            <path
              fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          <span>{{ errorMessage }}</span>
        </div>

        <!-- Form -->
        <form class="space-y-5" @submit.prevent="handleSubmit" novalidate>
          <!-- Email -->
          <div>
            <label
              class="mb-1.5 block text-xs font-semibold text-[#334155]"
              for="email"
            >
              E-Mail-Adresse
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              autocomplete="email"
              placeholder="agent@example.com"
              :disabled="uiState === 'loading'"
              class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] placeholder-[#9ca3af] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="
                v$.email.$error
                  ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                  : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
              "
            />
            <p v-if="v$.email.$error" class="mt-1.5 text-xs text-red-600">
              {{
                v$.email.required.$invalid
                  ? 'E-Mail-Adresse ist erforderlich.'
                  : 'Bitte eine gültige E-Mail-Adresse eingeben.'
              }}
            </p>
          </div>

          <!-- Password -->
          <div>
            <label
              class="mb-1.5 block text-xs font-semibold text-[#334155]"
              for="password"
            >
              Passwort
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                placeholder="••••••••"
                :disabled="uiState === 'loading'"
                class="h-11 w-full rounded-md border bg-white pl-4 pr-10 text-sm text-[#111827] placeholder-[#9ca3af] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  v$.password.$error
                    ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                    : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                "
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-[#9ca3af] transition hover:text-[#374151] disabled:opacity-40"
                :disabled="uiState === 'loading'"
                @click="showPassword = !showPassword"
                :aria-label="showPassword ? 'Passwort verstecken' : 'Passwort anzeigen'"
              >
                <svg v-if="showPassword" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path
                    fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"
                  />
                </svg>
                <svg v-else viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                  <path
                    fill-rule="evenodd"
                    d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                    clip-rule="evenodd"
                  />
                  <path
                    d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                  />
                </svg>
              </button>
            </div>
            <p v-if="v$.password.$error" class="mt-1.5 text-xs text-red-600">
              Passwort ist erforderlich.
            </p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="uiState === 'loading'"
            class="mt-1 flex h-11 w-full items-center justify-center gap-2 rounded-md bg-[#1547d1] text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f3db7] active:translate-y-px disabled:cursor-not-allowed disabled:bg-[#b6c7e8]"
          >
            <svg
              v-if="uiState === 'loading'"
              class="h-4 w-4 animate-spin"
              viewBox="0 0 24 24"
              fill="none"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            {{ uiState === 'loading' ? 'Anmelden…' : 'Anmelden' }}
          </button>
        </form>

        <div class="mt-8 h-px bg-[#e5e7eb]"></div>

        <p class="mt-5 text-xs leading-5 text-[#64748b]">
          Probleme mit dem Zugang? Wenden Sie sich an den Administrator.
        </p>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email as emailValidator } from '@vuelidate/validators'
import { useAuthStore } from '../../stores/auth.store.js'

const router = useRouter()
const authStore = useAuthStore()

const uiState = ref('initial')
const errorMessage = ref('')
const showPassword = ref(false)
const features = [
  {
    icon: 'ticket',
    title: 'Ticket-Management',
    description: 'Erfassen, priorisieren und verfolgen Sie Tickets effizient.',
  },
  {
    icon: 'users',
    title: 'Kundenhistorie',
    description: 'Behalten Sie alle Kundeninteraktionen und Historien im Blick.',
  },
  {
    icon: 'shield',
    title: 'Rollenbasierter Zugriff',
    description: 'Gewähren Sie rollenbasierte Zugriffe für mehr Sicherheit und Kontrolle.',
  },
]

const form = reactive({ email: '', password: '' })

const rules = {
  email: { required, email: emailValidator },
  password: { required },
}

const v$ = useVuelidate(rules, form)

async function handleSubmit() {
  const valid = await v$.value.$validate()
  if (!valid) return

  uiState.value = 'loading'
  errorMessage.value = ''
  try {
    await authStore.login({ email: form.email, password: form.password })
    router.push('/')
  } catch (err) {
    uiState.value = 'error'
    if (err?.response?.status === 403) {
      errorMessage.value = 'Ihr Konto ist deaktiviert. Wenden Sie sich an den Administrator.'
    } else if (err?.response?.status === 401) {
      errorMessage.value = 'Ungültige Zugangsdaten. Bitte erneut versuchen.'
    } else {
      errorMessage.value = 'Ein unerwarteter Fehler ist aufgetreten.'
    }
  }
}
</script>
