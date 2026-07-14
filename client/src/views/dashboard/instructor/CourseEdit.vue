<template>
  <div>
    <PageHeader title="Edit Kursus" back-to="/dashboard/courses" />
    <div v-if="loadingCourse" class="text-sm text-secondary-500">Memuat data kursus...</div>
    <CourseForm
      v-else :course="course" :categories="categories" :saving="saving" :error="error"
      @submit="handleSubmit" @cancel="goBack"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageHeader from '@/components/dashboard/PageHeader.vue'
import CourseForm from '@/components/course/CourseForm.vue'
import { useInstructorCourses } from '@/composables/useInstructorCourses'
import { useCourseDetail } from '@/composables/useCourseDetail'
import { useCourses } from '@/composables/useCourses'

const route = useRoute()
const router = useRouter()
const courseId = Number(route.params.id)

const { course, loading: loadingCourse, fetchCourse } = useCourseDetail()
const { saving, error, updateCourse } = useInstructorCourses()
const { categories, fetchCategories } = useCourses()

const handleSubmit = async (formData: FormData) => {
  try {
    await updateCourse(courseId, formData)
    router.push('/dashboard/courses')
  } catch {
    // error sudah ditangani composable, tetap di halaman ini
  }
}

const goBack = () => router.push('/dashboard/courses')

onMounted(() => {
  fetchCourse(courseId)
  fetchCategories()
})
</script>