<template>
  <basic-create
    title="Додати коментарій"
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
  </basic-create>
</template>

<script>
import RequestComment from '@/classes/RequestComment'
import { required } from '@/data/rules'

export default {
  components: {
    BasicCreate: () => import('@/common/components/dialogs/BasicCreate'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  props: {
    request: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form: {
        message: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Коментарій',
          rules: required,
          attrs: {
            type: 'textarea',
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

      RequestComment.fetchStore(this.request.id, form)
        .then(({ data }) => {
          this.$emit('comment-create', data.request_comment)
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
