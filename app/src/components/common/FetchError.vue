<template>
  <div class="rounded-xl border border-red-200 bg-red-50 p-6 text-center">
    <FontAwesomeIcon :icon="faCircleExclamation" class="mx-auto mb-3 h-8 w-8 text-red-400" />
    <p class="font-semibold text-red-700">{{ title }}</p>
    <p v-if="message" class="mt-1 text-sm text-red-600">{{ message }}</p>
    <button
      v-if="retryable"
      type="button"
      class="mt-4 rounded-md bg-red-100 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-200"
      @click="emit('retry')"
    >
      Erneut versuchen
    </button>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleExclamation } from '@fortawesome/free-solid-svg-icons'

defineProps({
  title: {
    type: String,
    default: 'Fehler beim Laden.',
  },
  message: {
    type: String,
    default: '',
  },
  retryable: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['retry'])
</script>
