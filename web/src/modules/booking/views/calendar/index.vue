<script setup lang="ts">
import { calendar } from '~/booking/api/calendar'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

defineOptions({ name: 'booking:calendar' })

const msg = useMessage()
const shopId = ref<number>()
const month = ref(new Date().toISOString().slice(0, 7))
const loading = ref(false)
const items = ref<any[]>([])

const days = computed(() => {
  const [y, m] = (month.value || '').split('-').map(Number)
  if (!y || !m) return []
  const first = new Date(Date.UTC(y, m - 1, 1))
  const startWeekday = first.getUTCDay()
  const daysInMonth = new Date(Date.UTC(y, m, 0)).getUTCDate()
  const cells: Array<{ date: string, day: number | null, bookings: any[] }> = []
  for (let i = 0; i < startWeekday; i++) cells.push({ date: '', day: null, bookings: [] })
  for (let d = 1; d <= daysInMonth; d++) {
    const date = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    cells.push({
      date,
      day: d,
      bookings: items.value.filter(b => String(b.start_at || '').startsWith(date)),
    })
  }
  return cells
})

async function load() {
  if (!shopId.value) {
    msg.warning('Enter shop id')
    return
  }
  loading.value = true
  try {
    const [y, m] = month.value.split('-').map(Number)
    const start = `${month.value}-01 00:00:00`
    const last = new Date(Date.UTC(y, m, 0)).getUTCDate()
    const end = `${month.value}-${String(last).padStart(2, '0')} 23:59:59`
    const res = await calendar({ shop_id: shopId.value, start_from: start, start_to: end })
    if (res.code !== ResultCode.SUCCESS) {
      msg.error(res.message)
      return
    }
    items.value = res.data?.list || []
  }
  catch (e: any) {
    msg.alertError(e)
  }
  finally {
    loading.value = false
  }
}

onMounted(load)
</script>

<template>
  <div class="mine-layout pt-3 px-3">
    <div class="mine-card mb-3 flex flex-wrap items-center gap-3 p-4">
      <el-input-number v-model="shopId" :min="1" placeholder="Shop ID" />
      <el-date-picker v-model="month" type="month" value-format="YYYY-MM" />
      <el-button type="primary" :loading="loading" @click="load">Load</el-button>
      <span class="text-gray-500 text-sm">{{ items.length }} bookings (UTC day buckets)</span>
    </div>
    <div class="grid grid-cols-7 gap-2">
      <div v-for="w in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="w" class="text-center text-sm font-semibold py-2">
        {{ w }}
      </div>
      <div
        v-for="(cell, idx) in days"
        :key="idx"
        class="min-h-28 rounded border border-gray-200 bg-white p-2"
        :class="{ 'bg-gray-50': !cell.day }"
      >
        <div v-if="cell.day" class="text-xs font-bold mb-1">{{ cell.day }}</div>
        <div v-for="b in cell.bookings" :key="b.id" class="mb-1 rounded bg-teal-50 px-1 py-0.5 text-[11px] leading-tight">
          <div class="font-semibold truncate">{{ b.booking_no }}</div>
          <div class="text-gray-500">{{ b.status }} · {{ String(b.start_at).slice(11, 16) }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
