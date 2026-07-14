<template>
  <BaseCard>
    <form @submit.prevent="handleSubmit" class="space-y-5 max-w-2xl">
      <BaseInput v-model="form.title" label="Judul Kursus" required placeholder="Contoh: Belajar Vue.js untuk Pemula" />

      <div>
        <label class="block text-sm font-medium text-secondary-700 mb-1.5">Deskripsi</label>
        <textarea
          v-model="form.description" rows="4"
          class="w-full rounded-xl border border-secondary-200 bg-white text-sm text-secondary-900 placeholder:text-secondary-400 px-4 py-2.5 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Deskripsi singkat kursus"
        />
      </div>

      <BaseSelect v-model="form.category_id" label="Kategori" :options="categoryOptions" placeholder="Pilih kategori" />

      <div class="grid grid-cols-2 gap-4">
        <BaseSelect
          v-model="form.type" label="Tipe" required
          :options="[{ label: 'Gratis', value: 'free' }, { label: 'Berbayar', value: 'paid' }]"
        />
        <BaseInput v-if="form.type === 'paid'" v-model="form.price" type="number" label="Harga (Rp)" required placeholder="0" />
      </div>

      <BaseSelect
        v-if="isEdit" v-model="form.status" label="Status"
        :options="[{ label: 'Draft', value: 'draft' }, { label: 'Published', value: 'published' }]"
      />

      <div>
        <label class="block text-sm font-medium text-secondary-700 mb-1.5">Thumbnail</label>
        <div class="flex items-center gap-4">
          <div v-if="currentThumbnail" class="w-24 h-16 rounded-lg overflow-hidden bg-secondary-100 shrink-0">
            <img :src="currentThumbnail" class="w-full h-full object-cover" />
          </div>
          <input
            type="file" accept="image/*"
            class="text-sm text-secondary-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary-50 file:text-primary-700 file:text-sm file:font-medium hover:file:bg-primary-100"
            @change="handleFileChange"
          />
        </div>
      </div>

      <BaseAlert v-if="error" variant="danger">{{ error }}</BaseAlert>

      <div class="flex justify-end gap-3 pt-4 border-t border-secondary-100">
        <BaseButton type="button" variant="outline" @click="$emit('cancel')">Batal</BaseButton>
        <BaseButton type="submit" :loading="saving">{{ isEdit ? 'Simpan Perubahan' : 'Buat Kursus' }}</BaseButton>
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
import type { Course, Category } from '@/types/course'

const props = defineProps<{
  course?: Course | null
  categories: Category[]
  saving: boolean
  error: string
}>()

const emit = defineEmits<{
  submit: [payload: FormData]
  cancel: []
}>()

const isEdit = computed(() => !!props.course)
const currentThumbnail = computed(() => props.course?.thumbnail_url ?? null)

const form = reactive({
  title: '',
  description: '',
  category_id: '',
  type: 'free',
  price: '0',
  status: 'draft',
})

let thumbnailFile: File | null = null

const fillForm = () => {
  form.title = props.course?.title ?? ''
  form.description = props.course?.description ?? ''
  form.category_id = props.course?.category?.id?.toString() ?? ''
  form.type = props.course?.type ?? 'free'
  form.price = props.course?.price?.toString() ?? '0'
  form.status = props.course?.status ?? 'draft'
  thumbnailFile = null
}

// Diisi begitu data course tersedia — bukan lagi bergantung pada "modal dibuka",
// karena di halaman Edit data course datang async dari fetch.
watch(() => props.course, fillForm, { immediate: true })

const categoryOptions = computed(() => props.categories.map((c) => ({ label: c.name, value: c.id })))

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  thumbnailFile = target.files?.[0] ?? null
}

const handleSubmit = () => {
  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('description', form.description || '')
  if (form.category_id) formData.append('category_id', form.category_id)
  formData.append('type', form.type)
  formData.append('price', form.type === 'paid' ? form.price : '0')
  if (isEdit.value) formData.append('status', form.status)
  if (thumbnailFile) formData.append('thumbnail', thumbnailFile)

  emit('submit', formData)
}
</script>