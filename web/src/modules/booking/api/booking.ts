/**
 * Booking admin APIs
 */
import type { PageList, ResponseStruct } from '#/global'

export interface BookingVo {
  id?: number
  shop_id?: number
  booking_no?: string
  status?: string
  customer_name?: string
  customer_email?: string
  customer_phone?: string
  service_id?: number
  location_id?: number
  staff_id?: number
  start_at?: string
  end_at?: string
  shopline_order_id?: string
  created_at?: string
}

export interface BookingSearchVo {
  shop_id?: number
  status?: string
  booking_no?: string
}

export function page(data: BookingSearchVo): Promise<ResponseStruct<PageList<BookingVo>>> {
  return useHttp().get('/admin/booking/list', { params: data })
}

export function cancel(id: number, shopId: number): Promise<ResponseStruct<null>> {
  return useHttp().post(`/admin/booking/${id}/cancel`, { shop_id: shopId })
}
