<script setup lang="ts">
import type { CmsContactMessageVo } from '../../api/contact-message'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { save } from '../../api/contact-message'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import { ref } from 'vue'

defineOptions({ name: 'cms:contact-message:form' })

const { data = null } = defineProps<{
  data?: CmsContactMessageVo | null
}>()

const maForm = ref<MaFormExpose>()
const model = ref<CmsContactMessageVo>({})

function formItems(): MaFormItem[] {
  return [
    { label: '姓名', prop: 'name', render: 'input', renderProps: { disabled: true } },
    { label: '邮箱', prop: 'email', render: 'input', renderProps: { disabled: true } },
    { label: '电话', prop: 'phone', render: 'input', renderProps: { disabled: true } },
    { label: '内容', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 5, disabled: true } },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { class: 'w-full', min: 0, max: 1 } },
  ]
}

useForm('maForm').then((form: MaFormExpose) => {
  if (data) {
    model.value = { ...data }
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(model.value.id as number, { status: model.value.status }).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ edit, maForm })
</script>

<template>
  <ma-form ref="maForm" v-model="model" />
</template>
