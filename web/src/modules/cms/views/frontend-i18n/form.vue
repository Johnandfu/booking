<script setup lang="ts">
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import type { CmsFrontendI18nVo } from '../../api/frontend-i18n'
import { create, languages, save } from '../../api/frontend-i18n'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:frontend-i18n:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsFrontendI18nVo | null
}>(), {
  formType: 'add',
  data: null,
})

const frontendI18nForm = ref<MaFormExpose>()
const frontendI18nModel = ref<CmsFrontendI18nVo>({
  group_type: 'home',
  language_code: 'en',
  status: 1,
  sort: 0,
})
const languageOptions = ref<Record<string, string>>({})

function formItems(): MaFormItem[] {
  return [
    { label: '分组', prop: 'group_type', render: 'input', itemProps: { rules: [{ required: true, message: '请输入分组' }] } },
    { label: '配置键', prop: 'key', render: 'input', itemProps: { rules: [{ required: true, message: '请输入配置键' }] } },
    {
      label: '语言',
      prop: 'language_code',
      render: 'select',
      renderProps: { class: 'w-full' },
      renderSlots: {
        default: () => Object.entries(languageOptions.value).map(([value, label]) => h(ElOption, { label, value })),
      },
      itemProps: { rules: [{ required: true, message: '请选择语言' }] },
    },
    { label: '说明', prop: 'title', render: 'input' },
    { label: '文案', prop: 'value', render: 'input', renderProps: { type: 'textarea', rows: 4 } },
    { label: '排序', prop: 'sort', render: 'inputNumber', renderProps: { class: 'w-full' } },
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
  ]
}

useForm('frontendI18nForm').then(async (form: MaFormExpose) => {
  const res = await languages()
  if (res.code === ResultCode.SUCCESS) {
    languageOptions.value = res.data || {}
  }

  if (props.formType === 'edit' && props.data) {
    const data = props.data as Record<string, any>
    const model = frontendI18nModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  if (props.formType === 'add') {
    frontendI18nModel.value.group_type = 'home'
    frontendI18nModel.value.language_code = 'en'
    frontendI18nModel.value.status = 1
    frontendI18nModel.value.sort = 0
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(frontendI18nModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(frontendI18nModel.value.id as number, frontendI18nModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: frontendI18nForm })
</script>

<template>
  <ma-form ref="frontendI18nForm" v-model="frontendI18nModel" />
</template>
