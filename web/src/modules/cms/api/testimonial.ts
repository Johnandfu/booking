import type { PageList, ResponseStruct } from '#/global'

type OrderByDirection = 'asc' | 'desc'

export interface CmsTestimonialVo {
  id?: number
  name?: string
  position?: string
  avatar?: string
  content?: string
  sort?: number
  status?: number
  created_at?: string
  updated_at?: string
}

export interface CmsTestimonialSearchVo {
  keyword?: string
  status?: number
  order_by?: string
  order_by_direction?: OrderByDirection
  created_at?: string[]
  updated_at?: string[]
}

export function page(data: CmsTestimonialSearchVo): Promise<ResponseStruct<PageList<CmsTestimonialVo>>> {
  return useHttp().get('/admin/cms/testimonial/list', { params: data })
}

export function create(data: CmsTestimonialVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/testimonial', data)
}

export function save(id: number, data: CmsTestimonialVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/testimonial/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/testimonial/${id}`)
  }
  return response as ResponseStruct<null>
}
