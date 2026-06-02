<script setup lang="ts">
import type { CmsArticleVo } from '../../api/article'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { h, ref } from 'vue'
import { ElInput, ElOption } from 'element-plus'
import { MdEditor } from 'md-editor-v3'
import { create, save } from '../../api/article'
import type { CmsCategoryVo } from '../../api/category'
import { page as categoryPage } from '../../api/category'
import MaRemoteSelect from '../../../../components/ma-remote-select/index.vue'
import MaUploadImage from '../../../../components/ma-upload-image/index.vue'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'
import 'md-editor-v3/lib/style.css'

defineOptions({ name: 'cms:article:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsArticleVo | null
}>(), {
  formType: 'add',
  data: null,
})

const articleForm = ref<MaFormExpose>()
const articleModel = ref<CmsArticleVo>({
  status: 1,
  views: 0,
})

function formItems(): MaFormItem[] {
  return [
    {
      label: '栏目',
      prop: 'category_id',
      render: () => MaRemoteSelect,
      cols: { md: 12, xs: 24 },
      renderProps: {
        class: 'w-full',
        placeholder: '请选择栏目',
        api: () => new Promise(resolve => resolve(categoryPage({ status: 1 }))),
        dataHandle: (response: any) => {
          return response.data.list?.map((item: CmsCategoryVo) => {
            return { label: item.name, value: item.id }
          })
        },
      },
      itemProps: { rules: [{ required: true, message: '请选择栏目' }] },
    },
    { label: '标题', prop: 'title', render: 'input', cols: { md: 12, xs: 24 }, itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: 'Slug', prop: 'slug', render: 'input', cols: { md: 12, xs: 24 }, renderProps: { placeholder: '如 cms-getting-started' }, itemProps: { rules: [{ required: true, message: '请输入 Slug' }] } },
    {
      label: '封面图片',
      prop: 'cover',
      render: () => h('div', { class: 'flex flex-col gap-2' }, [
        h(ElInput, {
          'modelValue': articleModel.value.cover ?? '',
          'placeholder': '请输入图片 URL，或上传/选择图片',
          'clearable': true,
          'onUpdate:modelValue': (value: string) => {
            articleModel.value.cover = value
          },
        }),
        h(MaUploadImage, {
          'modelValue': articleModel.value.cover ?? '',
          'limit': 1,
          'onUpdate:modelValue': (value: string | string[]) => {
            articleModel.value.cover = Array.isArray(value) ? value[0] : value
          },
        }),
      ]),
    },
    { label: '摘要', prop: 'summary', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
    {
      label: '正文HTML',
      prop: 'content',
      render: () => h(MdEditor, {
        'modelValue': articleModel.value.content ?? '',
        'class': 'w-full',
        'style': { height: '420px' },
        'previewTheme': 'github',
        'onUpdate:modelValue': (value: string) => {
          articleModel.value.content = value
        },
      }),
    },
    {
      label: '状态',
      prop: 'status',
      render: 'select',
      cols: { md: 12, xs: 24 },
      renderProps: { class: 'w-full' },
      renderSlots: {
        default: () => [
          h(ElOption, { label: '启用', value: 1 }),
          h(ElOption, { label: '禁用', value: 0 }),
        ],
      },
    },
    { label: '排序', prop: 'sort', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full', min: 0 } },
    { label: 'SEO标题', prop: 'seo_title', render: 'input' },
    { label: 'SEO描述', prop: 'seo_description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
  ]
}

useForm('articleForm').then((form: MaFormExpose) => {
  if (props.formType === 'edit' && props.data) {
    const data = props.data as Record<string, any>
    const model = articleModel.value as Record<string, any>
    Object.keys(props.data).map((key: string) => {
      model[key] = data[key]
    })
  }
  if (props.formType === 'add') {
    articleModel.value.status = 1
    articleModel.value.views = 0
    articleModel.value.sort = 0
  }
  form.setItems(formItems())
  form.setOptions({ labelWidth: '100px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(articleModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(articleModel.value.id as number, articleModel.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm: articleForm })
</script>

<template>
  <ma-form ref="articleForm" v-model="articleModel" />
</template>
