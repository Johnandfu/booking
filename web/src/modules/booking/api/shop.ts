/**
 * Booking shop APIs
 */
import type { PageList, ResponseStruct } from '#/global'

export interface ShopVo {
  id?: number
  handle?: string
  domain?: string
  timezone?: string
  status?: number
  installed_at?: string
}

export function page(data: Record<string, any> = {}): Promise<ResponseStruct<PageList<ShopVo>>> {
  return useHttp().get('/admin/booking/shop/list', { params: data })
}
