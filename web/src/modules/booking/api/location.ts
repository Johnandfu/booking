import type { PageList, ResponseStruct } from '#/global'

export interface LocationVo {
  id?: number
  shop_id?: number
  name?: string
  address?: string
  phone?: string
  status?: number
}

export function page(data: Record<string, any> = {}): Promise<ResponseStruct<PageList<LocationVo>>> {
  return useHttp().get('/admin/booking/location/list', { params: data })
}

export function create(data: LocationVo): Promise<ResponseStruct<LocationVo>> {
  return useHttp().post('/admin/booking/location', data)
}

export function save(id: number, data: LocationVo): Promise<ResponseStruct<LocationVo>> {
  return useHttp().put(`/admin/booking/location/${id}`, data)
}

export function deleteById(id: number): Promise<ResponseStruct<null>> {
  return useHttp().delete(`/admin/booking/location/${id}`)
}
