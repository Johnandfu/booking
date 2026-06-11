<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElImage, ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/article'
import ArticleForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useTrans } from '../../../../hooks/auto-imports/useTrans'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:article' })

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
  lgWidth: '900px',
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
  header: { mainTitle: () => t('cms.article.title'), subTitle: () => t('cms.article.subTitle') },
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
    { label: () => t('cms.fields.categoryId'), prop: 'category_id', render: 'inputNumber', renderProps: { min: 1 } },
    { label: () => t('crud.status'), prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
    { label: () => t('crud.createTime'), prop: 'created_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
    { label: () => t('crud.updateTime'), prop: 'updated_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { label: 'ID', prop: 'id', width: 90, headerRender: renderIdHeader },
    { label: () => t('cms.fields.cover'), prop: 'cover', width: 100, cellRender: ({ row }) => row.cover ? <ElImage src={row.cover} fit="cover" style="width:64px;height:42px;border-radius:6px;" preview-src-list={[row.cover]} previewTeleported zIndex={3000} /> : '-' },
    { label: () => t('cms.fields.title'), prop: 'title', minWidth: 180 },
    { label: () => t('cms.fields.category'), prop: 'category.name', width: 120, cellRender: ({ row }) => row.category?.name ?? row.category_id },
    { label: () => t('cms.fields.slug'), prop: 'slug', minWidth: 160 },
    { label: () => t('cms.fields.author'), prop: 'author', width: 100 },
    { label: () => t('cms.fields.views'), prop: 'views', width: 90 },
    { label: () => t('crud.status'), prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'info'}>{row.status === 1 ? t('cms.status.published') : t('cms.status.draft')}</ElTag> },
    { label: () => t('cms.fields.publishedAt'), prop: 'published_at', width: 180 },
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
              maDialog.setTitle(t('cms.article.editTitle'))
              maDialog.open({ formType: 'edit', data: row })
            },
          },
          { name: 'delete', icon: 'mdi:delete', text: () => t('crud.delete'), show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm(t('cms.article.deleteConfirm')).then(async () => {
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
  msg.confirm(t('cms.article.batchDeleteConfirm')).then(async () => {
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
        <el-button v-auth="['cms:article:create']" type="primary" @click="() => { maDialog.setTitle(t('cms.article.addTitle')); maDialog.open({ formType: 'add' }) }">
          {{ t('crud.add') }}
        </el-button>
      </template>
      <template #toolbarLeft>
        <el-button v-auth="['cms:article:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          {{ t('crud.delete') }}
        </el-button>
      </template>
    </MaProTable>
    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <ArticleForm
          :key="formType === 'edit' ? `edit-${data?.id ?? 'new'}` : 'add'"
          ref="formRef"
          :form-type="formType"
          :data="data"
        />
      </template>
    </component>
  </div>
</template>
