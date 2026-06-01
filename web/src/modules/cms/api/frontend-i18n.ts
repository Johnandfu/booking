import type { PageList, ResponseStruct } from '#/global'

export interface CmsFrontendI18nVo {
  id?: number
  group_type?: string
  key?: string
  title?: string
  en?: string
  zh?: string
  status?: number
  sort?: number
  created_at?: string
  updated_at?: string
}

export interface CmsFrontendI18nSearchVo {
  keyword?: string
  group_type?: string
  status?: number
}

export function page(data: CmsFrontendI18nSearchVo): Promise<ResponseStruct<PageList<CmsFrontendI18nVo>>> {
  return useHttp().get('/admin/cms/frontend-i18n/list', { params: data })
}

export function create(data: CmsFrontendI18nVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/frontend-i18n', data)
}

export function save(id: number, data: CmsFrontendI18nVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/frontend-i18n/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/frontend-i18n/${id}`)
  }
  return response as ResponseStruct<null>
}
