import { ref } from 'vue'
import api from '@/lib/axios'

export function useDashboardStats() {
  const stats = ref<Record<string, any> | null>(null)
  const loading = ref(false)

  const fetchStats = async (role: 'admin' | 'instructor' | 'student') => {
    loading.value = true
    try {
      const { data } = await api.get(`/dashboard/${role}`)
      stats.value = data
    } finally {
      loading.value = false
    }
  }

  return { stats, loading, fetchStats }
}