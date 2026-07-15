<template>
  <div>
    <PageHeader title="Tambah Lesson" :back-to="`/dashboard/courses/${courseId}/curriculum`" />
    <LessonForm :saving="saving" :error="error" @submit="handleSubmit" @cancel="goBack" />
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import PageHeader from '@/components/dashboard/PageHeader.vue'
import LessonForm from '@/components/course/LessonForm.vue'
import { useInstructorLessons } from '@/composables/useInstructorLessons'

const route = useRoute()
const router = useRouter()
const courseId = route.params.id as string
const moduleId = Number(route.params.moduleId)

const { saving, error, createLesson } = useInstructorLessons()

const handleSubmit = async (formData: FormData) => {
  try {
    await createLesson(moduleId, formData)
    router.push(`/dashboard/courses/${courseId}/curriculum`)
  } catch {
    // error sudah ditangani composable
  }
}

const goBack = () => router.push(`/dashboard/courses/${courseId}/curriculum`)
</script>