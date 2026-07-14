<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-secondary-700 mb-1.5">
      {{ label }}<span v-if="required" class="text-danger-500"> *</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="selectClass"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
    >
      <option value="" disabled>{{ placeholder }}</option>
      <option v-for="opt in options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
    </select>
    <p v-if="error" class="mt-1 text-xs text-danger-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Option {
  label: string
  value: string | number
}

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  id: { type: String, default: () => `select-${Math.random().toString(36).slice(2, 9)}` },
  label: { type: String, default: '' },
  placeholder: { type: String, default: 'Pilih...' },
  options: { type: Array as () => Option[], default: () => [] },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: String, default: '' },
})

defineEmits<{ 'update:modelValue': [value: string] }>()

const selectClass = computed(() => [
  'w-full rounded-xl border bg-white text-sm text-secondary-900 px-4 py-2.5 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:bg-secondary-50 disabled:cursor-not-allowed',
  props.error ? 'border-danger-300' : 'border-secondary-200',
])
</script>