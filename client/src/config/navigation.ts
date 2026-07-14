import type { Component } from 'vue'
import { LayoutDashboard, BookOpen, GraduationCap, FolderTree, Users, CreditCard, UserCircle } from 'lucide-vue-next'

export interface NavItem {
  label: string
  path: string
  icon: Component
  roles: Array<'admin' | 'instructor' | 'student'>
}

export const navItems: NavItem[] = [
  { label: 'Dashboard', path: '/dashboard', icon: LayoutDashboard, roles: ['admin', 'instructor', 'student'] },
  { label: 'Kursus Saya', path: '/dashboard/courses', icon: BookOpen, roles: ['instructor'] },
  { label: 'Kursus Diikuti', path: '/dashboard/enrollments', icon: GraduationCap, roles: ['student'] },
  { label: 'Kategori', path: '/dashboard/categories', icon: FolderTree, roles: ['admin'] },
  { label: 'Pengguna', path: '/dashboard/users', icon: Users, roles: ['admin'] },
  { label: 'Paket Langganan', path: '/dashboard/subscription-plans', icon: CreditCard, roles: ['admin'] },
  { label: 'Profil', path: '/dashboard/profile', icon: UserCircle, roles: ['admin', 'instructor', 'student'] },
]
