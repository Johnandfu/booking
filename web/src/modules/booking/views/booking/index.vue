<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { cancel, page } from '~/booking/api/booking'
import getSearchItems from './data/getSearchItems.tsx'
import getTableColumns from './data/getTableColumns.tsx'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

defineOptions({ name: 'booking:booking' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const msg = useMessage()

async function onCancel(row: any) {
  try {
    await msg.confirm(`Cancel booking ${row.booking_no}?`)
    const res = await cancel(row.id, row.shop_id)
    res.code === ResultCode.SUCCESS ? msg.success('Cancelled') : msg.error(res.message)
    proTableRef.value?.refresh()
  }
  catch (e: any) {
    if (e !== 'cancel') {
      msg.alertError(e)
    }
  }
}

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: {
    mainTitle: () => 'Bookings',
    subTitle: () => 'SHOPLINE Calendar Booking',
  },
  requestOptions: {
    api: page,
  },
})

const schema = ref<MaProTableSchema>({
  searchItems: getSearchItems(),
  tableColumns: getTableColumns({ onCancel }),
})
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema" />
  </div>
</template>
