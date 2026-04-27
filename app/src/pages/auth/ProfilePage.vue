<template>
  <div class="mx-auto max-w-3xl space-y-6 py-6 px-4 sm:px-6">

    <!-- Header -->
    <div>
      <h1 class="text-xl font-bold tracking-tight text-[#0f172a]">Mein Profil</h1>
      <p class="mt-1 text-sm text-[#64748b]">Eigene Profilinformationen anzeigen und bearbeiten.</p>
    </div>

    <!-- Loading Skeleton -->
    <ProfileSkeleton v-if="isLoadingProfile" />

    <!-- Error State -->
    <FetchError
      v-else-if="loadError"
      title="Profil konnte nicht geladen werden."
      :message="loadError"
      @retry="loadProfile"
    />

    <!-- Empty State -->
    <div
      v-else-if="!authStore.user"
      class="rounded-xl border border-[#e5eaf3] bg-white p-10 text-center"
    >
      <FontAwesomeIcon :icon="faUser" class="mx-auto mb-3 h-8 w-8 text-[#94a3b8]" />
      <p class="font-semibold text-[#475569]">Keine Profildaten verfügbar.</p>
      <p class="mt-1 text-sm text-[#94a3b8]">Bitte laden Sie die Seite neu oder melden Sie sich erneut an.</p>
      <button
        type="button"
        class="mt-4 rounded-md bg-[#f1f5f9] px-4 py-2 text-sm font-medium text-[#475569] transition hover:bg-[#e2e8f0]"
        @click="loadProfile"
      >
        Erneut versuchen
      </button>
    </div>

    <template v-else>
      <!-- Profile Header Card -->
      <div class="rounded-xl border border-[#e5eaf3] bg-white p-6 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-[#d9e6ff] text-xl font-bold text-[#1d4ed8]">
            {{ initials }}
          </div>
          <div>
            <p class="text-lg font-bold text-[#0f172a]">{{ authStore.user.first_name }} {{ authStore.user.last_name }}</p>
            <p class="text-sm text-[#64748b]">@{{ authStore.user.username }}</p>
            <span
              class="mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
              :class="authStore.user.is_active ? 'bg-[#dcfce7] text-[#15803d]' : 'bg-[#fee2e2] text-[#b91c1c]'"
            >
              <span
                class="h-1.5 w-1.5 rounded-full"
                :class="authStore.user.is_active ? 'bg-[#22c55e]' : 'bg-[#ef4444]'"
              ></span>
              {{ authStore.user.is_active ? 'Aktiv' : 'Inaktiv' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Edit Form Card -->
      <div class="rounded-xl border border-[#e5eaf3] bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-sm font-semibold text-[#0f172a]">Profil bearbeiten</h2>

        <!-- Success Banner -->
        <div
          v-if="saveSuccess"
          class="mb-5 flex items-center gap-2.5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
          role="status"
        >
          <FontAwesomeIcon :icon="faCircleCheck" class="h-4 w-4 shrink-0 text-green-500" />
          <span>Profil erfolgreich gespeichert.</span>
        </div>

        <!-- Save Error Banner -->
        <div
          v-if="saveError"
          class="mb-5 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
          role="alert"
        >
          <FontAwesomeIcon :icon="faCircleExclamation" class="mt-px h-4 w-4 shrink-0 text-red-500" />
          <span>{{ saveError }}</span>
        </div>

        <form class="space-y-5" @submit.prevent="handleSave" novalidate>
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- first_name -->
            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="first_name">
                Vorname
              </label>
              <input
                id="first_name"
                v-model="form.first_name"
                type="text"
                autocomplete="given-name"
                :disabled="isSaving"
                class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  fieldError('first_name')
                    ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                    : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                "
                @blur="v$.first_name.$touch()"
              />
              <p v-if="fieldError('first_name')" class="mt-1.5 text-xs text-red-600">
                {{ fieldError('first_name') }}
              </p>
            </div>

            <!-- last_name -->
            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="last_name">
                Nachname
              </label>
              <input
                id="last_name"
                v-model="form.last_name"
                type="text"
                autocomplete="family-name"
                :disabled="isSaving"
                class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  fieldError('last_name')
                    ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                    : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                "
                @blur="v$.last_name.$touch()"
              />
              <p v-if="fieldError('last_name')" class="mt-1.5 text-xs text-red-600">
                {{ fieldError('last_name') }}
              </p>
            </div>

            <!-- username -->
            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="username">
                Benutzername
              </label>
              <input
                id="username"
                v-model="form.username"
                type="text"
                autocomplete="username"
                :disabled="isSaving"
                class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
                :class="
                  fieldError('username')
                    ? 'border-red-400 focus:border-red-400 focus:ring-red-100'
                    : 'border-[#cbd5e1] focus:border-[#2563eb] focus:ring-blue-100'
                "
                @blur="v$.username.$touch()"
              />
              <p v-if="fieldError('username')" class="mt-1.5 text-xs text-red-600">
                {{ fieldError('username') }}
              </p>
            </div>

            <!-- email -->
            <div>
              <label class="mb-1.5 block text-xs font-semibold text-[#334155]" for="profile_email">
                E-Mail-Adresse
              </label>
              <input
                id="profile_email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                :disabled="isSaving"
                class="h-11 w-full rounded-md border bg-white px-4 text-sm text-[#111827] outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:opacity-50"
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
          </div>

          <div class="flex justify-end pt-2">
            <button
              type="submit"
              :disabled="isSaving"
              class="flex h-11 items-center gap-2 rounded-md bg-[#1547d1] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f3db7] active:translate-y-px disabled:cursor-not-allowed disabled:bg-[#b6c7e8]"
            >
              <FontAwesomeIcon v-if="isSaving" :icon="faSpinner" spin class="h-4 w-4" />
              {{ isSaving ? 'Speichern…' : 'Profil speichern' }}
            </button>
          </div>
        </form>
      </div>
    </template>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import useVuelidate from '@vuelidate/core'
import { email, helpers, maxLength, required } from '@vuelidate/validators'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleCheck, faCircleExclamation, faSpinner, faUser } from '@fortawesome/free-solid-svg-icons'
import { useAuthStore } from '@/stores/auth.store'
import FetchError from '@/components/common/FetchError.vue'
import ProfileSkeleton from '@/components/common/ProfileSkeleton.vue'

const authStore = useAuthStore()
const router = useRouter()

// ***************** UI State *******************
const isLoadingProfile = ref(false)
const loadError = ref('')
const isSaving = ref(false)
const saveError = ref('')
const saveSuccess = ref(false)
const serverErrors = ref({})

// ***************** Form State *******************
const form = reactive({
  first_name: '',
  last_name: '',
  username: '',
  email: '',
})

// ***************** Computed *******************
const initials = computed(() => {
  const u = authStore.user
  if (!u) return ''
  return `${u.first_name?.[0] ?? ''}${u.last_name?.[0] ?? ''}`.toUpperCase()
})

// ***************** Validation *******************
const rules = computed(() => ({
  first_name: {
    required: helpers.withMessage('Bitte einen Vornamen eingeben.', required),
    maxLength: helpers.withMessage('Maximal 255 Zeichen.', maxLength(255)),
  },
  last_name: {
    required: helpers.withMessage('Bitte einen Nachnamen eingeben.', required),
    maxLength: helpers.withMessage('Maximal 255 Zeichen.', maxLength(255)),
  },
  username: {
    required: helpers.withMessage('Bitte einen Benutzernamen eingeben.', required),
    maxLength: helpers.withMessage('Maximal 255 Zeichen.', maxLength(255)),
  },
  email: {
    required: helpers.withMessage('Bitte eine E-Mail-Adresse eingeben.', required),
    email: helpers.withMessage('Bitte eine gültige E-Mail-Adresse eingeben.', email),
    maxLength: helpers.withMessage('Maximal 255 Zeichen.', maxLength(255)),
  },
}))

const v$ = useVuelidate(rules, form)

const fieldError = (field) => {
  const clientError = v$.value[field].$errors[0]?.$message
  const serverError = serverErrors.value[field]?.[0]
  return clientError || serverError || ''
}

// ***************** Data Loading *******************
const fillForm = (user) => {
  form.first_name = user.first_name ?? ''
  form.last_name = user.last_name ?? ''
  form.username = user.username ?? ''
  form.email = user.email ?? ''
}

const loadProfile = async () => {
  isLoadingProfile.value = true
  loadError.value = ''

  try {
    const user = await authStore.fetchMe()
    fillForm(user)
  } catch (error) {
    if (error?.status === 401) {
      authStore.clearAuth()
      await router.replace({ name: 'login' })
      return
    }
    loadError.value = error?.data?.message ?? 'Profil konnte nicht geladen werden.'
  } finally {
    isLoadingProfile.value = false
  }
}

onMounted(() => {
  if (authStore.user) {
    fillForm(authStore.user)
  }
  loadProfile()
})

// ***************** Save *******************
const handleSave = async () => {
  serverErrors.value = {}
  saveError.value = ''
  saveSuccess.value = false

  const isValid = await v$.value.$validate()
  if (!isValid) return

  try {
    isSaving.value = true

    await authStore.updateProfile({
      first_name: form.first_name,
      last_name: form.last_name,
      username: form.username,
      email: form.email,
    })

    saveSuccess.value = true
  } catch (error) {
    if (error?.status === 422) {
      serverErrors.value = error?.data?.errors ?? {}
      saveError.value = error?.data?.message ?? 'Bitte die Fehlermeldungen korrigieren.'
    } else if (error?.status === 409) {
      saveError.value = error?.data?.message ?? 'Ein Konflikt ist aufgetreten. Bitte prüfen Sie E-Mail und Benutzername.'
    } else if (error?.status === 401) {
      authStore.clearAuth()
      await router.replace({ name: 'login' })
      return
    } else {
      saveError.value = error?.data?.message ?? 'Ein unerwarteter Fehler ist aufgetreten.'
    }
  } finally {
    isSaving.value = false
  }
}
</script>
