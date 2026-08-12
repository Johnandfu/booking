import type { PageList, ResponseStruct } from '#/global'

export interface StaffVo {
  id?: number
  shop_id?: number
  name?: string
  email?: string
  phone?: string
  status?: number
}

export function page(data: Record<string, any> = {}): Promise<ResponseStruct<PageList<StaffVo>>> {
  return useHttp().get('/admin/booking/staff/list', { params: data })
}

export function create(data: StaffVo): Promise<ResponseStruct<StaffVo>> {
  return useHttp().post('/admin/booking/staff', data)
}

export function save(id: number, data: StaffVo): Promise<ResponseStruct<StaffVo>> {
  return useHttp().put(`/admin/booking/staff/${id}`, data)
}

export function deleteById(id: number): Promise<ResponseStruct<null>> {
  return useHttp().delete(`/admin/booking/staff/${id}`)
}
