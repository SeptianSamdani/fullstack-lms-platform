<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-secondary-700 mb-1.5">
      {{ label }}<span v-if="required" class="text-danger-500"> *</span>
    </label>
    <div class="relative">
      <component :is="icon" v-if="icon" :size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary-400" />
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :class="inputClass"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      />
    </div>
    <p v-if="error" class="mt-1 text-xs text-danger-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-xs text-secondary-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed, type Component, type PropType } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  id: { type: String, default: () => `input-${Math.random().toString(36).slice(2, 9)}` },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: String, default: '' },
  hint: { type: String, default: '' },
  icon: { type: Object as PropType<Component>, default: null },
})

defineEmits<{ 'update:modelValue': [value: string] }>()

const inputClass = computed(() => [
  'w-full rounded-xl border bg-white text-sm text-secondary-900 placeholder:text-secondary-400 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:bg-secondary-50 disabled:cursor-not-allowed py-2.5',
  props.icon ? 'pl-9 pr-4' : 'px-4',
  props.error ? 'border-danger-300' : 'border-secondary-200',
])
</script>