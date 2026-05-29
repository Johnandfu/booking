<script setup lang="ts">
import type { CmsBannerVo } from '../../api/banner'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { ref } from 'vue'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'
import { create, save } from '../../api/banner'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:banner:form' })

const { formType = 'add', data = null } = defineProps<{
  formType: 'add' | 'edit'
  data?: CmsBannerVo | null
}>()

const maForm = ref<MaFormExpose>()
const model = ref<CmsBannerVo>({})

function formItems(): MaFormItem[] {
  if (formType === 'add') {
    model.value.status = 1
    model.value.sort = 0
  }

  return [
    { label: '标题', prop: 'title', render: 'input', itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: '图片', prop: 'image', render: () => MaUploadImage, itemProps: { rules: [{ required: true, message: '请上传图片' }] } },
    { label: '链接', prop: 'link', render: 'input', renderProps: { placeholder: '如 /article/demo' } },
    { label: '描述', prop: 'description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
    { label: '排序', prop: 'sort', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full' } },
    { label: '状态', prop: 'status', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full', min: 0, max: 1 } },
  ]
}

useForm('maForm').then((form: MaFormExpose) => {
  if (formType === 'edit' && data) {
    model.value = { ...data }
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(model.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(model.value.id as number, model.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm })
</script>

<template>
  <ma-form ref="maForm" v-model="model" />
</template>
