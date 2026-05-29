import type { PageList, ResponseStruct } from '#/global'

export interface CmsCategoryVo {
  id?: number
  name?: string
  slug?: string
  description?: string
  sort?: number
  status?: number
  created_at?: string
  updated_at?: string
}

export interface CmsCategorySearchVo {
  keyword?: string
  status?: number
}

export function page(data: CmsCategorySearchVo): Promise<ResponseStruct<PageList<CmsCategoryVo>>> {
  return useHttp().get('/admin/cms/category/list', { params: data })
}

export function create(data: CmsCategoryVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/category', data)
}

export function save(id: number, data: CmsCategoryVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/category/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/category/${id}`)
  }
  return response as ResponseStruct<null>
}
