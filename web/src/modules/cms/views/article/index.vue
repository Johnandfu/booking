<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElImage, ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/article'
import ArticleForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:article' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '900px',
  ok: ({ formType }, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    formRef.value.maForm.getElFormRef().validate().then(() => {
      const action = formType === 'add' ? formRef.value.add() : formRef.value.edit()
      action.then((res: any) => {
        res.code === ResultCode.SUCCESS ? msg.success(formType === 'add' ? '创建成功' : '更新成功') : msg.error(res.message)
        maDialog.close()
        proTableRef.value.refresh()
      }).catch((err: any) => msg.alertError(err)).finally(() => okLoadingState(false))
    }).catch(() => okLoadingState(false))
  },
})

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: 'CMS 文章', subTitle: '管理前台文章内容' },
  tableOptions: { on: { onSelectionChange: (selection: any[]) => selections.value = selection } },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: '关键词', prop: 'keyword', render: 'input' },
    { label: '栏目ID', prop: 'category_id', render: 'inputNumber', renderProps: { min: 1 } },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
    { label: '创建时间', prop: 'created_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
    { label: '更新时间', prop: 'updated_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { type: 'index' },
    { label: '封面', prop: 'cover', width: 100, cellRender: ({ row }) => row.cover ? <ElImage src={row.cover} fit="cover" style="width:64px;height:42px;border-radius:6px;" preview-src-list={[row.cover]} /> : '-' },
    { label: '标题', prop: 'title', minWidth: 180 },
    { label: '栏目', prop: 'category.name', width: 120, cellRender: ({ row }) => row.category?.name ?? row.category_id },
    { label: 'Slug', prop: 'slug', minWidth: 160 },
    { label: '作者', prop: 'author', width: 100 },
    { label: '浏览', prop: 'views', width: 90 },
    { label: '状态', prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'info'}>{row.status === 1 ? '发布' : '草稿'}</ElTag> },
    { label: '发布时间', prop: 'published_at', width: 180 },
    {
      type: 'operation',
      label: '操作',
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:edit-square-outline',
            text: '编辑',
            show: () => true,
            onClick: ({ row }) => {
              maDialog.setTitle('编辑文章')
              maDialog.open({ formType: 'edit', data: row })
            },
          },
          { name: 'delete', icon: 'mdi:delete', text: '删除', show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm('确认删除该文章？').then(async () => {
              const response = await deleteByIds([row.id])
              if (response.code === ResultCode.SUCCESS) {
                msg.success('删除成功')
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
  msg.confirm('确认删除选中文章？').then(async () => {
    const response = await deleteByIds(ids)
    if (response.code === ResultCode.SUCCESS) {
      msg.success('删除成功')
      proTableRef.value.refresh()
    }
  })
}
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema">
      <template #actions>
        <el-button v-auth="['cms:article:create']" type="primary" @click="() => { maDialog.setTitle('新增文章'); maDialog.open({ formType: 'add' }) }">
          新增
        </el-button>
      </template>
      <template #toolbarLeft>
        <el-button v-auth="['cms:article:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          删除
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
