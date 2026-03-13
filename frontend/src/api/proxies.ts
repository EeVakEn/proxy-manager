import axios from 'axios'
import type { Proxy, ProxyFormData, PaginatedResponse } from '@/types/proxy'

const http = axios.create({
  baseURL: '/api/v1',
  headers: { 'Content-Type': 'application/json' },
})

export const proxiesApi = {
  getAll: (page = 1, perPage = 15) =>
    http.get<PaginatedResponse<Proxy>>('/proxies', { params: { page, per_page: perPage } }).then(r => r.data),

  create: (data: ProxyFormData) =>
    http.post<{ data: Proxy }>('/proxies', data).then(r => r.data.data),

  update: (id: number, data: Partial<ProxyFormData>) =>
    http.put<{ data: Proxy }>(`/proxies/${id}`, data).then(r => r.data.data),

  remove: (id: number) => http.delete(`/proxies/${id}`),

  triggerCheck: (id: number) =>
    http.post<{ data: Proxy }>(`/proxies/${id}/check`).then(r => r.data.data),
}
