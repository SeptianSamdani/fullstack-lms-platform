<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-2">
        <button :class="tabClass(activeTab === 'active')" @click="switchTab('active')">Kursus Aktif</button>
        <button :class="tabClass(activeTab === 'trashed')" @click="switchTab('trashed')">Arsip</button>
      </div>
      <BaseButton v-if="activeTab === 'active'" :icon="Plus" @click="$router.push('/dashboard/courses/create')">
        Tambah Kursus
      </BaseButton>
    </div>

    <div v-if="loading" class="text-sm text-secondary-500">Memuat kursus...</div>

    <BaseEmptyState
      v-else-if="displayedCourses.length === 0" :icon="BookOpen"
      :title="activeTab === 'active' ? 'Belum ada kursus' : 'Arsip kosong'"
      :description="activeTab === 'active' ? 'Mulai buat kursus pertamamu.' : 'Kursus yang dihapus akan muncul di sini.'"
    >
      <template v-if="activeTab === 'active'" #action>
        <BaseButton :icon="Plus" @click="$router.push('/dashboard/courses/create')">Tambah Kursus</BaseButton>
      </template>
    </BaseEmptyState>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <BaseCard v-for="course in displayedCourses" :key="course.id" padding="none" class="overflow-hidden">
        <div class="aspect-video bg-secondary-100">
          <img v-if="course.thumbnail_url" :src="course.thumbnail_url" class="w-full h-full object-cover" />
        </div>
        <div class="p-4">
          <div class="flex items-center justify-between mb-2">
            <span
              :class="[
                'text-xs font-semibold px-2 py-1 rounded-lg',
                course.status === 'published' ? 'bg-success-50 text-success-700' : 'bg-warning-50 text-warning-700',
              ]"
            >
              {{ course.status === 'published' ? 'Published' : 'Draft' }}
            </span>
            <span class="text-xs text-secondary-500">{{ course.enrollments_count ?? 0 }} siswa</span>
          </div>
          <h3 class="font-heading font-semibold text-secondary-900 mb-3 line-clamp-2">{{ course.title }}</h3>

          <div v-if="activeTab === 'active'" class="flex items-center gap-2">
            <BaseButton size="sm" variant="outline" block @click="$router.push(`/dashboard/courses/${course.id}/edit`)">
              Edit
            </BaseButton>
            <BaseButton size="sm" variant="ghost" :icon="Trash2" @click="confirmDelete(course)" />
          </div>
          <BaseButton v-else size="sm" variant="outline" block :icon="RotateCcw" @click="handleRestore(course.id)">
            Pulihkan
          </BaseButton>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Plus, Trash2, RotateCcw, BookOpen } from 'lucide-vue-next'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseEmptyState from '@/components/base/BaseEmptyState.vue'
import { useInstructorCourses } from '@/composables/useInstructorCourses'
import type { Course } from '@/types/course'

const { courses, trashedCourses, loading, fetchMyCourses, fetchTrashed, deleteCourse, restoreCourse } = useInstructorCourses()

const activeTab = ref<'active' | 'trashed'>('active')

const displayedCourses = computed(() => (activeTab.value === 'active' ? courses.value : trashedCourses.value))

const tabClass = (active: boolean) => [
  'px-4 py-2 rounded-xl text-sm font-medium transition',
  active ? 'bg-primary-600 text-white' : 'bg-white border border-secondary-200 text-secondary-600 hover:bg-secondary-50',
]

const switchTab = async (tab: 'active' | 'trashed') => {
  activeTab.value = tab
  if (tab === 'trashed' && trashedCourses.value.length === 0) await fetchTrashed()
}

const confirmDelete = async (course: Course) => {
  if (!confirm(`Hapus kursus "${course.title}"? Kursus akan diarsipkan dan bisa dipulihkan nanti.`)) return
  await deleteCourse(course.id)
}

const handleRestore = async (id: number) => {
  await restoreCourse(id)
}

onMounted(fetchMyCourses)
</script>