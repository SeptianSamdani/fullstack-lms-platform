<template>
  <div class="min-h-screen bg-secondary-50 font-body">
    <DashboardSidebar :open="sidebarOpen" />

    <div v-if="sidebarOpen" class="fixed inset-0 z-20 bg-secondary-900/40 lg:hidden" @click="sidebarOpen = false" />

    <div class="lg:pl-64">
      <DashboardTopbar :title="pageTitle" @toggle-sidebar="sidebarOpen = !sidebarOpen" />
      <main class="p-4 lg:p-8">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import DashboardSidebar from '@/components/layout/DashboardSidebar.vue'
import DashboardTopbar from '@/components/layout/DashboardTopbar.vue'

const sidebarOpen = ref(false)
const route = useRoute()
const pageTitle = computed(() => (route.meta.title as string) || 'Dashboard')
</script>