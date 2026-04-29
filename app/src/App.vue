<template>
  <RouterView v-slot="{ Component }">
    <component :is="true">
      <component :is="Component" />
    </component>
  </RouterView>
</template>

<script setup>
</script>
