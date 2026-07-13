<template>
  <div class="min-h-screen flex bg-secondary-50 font-body">
    <AuthAside />

    <main class="flex-1 flex items-center justify-center px-6 py-12">
      <div class="w-full max-w-sm">
        <h1 class="font-heading text-3xl font-bold text-secondary-900 mb-2">Masuk</h1>
        <p class="text-secondary-500 text-sm mb-8">Lanjutkan proses belajarmu hari ini.</p>

        <BaseAlert v-if="errors.general" variant="danger" class="mb-5">
          {{ errors.general[0] }}
        </BaseAlert>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <BaseInput
            id="email" v-model="form.email" type="email" label="Email" required
            placeholder="nama@email.com" :error="errors.email?.[0]"
          />
          <BaseInput
            id="password" v-model="form.password" type="password" label="Password" required
            placeholder="••••••••" :error="errors.password?.[0]"
          />

          <BaseButton type="submit" block :loading="loading">
            {{ loading ? 'Memproses...' : 'Masuk' }}
          </BaseButton>
        </form>

        <p class="mt-8 text-sm text-secondary-500 text-center">
          Belum punya akun?
          <RouterLink to="/register" class="text-primary-600 font-medium hover:text-primary-700">Daftar sekarang</RouterLink>
        </p>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { RouterLink } from 'vue-router'
import AuthAside from '@/components/auth/AuthAside.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import { useAuth } from '@/composables/useAuth'

const { login, loading, errors } = useAuth()
const form = reactive({ email: '', password: '' })

const handleSubmit = async () => {
  try {
    await login(form)
  } catch {
    // errors sudah ditangani di composable
  }
}
</script>