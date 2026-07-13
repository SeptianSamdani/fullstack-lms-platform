<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-secondary-900/50" @click="close" />
        <div class="relative bg-white rounded-2xl shadow-md w-full max-w-md p-6" role="dialog" aria-modal="true">
          <div class="flex items-start justify-between mb-4">
            <h2 class="font-heading text-lg font-semibold text-secondary-900">{{ title }}</h2>
            <button @click="close" class="text-secondary-400 hover:text-secondary-600 transition" aria-label="Tutup">
              <X :size="20" />
            </button>
          </div>
          <slot />
          <div v-if="$slots.footer" class="mt-6 flex justify-end gap-3">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { X } from 'lucide-vue-next'

defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: '' },
})

const emit = defineEmits<{ 'update:modelValue': [value: boolean] }>()
const close = () => emit('update:modelValue', false)
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 200ms ease-in-out; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>