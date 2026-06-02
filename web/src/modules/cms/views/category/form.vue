<script setup lang="ts">
import type { CmsCategoryVo } from '../../api/category'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { create, save } from '../../api/category'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'

defineOptions({ name: 'cms:category:form' })

const { formType = 'add', data = null } = defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsCategoryVo | null
}>()

const categoryForm = ref<MaFormExpose>()
const categoryModel = ref<CmsCategoryVo>({})

function formItems(): MaFormItem[] {
  if (formType === 'add') {
    categoryModel.value.status = 1
    categoryModel.value.sort = 0
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
      label: '栏目图片',
      prop: 'image',
      render: () => MaUploadImage,
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
      render: 'select',
      cols: { md: 12, xs: 24 },
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

useForm('categoryForm').then((form: MaFormExpose) => {
  if (formType === 'edit' && data) {
    const model = categoryModel.value as Record<string, any>
    Object.keys(data).map((key: string) => {
      model[key] = (data as Record<string, any>)[key]
    })
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(categoryModel.value).then((res) => {
      res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)
    }).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(categoryModel.value.id as number, categoryModel.value).then((res) => {
      res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)
    }).catch(reject)
  })
}

defineExpose({ add, edit, maForm: categoryForm })
</script>

<template>
  <ma-form ref="categoryForm" v-model="categoryModel" />
</template>
