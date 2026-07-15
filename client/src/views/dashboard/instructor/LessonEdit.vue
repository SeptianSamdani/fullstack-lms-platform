<template>
  <div>
    <PageHeader title="Edit Lesson" :back-to="`/dashboard/courses/${courseId}/curriculum`" />
    <div v-if="loading" class="text-sm text-secondary-500">Memuat data lesson...</div>
    <LessonForm v-else :lesson="lesson" :saving="saving" :error="error" @submit="handleSubmit" @cancel="goBack" />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageHeader from '@/components/dashboard/PageHeader.vue'
import LessonForm from '@/components/course/LessonForm.vue'
import { useInstructorLessons } from '@/composables/useInstructorLessons'

const route = useRoute()
const router = useRouter()
const courseId = route.params.id as string
const lessonId = Number(route.params.lessonId)

const { lesson, loading, saving, error, fetchLesson, updateLesson } = useInstructorLessons()

const handleSubmit = async (formData: FormData) => {
  try {
    await updateLesson(lessonId, formData)
    router.push(`/dashboard/courses/${courseId}/curriculum`)
  } catch {
    // error sudah ditangani composable
  }
}

const goBack = () => router.push(`/dashboard/courses/${courseId}/curriculum`)

onMounted(() => fetchLesson(lessonId))
</script>