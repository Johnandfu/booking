<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import { ref, type Ref } from 'vue'
import { ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/page'
import PageForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:page' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '820px',
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
  header: { mainTitle: 'CMS 单页', subTitle: '管理前台独立页面' },
  tableOptions: { on: { onSelectionChange: (selection: any[]) => selections.value = selection } },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: '关键词', prop: 'keyword', render: 'input' },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { type: 'index' },
    { label: '标题', prop: 'title' },
    { label: 'Slug', prop: 'slug' },
    { label: 'SEO标题', prop: 'seo_title' },
    { label: '状态', prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'info'}>{row.status === 1 ? '启用' : '禁用'}</ElTag> },
    { label: '创建时间', prop: 'created_at', width: 180 },
    {
      type: 'operation',
      label: '操作',
      operationConfigure: {
        actions: [
          { name: 'edit', icon: 'material-symbols:edit-square-outline', text: '编辑', show: () => true, onClick: ({ row }) => { maDialog.setTitle('编辑单页'); maDialog.open({ formType: 'edit', data: row }) } },
          { name: 'delete', icon: 'mdi:delete', text: '删除', show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm('确认删除该单页？').then(async () => {
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
  msg.confirm('确认删除选中单页？').then(async () => {
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
        <el-button v-auth="['cms:page:create']" type="primary" @click="() => { maDialog.setTitle('新增单页'); maDialog.open({ formType: 'add' }) }">
          新增
        </el-button>
      </template>
      <template #toolbarLeft>
        <el-button v-auth="['cms:page:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          删除
        </el-button>
      </template>
    </MaProTable>
    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <PageForm ref="formRef" :form-type="formType" :data="data" />
      </template>
    </component>
  </div>
</template>
