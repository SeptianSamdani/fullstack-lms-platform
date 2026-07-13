import { ref } from 'vue'
import api from '@/lib/axios'

interface EnrollmentItem {
  id: number
  course_id: number
  status: string
}

// State di-share antar komponen
const enrolledCourseIds = ref<Set<number>>(new Set())

export function useEnrollment() {
  const loading = ref(false)
  const error = ref('')

  const fetchMyEnrollments = async () => {
    try {
      const { data } = await api.get('/enrollments')
      const items: EnrollmentItem[] = data.data ?? data
      enrolledCourseIds.value = new Set(items.map((e) => e.course_id))
    } catch {
      // guest / belum login, abaikan
    }
  }

  const isEnrolled = (courseId: number) => enrolledCourseIds.value.has(courseId)

  const enroll = async (courseId: number) => {
    loading.value = true
    error.value = ''
    try {
      const { data } = await api.post(`/courses/${courseId}/enroll`)
      enrolledCourseIds.value.add(courseId)
      return data
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Gagal enroll ke kursus ini.'
      throw e
    } finally {
      loading.value = false
    }
  }

  return { loading, error, isEnrolled, enroll, fetchMyEnrollments }
}