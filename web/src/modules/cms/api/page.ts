import type { PageList, ResponseStruct } from '#/global'

export interface CmsPageVo {
  id?: number
  title?: string
  slug?: string
  content?: string
  status?: number
  seo_title?: string
  seo_description?: string
  created_at?: string
  updated_at?: string
}

export interface CmsPageSearchVo {
  keyword?: string
  status?: number
}

export function page(data: CmsPageSearchVo): Promise<ResponseStruct<PageList<CmsPageVo>>> {
  return useHttp().get('/admin/cms/page/list', { params: data })
}

export function create(data: CmsPageVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/page', data)
}

export function save(id: number, data: CmsPageVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/page/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/page/${id}`)
  }
  return response as ResponseStruct<null>
}
