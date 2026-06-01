<script setup lang="ts">
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { ref } from 'vue'
import type { CmsFrontendI18nVo } from '../../api/frontend-i18n'
import { create, save } from '../../api/frontend-i18n'
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

const maForm = ref<MaFormExpose>()
const model = ref<CmsFrontendI18nVo>({
  group_type: 'home',
  language_code: 'en',
  status: 1,
  sort: 0,
})

function formItems(): MaFormItem[] {
  return [
    { label: '分组', prop: 'group_type', render: 'input', itemProps: { rules: [{ required: true, message: '请输入分组' }] } },
    { label: '配置键', prop: 'key', render: 'input', itemProps: { rules: [{ required: true, message: '请输入配置键' }] } },
    { label: '语言', prop: 'language_code', render: 'input', itemProps: { rules: [{ required: true, message: '请输入语言' }] } },
    { label: '说明', prop: 'title', render: 'input' },
    { label: '文案', prop: 'value', render: 'input', renderProps: { type: 'textarea', rows: 4 } },
    { label: '排序', prop: 'sort', render: 'inputNumber', renderProps: { class: 'w-full' } },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { class: 'w-full', min: 0, max: 1 } },
  ]
}

function fillModel(): void {
  if (props.formType === 'edit' && props.data) {
    Object.keys(model.value).forEach((key) => {
      delete model.value[key as keyof CmsFrontendI18nVo]
    })
    Object.assign(model.value, props.data)
    return
  }

  Object.assign(model.value, {
    key: '',
    group_type: 'home',
    language_code: 'en',
    title: '',
    value: '',
    status: 1,
    sort: 0,
  })
  delete model.value.id
}

fillModel()

useForm('maForm').then((form: MaFormExpose) => {
  fillModel()
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
