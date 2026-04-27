<template>
  <div class="flex min-h-screen bg-[#f8fafc] font-[Sora,sans-serif] text-[#0f172a]">
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
          <FontAwesomeIcon :icon="faHeadset" class="h-14 w-14 shrink-0 text-[#1547d1]" />
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
              <FontAwesomeIcon :icon="feat.icon" class="h-5 w-5" />
            </span>
            <span>
              <span class="block text-sm font-bold tracking-tight text-[#0f172a]">{{ feat.title }}</span>
              <span class="mt-1 block text-sm leading-6 text-[#475569]">{{ feat.description }}</span>
            </span>
          </li>
        </ul>
      </div>
    </aside>

    <main class="flex flex-1 items-center justify-center px-5 py-8 sm:px-8 lg:bg-[#fbfdff]">
      <div class="w-full max-w-[440px]">
        <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
          <FontAwesomeIcon :icon="faHeadset" class="h-10 w-10 shrink-0 text-[#1547d1]" />
          <span class="text-lg font-bold tracking-tight text-[#0f172a]">Support Desk</span>
        </div>

        <section class="rounded-xl border border-[#dbe3ef] bg-white px-8 py-9 shadow-[0_18px_45px_rgba(15,23,42,0.08)] sm:px-10">
          <p class="mb-4 text-[.68rem] font-bold uppercase tracking-[.12em] text-[#1d4ed8]">
            ANMELDUNG
          </p>
          <h2 class="mb-3 text-2xl font-bold tracking-tight text-[#111827]">Willkommen zurück</h2>
          <p class="mb-7 max-w-[280px] text-sm leading-6 text-[#475569]">
            Melden Sie sich mit Ihren Zugangsdaten an.
          </p>

          <div
            v-if="globalError"
            class="mb-5 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            role="alert"
          >
            <FontAwesomeIcon :icon="faCircleExclamation" class="mt-px h-4 w-4 shrink-0 text-red-500" />
            <span>{{ globalError }}</span>
          </div>

          <form class="space-y-5" @submit.prevent="handleSubmit" novalidate>
            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="email">
                E-Mail-Adresse
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                placeholder="agent@example.com"
                :disabled="isLoading"
                class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] placeholder-[#9ca3af] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  fieldError('email')
                    ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                    : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                "
                @blur="v$.email.$touch()"
              />
              <p v-if="fieldError('email')" class="mt-1.5 text-xs text-red-600">
                {{ fieldError('email') }}
              </p>
            </div>

            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="password">
                Passwort
              </label>
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  autocomplete="current-password"
                  placeholder="••••••••"
                  :disabled="isLoading"
                  class="h-11 w-full rounded-md border bg-white pl-4 pr-10 text-sm text-[#111827] placeholder-[#9ca3af] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                  :class="
                    fieldError('password')
                      ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                      : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                  "
                  @blur="v$.password.$touch()"
                />
                <button
                  type="button"
                  class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-[#9ca3af] transition hover:text-[#374151] disabled:opacity-40"
                  :disabled="isLoading"
                  :aria-label="showPassword ? 'Passwort verstecken' : 'Passwort anzeigen'"
                  @click="showPassword = !showPassword"
                >
                  <FontAwesomeIcon :icon="showPassword ? faEyeSlash : faEye" class="h-4 w-4" />
                </button>
              </div>
              <p v-if="fieldError('password')" class="mt-1.5 text-xs text-red-600">
                {{ fieldError('password') }}
              </p>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="mt-1 flex h-11 w-full items-center justify-center gap-2 rounded-md bg-[#1547d1] text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f3db7] active:translate-y-px disabled:cursor-not-allowed disabled:bg-[#b6c7e8]"
            >
              <FontAwesomeIcon v-if="isLoading" :icon="faSpinner" spin class="h-4 w-4" />
              {{ isLoading ? 'Anmelden…' : 'Anmelden' }}
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
import { computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import useVuelidate from '@vuelidate/core'
import { email, helpers, required } from '@vuelidate/validators'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faCircleExclamation,
  faEye,
  faEyeSlash,
  faHeadset,
  faShieldHalved,
  faSpinner,
  faTicket,
  faUsers,
} from '@fortawesome/free-solid-svg-icons'
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()
const router = useRouter()

// ***************** initial UI State *******************
const isLoading = ref(false)
const serverErrors = ref({})
const globalError = ref('')
const showPassword = ref(false)

const features = [
  {
    icon: faTicket,
    title: 'Ticket-Management',
    description: 'Erfassen, priorisieren und verfolgen Sie Tickets effizient.',
  },
  {
    icon: faUsers,
    title: 'Kundenhistorie',
    description: 'Behalten Sie alle Kundeninteraktionen und Historien im Blick.',
  },
  {
    icon: faShieldHalved,
    title: 'Rollenbasierter Zugriff',
    description: 'Gewähren Sie rollenbasierte Zugriffe für mehr Sicherheit und Kontrolle.',
  },
]

// ***************** initial State *******************
const form = reactive({
  email: '',
  password: '',
})

// ***************** initial Validation *******************
const rules = computed(() => ({
  email: {
    required: helpers.withMessage('Bitte eine E-Mail-Adresse eingeben.', required),
    email: helpers.withMessage('Bitte eine gültige E-Mail-Adresse eingeben.', email),
  },
  password: {
    required: helpers.withMessage('Bitte ein Passwort eingeben.', required),
  },
}))

const v$ = useVuelidate(rules, form)

// ***************** initial Helpers *******************
const fieldError = (field) => {
  const clientError = v$.value[field].$errors[0]?.$message
  const serverError = serverErrors.value[field]?.[0]
  return clientError || serverError || ''
}

// ***************** Submit *******************
const handleSubmit = async () => {
  serverErrors.value = {}
  globalError.value = ''

  const isValid = await v$.value.$validate()
  if (!isValid) return

  try {
    isLoading.value = true

    await authStore.login({
      email: form.email,
      password: form.password,
    })

    await router.replace({ name: 'profile' })
  } catch (error) {
    if (error?.status === 422) {
      serverErrors.value = error?.data?.errors ?? {}
    } else if (error?.status === 401) {
      globalError.value = error?.data?.message ?? 'Ungültige Zugangsdaten.'
    } else if (error?.status === 403) {
      globalError.value = error?.data?.message ?? 'Ihr Konto ist deaktiviert.'
    } else {
      globalError.value = error?.data?.message ?? 'Ein unerwarteter Fehler ist aufgetreten.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>
