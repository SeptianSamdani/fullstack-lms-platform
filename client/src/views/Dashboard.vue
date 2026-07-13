<template>
  <div class="min-h-screen bg-secondary-50 font-body flex items-center justify-center">
    <BaseCard class="text-center max-w-sm">
      <p class="font-heading text-xl font-semibold text-secondary-900 mb-1">Selamat datang, {{ user?.name }} 👋</p>
      <p class="text-secondary-500 text-sm mb-6">Role: {{ user?.roles?.[0] }}</p>
      <BaseButton variant="outline" @click="logout">Keluar</BaseButton>
    </BaseCard>
  </div>
</template>

<script setup lang="ts">
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useAuth } from '@/composables/useAuth'

const { user, logout } = useAuth()
</script>