<template>
  <div class="min-h-screen bg-secondary-50 font-body flex flex-col">
    <TopNavbar />
    <div class="flex-1">
      <RouterView />
    </div>
    <Footer />
  </div>
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router'
import TopNavbar from '@/components/layout/TopNavbar.vue'
import Footer from '@/components/layout/Footer.vue'
</script>