<template>
  <basic-edit
    title="Коментарій"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <generate-form
      ref="form"
      :form="form"
      :loading="loading"
      @submit="fetchRequest"
    />
  </basic-edit>
</template>

<script>
import RequestComment from '@/classes/RequestComment'
import { required } from '@/data/rules'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  props: {
    request: {
      type: Object,
      required: true
    },
    comment: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      loading: false,
      form: {
        message: {
          component: () => import('element-ui/lib/input'),
          value: this.comment.message,
          rules: required,
          attrs: {
            type: 'textarea',
            placeholder: 'Коментарій',
            autosize: { minRows: 3 }
          }
        }
      }
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: () => {
          this.$refs.form.onSubmit()
        }
      }
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      RequestComment.fetchEdit(this.request.id, this.comment.id, form)
        .then(({ data }) => {
          this.$emit('comment-update', data.request_comment, this.index)
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
