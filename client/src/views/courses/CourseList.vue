<template>
  <div class="max-w-7xl mx-auto px-6 py-10 w-full">
    <div class="mb-8">
      <h1 class="font-heading text-3xl font-bold text-secondary-900 mb-2">Jelajahi Kursus</h1>
      <p class="text-secondary-500">Temukan kursus yang sesuai dengan tujuan belajarmu.</p>
    </div>

    <div class="mb-6">
      <BaseInput v-model="search" placeholder="Cari kursus..." :icon="Search" @keyup.enter="applyFilters" />
    </div>

    <CategoryFilter
      :categories="categories" :active-id="activeCategoryId" class="mb-8"
      @select="handleCategorySelect"
    />

    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="i in 6" :key="i" class="animate-pulse">
        <div class="aspect-video bg-secondary-200 rounded-2xl mb-3" />
        <div class="h-4 bg-secondary-200 rounded w-3/4 mb-2" />
        <div class="h-3 bg-secondary-200 rounded w-1/2" />
      </div>
    </div>

    <BaseEmptyState
      v-else-if="!courses.length" :icon="BookOpen"
      title="Belum ada kursus ditemukan"
      description="Coba ubah kata kunci pencarian atau filter kategori."
    />

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <CourseCard v-for="course in courses" :key="course.id" :course="course" />
    </div>

    <div v-if="meta.last_page > 1" class="flex items-center justify-center gap-2 mt-10">
      <BaseButton variant="outline" size="sm" :disabled="meta.current_page === 1" @click="changePage(meta.current_page - 1)">
        Sebelumnya
      </BaseButton>
      <span class="text-sm text-secondary-500 px-2">
        Halaman {{ meta.current_page }} dari {{ meta.last_page }}
      </span>
      <BaseButton variant="outline" size="sm" :disabled="meta.current_page === meta.last_page" @click="changePage(meta.current_page + 1)">
        Selanjutnya
      </BaseButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Search, BookOpen } from 'lucide-vue-next'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseEmptyState from '@/components/base/BaseEmptyState.vue'
import CourseCard from '@/components/course/CourseCard.vue'
import CategoryFilter from '@/components/course/CategoryFilter.vue'
import { useCourses } from '@/composables/useCourses'

const { courses, categories, loading, meta, fetchCourses, fetchCategories } = useCourses()

const search = ref('')
const activeCategoryId = ref<number | null>(null)
const currentPage = ref(1)

const loadCourses = () => {
  fetchCourses({
    search: search.value || undefined,
    category_id: activeCategoryId.value || undefined,
    page: currentPage.value,
    per_page: 9,
  })
}

const applyFilters = () => {
  currentPage.value = 1
  loadCourses()
}

const handleCategorySelect = (id: number | null) => {
  activeCategoryId.value = id
  currentPage.value = 1
  loadCourses()
}

const changePage = (page: number) => {
  currentPage.value = page
  loadCourses()
}

onMounted(() => {
  fetchCategories()
  loadCourses()
})
</script>