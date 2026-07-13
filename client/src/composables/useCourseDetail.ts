import { ref } from 'vue'
import api from '@/lib/axios'
import type { Course } from '@/types/course'

export function useCourseDetail() {
  const course = ref<Course | null>(null)
  const loading = ref(false)

  const fetchCourse = async (id: number | string) => {
    loading.value = true
    try {
      const { data } = await api.get<Course>(`/courses/${id}`)
      course.value = data
    } finally {
      loading.value = false
    }
  }

  return { course, loading, fetchCourse }
}