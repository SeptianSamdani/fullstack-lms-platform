<template>
  <div>
    <PageHeader :title="`Kurikulum: ${course?.title ?? ''}`" back-to="/dashboard/courses" />

    <BaseCard class="mb-6">
      <p class="text-sm font-medium text-secondary-700 mb-3">Tambah Modul Baru</p>
      <ModuleFormInline :saving="savingModule" @submit="handleCreateModule" />
      <BaseAlert v-if="moduleError" variant="danger" class="mt-3">{{ moduleError }}</BaseAlert>
    </BaseCard>

    <div v-if="loadingModules" class="text-sm text-secondary-500">Memuat modul...</div>

    <BaseEmptyState
      v-else-if="!modules.length" :icon="Layers"
      title="Belum ada modul" description="Tambahkan modul pertama untuk mulai menyusun materi kursus."
    />

    <ModuleListEditable
      v-else :modules="modules" :course-id="courseId" :saving="savingModule"
      :on-update-module="updateModule" :on-delete-module="handleDeleteModule" :on-delete-lesson="handleDeleteLesson"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Layers } from 'lucide-vue-next'
import PageHeader from '@/components/dashboard/PageHeader.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import BaseEmptyState from '@/components/base/BaseEmptyState.vue'
import ModuleFormInline from '@/components/course/ModuleFormInline.vue'
import ModuleListEditable from '@/components/course/ModuleListEditable.vue'
import { useInstructorModules } from '@/composables/useInstructorModules'
import { useInstructorLessons } from '@/composables/useInstructorLessons'
import { useCourseDetail } from '@/composables/useCourseDetail'
import type { Module, Lesson } from '@/types/course'

const route = useRoute()
const courseId = Number(route.params.id)

const { course, fetchCourse } = useCourseDetail()
const {
  modules, loading: loadingModules, saving: savingModule, error: moduleError,
  fetchModules, createModule, updateModule, deleteModule,
} = useInstructorModules()
const { deleteLesson } = useInstructorLessons()

const handleCreateModule = async (payload: { title: string; order: number }) => {
  try {
    await createModule(courseId, payload)
  } catch {
    // error sudah ditangani composable
  }
}

const handleDeleteModule = async (module: Module) => {
  if (!confirm(`Hapus modul "${module.title}"? Seluruh lesson di dalamnya ikut terhapus.`)) return
  await deleteModule(module.id)
}

const handleDeleteLesson = async (lesson: Lesson) => {
  if (!confirm(`Hapus lesson "${lesson.title}"?`)) return
  await deleteLesson(lesson.id)
  await fetchModules(courseId)
}

onMounted(() => {
  fetchCourse(courseId)
  fetchModules(courseId)
})
</script>