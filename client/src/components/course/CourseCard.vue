<template>
  <RouterLink :to="`/courses/${course.id}`" class="group block">
    <BaseCard padding="none" class="overflow-hidden hover:shadow-md transition">
      <div class="aspect-video bg-secondary-100 overflow-hidden">
        <img
          v-if="course.thumbnail_url"
          :src="course.thumbnail_url"
          :alt="course.title"
          class="w-full h-full object-cover group-hover:scale-105 transition duration-200"
        />
        <div v-else class="w-full h-full flex items-center justify-center text-secondary-300">
          <BookOpen :size="32" />
        </div>
      </div>

      <div class="p-4">
        <div class="flex items-center justify-between mb-2">
          <span v-if="course.category" class="text-xs font-medium text-primary-700 bg-primary-50 px-2 py-1 rounded-lg">
            {{ course.category.name }}
          </span>
          <span
            :class="[
              'text-xs font-semibold px-2 py-1 rounded-lg',
              course.type === 'free' ? 'text-success-700 bg-success-50' : 'text-accent-700 bg-accent-50',
            ]"
          >
            {{ course.type === 'free' ? 'Gratis' : formatPrice(course.price) }}
          </span>
        </div>

        <h3 class="font-heading font-semibold text-secondary-900 mb-1 line-clamp-2">
          {{ course.title }}
        </h3>
        <p v-if="course.instructor" class="text-sm text-secondary-500 mb-3">
          {{ course.instructor.name }}
        </p>

        <div class="flex items-center justify-between text-xs text-secondary-500">
          <div class="flex items-center gap-1">
            <Star :size="14" class="text-warning-500 fill-warning-500" />
            <span>{{ course.reviews_avg_rating ? Number(course.reviews_avg_rating).toFixed(1) : '—' }}</span>
            <span v-if="course.reviews_count">({{ course.reviews_count }})</span>
          </div>
          <div class="flex items-center gap-1">
            <Users :size="14" />
            <span>{{ course.enrollments_count ?? 0 }}</span>
          </div>
        </div>
      </div>
    </BaseCard>
  </RouterLink>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { BookOpen, Star, Users } from 'lucide-vue-next'
import BaseCard from '@/components/base/BaseCard.vue'
import type { Course } from '@/types/course'

defineProps<{ course: Course }>()

const formatPrice = (price: string | number) => {
  const value = Number(price)
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(value)
}
</script>