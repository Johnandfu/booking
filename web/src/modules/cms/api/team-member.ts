import type { PageList, ResponseStruct } from '#/global'

export interface CmsTeamMemberVo {
  id?: number
  name?: string
  position?: string
  phone?: string
  avatar?: string
  sort?: number
  status?: number
  created_at?: string
  updated_at?: string
}

export interface CmsTeamMemberSearchVo {
  keyword?: string
  status?: number
  created_at?: string[]
  updated_at?: string[]
}

export function page(data: CmsTeamMemberSearchVo): Promise<ResponseStruct<PageList<CmsTeamMemberVo>>> {
  return useHttp().get('/admin/cms/team-member/list', { params: data })
}

export function create(data: CmsTeamMemberVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/cms/team-member', data)
}

export function save(id: number, data: CmsTeamMemberVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/cms/team-member/${id}`, data)
}

export async function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  let response: ResponseStruct<null> | null = null
  for (const id of ids) {
    response = await useHttp().delete(`/admin/cms/team-member/${id}`)
  }
  return response as ResponseStruct<null>
}
