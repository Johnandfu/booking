<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/contact-message'
import MessageForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:contact-message' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '680px',
  ok: (_, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    formRef.value?.maForm?.getElFormRef().validate().then(() => {
      formRef.value?.edit().then((res: any) => {
        res.code === ResultCode.SUCCESS ? msg.success('更新成功') : msg.error(res.message)
        maDialog.close()
        proTableRef.value.refresh()
      }).catch((err: any) => msg.alertError(err)).finally(() => okLoadingState(false))
    }).catch(() => okLoadingState(false))
  },
})

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: 'CMS 留言', subTitle: '管理前台联系表单留言' },
  tableOptions: { on: { onSelectionChange: (selection: any[]) => selections.value = selection } },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: '关键词', prop: 'keyword', render: 'input' },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
    { label: '创建时间', prop: 'created_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
    { label: '更新时间', prop: 'updated_at', render: 'datePicker', renderProps: { type: 'daterange', valueFormat: 'YYYY-MM-DD HH:mm:ss' } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { type: 'index' },
    { label: '姓名', prop: 'name', width: 120 },
    { label: '邮箱', prop: 'email', minWidth: 180 },
    { label: '电话', prop: 'phone', width: 140 },
    { label: '内容', prop: 'content', minWidth: 260 },
    { label: 'IP', prop: 'ip', width: 140 },
    { label: '状态', prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'warning'}>{row.status === 1 ? '已处理' : '未处理'}</ElTag> },
    { label: '创建时间', prop: 'created_at', width: 180 },
    {
      type: 'operation',
      label: '操作',
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:edit-square-outline',
            text: '处理',
            show: () => true,
            onClick: ({ row }) => {
              maDialog.setTitle('处理留言')
              maDialog.open({ data: row })
            },
          },
          { name: 'delete', icon: 'mdi:delete', text: '删除', show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm('确认删除该留言？').then(async () => {
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
  msg.confirm('确认删除选中留言？').then(async () => {
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
      <template #toolbarLeft>
        <el-button v-auth="['cms:contact-message:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          删除
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
