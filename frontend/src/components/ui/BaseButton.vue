<script setup lang="ts">
defineProps<{
  variant?: 'primary' | 'danger' | 'secondary'
  disabled?: boolean
  loading?: boolean
}>()
</script>

<template>
  <button
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed',
      variant === 'danger'    ? 'bg-red-600 text-white hover:bg-red-700' :
      variant === 'secondary' ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' :
                                'bg-blue-600 text-white hover:bg-blue-700',
    ]"
  >
    <span v-if="loading" class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-current border-t-transparent" />
    <slot />
  </button>
</template>