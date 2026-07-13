import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import CourseList from '@/views/courses/CourseList.vue'
import CourseDetail from '@/views/courses/CourseDetail.vue'
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import Dashboard from '@/views/Dashboard.vue'

const routes: RouteRecordRaw[] = [
  { path: '/', redirect: '/courses' },
  { path: '/courses', name: 'courses', component: CourseList },
  { path: '/courses/:id', name: 'course-detail', component: CourseDetail },
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  if (to.meta.requiresAuth && !token) return { name: 'login' }
  if (to.meta.guestOnly && token) return { name: 'dashboard' }
})

export default router