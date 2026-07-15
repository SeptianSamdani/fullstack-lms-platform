<template>
  <BaseCard>
    <form @submit.prevent="handleSubmit" class="space-y-5 max-w-2xl">
      <BaseInput v-model="form.title" label="Judul Lesson" required placeholder="Contoh: Pengenalan Variabel" />

      <BaseSelect
        v-model="form.content_type" label="Tipe Konten" required
        :options="[
          { label: 'Video', value: 'video' },
          { label: 'Teks', value: 'text' },
          { label: 'Video + Teks', value: 'mixed' },
        ]"
      />

      <div v-if="form.content_type !== 'text'" class="space-y-3">
        <BaseInput
          v-model="form.content_url" label="Link Video"
          placeholder="https://youtu.be/..." hint="Isi salah satu: link video ATAU upload file di bawah"
        />
        <div>
          <label class="block text-sm font-medium text-secondary-700 mb-1.5">Upload Video</label>
          <p v-if="hasExistingVideo" class="text-xs text-secondary-500 mb-1.5">Video saat ini sudah tersimpan — upload file baru untuk menggantinya.</p>
          <input
            type="file" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
            class="w-full text-sm text-secondary-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary-50 file:text-primary-700 file:text-sm file:font-medium hover:file:bg-primary-100"
            @change="handleFileChange"
          />
          <p class="mt-1 text-xs text-secondary-400">Format mp4/mov/avi/webm, maksimal 100MB.</p>
        </div>
      </div>

      <div v-if="form.content_type !== 'video'">
        <label class="block text-sm font-medium text-secondary-700 mb-1.5">Konten Teks</label>
        <textarea
          v-model="form.content" rows="6"
          class="w-full rounded-xl border border-secondary-200 bg-white text-sm text-secondary-900 placeholder:text-secondary-400 px-4 py-2.5 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Tulis materi teks di sini..."
        />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <BaseInput v-model="form.duration" type="number" label="Durasi (menit)" placeholder="10" />
        <BaseInput v-model="form.order" type="number" label="Urutan" placeholder="0" />
      </div>

      <BaseAlert v-if="error" variant="danger">{{ error }}</BaseAlert>

      <div class="flex justify-end gap-3 pt-4 border-t border-secondary-100">
        <BaseButton type="button" variant="outline" @click="$emit('cancel')">Batal</BaseButton>
        <BaseButton type="submit" :loading="saving">{{ isEdit ? 'Simpan Perubahan' : 'Tambah Lesson' }}</BaseButton>
      </div>
    </form>
  </BaseCard>
</template>

<script setup lang="ts">
import { reactive, computed, watch } from 'vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseSelect from '@/components/base/BaseSelect.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import type { Lesson } from '@/types/course'

const props = defineProps<{ lesson?: Lesson | null; saving: boolean; error: string }>()
const emit = defineEmits<{ submit: [payload: FormData]; cancel: [] }>()

const isEdit = computed(() => !!props.lesson)
const hasExistingVideo = computed(() => !!props.lesson?.content_url && props.lesson.content_type !== 'text')

const form = reactive({
  title: '',
  content_type: 'video',
  content_url: '',
  content: '',
  duration: '',
  order: '0',
})

let videoFile: File | null = null

const fillForm = () => {
  form.title = props.lesson?.title ?? ''
  form.content_type = props.lesson?.content_type ?? 'video'
  form.content_url = props.lesson?.content_type !== 'text' ? (props.lesson?.content_url ?? '') : ''
  form.content = props.lesson?.content ?? ''
  form.duration = props.lesson?.duration ? String(Math.round(props.lesson.duration / 60)) : ''
  form.order = props.lesson?.order?.toString() ?? '0'
  videoFile = null
}

watch(() => props.lesson, fillForm, { immediate: true })

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  videoFile = target.files?.[0] ?? null
}

const handleSubmit = () => {
  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('content_type', form.content_type)
  if (form.content_type !== 'text' && form.content_url) formData.append('content_url', form.content_url)
  if (form.content_type !== 'video' && form.content) formData.append('content', form.content)
  if (form.duration) formData.append('duration', String(Number(form.duration) * 60))
  formData.append('order', form.order)
  if (videoFile) formData.append('video', videoFile)

  emit('submit', formData)
}
</script>