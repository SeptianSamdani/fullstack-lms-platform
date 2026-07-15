import { ref } from 'vue'
import api from '@/lib/axios'
import type { Module } from '@/types/course'

export function useInstructorModules() {
  const modules = ref<Module[]>([])
  const loading = ref(false)
  const saving = ref(false)
  const error = ref('')

  const fetchModules = async (courseId: number | string) => {
    loading.value = true
    try {
      const { data } = await api.get<Module[]>(`/courses/${courseId}/modules`)
      modules.value = data
    } finally {
      loading.value = false
    }
  }

  const createModule = async (courseId: number | string, payload: { title: string; order: number }) => {
    saving.value = true
    error.value = ''
    try {
      const { data } = await api.post(`/courses/${courseId}/modules`, payload)
      modules.value.push({ ...data, lessons: [] })
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal menambah modul.'
      throw e
    } finally {
      saving.value = false
    }
  }

  const updateModule = async (id: number, payload: { title: string; order: number }) => {
    saving.value = true
    error.value = ''
    try {
      const { data } = await api.put(`/modules/${id}`, payload)
      const idx = modules.value.findIndex((m) => m.id === id)
      if (idx !== -1) modules.value[idx] = { ...modules.value[idx], ...data }
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal memperbarui modul.'
      throw e
    } finally {
      saving.value = false
    }
  }

  const deleteModule = async (id: number) => {
    await api.delete(`/modules/${id}`)
    modules.value = modules.value.filter((m) => m.id !== id)
  }

  return { modules, loading, saving, error, fetchModules, createModule, updateModule, deleteModule }
}