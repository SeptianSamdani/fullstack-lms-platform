<template>
  <div class="overflow-x-auto rounded-2xl border border-secondary-200">
    <table class="w-full text-sm">
      <thead class="bg-secondary-50 text-secondary-500">
        <tr>
          <th v-for="col in columns" :key="col.key" class="px-4 py-3 text-left font-medium">
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-secondary-100">
        <tr v-if="loading">
          <td :colspan="columns.length" class="px-4 py-10 text-center text-secondary-500">
            <BaseSpinner class="mx-auto mb-2" /> Memuat data...
          </td>
        </tr>
        <tr v-else-if="!rows.length">
          <td :colspan="columns.length" class="px-4 py-10 text-center text-secondary-500">
            {{ emptyText }}
          </td>
        </tr>
        <tr v-else v-for="(row, i) in rows" :key="row.id ?? i" class="hover:bg-secondary-50 transition">
          <td v-for="col in columns" :key="col.key" class="px-4 py-3 text-secondary-700">
            <slot :name="`cell-${col.key}`" :row="row">{{ row[col.key] }}</slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import BaseSpinner from './BaseSpinner.vue'

export interface TableColumn {
  key: string
  label: string
}

defineProps({
  columns: { type: Array as () => TableColumn[], required: true },
  rows: { type: Array as () => Record<string, any>[], default: () => [] },
  loading: { type: Boolean, default: false },
  emptyText: { type: String, default: 'Belum ada data.' },
})
</script>