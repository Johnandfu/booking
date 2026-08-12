import type { PageList, ResponseStruct } from '#/global'

export interface ServiceVo {
  id?: number
  shop_id?: number
  name?: string
  description?: string
  duration_minutes?: number
  capacity?: number
  status?: number
  shopline_product_id?: string
}

export function page(data: Record<string, any> = {}): Promise<ResponseStruct<PageList<ServiceVo>>> {
  return useHttp().get('/admin/booking/service/list', { params: data })
}

export function create(data: ServiceVo): Promise<ResponseStruct<ServiceVo>> {
  return useHttp().post('/admin/booking/service', data)
}

export function save(id: number, data: ServiceVo): Promise<ResponseStruct<ServiceVo>> {
  return useHttp().put(`/admin/booking/service/${id}`, data)
}

export function deleteById(id: number): Promise<ResponseStruct<null>> {
  return useHttp().delete(`/admin/booking/service/${id}`)
}
