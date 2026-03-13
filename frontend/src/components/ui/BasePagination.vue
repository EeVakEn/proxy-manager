<script setup lang="ts">
import type { PaginationMeta } from '@/types/proxy'

const props = defineProps<{ meta: PaginationMeta }>()
const emit = defineEmits<{ change: [page: number] }>()

function pages(): number[] {
  const total = props.meta.last_page
  const cur   = props.meta.current_page
  const delta = 2
  const range: number[] = []

  for (let i = Math.max(1, cur - delta); i <= Math.min(total, cur + delta); i++) {
    range.push(i)
  }
  return range
}
</script>

<template>
  <div v-if="meta.last_page > 1" class="flex items-center justify-between text-sm text-gray-600 mt-4">
    <span>
      Showing {{ meta.from }}–{{ meta.to }} of {{ meta.total }}
    </span>
    <div class="flex items-center gap-1">
      <button
        :disabled="meta.current_page === 1"
        class="px-2 py-1 rounded border border-gray-300 disabled:opacity-40 hover:bg-gray-100"
        @click="emit('change', meta.current_page - 1)"
      >
        ‹
      </button>

      <template v-if="(pages().at(0) ?? 1) > 1">
        <button class="px-2.5 py-1 rounded border border-gray-300 hover:bg-gray-100" @click="emit('change', 1)">1</button>
        <span v-if="(pages().at(0) ?? 1) > 2" class="px-1">…</span>
      </template>

      <button
        v-for="p in pages()"
        :key="p"
        :class="[
          'px-2.5 py-1 rounded border',
          p === meta.current_page
            ? 'bg-blue-600 text-white border-blue-600'
            : 'border-gray-300 hover:bg-gray-100',
        ]"
        @click="emit('change', p)"
      >
        {{ p }}
      </button>

      <template v-if="(pages().at(-1) ?? meta.last_page) < meta.last_page">
        <span v-if="(pages().at(-1) ?? meta.last_page) < meta.last_page - 1" class="px-1">…</span>
        <button class="px-2.5 py-1 rounded border border-gray-300 hover:bg-gray-100" @click="emit('change', meta.last_page)">
          {{ meta.last_page }}
        </button>
      </template>

      <button
        :disabled="meta.current_page === meta.last_page"
        class="px-2 py-1 rounded border border-gray-300 disabled:opacity-40 hover:bg-gray-100"
        @click="emit('change', meta.current_page + 1)"
      >
        ›
      </button>
    </div>
  </div>
</template>
