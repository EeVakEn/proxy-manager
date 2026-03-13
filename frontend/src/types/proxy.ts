export type ProxyType = 'http' | 'https' | 'socks5'
export type ProxyStatus = 'active' | 'inactive' | 'checking'

export interface Proxy {
  id: number
  host: string
  port: number
  type: ProxyType
  login: string | null
  status: ProxyStatus
  last_checked_at: string | null
  created_at: string
  updated_at: string
}

export interface PaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number | null
  to: number | null
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
}

export interface ProxyFormData {
  host: string
  port: number | null
  type: ProxyType
  login: string
  password: string
}