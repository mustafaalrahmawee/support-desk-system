<template>
  <div class="flex min-h-screen bg-[#0c0e14] font-[Sora,sans-serif]">
    <!-- Left: Brand panel -->
    <aside
      class="relative hidden lg:flex w-[380px] shrink-0 flex-col justify-between border-r border-[#1e2130] bg-[#0f1117] px-10 py-12 overflow-hidden"
    >
      <!-- Grid texture -->
      <div
        class="pointer-events-none absolute inset-0"
        style="
          background-image:
            linear-gradient(rgba(245, 158, 11, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(245, 158, 11, 0.04) 1px, transparent 1px);
          background-size: 32px 32px;
        "
        aria-hidden="true"
      ></div>

      <div class="relative z-10">
        <!-- Logo mark -->
        <svg class="mb-8" width="40" height="40" viewBox="0 0 40 40" fill="none">
          <rect x="2" y="2" width="16" height="16" stroke="#f59e0b" stroke-width="2" />
          <rect
            x="22"
            y="2"
            width="16"
            height="16"
            stroke="#f59e0b"
            stroke-width="2"
            opacity="0.5"
          />
          <rect
            x="2"
            y="22"
            width="16"
            height="16"
            stroke="#f59e0b"
            stroke-width="2"
            opacity="0.5"
          />
          <rect x="22" y="22" width="16" height="16" stroke="#f59e0b" stroke-width="2" />
        </svg>

        <h1 class="text-[2.4rem] font-bold leading-[1.1] tracking-tight text-slate-100 mb-2">
          Support<br />Desk
        </h1>
        <p class="mb-8 text-xs uppercase tracking-[.14em] text-slate-500">Internes Agentensystem</p>

        <div class="mb-6 h-0.5 w-10 bg-amber-500"></div>

        <ul class="space-y-3">
          <li
            v-for="feat in features"
            :key="feat"
            class="flex items-center gap-2.5 text-sm font-light text-slate-400"
          >
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
            {{ feat }}
          </li>
        </ul>
      </div>

      <p class="relative z-10 font-mono text-[.65rem] tracking-widest text-slate-700">
        v1.0 · Internes System
      </p>
    </aside>

    <!-- Right: Form panel -->
    <main class="flex flex-1 items-center justify-center px-6 py-10">
      <div class="w-full max-w-[420px]">
        <!-- Header -->
        <p
          class="mb-3 font-mono text-[.65rem] font-semibold uppercase tracking-[.2em] text-amber-500"
        >
          ANMELDUNG
        </p>
        <h2 class="mb-2 text-3xl font-bold tracking-tight text-slate-100">Willkommen zurück</h2>
        <p class="mb-8 text-sm font-light text-slate-500">
          Melden Sie sich mit Ihren Zugangsdaten an.
        </p>

        <!-- Global error -->
        <div
          v-if="uiState === 'error'"
          class="mb-6 flex items-start gap-2.5 rounded-md border border-red-500/25 bg-red-500/8 px-3.5 py-3 text-sm text-red-300"
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
              class="mb-1.5 block text-[.72rem] font-semibold uppercase tracking-[.08em] text-slate-400"
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
              class="w-full rounded-md border bg-[#131620] px-3.5 py-3 font-mono text-sm text-slate-200 placeholder-slate-700 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="
                v$.email.$error
                  ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                  : 'border-[#1e2535] focus:border-amber-500 focus:ring-amber-500/15'
              "
            />
            <p v-if="v$.email.$error" class="mt-1.5 font-mono text-xs text-red-400">
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
              class="mb-1.5 block text-[.72rem] font-semibold uppercase tracking-[.08em] text-slate-400"
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
                class="w-full rounded-md border bg-[#131620] py-3 pl-3.5 pr-10 font-mono text-sm text-slate-200 placeholder-slate-700 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  v$.password.$error
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
                    : 'border-[#1e2535] focus:border-amber-500 focus:ring-amber-500/15'
                "
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-600 transition hover:text-slate-400 disabled:opacity-40"
                :disabled="uiState === 'loading'"
                @click="showPassword = !showPassword"
                :aria-label="showPassword ? 'Passwort verstecken' : 'Passwort anzeigen'"
              >
                <svg v-if="!showPassword" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
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
            <p v-if="v$.password.$error" class="mt-1.5 font-mono text-xs text-red-400">
              Passwort ist erforderlich.
            </p>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="uiState === 'loading'"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-md bg-amber-500 py-3 text-sm font-semibold tracking-wide text-[#0c0e14] transition hover:bg-amber-400 active:translate-y-px disabled:cursor-not-allowed disabled:opacity-70"
          >
            <svg
              v-if="uiState === 'loading'"
              class="h-4 w-4 animate-spin"
              viewBox="0 0 24 24"
              fill="none"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            {{ uiState === 'loading' ? 'Anmelden…' : 'Anmelden' }}
          </button>
        </form>

        <p class="mt-6 text-center text-xs text-slate-700 leading-relaxed">
          Probleme mit dem Zugang? Wenden Sie sich an den Administrator.
        </p>
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
const features = ['Ticket-Management', 'Kundenhistorie', 'Rollenbasierter Zugriff']

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
