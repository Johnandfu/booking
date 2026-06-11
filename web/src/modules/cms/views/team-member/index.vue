<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ref } from 'vue'
import { ElImage, ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/team-member'
import TeamMemberForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useTrans } from '../../../../hooks/auto-imports/useTrans'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:team-member' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()
const t = useTrans().globalTrans

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '700px',
  ok: ({ formType }, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    formRef.value.maForm.getElFormRef().validate().then(() => {
      const action = formType === 'add' ? formRef.value.add() : formRef.value.edit()
      action.then((res: any) => {
        res.code === ResultCode.SUCCESS ? msg.success(formType === 'add' ? t('crud.createSuccess') : t('crud.updateSuccess')) : msg.error(res.message)
        maDialog.close()
        proTableRef.value.refresh()
      }).catch((err: any) => msg.alertError(err)).finally(() => okLoadingState(false))
    }).catch(() => okLoadingState(false))
  },
})

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: () => t('cms.teamMember.title'), subTitle: () => t('cms.teamMember.subTitle') },
  tableOptions: { on: { onSelectionChange: (selection: any[]) => selections.value = selection } },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: () => t('cms.fields.keyword'), prop: 'keyword', render: 'input' },
    { label: () => t('crud.status'), prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
    { label: () => t('crud.createTime'), prop: 'created_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
    { label: () => t('crud.updateTime'), prop: 'updated_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { label: 'ID', prop: 'id', width: 80 },
    { label: () => t('cms.fields.avatar'), prop: 'avatar', width: 100, cellRender: ({ row }) => row.avatar ? <ElImage src={row.avatar} fit="cover" style="width:52px;height:52px;border-radius:50%;" preview-src-list={[row.avatar]} previewTeleported zIndex={3000} /> : '-' },
    { label: () => t('cms.fields.name'), prop: 'name' },
    { label: () => t('cms.fields.position'), prop: 'position' },
    { label: () => t('cms.fields.phone'), prop: 'phone' },
    { label: () => t('crud.sort'), prop: 'sort', width: 100 },
    { label: () => t('crud.status'), prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'info'}>{row.status === 1 ? t('dictionary.system.statusEnabled') : t('dictionary.system.statusDisabled')}</ElTag> },
    { label: () => t('crud.createTime'), prop: 'created_at', width: 180 },
    {
      type: 'operation',
      label: () => t('crud.operation'),
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:edit-square-outline',
            text: () => t('crud.edit'),
            show: () => true,
            onClick: ({ row }) => {
              maDialog.setTitle(t('cms.teamMember.editTitle'))
              maDialog.open({ formType: 'edit', data: row })
            },
          },
          { name: 'delete', icon: 'mdi:delete', text: () => t('crud.delete'), show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm(t('cms.teamMember.deleteConfirm')).then(async () => {
              const response = await deleteByIds([row.id])
              if (response.code === ResultCode.SUCCESS) {
                msg.success(t('crud.delSuccess'))
                proxy.refresh()
              }
            })
          } },
        ],
      },
    },
  ],
})

function handleDelete() {
  const ids = selections.value.map(item => item.id)
  msg.confirm(t('cms.teamMember.batchDeleteConfirm')).then(async () => {
    const response = await deleteByIds(ids)
    if (response.code === ResultCode.SUCCESS) {
      msg.success(t('crud.delSuccess'))
      proTableRef.value.refresh()
    }
  })
}
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema">
      <template #actions>
        <el-button v-auth="['cms:team-member:create']" type="primary" @click="() => { maDialog.setTitle(t('cms.teamMember.addTitle')); maDialog.open({ formType: 'add' }) }">
          {{ t('crud.add') }}
        </el-button>
      </template>
      <template #toolbarLeft>
        <el-button v-auth="['cms:team-member:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          {{ t('crud.delete') }}
        </el-button>
      </template>
    </MaProTable>
    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <TeamMemberForm ref="formRef" :key="`${formType}-${data?.id ?? 'new'}`" :form-type="formType" :data="data" />
      </template>
    </component>
  </div>
</template>
