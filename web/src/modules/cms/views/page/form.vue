<script setup lang="ts">
import type { CmsPageVo } from '../../api/page'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { create, save } from '../../api/page'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import { ref } from 'vue'

defineOptions({ name: 'cms:page:form' })

const { formType = 'add', data = null } = defineProps<{
  formType: 'add' | 'edit'
  data?: CmsPageVo | null
}>()

const maForm = ref<MaFormExpose>()
const model = ref<CmsPageVo>({})

function formItems(): MaFormItem[] {
  if (formType === 'add') {
    model.value.status = 1
  }

  return [
    { label: '标题', prop: 'title', render: 'input', itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: 'Slug', prop: 'slug', render: 'input', renderProps: { placeholder: '如 terms' }, itemProps: { rules: [{ required: true, message: '请输入 Slug' }] } },
    { label: '正文HTML', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 8 } },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { class: 'w-full', min: 0, max: 1 } },
    { label: 'SEO标题', prop: 'seo_title', render: 'input' },
    { label: 'SEO描述', prop: 'seo_description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
  ]
}

useForm('maForm').then((form: MaFormExpose) => {
  if (formType === 'edit' && data) {
    model.value = { ...data }
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '100px' })
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
