import type { PageList, ResponseStruct } from '#/global'
import type { CmsCategoryVo } from './category'

export interface CmsArticleVo {
  id?: number
  category_id?: number
  category?: CmsCategoryVo
  title?: string
  slug?: string
  summary?: string
  content?: string
  cover?: string
  author?: string
  views?: number
  status?: number
  published_at?: string
  seo_title?: string
  seo_description?: string
  created_at?: string
  updated_at?: string
}

export interface CmsArticleSearchVo {
  keyword?: string
  category_id?: number
  status?: number
  created_at?: string[]
  updated_at?: string[]
}

export function page(data: CmsArticleSearchVo): Promise<ResponseStruct<PageList<CmsArticleVo>>> {
  return useHttp().get('/admin/cms/article/list', { params: data })
}

export function create(data: CmsArticleVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/article', data)
}

export function save(id: number, data: CmsArticleVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/article/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/article/${id}`)
  }
  return response as ResponseStruct<null>
}
