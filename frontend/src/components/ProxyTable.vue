<script setup lang="ts">
import type { Proxy } from '@/types/proxy'
import ProxyStatusBadge from '@/components/ProxyStatusBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'

defineProps<{ proxies: Proxy[] }>()
defineEmits<{
  edit: [proxy: Proxy]
  delete: [proxy: Proxy]
  check: [proxy: Proxy]
}>()

function formatDate(iso: string | null) {
  if (!iso) return '—'
  return new Date(iso).toLocaleString('en-GB', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' })
}
</script>

<template>
  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wide">
        <tr>
          <th class="px-4 py-3 text-left">Host : Port</th>
          <th class="px-4 py-3 text-left">Type</th>
          <th class="px-4 py-3 text-left">Login</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-left">Checked</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 bg-white">
        <tr v-if="proxies.length === 0">
          <td colspan="6" class="px-4 py-8 text-center text-gray-400">No proxies found</td>
        </tr>
        <tr v-for="proxy in proxies" :key="proxy.id" class="hover:bg-gray-50 transition-colors">
          <td class="px-4 py-3 font-mono">{{ proxy.host }}:{{ proxy.port }}</td>
          <td class="px-4 py-3 uppercase text-xs text-gray-600">{{ proxy.type }}</td>
          <td class="px-4 py-3 text-gray-600">{{ proxy.login ?? '—' }}</td>
          <td class="px-4 py-3"><ProxyStatusBadge :status="proxy.status" /></td>
          <td class="px-4 py-3 text-gray-400">{{ formatDate(proxy.last_checked_at) }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <BaseButton
                variant="secondary"
                :disabled="proxy.status === 'checking'"
                @click="$emit('check', proxy)"
              >
                ↻
              </BaseButton>
              <BaseButton variant="secondary" @click="$emit('edit', proxy)">Edit</BaseButton>
              <BaseButton variant="danger" @click="$emit('delete', proxy)">Del</BaseButton>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>