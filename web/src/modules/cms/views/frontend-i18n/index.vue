<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import { ref, type Ref } from 'vue'
import type { UseDialogExpose } from '../../../../hooks/useDialog'
import { ElTag } from 'element-plus'
import { deleteByIds, page } from '../../api/frontend-i18n'
import FrontendI18nForm from './form.vue'
import useDialog from '../../../../hooks/useDialog'
import { useMessage } from '../../../../hooks/useMessage'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:frontend-i18n' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const selections = ref<any[]>([])
const msg = useMessage()

const maDialog: UseDialogExpose = useDialog({
  lgWidth: '860px',
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
  header: { mainTitle: '前台多语言', subTitle: '管理前台 EN/ZH 静态文案' },
  tableOptions: { on: { onSelectionChange: (selection: any[]) => selections.value = selection } },
  searchOptions: { fold: true },
  searchFormOptions: { labelWidth: '90px' },
  requestOptions: { api: page },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: '关键词', prop: 'keyword', render: 'input' },
    { label: '分组', prop: 'group_type', render: 'input' },
    { label: '状态', prop: 'status', render: 'inputNumber', renderProps: { min: 0, max: 1 } },
  ],
  tableColumns: [
    { type: 'selection', showOverflowTooltip: false },
    { type: 'index' },
    { label: '分组', prop: 'group_type', width: 110 },
    { label: '配置键', prop: 'key', width: 180 },
    { label: '说明', prop: 'title', width: 160 },
    { label: '英文', prop: 'en', minWidth: 240 },
    { label: '中文', prop: 'zh', minWidth: 240 },
    { label: '排序', prop: 'sort', width: 90 },
    { label: '状态', prop: 'status', width: 100, cellRender: ({ row }) => <ElTag type={row.status === 1 ? 'success' : 'info'}>{row.status === 1 ? '启用' : '禁用'}</ElTag> },
    {
      type: 'operation',
      label: '操作',
      width: 160,
      operationConfigure: {
        actions: [
          { name: 'edit', icon: 'material-symbols:edit-square-outline', text: '编辑', show: () => true, onClick: ({ row }) => { maDialog.setTitle('编辑多语言'); maDialog.open({ formType: 'edit', data: row }) } },
          { name: 'delete', icon: 'mdi:delete', text: '删除', show: () => true, onClick: ({ row }, proxy) => {
            msg.delConfirm('确认删除该配置？').then(async () => {
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
  msg.confirm('确认删除选中配置？').then(async () => {
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
        <el-button v-auth="['cms:frontend-i18n:create']" type="primary" @click="() => { maDialog.setTitle('新增多语言'); maDialog.open({ formType: 'add' }) }">
          新增
        </el-button>
      </template>
      <template #toolbarLeft>
        <el-button v-auth="['cms:frontend-i18n:delete']" type="danger" plain :disabled="selections.length < 1" @click="handleDelete">
          删除
        </el-button>
      </template>
    </MaProTable>
    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <FrontendI18nForm ref="formRef" :key="`${formType}-${data?.id ?? 'new'}`" :form-type="formType" :data="data" />
      </template>
    </component>
  </div>
</template>
