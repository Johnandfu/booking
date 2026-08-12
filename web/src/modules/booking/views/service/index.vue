<script setup lang="tsx">
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import { create, deleteById, page, save } from '~/booking/api/service'
import useDialog from '@/hooks/useDialog.ts'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

defineOptions({ name: 'booking:service' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const msg = useMessage()
const form = reactive({
  id: 0,
  shop_id: undefined as number | undefined,
  name: '',
  duration_minutes: 30,
  capacity: 1,
  status: 1,
  shopline_product_id: '',
})

const dialog = useDialog({
  lgWidth: '560px',
  ok: async ({ formType }, okLoadingState: (s: boolean) => void) => {
    okLoadingState(true)
    try {
      const payload = { ...form }
      const res = formType === 'add'
        ? await create(payload)
        : await save(form.id, payload)
      res.code === ResultCode.SUCCESS ? msg.success('Saved') : msg.error(res.message)
      dialog.close()
      proTableRef.value?.refresh()
    }
    catch (e: any) {
      msg.alertError(e)
    }
    finally {
      okLoadingState(false)
    }
  },
})

function openAdd() {
  Object.assign(form, { id: 0, shop_id: undefined, name: '', duration_minutes: 30, capacity: 1, status: 1, shopline_product_id: '' })
  dialog.setTitle('Add service')
  dialog.open({ formType: 'add' })
}

function openEdit(row: any) {
  Object.assign(form, row)
  dialog.setTitle('Edit service')
  dialog.open({ formType: 'edit' })
}

async function onDelete(row: any) {
  await msg.confirm(`Delete service ${row.name}?`)
  const res = await deleteById(row.id)
  res.code === ResultCode.SUCCESS ? msg.success('Deleted') : msg.error(res.message)
  proTableRef.value?.refresh()
}

const options = ref<MaProTableOptions>({
  adaptionOffsetBottom: 161,
  header: { mainTitle: () => 'Services', subTitle: () => 'Booking services' },
  requestOptions: { api: page },
  toolBar: [
    { text: () => 'Add', type: 'primary', onClick: openAdd },
  ],
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
    { label: () => 'Duration', prop: 'duration_minutes', width: '100px' },
    { label: () => 'Capacity', prop: 'capacity', width: '90px' },
    { label: () => 'Product ID', prop: 'shopline_product_id', minWidth: '160px' },
    {
      type: 'operation',
      label: () => 'Actions',
      width: '160px',
      operationConfigure: {
        actions: [
          { name: 'edit', text: () => 'Edit', onClick: ({ row }) => openEdit(row) },
          { name: 'delete', text: () => 'Delete', onClick: ({ row }) => onDelete(row) },
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
      <el-form label-width="120px">
        <el-form-item label="Shop ID">
          <el-input-number v-model="form.shop_id" :min="1" class="w-full" />
        </el-form-item>
        <el-form-item label="Name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="Duration">
          <el-input-number v-model="form.duration_minutes" :min="1" class="w-full" />
        </el-form-item>
        <el-form-item label="Capacity">
          <el-input-number v-model="form.capacity" :min="1" class="w-full" />
        </el-form-item>
        <el-form-item label="Product ID">
          <el-input v-model="form.shopline_product_id" placeholder="optional / synced" />
        </el-form-item>
      </el-form>
    </component>
  </div>
</template>
