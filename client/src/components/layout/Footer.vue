<template>
  <footer class="bg-white border-t border-secondary-200">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-3 gap-8">
      <div>
        <p class="font-heading text-lg font-bold text-secondary-900 mb-2">LMS<span class="text-primary-600">.</span></p>
        <p class="text-sm text-secondary-500 leading-relaxed">
          Platform belajar online untuk mengembangkan skill dan membangun karier, satu kursus setiap saat.
        </p>
      </div>

      <div>
        <p class="text-sm font-semibold text-secondary-900 mb-3">Navigasi</p>
        <ul class="space-y-2 text-sm text-secondary-500">
          <li><RouterLink to="/courses" class="hover:text-primary-600">Kursus</RouterLink></li>
          <li><RouterLink to="/login" class="hover:text-primary-600">Masuk</RouterLink></li>
          <li><RouterLink to="/register" class="hover:text-primary-600">Daftar</RouterLink></li>
        </ul>
      </div>

      <div>
        <p class="text-sm font-semibold text-secondary-900 mb-3">Bantuan</p>
        <ul class="space-y-2 text-sm text-secondary-500">
          <li>support@lms.com</li>
        </ul>
      </div>
    </div>

    <div class="border-t border-secondary-100">
      <div class="max-w-7xl mx-auto px-6 py-4 text-xs text-secondary-400 text-center">
        © {{ currentYear }} LMS Platform. Seluruh hak cipta dilindungi.
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'

const currentYear = new Date().getFullYear()
</script>