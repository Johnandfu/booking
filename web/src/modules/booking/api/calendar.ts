import type { ResponseStruct } from '#/global'
import type { BookingVo } from './booking'

export function calendar(data: Record<string, any>): Promise<ResponseStruct<{ list: BookingVo[] }>> {
  return useHttp().get('/admin/booking/calendar', { params: data })
}
