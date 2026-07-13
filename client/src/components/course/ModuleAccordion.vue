<template>
  <div class="space-y-3">
    <div v-for="(module, idx) in modules" :key="module.id" class="border border-secondary-200 rounded-2xl overflow-hidden bg-white">
      <button class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-secondary-50 transition" @click="toggle(module.id)">
        <div class="flex items-center gap-3">
          <span class="text-xs font-semibold text-secondary-400">{{ String(idx + 1).padStart(2, '0') }}</span>
          <span class="font-heading font-semibold text-secondary-900">{{ module.title }}</span>
        </div>
        <div class="flex items-center gap-3 text-sm text-secondary-500">
          <span>{{ module.lessons.length }} lesson</span>
          <ChevronDown :size="16" :class="['transition-transform', openIds.has(module.id) ? 'rotate-180' : '']" />
        </div>
      </button>

      <div v-if="openIds.has(module.id)" class="border-t border-secondary-100 divide-y divide-secondary-100">
        <div v-for="lesson in module.lessons" :key="lesson.id" class="flex items-center justify-between px-4 py-3 text-sm">
          <div class="flex items-center gap-3 text-secondary-700">
            <component :is="lessonIcon(lesson.content_type)" :size="16" class="text-secondary-400" />
            <span>{{ lesson.title }}</span>
          </div>
          <div class="flex items-center gap-2 text-secondary-400">
            <span v-if="lesson.duration">{{ formatDuration(lesson.duration) }}</span>
            <Lock v-if="!canAccess" :size="14" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { ChevronDown, PlayCircle, FileText, Lock } from 'lucide-vue-next'
import type { Module } from '@/types/course'

const props = defineProps<{ modules: Module[]; canAccess: boolean }>()

const openIds = ref<Set<number>>(new Set(props.modules[0] ? [props.modules[0].id] : []))

const toggle = (id: number) => {
  if (openIds.value.has(id)) openIds.value.delete(id)
  else openIds.value.add(id)
}

const lessonIcon = (type: string) => (type === 'text' ? FileText : PlayCircle)

const formatDuration = (seconds: number) => `${Math.floor(seconds / 60)} menit`
</script>