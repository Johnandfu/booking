<script setup lang="ts">
import type { CmsMapSettingVo } from '../../api/map-setting'
import { computed, onMounted, reactive, ref } from 'vue'
import { detail, geocode, save } from '../../api/map-setting'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:map-setting' })

const msg = useMessage()
const loading = ref(false)
const geocoding = ref(false)
const saving = ref(false)
const model = reactive<CmsMapSettingVo>({
  title: '',
  address: '',
  latitude: null,
  longitude: null,
  zoom: 14,
})

const mapUrl = computed(() => {
  const query = model.latitude != null && model.longitude != null
    ? `${model.latitude},${model.longitude}`
    : model.address
  return `https://www.google.com/maps?q=${encodeURIComponent(query || '')}&z=${model.zoom || 14}&output=embed`
})

async function loadSetting(): Promise<void> {
  loading.value = true
  try {
    const res = await detail()
    if (res.code === ResultCode.SUCCESS && res.data) {
      Object.assign(model, res.data)
    }
  }
  finally {
    loading.value = false
  }
}

async function handleGeocode(): Promise<void> {
  if (!model.address) {
    msg.error('请输入地址')
    return
  }

  geocoding.value = true
  try {
    const res = await geocode(model.address)
    if (res.code === ResultCode.SUCCESS && res.data) {
      Object.assign(model, res.data)
      msg.success('坐标获取成功')
      return
    }
    msg.error(res.message || '坐标获取失败')
  }
  catch (error: any) {
    msg.alertError(error)
  }
  finally {
    geocoding.value = false
  }
}

async function handleSave(): Promise<void> {
  saving.value = true
  try {
    const res = await save(model)
    res.code === ResultCode.SUCCESS ? msg.success('保存成功') : msg.error(res.message)
  }
  catch (error: any) {
    msg.alertError(error)
  }
  finally {
    saving.value = false
  }
}

onMounted(loadSetting)
</script>

<template>
  <div class="mine-layout pt-3">
    <el-card v-loading="loading" shadow="never">
      <template #header>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-base font-bold">
              前台 Google Map 配置
            </div>
            <div class="text-sm text-gray-500 mt-1">
              设置地址并获取坐标后，前台联系页会读取这里的配置。
            </div>
          </div>
          <el-button v-auth="['cms:map-setting:update']" type="primary" :loading="saving" @click="handleSave">
            保存配置
          </el-button>
        </div>
      </template>

      <el-row :gutter="24">
        <el-col :lg="12" :md="24">
          <el-form label-width="100px" :model="model">
            <el-form-item label="地图标题" required>
              <el-input v-model="model.title" placeholder="如 Brisbane, Queensland, Australia" />
            </el-form-item>
            <el-form-item label="地址" required>
              <el-input v-model="model.address" type="textarea" :rows="3" placeholder="请输入 Google Map 可识别的地址" />
            </el-form-item>
            <el-form-item label="坐标">
              <div class="w-full flex gap-3">
                <el-input-number v-model="model.latitude" class="flex-1!" :precision="7" :min="-90" :max="90" placeholder="纬度" />
                <el-input-number v-model="model.longitude" class="flex-1!" :precision="7" :min="-180" :max="180" placeholder="经度" />
              </div>
            </el-form-item>
            <el-form-item label="缩放级别">
              <el-input-number v-model="model.zoom" :min="1" :max="20" />
            </el-form-item>
            <el-form-item>
              <el-button v-auth="['cms:map-setting:update']" :loading="geocoding" @click="handleGeocode">
                根据地址获取坐标
              </el-button>
              <span class="ml-3 text-sm text-gray-500">需要后端配置 GOOGLE_MAPS_API_KEY</span>
            </el-form-item>
          </el-form>
        </el-col>

        <el-col :lg="12" :md="24">
          <div class="mb-2 text-sm text-gray-500">
            前台地图预览
          </div>
          <iframe
            :src="mapUrl"
            width="100%"
            height="360"
            style="border:0; display:block;"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
