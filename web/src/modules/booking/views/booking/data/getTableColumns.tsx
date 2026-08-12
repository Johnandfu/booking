import type { MaProTableColumns } from '@mineadmin/pro-table'

export default function getTableColumns(options: {
  onCancel: (row: any) => void
}): MaProTableColumns[] {
  return [
    { label: () => 'ID', prop: 'id', width: '80px' },
    { label: () => 'Shop', prop: 'shop_id', width: '90px' },
    { label: () => 'Booking No', prop: 'booking_no', minWidth: '160px' },
    { label: () => 'Status', prop: 'status', width: '110px' },
    { label: () => 'Customer', prop: 'customer_name', minWidth: '120px' },
    { label: () => 'Start (UTC)', prop: 'start_at', minWidth: '170px' },
    { label: () => 'Order', prop: 'shopline_order_id', minWidth: '140px' },
    {
      type: 'operation',
      label: () => 'Actions',
      width: '120px',
      operationConfigure: {
        actions: [
          {
            name: 'cancel',
            show: ({ row }) => ['pending', 'confirmed'].includes(row.status),
            text: () => 'Cancel',
            onClick: ({ row }) => options.onCancel(row),
          },
        ],
      },
    },
  ]
}
