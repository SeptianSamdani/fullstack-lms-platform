<template>
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-secondary-200">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
      <RouterLink to="/courses" class="font-heading text-xl font-bold text-secondary-900">
        LMS<span class="text-primary-600">.</span>
      </RouterLink>

      <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-secondary-600">
        <RouterLink to="/courses" class="hover:text-primary-600">Kursus</RouterLink>
      </nav>

      <template v-if="user">
        <RouterLink to="/dashboard" class="flex items-center gap-2 text-sm font-medium text-secondary-700 hover:text-primary-600">
          <img v-if="user.avatar_url" :src="user.avatar_url" class="w-8 h-8 rounded-full object-cover" />
          <span>{{ user.name }}</span>
        </RouterLink>
      </template>
      <template v-else>
        <RouterLink to="/login">
          <BaseButton size="sm" variant="outline">Masuk</BaseButton>
        </RouterLink>
      </template>
    </div>
  </header>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import BaseButton from '@/components/base/BaseButton.vue'
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()
</script>