<script setup lang="ts">
import type { CmsArticleVo } from '../../api/article'
import type { MaFormExpose, MaFormItem } from '@mineadmin/form'
import { ref } from 'vue'
import { create, save } from '../../api/article'
import useForm from '../../../../hooks/useForm'
import { ResultCode } from '../../../../utils/ResultCode'

defineOptions({ name: 'cms:article:form' })

const props = withDefaults(defineProps<{
  formType?: 'add' | 'edit'
  data?: CmsArticleVo | null
}>(), {
  formType: 'add',
  data: null,
})

const maForm = ref<MaFormExpose>()
const model = ref<CmsArticleVo>({
  status: 1,
  views: 0,
})

function formItems(): MaFormItem[] {
  return [
    { label: '栏目ID', prop: 'category_id', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full', min: 1 }, itemProps: { rules: [{ required: true, message: '请输入栏目 ID' }] } },
    { label: '标题', prop: 'title', render: 'input', cols: { md: 12, xs: 24 }, itemProps: { rules: [{ required: true, message: '请输入标题' }] } },
    { label: 'Slug', prop: 'slug', render: 'input', cols: { md: 12, xs: 24 }, renderProps: { placeholder: '如 cms-getting-started' }, itemProps: { rules: [{ required: true, message: '请输入 Slug' }] } },
    { label: '作者', prop: 'author', render: 'input', cols: { md: 12, xs: 24 } },
    { label: '封面URL', prop: 'cover', render: 'input', renderProps: { placeholder: '请输入图片 URL' } },
    { label: '摘要', prop: 'summary', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
    { label: '正文HTML', prop: 'content', render: 'input', renderProps: { type: 'textarea', rows: 8 } },
    { label: '发布时间', prop: 'published_at', render: 'input', cols: { md: 12, xs: 24 }, renderProps: { placeholder: 'YYYY-MM-DD HH:mm:ss' } },
    { label: '状态', prop: 'status', render: 'inputNumber', cols: { md: 12, xs: 24 }, renderProps: { class: 'w-full', min: 0, max: 1 } },
    { label: 'SEO标题', prop: 'seo_title', render: 'input' },
    { label: 'SEO描述', prop: 'seo_description', render: 'input', renderProps: { type: 'textarea', rows: 3 } },
  ]
}

function fillModel(): void {
  if (props.formType === 'edit' && props.data) {
    Object.assign(model.value, {
      id: props.data.id,
      category_id: props.data.category_id,
      title: props.data.title ?? '',
      slug: props.data.slug ?? '',
      author: props.data.author ?? '',
      cover: props.data.cover ?? '',
      summary: props.data.summary ?? '',
      content: props.data.content ?? '',
      published_at: props.data.published_at ?? '',
      status: props.data.status ?? 1,
      views: props.data.views ?? 0,
      seo_title: props.data.seo_title ?? '',
      seo_description: props.data.seo_description ?? '',
    })
    return
  }

  Object.assign(model.value, {
    status: 1,
    views: 0,
    category_id: undefined,
    title: '',
    slug: '',
    author: '',
    cover: '',
    summary: '',
    content: '',
    published_at: '',
    seo_title: '',
    seo_description: '',
  })
}

useForm('maForm').then((form: MaFormExpose) => {
  fillModel()
  form.setItems(formItems())
  form.setOptions({ labelWidth: '100px' })
})

function add(): Promise<any> {
  return new Promise((resolve, reject) => {
    create(model.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

function edit(): Promise<any> {
  return new Promise((resolve, reject) => {
    save(model.value.id as number, model.value).then(res => res.code === ResultCode.SUCCESS ? resolve(res) : reject(res)).catch(reject)
  })
}

defineExpose({ add, edit, maForm })
</script>

<template>
  <ma-form ref="maForm" v-model="model" />
</template>
