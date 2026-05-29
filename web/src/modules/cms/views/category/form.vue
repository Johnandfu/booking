<script setup lang="ts">
import type { CmsCategoryVo } from '../../api/category'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { create, save } from '../../api/category'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import { ref } from 'vue'

defineOptions({ name: 'cms:category:form' })

const { formType = 'add', data = null } = defineProps<{
  formType: 'add' | 'edit'
  data?: CmsCategoryVo | null
}>()

const maForm = ref<MaFormExpose>()
const model = ref<CmsCategoryVo>({})

function formItems(): MaFormItem[] {
  if (formType === 'add') {
    model.value.status = 1
    model.value.sort = 0
  }

  return [
    {
      label: '栏目名称',
      prop: 'name',
      render: 'input',
      itemProps: { rules: [{ required: true, message: '请输入栏目名称' }] },
    },
    {
      label: 'URL别名',
      prop: 'slug',
      render: 'input',
      renderProps: { placeholder: '如 news' },
      itemProps: { rules: [{ required: true, message: '请输入 URL 别名' }] },
    },
    {
      label: '栏目描述',
      prop: 'description',
      render: 'input',
      renderProps: { type: 'textarea', rows: 3 },
    },
    {
      label: '排序',
      prop: 'sort',
      render: 'inputNumber',
      cols: { md: 12, xs: 24 },
      renderProps: { class: 'w-full' },
    },
    {
      label: '状态',
      prop: 'status',
      render: 'inputNumber',
      cols: { md: 12, xs: 24 },
      renderProps: { class: 'w-full', min: 0, max: 1 },
    },
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
    create(model.value).then((res) => {
      res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)
    }).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(model.value.id as number, model.value).then((res) => {
      res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)
    }).catch(reject)
  })
}

defineExpose({ add, edit, maForm })
</script>

<template>
  <ma-form ref="maForm" v-model="model" />
</template>
