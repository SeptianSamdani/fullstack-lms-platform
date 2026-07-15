<template>
  <div class="space-y-4">
    <div v-for="module in modules" :key="module.id" class="border border-secondary-200 rounded-2xl bg-white overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 bg-secondary-50">
        <ModuleFormInline
          v-if="editingModuleId === module.id"
          :module="module" :saving="saving" class="flex-1"
          @submit="(payload) => handleUpdateModule(module.id, payload)"
          @cancel="editingModuleId = null"
        />
        <template v-else>
          <div class="flex items-center gap-3">
            <span class="text-xs font-semibold text-secondary-400">#{{ module.order }}</span>
            <span class="font-heading font-semibold text-secondary-900">{{ module.title }}</span>
          </div>
          <div class="flex items-center gap-2">
            <BaseButton size="sm" variant="ghost" :icon="Pencil" @click="editingModuleId = module.id" />
            <BaseButton size="sm" variant="ghost" :icon="Trash2" @click="onDeleteModule(module)" />
          </div>
        </template>
      </div>

      <div class="divide-y divide-secondary-100">
        <div v-for="lesson in module.lessons" :key="lesson.id" class="flex items-center justify-between px-4 py-3 text-sm">
          <div class="flex items-center gap-3 text-secondary-700">
            <component :is="lesson.content_type === 'text' ? FileText : PlayCircle" :size="16" class="text-secondary-400" />
            <span>{{ lesson.title }}</span>
          </div>
          <div class="flex items-center gap-2">
            <BaseButton
              size="sm" variant="ghost" :icon="Pencil"
              @click="$router.push(`/dashboard/courses/${courseId}/modules/${module.id}/lessons/${lesson.id}/edit`)"
            />
            <BaseButton size="sm" variant="ghost" :icon="Trash2" @click="onDeleteLesson(lesson)" />
          </div>
        </div>

        <div class="px-4 py-3">
          <BaseButton
            size="sm" variant="outline" :icon="Plus"
            @click="$router.push(`/dashboard/courses/${courseId}/modules/${module.id}/lessons/create`)"
          >
            Tambah Lesson
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Pencil, Trash2, Plus, FileText, PlayCircle } from 'lucide-vue-next'
import BaseButton from '@/components/base/BaseButton.vue'
import ModuleFormInline from './ModuleFormInline.vue'
import type { Module, Lesson } from '@/types/course'

const props = defineProps<{
  modules: Module[]
  courseId: number | string
  saving: boolean
  onUpdateModule: (id: number, payload: { title: string; order: number }) => Promise<any>
  onDeleteModule: (module: Module) => void
  onDeleteLesson: (lesson: Lesson) => void
}>()

const editingModuleId = ref<number | null>(null)

const handleUpdateModule = async (id: number, payload: { title: string; order: number }) => {
  try {
    await props.onUpdateModule(id, payload)
    editingModuleId.value = null
  } catch {
    // form tetap terbuka agar bisa dicoba lagi
  }
}
</script>