<template>
  <header class="sticky top-0 z-20 h-16 bg-white/80 backdrop-blur border-b border-secondary-200 flex items-center justify-between px-4 lg:px-8">
    <div class="flex items-center gap-3">
      <button class="lg:hidden text-secondary-600" @click="$emit('toggle-sidebar')" aria-label="Buka menu">
        <Menu :size="22" />
      </button>
      <h1 class="font-heading text-lg font-semibold text-secondary-900">{{ title }}</h1>
    </div>

    <RouterLink to="/dashboard/profile" class="flex items-center gap-2">
      <img v-if="user?.avatar_url" :src="user.avatar_url" class="w-9 h-9 rounded-full object-cover" />
      <div v-else class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-semibold">
        {{ user?.name?.charAt(0) }}
      </div>
      <div class="hidden sm:block text-left">
        <p class="text-sm font-medium text-secondary-900 leading-tight">{{ user?.name }}</p>
        <p class="text-xs text-secondary-500 capitalize leading-tight">{{ user?.roles?.[0] }}</p>
      </div>
    </RouterLink>
  </header>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { Menu } from 'lucide-vue-next'
import { useAuth } from '@/composables/useAuth'

defineProps<{ title: string }>()
defineEmits<{ 'toggle-sidebar': [] }>()

const { user } = useAuth()
</script>