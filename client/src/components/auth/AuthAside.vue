<template>
  <aside class="hidden lg:flex lg:w-[42%] flex-col justify-between bg-primary-900 text-white p-12 relative overflow-hidden">
    <p class="font-heading text-2xl font-bold tracking-tight relative z-10">LMS<span class="text-accent-400">.</span></p>

    <div class="relative z-10 max-w-sm">
      <p class="font-heading text-3xl font-semibold leading-snug mb-4">{{ headline }}</p>
      <p class="text-primary-100/80 text-sm leading-relaxed">{{ subtext }}</p>
    </div>

    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 400 600" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M60 550 C 60 450, 180 470, 180 380 S 300 300, 300 220 S 220 120, 220 60"
        stroke="var(--color-primary-700)" stroke-width="2" stroke-dasharray="6 8" fill="none" />
      <circle cx="60" cy="550" r="7" fill="var(--color-accent-400)" />
      <circle cx="180" cy="380" r="6" fill="var(--color-primary-600)" />
      <circle cx="300" cy="220" r="6" fill="var(--color-primary-600)" />
      <circle cx="220" cy="60" r="9" fill="none" stroke="var(--color-accent-400)" stroke-width="2" />
      <circle cx="220" cy="60" r="4" fill="var(--color-accent-400)" />
    </svg>
  </aside>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    headline?: string
    subtext?: string
  }>(),
  {
    headline: 'Setiap kursus adalah satu langkah.',
    subtext: 'Lacak progres belajarmu dan bangun karier dari modul pertama.',
  },
)
</script>