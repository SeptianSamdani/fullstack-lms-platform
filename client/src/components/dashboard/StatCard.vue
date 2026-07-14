<template>
  <BaseCard>
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-secondary-500 mb-1">{{ label }}</p>
        <p class="font-heading text-2xl font-bold text-secondary-900">{{ value }}</p>
      </div>
      <div :class="['w-11 h-11 rounded-xl flex items-center justify-center', iconBg]">
        <component :is="icon" :size="20" :class="iconColor" />
      </div>
    </div>
  </BaseCard>
</template>

<script setup lang="ts">
import { computed, type Component, type PropType } from 'vue'
import BaseCard from '@/components/base/BaseCard.vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  icon: { type: Object as PropType<Component>, required: true },
  variant: { type: String as PropType<'primary' | 'success' | 'warning' | 'danger'>, default: 'primary' },
})

const styles: Record<'primary' | 'success' | 'warning' | 'danger', { bg: string; color: string }> = {
  primary: { bg: 'bg-primary-50', color: 'text-primary-600' },
  success: { bg: 'bg-success-50', color: 'text-success-600' },
  warning: { bg: 'bg-warning-50', color: 'text-warning-600' },
  danger: { bg: 'bg-danger-50', color: 'text-danger-600' },
}

const iconBg = computed(() => styles[props.variant].bg)
const iconColor = computed(() => styles[props.variant].color)
</script>