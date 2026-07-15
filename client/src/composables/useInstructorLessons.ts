import { ref } from 'vue'
import api from '@/lib/axios'
import type { Lesson } from '@/types/course'

export function useInstructorLessons() {
  const lesson = ref<Lesson | null>(null)
  const loading = ref(false)
  const saving = ref(false)
  const error = ref('')

  const fetchLesson = async (id: number | string) => {
    loading.value = true
    try {
      const { data } = await api.get(`/lessons/${id}`)
      lesson.value = data
    } finally {
      loading.value = false
    }
  }

  const createLesson = async (moduleId: number, payload: FormData) => {
    saving.value = true
    error.value = ''
    try {
      const { data } = await api.post(`/modules/${moduleId}/lessons`, payload)
      return data
    } catch (e: any) {
      error.value = e.response?.data?.errors?.video?.[0] || e.response?.data?.message || 'Gagal menyimpan lesson.'
      throw e
    } finally {
      saving.value = false
    }
  }

  const updateLesson = async (id: number, payload: FormData) => {
    saving.value = true
    error.value = ''
    try {
      payload.append('_method', 'PUT')
      const { data } = await api.post(`/lessons/${id}`, payload)
      return data
    } catch (e: any) {
      error.value = e.response?.data?.errors?.video?.[0] || e.response?.data?.message || 'Gagal memperbarui lesson.'
      throw e
    } finally {
      saving.value = false
    }
  }

  const deleteLesson = async (id: number) => {
    await api.delete(`/lessons/${id}`)
  }

  return { lesson, loading, saving, error, fetchLesson, createLesson, updateLesson, deleteLesson }
}