<script setup lang="ts">
import { useToast } from '@/composables/useToast'

const { toasts, remove } = useToast()
</script>

<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-80">
      <TransitionGroup
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-x-8"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-8"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="[
            'flex items-start justify-between gap-3 px-4 py-3 rounded-lg shadow-lg text-sm text-white cursor-pointer',
            toast.type === 'success' ? 'bg-green-600' :
            toast.type === 'error'   ? 'bg-red-600'   : 'bg-gray-700',
          ]"
          @click="remove(toast.id)"
        >
          <span>{{ toast.message }}</span>
          <button class="opacity-70 hover:opacity-100 shrink-0">✕</button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>
