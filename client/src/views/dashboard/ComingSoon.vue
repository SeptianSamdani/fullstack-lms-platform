<template>
  <BaseCard class="flex flex-col items-center justify-center text-center py-20">
    <Construction :size="40" class="text-secondary-300 mb-4" />
    <p class="font-heading text-lg font-semibold text-secondary-900 mb-1">Segera hadir</p>
    <p class="text-sm text-secondary-500">Halaman ini sedang dalam pengembangan.</p>
  </BaseCard>
</template>

<script setup lang="ts">
import { Construction } from 'lucide-vue-next'
import BaseCard from '@/components/base/BaseCard.vue'
</script>