<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { create, deleteById, page, save } from '~/booking/api/staff'
import useDialog from '@/hooks/useDialog.ts'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

defineOptions({ name: 'booking:staff' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const msg = useMessage()
const form = reactive({ id: 0, shop_id: undefined as number | undefined, name: '', email: '', phone: '', status: 1 })

const dialog = useDialog({
  lgWidth: '560px',
  ok: async ({ formType }, okLoadingState: (s: boolean) => void) => {
    okLoadingState(true)
    try {
      const res = formType === 'add' ? await create({ ...form }) : await save(form.id, { ...form })
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
  header: { mainTitle: () => 'Staff', subTitle: () => 'Booking staff' },
  requestOptions: { api: page },
  toolBar: [{ text: () => 'Add', type: 'primary', onClick: () => { Object.assign(form, { id: 0, shop_id: undefined, name: '', email: '', phone: '', status: 1 }); dialog.setTitle('Add staff'); dialog.open({ formType: 'add' }) } }],
})

const schema = ref<MaProTableSchema>({
  searchItems: [
    { label: () => 'Shop ID', prop: 'shop_id', render: () => <el-input /> },
    { label: () => 'Name', prop: 'name', render: () => <el-input /> },
  ],
  tableColumns: [
    { label: () => 'ID', prop: 'id', width: '70px' },
    { label: () => 'Shop', prop: 'shop_id', width: '80px' },
    { label: () => 'Name', prop: 'name', minWidth: '140px' },
    { label: () => 'Email', prop: 'email', minWidth: '160px' },
    {
      type: 'operation',
      label: () => 'Actions',
      width: '160px',
      operationConfigure: {
        actions: [
          { name: 'edit', text: () => 'Edit', onClick: ({ row }) => { Object.assign(form, row); dialog.setTitle('Edit staff'); dialog.open({ formType: 'edit' }) } },
          { name: 'delete', text: () => 'Delete', onClick: async ({ row }) => { await msg.confirm(`Delete ${row.name}?`); const res = await deleteById(row.id); res.code === ResultCode.SUCCESS ? msg.success('Deleted') : msg.error(res.message); proTableRef.value?.refresh() } },
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
      <el-form label-width="100px">
        <el-form-item label="Shop ID"><el-input-number v-model="form.shop_id" :min="1" class="w-full" /></el-form-item>
        <el-form-item label="Name"><el-input v-model="form.name" /></el-form-item>
        <el-form-item label="Email"><el-input v-model="form.email" /></el-form-item>
        <el-form-item label="Phone"><el-input v-model="form.phone" /></el-form-item>
      </el-form>
    </component>
  </div>
</template>
