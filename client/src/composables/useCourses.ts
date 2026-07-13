import { ref } from 'vue'
import api from '@/lib/axios'
import type { Course, Category, PaginatedResponse } from '@/types/course'

export function useCourses() {
  const courses = ref<Course[]>([])
  const categories = ref<Category[]>([])
  const loading = ref(false)
  const meta = ref({ current_page: 1, last_page: 1, total: 0 })

  const fetchCourses = async (params: Record<string, any> = {}) => {
    loading.value = true
    try {
      const { data } = await api.get<PaginatedResponse<Course>>('/courses', { params })
      courses.value = data.data
      meta.value = {
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total,
      }
    } finally {
      loading.value = false
    }
  }

  const fetchCategories = async () => {
    const { data } = await api.get<Category[]>('/categories')
    categories.value = data
  }

  return { courses, categories, loading, meta, fetchCourses, fetchCategories }
}