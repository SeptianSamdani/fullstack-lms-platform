<template>
  <div class="min-h-screen flex bg-secondary-50 font-body">
    <AuthAside
      headline="Mulai jalur belajarmu."
      subtext="Buat akun gratis dan akses kursus dari instruktur berpengalaman."
    />

    <main class="flex-1 flex items-center justify-center px-6 py-12">
      <div class="w-full max-w-sm">
        <h1 class="font-heading text-3xl font-bold text-secondary-900 mb-2">Buat akun</h1>
        <p class="text-secondary-500 text-sm mb-8">Gratis, dan hanya perlu beberapa detik.</p>

        <BaseAlert v-if="errors.general" variant="danger" class="mb-5">
          {{ errors.general[0] }}
        </BaseAlert>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <BaseInput
            id="name" v-model="form.name" label="Nama lengkap" required
            placeholder="Nama kamu" :error="errors.name?.[0]"
          />
          <BaseInput
            id="email" v-model="form.email" type="email" label="Email" required
            placeholder="nama@email.com" :error="errors.email?.[0]"
          />
          <BaseInput
            id="password" v-model="form.password" type="password" label="Password" required
            placeholder="Minimal 8 karakter" :error="errors.password?.[0]"
          />
          <BaseInput
            id="password_confirmation" v-model="form.password_confirmation" type="password"
            label="Konfirmasi password" required placeholder="Ulangi password"
          />

          <BaseButton type="submit" block :loading="loading">
            {{ loading ? 'Memproses...' : 'Daftar' }}
          </BaseButton>
        </form>

        <p class="mt-8 text-sm text-secondary-500 text-center">
          Sudah punya akun?
          <RouterLink to="/login" class="text-primary-600 font-medium hover:text-primary-700">Masuk di sini</RouterLink>
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

const { register, loading, errors } = useAuth()
const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })

const handleSubmit = async () => {
  try {
    await register(form)
  } catch {
    // errors sudah ditangani di composable
  }
}
</script>