<template>
  <button :type="type" :disabled="disabled || loading" :class="buttonClass">
    <Loader2 v-if="loading" :size="iconSize" class="animate-spin" />
    <component :is="icon" v-else-if="icon" :size="iconSize" />
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed, type Component, type PropType } from 'vue'
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  variant: { type: String, default: 'primary' }, // primary | secondary | outline | ghost | danger
  size: { type: String, default: 'md' },          // sm | md | lg
  type: { type: String as PropType<'button' | 'submit' | 'reset'>, default: 'button' },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
  icon: { type: Object as PropType<Component>, default: null },
})

const variantClasses: Record<string, string> = {
  primary: 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
  secondary: 'bg-secondary-100 text-secondary-700 hover:bg-secondary-200 focus:ring-secondary-400',
  outline: 'border border-secondary-200 text-secondary-700 bg-white hover:bg-secondary-50 focus:ring-secondary-400',
  ghost: 'text-secondary-600 hover:bg-secondary-100 focus:ring-secondary-400',
  danger: 'bg-danger-600 text-white hover:bg-danger-700 focus:ring-danger-500',
}

const sizeClasses: Record<string, string> = {
  sm: 'text-sm px-3 py-1.5',
  md: 'text-sm px-4 py-2.5',
  lg: 'text-base px-5 py-3',
}

const buttonClass = computed(() => [
  'inline-flex items-center justify-center gap-2 rounded-xl font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
  variantClasses[props.variant],
  sizeClasses[props.size],
  props.block ? 'w-full' : '',
])

const iconSize = computed(() => (props.size === 'sm' ? 14 : props.size === 'lg' ? 20 : 16))
</script>