<template>
  <div :class="['bg-white rounded-2xl border border-secondary-200', shadow ? 'shadow-sm' : '', paddingClass]">
    <div v-if="$slots.header" class="mb-4 flex items-center justify-between">
      <slot name="header" />
    </div>
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue'

const props = defineProps({
  padding: { type: String as PropType<'none' | 'sm' | 'md' | 'lg'>, default: 'md' },
  shadow: { type: Boolean, default: true },
})

const paddingMap: Record<string, string> = { none: '', sm: 'p-4', md: 'p-6', lg: 'p-8' }
const paddingClass = computed(() => paddingMap[props.padding])
</script>