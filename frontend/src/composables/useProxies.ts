import { ref, onMounted, onUnmounted } from 'vue'
import { proxiesApi } from '@/api/proxies'
import { useToast } from '@/composables/useToast'
import type { Proxy, ProxyFormData, PaginationMeta } from '@/types/proxy'

const POLL_INTERVAL = 10000
const PER_PAGE = 10

export function useProxies() {
  const proxies = ref<Proxy[]>([])
  const meta    = ref<PaginationMeta | null>(null)
  const page    = ref(1)
  const loading = ref(false)
  const toast   = useToast()

  let pollTimer: ReturnType<typeof setInterval> | null = null

  async function fetchProxies() {
    try {
      const response = await proxiesApi.getAll(page.value, PER_PAGE)
      proxies.value = response.data
      meta.value    = response.meta
    } catch {
      toast.error('Failed to load proxy list')
    }
  }

  async function goToPage(p: number) {
    page.value = p
    await fetchProxies()
  }

  async function addProxy(data: ProxyFormData) {
    await proxiesApi.create(data)
    await fetchProxies()
    toast.success('Proxy added')
  }

  async function editProxy(id: number, data: Partial<ProxyFormData>) {
    const updated = await proxiesApi.update(id, data)
    const idx = proxies.value.findIndex(p => p.id === id)
    if (idx !== -1) proxies.value[idx] = updated
    toast.success('Proxy updated')
  }

  async function deleteProxy(id: number) {
    await proxiesApi.remove(id)
    if (proxies.value.length === 1 && page.value > 1) page.value--
    await fetchProxies()
    toast.success('Proxy deleted')
  }

  async function checkProxy(id: number) {
    const idx = proxies.value.findIndex(p => p.id === id)
    const proxy = proxies.value[idx]
    if (idx !== -1 && proxy) proxy.status = 'checking'

    const updated = await proxiesApi.triggerCheck(id)
    if (idx !== -1 && proxy) proxies.value[idx] = updated
    toast.info('Health check started')
  }

  onMounted(async () => {
    loading.value = true
    await fetchProxies()
    loading.value = false
    pollTimer = setInterval(fetchProxies, POLL_INTERVAL)
  })

  onUnmounted(() => {
    if (pollTimer) clearInterval(pollTimer)
  })

  return { proxies, meta, page, loading, goToPage, addProxy, editProxy, deleteProxy, checkProxy }
}
