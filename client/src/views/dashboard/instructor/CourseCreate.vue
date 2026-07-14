<template>
  <div>
    <PageHeader title="Tambah Kursus" back-to="/dashboard/courses" />
    <CourseForm :categories="categories" :saving="saving" :error="error" @submit="handleSubmit" @cancel="goBack" />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageHeader from '@/components/dashboard/PageHeader.vue'
import CourseForm from '@/components/course/CourseForm.vue'
import { useInstructorCourses } from '@/composables/useInstructorCourses'
import { useCourses } from '@/composables/useCourses'

const router = useRouter()
const { saving, error, createCourse } = useInstructorCourses()
const { categories, fetchCategories } = useCourses()

const handleSubmit = async (formData: FormData) => {
  try {
    await createCourse(formData)
    router.push('/dashboard/courses')
  } catch {
    // error sudah ditangani composable, tetap di halaman ini
  }
}

const goBack = () => router.push('/dashboard/courses')

onMounted(fetchCategories)
</script>