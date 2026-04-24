<template>
  <section class="rounded-lg border border-red-200 bg-red-50 px-6 py-10 text-center shadow-sm">
    <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border-2 border-red-500 text-red-500">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-7 w-7" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v6" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01" />
      </svg>
    </div>

    <h2 class="text-base font-bold text-[#111827]">{{ title }}</h2>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#64748b]">{{ description }}</p>

    <button
      v-if="actionLabel"
      type="button"
      class="mt-6 inline-flex items-center justify-center gap-2 rounded-md bg-[#1547d1] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f3db7] active:translate-y-px"
      @click="$emit('retry')"
    >
      <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 1 1-1.108-4.805.75.75 0 0 0 1.061-1.06 7 7 0 1 0 1.407 6.113.75.75 0 0 0-1.36-.248Z" clip-rule="evenodd" />
        <path fill-rule="evenodd" d="M14.25 3.75a.75.75 0 0 1 .75.75V8h-3.5a.75.75 0 0 1 0-1.5h2V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
      </svg>
      {{ actionLabel }}
    </button>
  </section>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  actionLabel: {
    type: String,
    default: '',
  },
})

defineEmits(['retry'])
</script>
