<template>
  <div>
    <div v-if="loading" class="text-secondary-500 text-sm">Memuat data dashboard...</div>

    <template v-else-if="stats">
      <div v-if="role === 'admin'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Total Pengguna" :value="stats.total_users" :icon="Users" variant="primary" />
        <StatCard label="Total Kursus" :value="stats.total_courses" :icon="BookOpen" variant="success" />
        <StatCard label="Total Pendapatan" :value="formatPrice(stats.total_revenue)" :icon="Wallet" variant="warning" />
        <StatCard label="Subscription Aktif" :value="stats.active_subscriptions" :icon="CreditCard" variant="danger" />
      </div>

      <div v-else-if="role === 'instructor'">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <StatCard label="Total Kursus" :value="stats.total_courses" :icon="BookOpen" variant="primary" />
          <StatCard label="Total Siswa" :value="stats.total_students" :icon="Users" variant="success" />
          <StatCard label="Published" :value="stats.published" :icon="CheckCircle2" variant="success" />
          <StatCard label="Draft" :value="stats.draft" :icon="FileEdit" variant="warning" />
        </div>

        <BaseCard>
          <template #header>
            <h2 class="font-heading font-semibold text-secondary-900">Kursus Saya</h2>
            <RouterLink to="/dashboard/courses" class="text-sm text-primary-600 font-medium hover:text-primary-700">
              Kelola semua
            </RouterLink>
          </template>
          <BaseEmptyState
            v-if="!stats.courses?.length" :icon="BookOpen"
            title="Belum ada kursus" description="Mulai buat kursus pertamamu."
          />
          <div v-else class="divide-y divide-secondary-100">
            <div v-for="course in stats.courses" :key="course.id" class="py-3 flex items-center justify-between text-sm">
              <span class="font-medium text-secondary-900">{{ course.title }}</span>
              <span class="text-secondary-500">{{ course.enrollments_count }} siswa</span>
            </div>
          </div>
        </BaseCard>
      </div>

      <div v-else-if="role === 'student'" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <StatCard label="Kursus Diikuti" :value="stats.total_enrolled" :icon="BookOpen" variant="primary" />
        <StatCard label="Kursus Selesai" :value="stats.total_completed" :icon="CheckCircle2" variant="success" />
        <StatCard
          label="Status Subscription" :value="stats.has_subscription ? 'Aktif' : 'Tidak aktif'"
          :icon="CreditCard" :variant="stats.has_subscription ? 'success' : 'warning'"
        />
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { Users, BookOpen, Wallet, CreditCard, CheckCircle2, FileEdit } from 'lucide-vue-next'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseEmptyState from '@/components/base/BaseEmptyState.vue'
import StatCard from '@/components/dashboard/StatCard.vue'
import { useDashboardStats } from '@/composables/useDashboardStats'
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()
const { stats, loading, fetchStats } = useDashboardStats()

const role = computed(() => (user.value?.roles?.[0] as 'admin' | 'instructor' | 'student') || 'student')

const formatPrice = (price: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price)

onMounted(() => fetchStats(role.value))
</script>