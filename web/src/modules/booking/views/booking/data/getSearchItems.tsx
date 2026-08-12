import type { MaSearchItem } from '@mineadmin/search'

export default function getSearchItems(): MaSearchItem[] {
  return [
    {
      label: () => 'Shop ID',
      prop: 'shop_id',
      render: () => <el-input />,
    },
    {
      label: () => 'Status',
      prop: 'status',
      render: () => (
        <el-select clearable>
          <el-option label="pending" value="pending" />
          <el-option label="confirmed" value="confirmed" />
          <el-option label="cancelled" value="cancelled" />
          <el-option label="completed" value="completed" />
          <el-option label="no_show" value="no_show" />
          <el-option label="expired" value="expired" />
        </el-select>
      ),
    },
    {
      label: () => 'Booking No',
      prop: 'booking_no',
      render: () => <el-input />,
    },
  ]
}
