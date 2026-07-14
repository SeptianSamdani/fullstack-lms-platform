<template>
  <aside
    :class="[
      'fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-secondary-200 flex flex-col transition-transform lg:translate-x-0',
      open ? 'translate-x-0' : '-translate-x-full',
    ]"
  >
    <div class="h-16 flex items-center px-6 border-b border-secondary-200">
      <RouterLink to="/dashboard" class="font-heading text-xl font-bold text-secondary-900">
        LMS<span class="text-primary-600">.</span>
      </RouterLink>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
      <RouterLink
        v-for="item in visibleItems" :key="item.path" :to="item.path"
        :class="[
          'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition',
          isActive(item.path) ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-50',
        ]"
      >
        <component :is="item.icon" :size="18" />
        {{ item.label }}
      </RouterLink>
    </nav>

    <div class="p-3 border-t border-secondary-200">
      <button
        @click="logout"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-secondary-600 hover:bg-danger-50 hover:text-danger-600 transition"
      >
        <LogOut :size="18" />
        Keluar
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { LogOut } from 'lucide-vue-next'
import { navItems } from '@/config/navigation'
import { useAuth } from '@/composables/useAuth'

defineProps<{ open: boolean }>()

const route = useRoute()
const { user, logout } = useAuth()

const visibleItems = computed(() =>
  navItems.filter((item) => item.roles.some((r) => user.value?.roles?.includes(r))),
)

const isActive = (path: string) =>
  path === '/dashboard' ? route.path === '/dashboard' : route.path.startsWith(path)
</script>