<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { create, deleteById, page } from '~/booking/api/schedule'
import useDialog from '@/hooks/useDialog.ts'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

defineOptions({ name: 'booking:schedule' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const msg = useMessage()
const form = reactive({
  shop_id: undefined as number | undefined,
  staff_id: undefined as number | undefined,
  location_id: undefined as number | undefined,
  weekday: 1,
  start_time: '09:00:00',
  end_time: '18:00:00',
  status: 1,
})

const dialog = useDialog({
  lgWidth: '560px',
  ok: async (_ctx, okLoadingState: (s: boolean) => void) => {
    okLoadingState(true)
    try {
      const res = await create({ ...form })
      res.code === ResultCode.SUCCESS ? msg.success('Saved') : msg.error(res.message)
      dialog.close()
      proTableRef.value?.refresh()
    }
    catch (e: any) { msg.alertError(e) }
    finally { okLoadingState(false) }
  },
})

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: () => 'Schedules', subTitle: () => 'Weekly staff schedules' },
  requestOptions: { api: page },
  toolBar: [{
    text: () => 'Add',
    type: 'primary',
    onClick: () => {
      Object.assign(form, { shop_id: undefined, staff_id: undefined, location_id: undefined, weekday: 1, start_time: '09:00:00', end_time: '18:00:00', status: 1 })
      dialog.setTitle('Add schedule')
      dialog.open({ formType: 'add' })
    },
  }],
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: () => 'Shop ID', prop: 'shop_id', render: () => <el-input /> },
    { label: () => 'Staff ID', prop: 'staff_id', render: () => <el-input /> },
  ],
  tableColumns: [
    { label: () => 'ID', prop: 'id', width: '70px' },
    { label: () => 'Shop', prop: 'shop_id', width: '80px' },
    { label: () => 'Staff', prop: 'staff_id', width: '90px' },
    { label: () => 'Weekday', prop: 'weekday', width: '90px' },
    { label: () => 'Start', prop: 'start_time', width: '110px' },
    { label: () => 'End', prop: 'end_time', width: '110px' },
    {
      type: 'operation',
      label: () => 'Actions',
      width: '120px',
      operationConfigure: {
        actions: [
          {
            name: 'delete',
            text: () => 'Delete',
            onClick: async ({ row }) => {
              await msg.confirm('Delete schedule?')
              const res = await deleteById(row.id)
              res.code === ResultCode.SUCCESS ? msg.success('Deleted') : msg.error(res.message)
              proTableRef.value?.refresh()
            },
          },
        ],
      },
    },
  ],
})
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema" />
    <component :is="dialog.Dialog">
      <el-form label-width="110px">
        <el-form-item label="Shop ID"><el-input-number v-model="form.shop_id" :min="1" class="w-full" /></el-form-item>
        <el-form-item label="Staff ID"><el-input-number v-model="form.staff_id" :min="1" class="w-full" /></el-form-item>
        <el-form-item label="Location ID"><el-input-number v-model="form.location_id" :min="0" class="w-full" /></el-form-item>
        <el-form-item label="Weekday"><el-input-number v-model="form.weekday" :min="0" :max="6" class="w-full" /></el-form-item>
        <el-form-item label="Start"><el-input v-model="form.start_time" placeholder="09:00:00" /></el-form-item>
        <el-form-item label="End"><el-input v-model="form.end_time" placeholder="18:00:00" /></el-form-item>
      </el-form>
    </component>
  </div>
</template>
