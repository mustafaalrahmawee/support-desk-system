<template>
  <div class="min-h-screen bg-[#f6f8fc] text-[#0f172a]">
    <div class="flex min-h-screen">
      <aside class="hidden w-[264px] shrink-0 border-r border-[#e5eaf3] bg-white xl:flex xl:flex-col">
        <div class="border-b border-[#eef2f7] px-5 py-5">
          <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#eff5ff] text-[#2563eb]">
              <FontAwesomeIcon :icon="faHeadset" class="h-5 w-5" />
            </div>

            <div>
              <p class="text-[1.05rem] font-bold tracking-tight text-[#172033]">Smart Support</p>
              <p class="text-sm text-[#64748b]">Desk System</p>
            </div>
          </div>
        </div>

        <div class="flex flex-1 flex-col overflow-y-auto px-4 py-6">
          <nav class="space-y-1.5">
            <a
              v-for="item in primaryNavItems"
              :key="item.label"
              href="#"
              class="flex items-center justify-between rounded-xl px-3 py-3 text-sm font-medium transition"
              :class="
                item.active
                  ? 'bg-[#edf4ff] text-[#2563eb]'
                  : 'text-[#334155] hover:bg-[#f8fafc] hover:text-[#0f172a]'
              "
            >
              <span class="flex items-center gap-3">
                <FontAwesomeIcon :icon="item.icon" class="h-4 w-4" />
                <span>{{ item.label }}</span>
              </span>

              <span
                v-if="item.badge"
                class="rounded-full bg-[#2563eb] px-1.5 py-0.5 text-[0.65rem] font-semibold text-white"
              >
                {{ item.badge }}
              </span>
            </a>
          </nav>

          <section class="mt-8 border-t border-[#eef2f7] pt-6">
            <p class="mb-4 text-sm font-semibold text-[#475569]">Quick Filters</p>

            <div class="space-y-1.5">
              <a
                v-for="filter in quickFilters"
                :key="filter.label"
                href="#"
                class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm text-[#334155] transition hover:bg-[#f8fafc] hover:text-[#0f172a]"
              >
                <span>{{ filter.label }}</span>
                <span
                  class="min-w-6 rounded-full px-1.5 py-0.5 text-center text-xs font-semibold"
                  :class="filter.highlight ? 'bg-[#eff5ff] text-[#2563eb]' : 'text-[#64748b]'"
                >
                  {{ filter.count }}
                </span>
              </a>
            </div>

            <a
              href="#"
              class="mt-3 flex items-center justify-between rounded-lg px-3 py-2.5 text-sm text-[#475569] transition hover:bg-[#f8fafc] hover:text-[#0f172a]"
            >
              <span>View all filters</span>
              <FontAwesomeIcon :icon="faChevronRight" class="h-3 w-3" />
            </a>
          </section>
        </div>

        <div class="border-t border-[#eef2f7] px-5 py-4 text-sm text-[#64748b]">
          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-[#65c466]"></span>
            <span>v2.4.1</span>
            <span>&middot;</span>
            <span>&copy; 2024 SSD</span>
          </div>
        </div>
      </aside>

      <div class="flex min-w-0 flex-1 flex-col">
        <header class="border-b border-[#e5eaf3] bg-white">
          <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 xl:px-8">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
              <div class="flex items-center gap-2 overflow-x-auto text-sm whitespace-nowrap text-[#64748b]">
                <button
                  type="button"
                  class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#dbe3ef] text-[#334155] transition hover:bg-[#f8fafc] xl:hidden"
                  aria-label="Navigation anzeigen"
                >
                  <FontAwesomeIcon :icon="faBars" class="h-4 w-4" />
                </button>

                <span class="font-semibold text-[#0f172a]">{{ pageTitle }}</span>
              </div>

              <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-end">
                <div class="flex flex-wrap items-center gap-3">
                  <button
                    type="button"
                    class="relative inline-flex h-11 w-11 items-center justify-center rounded-xl border border-transparent text-[#475569] transition hover:bg-[#f8fafc]"
                    aria-label="Benachrichtigungen"
                  >
                    <FontAwesomeIcon :icon="faBell" class="h-4 w-4" />
                  </button>

                  <RouterLink
                    :to="{ name: 'profile' }"
                    class="inline-flex items-center gap-3 rounded-xl border border-transparent px-2 py-1.5 transition hover:bg-[#f8fafc]"
                  >
                    <div class="relative">
                      <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#d9e6ff] text-sm font-bold text-[#1d4ed8]"
                      >
                        {{ userInitials }}
                      </div>
                      <span
                        class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-[#41b55e]"
                      ></span>
                    </div>

                    <span class="hidden text-left sm:block">
                      <span class="block text-sm font-semibold text-[#0f172a]">{{ userName }}</span>
                      <span class="block text-sm text-[#64748b]">{{ authStore.user?.email }}</span>
                    </span>
                  </RouterLink>

                  <button
                    type="button"
                    :disabled="isLoggingOut"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-transparent text-[#475569] transition hover:bg-[#fee2e2] hover:text-[#b91c1c] disabled:opacity-40"
                    aria-label="Abmelden"
                    @click="handleLogout"
                  >
                    <FontAwesomeIcon v-if="isLoggingOut" :icon="faSpinner" spin class="h-4 w-4" />
                    <FontAwesomeIcon v-else :icon="faArrowRightFromBracket" class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </header>

        <main class="min-h-0 flex-1 overflow-auto px-4 py-4 sm:px-6 xl:px-8 xl:py-6">
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faAddressBook,
  faArrowRightFromBracket,
  faBars,
  faBell,
  faChevronRight,
  faFileCircleCheck,
  faHeadset,
  faInbox,
  faMessage,
  faShieldHalved,
  faSpinner,
  faTableCellsLarge,
  faTicket,
  faUsers,
} from '@fortawesome/free-solid-svg-icons'
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const pageTitle = computed(() => route.meta?.title ?? route.name ?? '')

const isLoggingOut = ref(false)

const userInitials = computed(() => {
  const u = authStore.user
  if (!u) return ''
  return `${u.first_name?.[0] ?? ''}${u.last_name?.[0] ?? ''}`.toUpperCase()
})

const userName = computed(() => {
  const u = authStore.user
  if (!u) return ''
  return `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim()
})

const handleLogout = async () => {
  isLoggingOut.value = true
  try {
    await authStore.logout()
    await router.replace({ name: 'login' })
  } finally {
    isLoggingOut.value = false
  }
}

const primaryNavItems = [
  {
    label: 'Tickets',
    icon: faTicket,
    active: true,
  },
  {
    label: 'Inbound Review Cases',
    icon: faInbox,
    badge: 12,
    active: false,
  },
  {
    label: 'Customers',
    icon: faUsers,
    active: false,
  },
  {
    label: 'Contacts',
    icon: faAddressBook,
    active: false,
  },
  {
    label: 'Contracts',
    icon: faFileCircleCheck,
    active: false,
  },
  {
    label: 'Categories',
    icon: faTableCellsLarge,
    active: false,
  },
  {
    label: 'Messages',
    icon: faMessage,
    active: false,
  },
  {
    label: 'Audit Logs',
    icon: faShieldHalved,
    active: false,
  },
]

const quickFilters = [
  { label: 'My Open Tickets', count: 8, highlight: false },
  { label: 'Unassigned', count: 14, highlight: false },
  { label: 'Waiting for Customer', count: 21, highlight: false },
  { label: 'SLA Breaches', count: 3, highlight: true },
]
</script>
