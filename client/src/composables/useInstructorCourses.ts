import { ref } from 'vue'
import api from '@/lib/axios'
import type { Course } from '@/types/course'

export function useInstructorCourses() {
  const courses = ref<Course[]>([])
  const trashedCourses = ref<Course[]>([])
  const loading = ref(false)
  const saving = ref(false)
  const error = ref('')

  const fetchMyCourses = async () => {
    loading.value = true
    try {
      const { data } = await api.get('/instructor/courses')
      courses.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  const fetchTrashed = async () => {
    const { data } = await api.get('/instructor/courses/trashed')
    trashedCourses.value = data.data ?? data
  }

  // Catatan: Content-Type multipart TIDAK di-set manual — axios otomatis
  // menyertakan boundary yang benar saat data berupa instance FormData.
  const createCourse = async (payload: FormData) => {
    saving.value = true
    error.value = ''
    try {
      const { data } = await api.post('/courses', payload)
      courses.value.unshift(data)
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal menyimpan kursus.'
      throw e
    } finally {
      saving.value = false
    }
  }

  // Laravel butuh method-spoofing (_method=PUT) karena PHP tidak parse
  // multipart/form-data untuk request PUT asli.
  const updateCourse = async (id: number, payload: FormData) => {
    saving.value = true
    error.value = ''
    try {
      payload.append('_method', 'PUT')
      const { data } = await api.post(`/courses/${id}`, payload)
      const idx = courses.value.findIndex((c) => c.id === id)
      if (idx !== -1) courses.value[idx] = data
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal memperbarui kursus.'
      throw e
    } finally {
      saving.value = false
    }
  }

  const deleteCourse = async (id: number) => {
    await api.delete(`/courses/${id}`)
    courses.value = courses.value.filter((c) => c.id !== id)
  }

  const restoreCourse = async (id: number) => {
    await api.post(`/courses/${id}/restore`)
    trashedCourses.value = trashedCourses.value.filter((c) => c.id !== id)
    await fetchMyCourses()
  }

  return {
    courses,
    trashedCourses,
    loading,
    saving,
    error,
    fetchMyCourses,
    fetchTrashed,
    createCourse,
    updateCourse,
    deleteCourse,
    restoreCourse,
  }
}