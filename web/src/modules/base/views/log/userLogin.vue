<!--
 - MineAdmin is committed to providing solutions for quickly building web applications
 - Please view the LICENSE file that was distributed with this source code,
 - For the full copyright and license information.
 - Thank you very much for using MineAdmin.
 -
 - @Author X.Mo<root@imoi.cn>
 - @Link   https://github.com/mineadmin
-->
<script setup lang="tsx">
import type { UserLoginVo } from '~/base/api/log.ts'
import { UserLoginLog } from '~/base/api/log.ts'
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import getColumns from './userLoginLogData/UserLoginLogColumn.tsx'
import getSearchItems from './userLoginLogData/UserLoginLogSearch.tsx'
import { ResultCode } from '@/utils/ResultCode.ts'
import { useMessage } from '@/hooks/useMessage.ts'

defineOptions({ name: 'log:userLogin' })

const t = useTrans().globalTrans
const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const selections: Ref<UserLoginVo[]> = ref([])
const msg = useMessage()
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

async function clickDelete() {
  const ids = selections.value.map((value: UserLoginVo) => value.id)
  msg.confirm(t('crud.delMessage')).then(async () => {
    const res = await UserLoginLog.delete(ids)
    if (res.code === ResultCode.SUCCESS) {
      proTableRef.value.refresh()
    }
  })
}

const options = ref<MaProTableOptions>({
  // 表格距离底部的像素偏移适配
  adaptionOffsetBottom: 161,
  header: {
    mainTitle: () => t('baseLoginLog.title'),
  },
  // 表格参数
  tableOptions: {
    defaultSort: { prop: 'id', order: 'descending' },
    on: {
      // 表格选择事件
      onSelectionChange: (selection: any[]) => selections.value = selection,
    },
  },
  // 搜索参数
  searchOptions: {
    fold: true,
    text: {
      searchBtn: () => t('crud.search'),
      resetBtn: () => t('crud.reset'),
      isFoldBtn: () => t('crud.searchFold'),
      notFoldBtn: () => t('crud.searchUnFold'),
    },
  },
  // 搜索表单参数
  searchFormOptions: { labelWidth: '90px' },
  // 请求配置
  requestOptions: {
    api: UserLoginLog.page,
    requestParams: { order_by: 'id', order_by_direction: 'desc' },
  },
})
// 架构配置
const schema = ref<MaProTableSchema>({
  // 搜索项
  searchItems: getSearchItems(t),
  // 表格列
  tableColumns: getColumns(t, renderIdHeader),
})
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema">
      <template #toolbarLeft>
        <el-button type="danger" plain :disabled="selections.length < 1" @click="clickDelete">
          {{ t('crud.delete') }}
        </el-button>
      </template>
    </MaProTable>
  </div>
</template>

<style scoped lang="scss">

</style>
