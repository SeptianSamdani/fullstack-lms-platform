import { ref } from 'vue'
import api from '@/lib/axios'
import type { Review } from '@/types/course'

export function useReviews() {
  const reviews = ref<Review[]>([])
  const loading = ref(false)
  const submitting = ref(false)
  const error = ref('')

  const fetchReviews = async (courseId: number | string) => {
    loading.value = true
    try {
      const { data } = await api.get(`/courses/${courseId}/reviews`)
      reviews.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  const submitReview = async (courseId: number | string, payload: { rating: number; comment: string }) => {
    submitting.value = true
    error.value = ''
    try {
      const { data } = await api.post(`/courses/${courseId}/reviews`, payload)
      reviews.value = [data, ...reviews.value]
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal mengirim review.'
      throw e
    } finally {
      submitting.value = false
    }
  }

  return { reviews, loading, submitting, error, fetchReviews, submitReview }
}