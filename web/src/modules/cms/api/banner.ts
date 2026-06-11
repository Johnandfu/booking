import type { PageList, ResponseStruct } from '#/global'

type OrderByDirection = 'asc' | 'desc'

export interface CmsBannerVo {
  id?: number
  title?: string
  image?: string
  link?: string
  description?: string
  sort?: number
  status?: number
  created_at?: string
  updated_at?: string
}

export interface CmsBannerSearchVo {
  keyword?: string
  status?: number
  order_by?: string
  order_by_direction?: OrderByDirection
  created_at?: string[]
  updated_at?: string[]
}

export function page(data: CmsBannerSearchVo): Promise<ResponseStruct<PageList<CmsBannerVo>>> {
  return useHttp().get('/admin/cms/banner/list', { params: data })
}

export function create(data: CmsBannerVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/banner', data)
}

export function save(id: number, data: CmsBannerVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/banner/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/banner/${id}`)
  }
  return response as ResponseStruct<null>
}
