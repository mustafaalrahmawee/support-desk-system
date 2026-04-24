<template>
  <RouterView v-slot="{ Component }">
    <component :is="currentLayout">
      <component :is="Component" />
    </component>
  </RouterView>
</template>

<script setup>
import { computed } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import PublicLayout from './layouts/PublicLayout.vue'
import AuthenticatedLayout from './layouts/AuthenticatedLayout.vue'

const route = useRoute()

const currentLayout = computed(() => {
  return route.meta.requiresAuth ? AuthenticatedLayout : PublicLayout
})
</script>
