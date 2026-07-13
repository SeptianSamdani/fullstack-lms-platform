<template>
  <Loader2 :size="size" class="animate-spin text-primary-600" />
</template>

<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next'
defineProps({ size: { type: Number, default: 20 } })
</script>