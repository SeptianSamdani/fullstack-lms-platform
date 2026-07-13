<template>
  <div class="min-h-screen bg-secondary-50 font-body">
    <TopNavbar />

    <div v-if="loading" class="max-w-5xl mx-auto px-6 py-16 text-center text-secondary-500">
      Memuat kursus...
    </div>

    <template v-else-if="course">
      <div class="bg-primary-900 text-white">
        <div class="max-w-5xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-8">
          <div class="md:col-span-2">
            <span v-if="course.category" class="inline-block text-xs font-medium bg-white/10 px-3 py-1 rounded-lg mb-3">
              {{ course.category.name }}
            </span>
            <h1 class="font-heading text-3xl font-bold mb-3">{{ course.title }}</h1>
            <p class="text-primary-100/80 mb-4">{{ course.description }}</p>
            <div class="flex items-center gap-4 text-sm text-primary-100/80">
              <div class="flex items-center gap-1">
                <Star :size="16" class="text-warning-400 fill-warning-400" />
                <span>{{ course.reviews_avg_rating ? Number(course.reviews_avg_rating).toFixed(1) : 'Belum ada rating' }}</span>
                <span v-if="course.reviews_count">({{ course.reviews_count }} review)</span>
              </div>
              <div class="flex items-center gap-1">
                <Users :size="16" />
                <span>{{ course.enrollments_count ?? 0 }} siswa</span>
              </div>
              <div v-if="course.instructor" class="flex items-center gap-1">
                <UserCircle :size="16" />
                <span>{{ course.instructor.name }}</span>
              </div>
            </div>
          </div>

          <BaseCard class="h-fit">
            <div class="aspect-video bg-secondary-100 rounded-xl overflow-hidden mb-4">
              <img v-if="course.thumbnail_url" :src="course.thumbnail_url" class="w-full h-full object-cover" />
            </div>
            <p class="font-heading text-2xl font-bold text-secondary-900 mb-4">
              {{ course.type === 'free' ? 'Gratis' : formatPrice(course.price) }}
            </p>

            <BaseAlert v-if="enrollError" variant="danger" class="mb-4">{{ enrollError }}</BaseAlert>

            <BaseButton v-if="!isAuthenticated()" block @click="$router.push('/login')">
              Masuk untuk enroll
            </BaseButton>
            <BaseButton v-else-if="isEnrolled(course.id)" block variant="secondary" disabled>
              Sudah Terdaftar
            </BaseButton>
            <BaseButton v-else block :loading="enrolling" @click="handleEnroll">
              Enroll Sekarang
            </BaseButton>
          </BaseCard>
        </div>
      </div>

      <main class="max-w-5xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-10">
          <section>
            <h2 class="font-heading text-xl font-bold text-secondary-900 mb-4">Materi Kursus</h2>
            <ModuleAccordion
              v-if="course.modules?.length" :modules="course.modules"
              :can-access="course.type === 'free' || isEnrolled(course.id)"
            />
            <p v-else class="text-sm text-secondary-500">Modul belum tersedia.</p>
          </section>

          <section>
            <h2 class="font-heading text-xl font-bold text-secondary-900 mb-4">Review</h2>

            <BaseCard v-if="isEnrolled(course.id) && !hasReviewed" class="mb-6">
              <ReviewForm :submitting="submittingReview" :error="reviewError" @submit="handleReviewSubmit" />
            </BaseCard>

            <ReviewList :reviews="reviews" :loading="reviewsLoading" />
          </section>
        </div>

        <aside>
          <BaseCard v-if="course.instructor">
            <p class="text-xs font-medium text-secondary-500 mb-2">Instruktur</p>
            <p class="font-heading font-semibold text-secondary-900">{{ course.instructor.name }}</p>
          </BaseCard>
        </aside>
      </main>
    </template>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Star, Users, UserCircle } from 'lucide-vue-next'
import TopNavbar from '@/components/layout/TopNavbar.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import ModuleAccordion from '@/components/course/ModuleAccordion.vue'
import ReviewList from '@/components/course/ReviewList.vue'
import ReviewForm from '@/components/course/ReviewForm.vue'
import { useCourseDetail } from '@/composables/useCourseDetail'
import { useEnrollment } from '@/composables/useEnrollment'
import { useReviews } from '@/composables/useReviews'
import { useAuth } from '@/composables/useAuth'

const route = useRoute()
const courseId = route.params.id as string

const { course, loading, fetchCourse } = useCourseDetail()
const { isEnrolled, enroll, fetchMyEnrollments, loading: enrolling, error: enrollError } = useEnrollment()
const { reviews, loading: reviewsLoading, submitting: submittingReview, error: reviewError, fetchReviews, submitReview } = useReviews()
const { user, isAuthenticated } = useAuth()

const hasReviewed = computed(() => reviews.value.some((r) => r.user.id === user.value?.id))

const formatPrice = (price: string | number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(price))

const handleEnroll = async () => {
  try {
    await enroll(Number(courseId))
  } catch {
    // error sudah ditangani composable
  }
}

const handleReviewSubmit = async (payload: { rating: number; comment: string }) => {
  try {
    await submitReview(courseId, payload)
  } catch {
    // error sudah ditangani composable
  }
}

onMounted(async () => {
  await fetchCourse(courseId)
  await fetchReviews(courseId)
  if (isAuthenticated()) await fetchMyEnrollments()
})
</script>