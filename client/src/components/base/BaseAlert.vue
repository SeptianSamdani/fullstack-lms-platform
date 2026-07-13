<template>
  <div :class="['flex items-start gap-3 rounded-xl border px-4 py-3 text-sm', styles[variant]]">
    <component :is="icons[variant]" :size="18" class="mt-0.5 shrink-0" />
    <div>
      <p v-if="title" class="font-medium">{{ title }}</p>
      <p :class="title ? 'mt-0.5 opacity-90' : ''"><slot /></p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { CheckCircle2, AlertTriangle, XCircle, Info, type LucideIcon } from 'lucide-vue-next'
import type { PropType } from 'vue'

defineProps({
  variant: { type: String as PropType<'success' | 'warning' | 'danger' | 'info'>, default: 'info' },
  title: { type: String, default: '' },
})

const styles: Record<string, string> = {
  success: 'bg-success-50 border-success-200 text-success-700',
  warning: 'bg-warning-50 border-warning-200 text-warning-700',
  danger: 'bg-danger-50 border-danger-200 text-danger-700',
  info: 'bg-primary-50 border-primary-200 text-primary-700',
}

const icons: Record<string, LucideIcon> = {
  success: CheckCircle2,
  warning: AlertTriangle,
  danger: XCircle,
  info: Info,
}
</script>