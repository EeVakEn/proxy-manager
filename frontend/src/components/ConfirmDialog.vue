<script setup lang="ts">
defineProps<{ message: string }>()
defineEmits<{ confirm: []; cancel: [] }>()
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5">
      <p class="text-sm text-gray-700 mb-4">{{ message }}</p>
      <div class="flex justify-end gap-2">
        <button
          class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
          @click="$emit('cancel')"
        >
          Cancel
        </button>
        <button
          class="px-3 py-1.5 text-sm bg-red-600 text-white rounded hover:bg-red-700"
          @click="$emit('confirm')"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>