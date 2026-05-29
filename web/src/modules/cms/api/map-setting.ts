import type { ResponseStruct } from '#/global'

export interface CmsMapSettingVo {
  id?: number
  title?: string
  address?: string
  latitude?: number | null
  longitude?: number | null
  zoom?: number
}

export function detail(): Promise<ResponseStruct<CmsMapSettingVo>> {
  return useHttp().get('/admin/cms/map-setting')
}

export function save(data: CmsMapSettingVo): Promise<ResponseStruct<null>> {
  return useHttp().put('/admin/cms/map-setting', data)
}

export function geocode(address: string): Promise<ResponseStruct<Pick<CmsMapSettingVo, 'address' | 'latitude' | 'longitude'>>> {
  return useHttp().post('/admin/cms/map-setting/geocode', { address })
}
