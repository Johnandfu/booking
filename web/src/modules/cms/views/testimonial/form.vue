<script setup lang="ts">
import type { CmsTestimonialVo } from '../../api/testimonial'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'
import { create, save } from '../../api/testimonial'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:testimonial:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsTestimonialVo | null
}>(), {
  formType: 'add',
  data: null,
})

const testimonialForm = ref<MaFormExpose>()
const testimonialModel = ref<CmsTestimonialVo>({})

function formItems(): MaFormItem[] {
  return [
    { label: '客户姓名', prop: 'name', render: 'input', itemProps: { rules: [{ required: true, message: '请输入客户姓名' }] } },
    { label: '职位', prop: 'position', render: 'input' },
    { label: '头像', prop: 'avatar', render: () => MaUploadImage },
    { label: '反馈内容', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 5 } },
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

useForm('testimonialForm').then((form: MaFormExpose) => {
  if (props.formType === 'edit' && props.data) {
    const data = props.data as Record<string, any>
    const model = testimonialModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  if (props.formType === 'add') {
    testimonialModel.value.status = 1
    testimonialModel.value.sort = 0
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(testimonialModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(testimonialModel.value.id as number, testimonialModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: testimonialForm })
</script>

<template>
  <ma-form ref="testimonialForm" v-model="testimonialModel" />
</template>
