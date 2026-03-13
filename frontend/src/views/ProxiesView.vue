<script setup lang="ts">
import { ref } from 'vue'
import { useProxies } from '@/composables/useProxies'
import type { Proxy, ProxyFormData } from '@/types/proxy'
import ProxyTable from '@/components/ProxyTable.vue'
import ProxyForm from '@/components/ProxyForm.vue'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BasePagination from '@/components/ui/BasePagination.vue'

const { proxies, meta, loading, error, goToPage, addProxy, editProxy, deleteProxy, checkProxy } = useProxies()

const showForm      = ref(false)
const editingProxy  = ref<Proxy | null>(null)
const deletingProxy = ref<Proxy | null>(null)

function openAdd() {
  editingProxy.value = null
  showForm.value = true
}

function openEdit(proxy: Proxy) {
  editingProxy.value = proxy
  showForm.value = true
}

function closeForm() {
  showForm.value = false
  editingProxy.value = null
}

async function handleSubmit(data: ProxyFormData) {
  if (editingProxy.value) {
    await editProxy(editingProxy.value.id, data)
  } else {
    await addProxy(data)
  }
  closeForm()
}

async function handleConfirmDelete() {
  if (deletingProxy.value) {
    await deleteProxy(deletingProxy.value.id)
    deletingProxy.value = null
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">

      <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Proxy Manager</h1>
        <BaseButton @click="openAdd">+ Add proxy</BaseButton>
      </div>

      <div v-if="error" class="mb-4 rounded bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        {{ error }}
      </div>

      <div v-if="loading" class="flex justify-center py-16">
        <span class="h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent" />
      </div>

      <template v-else>
        <ProxyTable
          :proxies="proxies"
          @edit="openEdit"
          @delete="proxy => deletingProxy = proxy"
          @check="proxy => checkProxy(proxy.id)"
        />
        <BasePagination v-if="meta" :meta="meta" @change="goToPage" />
      </template>

    </div>

    <ProxyForm
      v-if="showForm"
      :proxy="editingProxy ?? undefined"
      @submit="handleSubmit"
      @close="closeForm"
    />

    <ConfirmDialog
      v-if="deletingProxy"
      :message="`Delete proxy ${deletingProxy.host}:${deletingProxy.port}?`"
      @confirm="handleConfirmDelete"
      @cancel="deletingProxy = null"
    />
  </div>
</template>
