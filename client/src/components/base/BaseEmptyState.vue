<template>
  <div class="flex flex-col items-center justify-center text-center py-16 px-6">
    <component :is="icon" v-if="icon" :size="40" class="text-secondary-300 mb-4" />
    <p class="font-heading font-semibold text-secondary-900 mb-1">{{ title }}</p>
    <p class="text-sm text-secondary-500 max-w-sm">{{ description }}</p>
    <div v-if="$slots.action" class="mt-6">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Component, PropType } from 'vue'

defineProps({
  icon: { type: Object as PropType<Component>, default: null },
  title: { type: String, default: 'Belum ada data' },
  description: { type: String, default: '' },
})
</script>