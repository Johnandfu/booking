import type { PageList, ResponseStruct } from '#/global'

type OrderByDirection = 'asc' | 'desc'

export interface CmsContactMessageVo {
  id?: number
  name?: string
  email?: string
  phone?: string
  content?: string
  ip?: string
  user_agent?: string
  status?: number
  created_at?: string
  updated_at?: string
}

export interface CmsContactMessageSearchVo {
  keyword?: string
  status?: number
  order_by?: string
  order_by_direction?: OrderByDirection
  created_at?: string[]
  updated_at?: string[]
}

export function page(data: CmsContactMessageSearchVo): Promise<ResponseStruct<PageList<CmsContactMessageVo>>> {
  return useHttp().get('/admin/cms/contact-message/list', { params: data })
}

export function save(id: number, data: Pick<CmsContactMessageVo, 'status'>): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/contact-message/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/contact-message/${id}`)
  }
  return response as ResponseStruct<null>
}
