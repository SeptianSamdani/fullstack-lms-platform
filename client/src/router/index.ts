import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import PublicLayout from '@/layouts/PublicLayout.vue'
import CourseList from '@/views/courses/CourseList.vue'
import CourseDetail from '@/views/courses/CourseDetail.vue'
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import DashboardHome from '@/views/dashboard/DashboardHome.vue'
import ComingSoon from '@/views/dashboard/ComingSoon.vue'
import CourseManagement from '@/views/dashboard/instructor/CourseManagement.vue'
import CourseCreate from '@/views/dashboard/instructor/CourseCreate.vue'
import CourseEdit from '@/views/dashboard/instructor/CourseEdit.vue'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      { path: '', redirect: '/courses' },
      { path: 'courses', name: 'courses', component: CourseList },
      { path: 'courses/:id', name: 'course-detail', component: CourseDetail },
    ],
  },
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'dashboard-home', component: DashboardHome, meta: { title: 'Dashboard' } },
      { path: 'courses', name: 'dashboard-courses', component: CourseManagement, meta: { title: 'Kursus Saya' } },
      { path: 'courses/create', name: 'dashboard-courses-create', component: CourseCreate, meta: { title: 'Tambah Kursus' } },
      { path: 'courses/:id/edit', name: 'dashboard-courses-edit', component: CourseEdit, meta: { title: 'Edit Kursus' } },
      { path: 'categories', name: 'dashboard-categories', component: ComingSoon, meta: { title: 'Kategori' } },
      { path: 'users', name: 'dashboard-users', component: ComingSoon, meta: { title: 'Pengguna' } },
      { path: 'subscription-plans', name: 'dashboard-subscription-plans', component: ComingSoon, meta: { title: 'Paket Langganan' } },
      { path: 'enrollments', name: 'dashboard-enrollments', component: ComingSoon, meta: { title: 'Kursus Diikuti' } },
      { path: 'profile', name: 'dashboard-profile', component: ComingSoon, meta: { title: 'Profil' } },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth)
  const guestOnly = to.matched.some((record) => record.meta.guestOnly)

  if (requiresAuth && !token) return { name: 'login' }
  if (guestOnly && token) return { name: 'dashboard-home' }
})

export default router