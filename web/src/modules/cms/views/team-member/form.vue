<script setup lang="ts">
import type { CmsTeamMemberVo } from '../../api/team-member'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElOption } from 'element-plus'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'
import { create, save } from '../../api/team-member'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:team-member:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsTeamMemberVo | null
}>(), {
  formType: 'add',
  data: null,
})

const teamMemberForm = ref<MaFormExpose>()
const teamMemberModel = ref<CmsTeamMemberVo>({})

function formItems(): MaFormItem[] {
  return [
    { label: '姓名', prop: 'name', render: 'input', itemProps: { rules: [{ required: true, message: '请输入姓名' }] } },
    { label: '职位', prop: 'position', render: 'input' },
    { label: '电话', prop: 'phone', render: 'input' },
    { label: '头像', prop: 'avatar', render: () => MaUploadImage },
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

useForm('teamMemberForm').then((form: MaFormExpose) => {
  if (props.formType === 'edit' && props.data) {
    const data = props.data as Record<string, any>
    const model = teamMemberModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  if (props.formType === 'add') {
    teamMemberModel.value.status = 1
    teamMemberModel.value.sort = 0
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '90px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(teamMemberModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(teamMemberModel.value.id as number, teamMemberModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: teamMemberForm })
</script>

<template>
  <ma-form ref="teamMemberForm" v-model="teamMemberModel" />
</template>
