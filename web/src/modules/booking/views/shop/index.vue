<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { page } from '~/booking/api/shop'

defineOptions({ name: 'booking:shop' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: {
    mainTitle: () => 'Shops',
    subTitle: () => 'Installed SHOPLINE stores',
  },
  requestOptions: {
    api: page,
  },
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    {
      label: () => 'Handle',
      prop: 'handle',
      render: () => <el-input />,
    },
  ],
  tableColumns: [
    { label: () => 'ID', prop: 'id', width: '80px' },
    { label: () => 'Handle', prop: 'handle', minWidth: '140px' },
    { label: () => 'Domain', prop: 'domain', minWidth: '200px' },
    { label: () => 'Timezone', prop: 'timezone', width: '140px' },
    { label: () => 'Status', prop: 'status', width: '90px' },
    { label: () => 'Installed At', prop: 'installed_at', minWidth: '170px' },
  ],
})
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema" />
  </div>
</template>
