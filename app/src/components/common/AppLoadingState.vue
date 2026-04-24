<template>
  <div class="rounded-lg border border-[#dfe5ef] bg-white p-6 shadow-sm">
    <div class="flex items-center gap-5">
      <div class="h-16 w-16 shrink-0 animate-pulse rounded-full bg-[#e6ebf2]"></div>
      <div class="min-w-0 flex-1 space-y-3">
        <div class="h-4 w-44 animate-pulse rounded bg-[#e6ebf2]"></div>
        <div class="h-3.5 w-56 animate-pulse rounded bg-[#eef2f7]"></div>
      </div>
      <div class="hidden h-10 w-40 animate-pulse rounded bg-[#e6ebf2] sm:block"></div>
    </div>

    <div class="mt-7 border-t border-[#edf1f6] pt-4">
      <div
        v-for="row in rows"
        :key="row"
        class="grid grid-cols-[minmax(8rem,14rem)_1fr] gap-5 border-b border-[#edf1f6] py-3.5 last:border-b-0"
      >
        <div class="h-3 w-24 animate-pulse rounded bg-[#e6ebf2]"></div>
        <div class="h-3 w-40 animate-pulse rounded bg-[#eef2f7]"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  rows: {
    type: Number,
    default: 6,
  },
})
</script>
