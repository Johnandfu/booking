<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/contact-message'
import MessageForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useTrans } from '../../../../hooks/auto-imports/useTrans'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:contact-message' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()
const t = useTrans().globalTrans
const orderByDirection = ref<'asc' | 'desc'>('desc')

function toggleIdSort() {
  orderByDirection.value = orderByDirection.value === 'desc' ? 'asc' : 'desc'
  proTableRef.value?.setRequestParams({ order_by: 'id', order_by_direction: orderByDirection.value }, true)
}

function renderIdHeader() {
  return (
    <button
      type="button"
      style="display:inline-flex;align-items:center;gap:4px;border:0;background:transparent;font-weight:700;cursor:pointer;"
      onClick={(event: MouseEvent) => {
        event.stopPropagation()
        toggleIdSort()
      }}
    >
      <span>ID</span>
      <span style="display:inline-grid;font-size:10px;line-height:9px;">
        <span style={{ color: orderByDirection.value === 'asc' ? 'var(--el-color-primary)' : 'var(--el-text-color-placeholder)' }}>▲</span>
        <span style={{ color: orderByDirection.value === 'desc' ? 'var(--el-color-primary)' : 'var(--el-text-color-placeholder)' }}>▼</span>
      </span>
    </button>
  )
}

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '680px',
  ok: (_, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    formRef.value?.maForm?.getElFormRef().validate().then(() => {
      formRef.value?.edit().then((res: any) => {
        res.code === ResultCode.SUCCESS ? msg.success(t('crud.updateSuccess')) : msg.error(res.message)
        maDialog.close()
        proTableRef.value.refresh()
      }).catch((err: any) => msg.alertError(err)).finally(() => okLoadingState(false))
    }).catch(() => okLoadingState(false))
  },
})

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: () => t('cms.contactMessage.title'), subTitle: () => t('cms.contactMessage.subTitle') },
  tableOptions: {
    defaultSort: { prop: 'id', order: 'descending' },
    on: {
      onSelectionChange: (selection: any[]) => selections.value = selection,
    },
  },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page, requestParams: { order_by: 'id', order_by_direction: 'desc' } },
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
    { label: 'ID', prop: 'id', width: 90, headerRender: renderIdHeader },
    { label: () => t('cms.fields.name'), prop: 'name', width: 120 },
    { label: () => t('cms.fields.email'), prop: 'email', minWidth: 180 },
    { label: () => t('cms.fields.phone'), prop: 'phone', width: 140 },
    { label: () => t('cms.fields.content'), prop: 'content', minWidth: 260 },
    { label: () => t('cms.fields.ip'), prop: 'ip', width: 140 },
    { label: () => t('crud.status'), prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'warning'}>{row.status === 1 ? t('cms.status.processed') : t('cms.status.pending')}</ElTag> },
    { label: () => t('crud.createTime'), prop: 'created_at', width: 180 },
    {
      type: 'operation',
      label: () => t('crud.operation'),
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:edit-square-outline',
            text: () => t('cms.actions.process'),
            show: () => true,
            onClick: ({ row }) => {
              maDialog.setTitle(t('cms.contactMessage.processTitle'))
              maDialog.open({ data: row })
            },
          },
          { name: 'delete', icon: 'mdi:delete', text: () => t('crud.delete'), show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm(t('cms.contactMessage.deleteConfirm')).then(async () => {
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
  msg.confirm(t('cms.contactMessage.batchDeleteConfirm')).then(async () => {
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
      <template #toolbarLeft>
        <el-button v-auth="['cms:contact-message:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          {{ t('crud.delete') }}
        </el-button>
      </template>
    </MaProTable>
    <component :is="maDialog.Dialog">
      <template #default="{ data }">
        <MessageForm ref="formRef" :key="`edit-${data?.id ?? 'new'}`" :data="data" />
      </template>
    </component>
  </div>
</template>
