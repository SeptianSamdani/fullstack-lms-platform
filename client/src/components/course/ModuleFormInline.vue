<template>
  <form @submit.prevent="handleSubmit" class="flex items-end gap-3">
    <div class="flex-1">
      <BaseInput v-model="title" :label="isEdit ? '' : 'Judul Modul'" placeholder="Contoh: Pengenalan Dasar" required />
    </div>
    <div class="w-24">
      <BaseInput v-model="order" :label="isEdit ? '' : 'Urutan'" type="number" placeholder="0" />
    </div>
    <BaseButton type="submit" :loading="saving">{{ isEdit ? 'Simpan' : 'Tambah' }}</BaseButton>
    <BaseButton v-if="isEdit" type="button" variant="ghost" @click="$emit('cancel')">Batal</BaseButton>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import type { Module } from '@/types/course'

const props = defineProps<{ module?: Module | null; saving: boolean }>()
const emit = defineEmits<{ submit: [payload: { title: string; order: number }]; cancel: [] }>()

const isEdit = !!props.module
const title = ref(props.module?.title ?? '')
const order = ref(props.module?.order ?? 0)

const handleSubmit = () => emit('submit', { title: title.value, order: Number(order.value) })
</script>