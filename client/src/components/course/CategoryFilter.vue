<template>
  <div class="flex items-center gap-2 overflow-x-auto pb-2 -mx-1 px-1">
    <button :class="chipClass(activeId === null)" @click="$emit('select', null)">
      Semua
    </button>
    <button
      v-for="cat in categories" :key="cat.id"
      :class="chipClass(activeId === cat.id)"
      @click="$emit('select', cat.id)"
    >
      {{ cat.name }}
    </button>
  </div>
</template>

<script setup lang="ts">
import type { Category } from '@/types/course'

defineProps<{ categories: Category[]; activeId: number | null }>()
defineEmits<{ select: [id: number | null] }>()

const chipClass = (active: boolean) => [
  'shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition whitespace-nowrap',
  active
    ? 'bg-primary-600 text-white'
    : 'bg-white border border-secondary-200 text-secondary-600 hover:bg-secondary-50',
]
</script>