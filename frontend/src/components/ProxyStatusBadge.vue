<script setup lang="ts">
import type { ProxyStatus } from '@/types/proxy'

defineProps<{ status: ProxyStatus }>()
</script>

<template>
  <span
    :class="[
      'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium',
      status === 'active'   ? 'bg-green-100 text-green-700' :
      status === 'inactive' ? 'bg-red-100 text-red-700' :
                              'bg-yellow-100 text-yellow-700',
    ]"
  >
    <span
      v-if="status === 'checking'"
      class="h-2.5 w-2.5 animate-spin rounded-full border-2 border-yellow-500 border-t-transparent"
    />
    <span
      v-else
      :class="['h-1.5 w-1.5 rounded-full', status === 'active' ? 'bg-green-500' : 'bg-red-500']"
    />
    {{ status }}
  </span>
</template>