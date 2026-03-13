<script setup lang="ts">
import { reactive } from 'vue'
import type { Proxy, ProxyFormData } from '@/types/proxy'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseModal from '@/components/ui/BaseModal.vue'

const props = defineProps<{ proxy?: Proxy }>()
const emit = defineEmits<{
  submit: [data: ProxyFormData]
  close: []
}>()

const form = reactive<ProxyFormData>({
  host:     props.proxy?.host     ?? '',
  port:     props.proxy?.port     ?? null,
  type:     props.proxy?.type     ?? 'http',
  login:    props.proxy?.login    ?? '',
  password: '',
})

function handleSubmit() {
  emit('submit', { ...form })
}
</script>

<template>
  <BaseModal :title="proxy ? 'Edit proxy' : 'Add proxy'" @close="emit('close')">
    <form class="space-y-3" @submit.prevent="handleSubmit">
      <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
          <label class="block text-xs font-medium text-gray-600 mb-1">Host</label>
          <input
            v-model="form.host"
            required
            placeholder="192.168.1.1"
            class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Port</label>
          <input
            v-model.number="form.port"
            required
            type="number"
            min="1"
            max="65535"
            placeholder="8080"
            class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
          <select
            v-model="form.type"
            class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500"
          >
            <option value="http">HTTP</option>
            <option value="https">HTTPS</option>
            <option value="socks5">SOCKS5</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Login</label>
          <input
            v-model="form.login"
            placeholder="optional"
            class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Password</label>
          <input
            v-model="form.password"
            type="password"
            placeholder="optional"
            class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500"
          />
        </div>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <BaseButton variant="secondary" type="button" @click="emit('close')">Cancel</BaseButton>
        <BaseButton type="submit">{{ proxy ? 'Save' : 'Add' }}</BaseButton>
      </div>
    </form>
  </BaseModal>
</template>