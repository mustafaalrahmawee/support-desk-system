<template>
  <component :is="currentLayout" />
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import PublicLayout from './layouts/PublicLayout.vue'
import AuthenticatedLayout from './layouts/AuthenticatedLayout.vue'

const route = useRoute()

const currentLayout = computed(() => {
  return route.meta.requiresAuth ? AuthenticatedLayout : PublicLayout
})
</script>
