<script setup lang="ts">
import type { CmsPageVo } from '../../api/page'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { create, save } from '../../api/page'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'

defineOptions({ name: 'cms:page:form' })

const { formType = 'add', data = null } = defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsPageVo | null
}>()

const pageForm = ref<MaFormExpose>()
const pageModel = ref<CmsPageVo>({})

function formItems(): MaFormItem[] {
  if (formType === 'add') {
    pageModel.value.status = 1
  }

  return [
    { label: '标题', prop: 'title', render: 'input', itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: 'Slug', prop: 'slug', render: 'input', renderProps: { placeholder: '如 terms' }, itemProps: { rules: [{ required: true, message: '请输入 Slug' }] } },
    { label: '正文HTML', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 8 } },
    {
      label: '状态',
      prop: 'status',
      render: 'select',
      renderProps: { class: 'w-full' },
      renderSlots: {
        default: () => [
          h(ElOption, { label: '启用', value: 1 }),
          h(ElOption, { label: '禁用', value: 0 }),
        ],
      },
    },
    { label: 'SEO标题', prop: 'seo_title', render: 'input' },
    { label: 'SEO描述', prop: 'seo_description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
  ]
}

useForm('pageForm').then((form: MaFormExpose) => {
  if (formType === 'edit' && data) {
    const model = pageModel.value as Record<string, any>
    Object.keys(data).map((key: string) => {
      model[key] = (data as Record<string, any>)[key]
    })
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '100px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(pageModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(pageModel.value.id as number, pageModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: pageForm })
</script>

<template>
  <ma-form ref="pageForm" v-model="pageModel" />
</template>
