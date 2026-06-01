<script setup lang="ts">
import type { CmsContactMessageVo } from '../../api/contact-message'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import { save } from '../../api/contact-message'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:contact-message:form' })

const props = withDefaults(defineProps<{
  data?: CmsContactMessageVo | null
}>(), {
  data: null,
})

const contactMessageForm = ref<MaFormExpose>()
const contactMessageModel = ref<CmsContactMessageVo>({})

function formItems(): MaFormItem[] {
  return [
    { label: '姓名', prop: 'name', render: 'input', renderProps: { disabled: true } },
    { label: '邮箱', prop: 'email', render: 'input', renderProps: { disabled: true } },
    { label: '电话', prop: 'phone', render: 'input', renderProps: { disabled: true } },
    { label: '内容', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 5, disabled: true } },
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

useForm('contactMessageForm').then((form: MaFormExpose) => {
  if (props.data) {
    const data = props.data as Record<string, any>
    const model = contactMessageModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(contactMessageModel.value.id as number, { status: contactMessageModel.value.status }).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ edit, maForm: contactMessageForm })
</script>

<template>
  <ma-form ref="contactMessageForm" v-model="contactMessageModel" />
</template>
