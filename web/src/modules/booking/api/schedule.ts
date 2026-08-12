import type { PageList, ResponseStruct } from '#/global'

export interface ScheduleVo {
  id?: number
  shop_id?: number
  staff_id?: number
  location_id?: number | null
  weekday?: number
  start_time?: string
  end_time?: string
  status?: number
}

export function page(data: Record<string, any> = {}): Promise<ResponseStruct<PageList<ScheduleVo>>> {
  return useHttp().get('/admin/booking/schedule/list', { params: data })
}

export function create(data: ScheduleVo): Promise<ResponseStruct<ScheduleVo>> {
  return useHttp().post('/admin/booking/schedule', data)
}

export function deleteById(id: number): Promise<ResponseStruct<null>> {
  return useHttp().delete(`/admin/booking/schedule/${id}`)
}
