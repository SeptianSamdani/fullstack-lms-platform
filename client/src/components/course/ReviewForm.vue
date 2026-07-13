<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <div>
      <p class="text-sm font-medium text-secondary-700 mb-2">Rating kamu</p>
      <div class="flex items-center gap-1">
        <button v-for="i in 5" :key="i" type="button" @click="rating = i">
          <Star :size="22" :class="i <= rating ? 'text-warning-500 fill-warning-500' : 'text-secondary-200'" />
        </button>
      </div>
    </div>
    <BaseInput v-model="comment" label="Komentar (opsional)" placeholder="Bagaimana pengalaman belajarmu?" />
    <BaseAlert v-if="error" variant="danger">{{ error }}</BaseAlert>
    <BaseButton type="submit" :loading="submitting" :disabled="rating === 0">Kirim Review</BaseButton>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Star } from 'lucide-vue-next'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'

defineProps<{ submitting: boolean; error: string }>()
const emit = defineEmits<{ submit: [payload: { rating: number; comment: string }] }>()

const rating = ref(0)
const comment = ref('')

const handleSubmit = () => emit('submit', { rating: rating.value, comment: comment.value })
</script>