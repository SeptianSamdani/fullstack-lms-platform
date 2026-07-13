<template>
  <div class="space-y-4">
    <div v-if="loading" class="text-sm text-secondary-500">Memuat review...</div>
    <BaseEmptyState
      v-else-if="!reviews.length" :icon="MessageSquare"
      title="Belum ada review" description="Jadilah yang pertama memberi review untuk kursus ini."
    />
    <div v-else v-for="review in reviews" :key="review.id" class="border-b border-secondary-100 pb-4 last:border-0">
      <div class="flex items-center gap-3 mb-2">
        <img v-if="review.user.avatar_url" :src="review.user.avatar_url" class="w-9 h-9 rounded-full object-cover" />
        <div v-else class="w-9 h-9 rounded-full bg-secondary-200 flex items-center justify-center text-xs font-semibold text-secondary-600">
          {{ review.user.name.charAt(0) }}
        </div>
        <div>
          <p class="text-sm font-medium text-secondary-900">{{ review.user.name }}</p>
          <div class="flex items-center gap-0.5">
            <Star v-for="i in 5" :key="i" :size="12" :class="i <= review.rating ? 'text-warning-500 fill-warning-500' : 'text-secondary-200'" />
          </div>
        </div>
      </div>
      <p v-if="review.comment" class="text-sm text-secondary-600">{{ review.comment }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Star, MessageSquare } from 'lucide-vue-next'
import BaseEmptyState from '@/components/base/BaseEmptyState.vue'
import type { Review } from '@/types/course'

defineProps<{ reviews: Review[]; loading: boolean }>()
</script>