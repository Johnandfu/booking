<script setup lang="ts">
import type { CmsBannerVo } from '../../api/banner'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'
import { create, save } from '../../api/banner'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:banner:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsBannerVo | null
}>(), {
  formType: 'add',
  data: null,
})

const bannerForm = ref<MaFormExpose>()
const bannerModel = ref<CmsBannerVo>({})

function formItems(): MaFormItem[] {
  return [
    { label: '标题', prop: 'title', render: 'input', itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: '图片', prop: 'image', render: () => MaUploadImage, itemProps: { rules: [{ required: true, message: '请上传图片' }] } },
    { label: '链接', prop: 'link', render: 'input', renderProps: { placeholder: '如 /article/demo' } },
    { label: '描述', prop: 'description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
    { label: '排序', prop: 'sort', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full' } },
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

useForm('bannerForm').then((form: MaFormExpose) => {
  if (props.formType === 'edit' && props.data) {
    const data = props.data as Record<string, any>
    const model = bannerModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  if (props.formType === 'add') {
    bannerModel.value.status = 1
    bannerModel.value.sort = 0
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(bannerModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(bannerModel.value.id as number, bannerModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: bannerForm })
</script>

<template>
  <ma-form ref="bannerForm" v-model="bannerModel" />
</template>
