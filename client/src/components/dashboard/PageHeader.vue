<template>
  <div class="flex items-center gap-3 mb-6">
    <button
      v-if="backTo"
      class="w-9 h-9 rounded-xl border border-secondary-200 bg-white flex items-center justify-center text-secondary-500 hover:bg-secondary-50 transition"
      @click="$router.push(backTo)" aria-label="Kembali"
    >
      <ArrowLeft :size="18" />
    </button>
    <h2 class="font-heading text-xl font-bold text-secondary-900">{{ title }}</h2>
  </div>
</template>

<script setup lang="ts">
import { ArrowLeft } from 'lucide-vue-next'

defineProps<{ title: string; backTo?: string }>()
</script>