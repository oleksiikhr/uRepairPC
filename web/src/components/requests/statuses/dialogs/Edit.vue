<template>
  <basic-edit
    :title="item.name"
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
import RequestStatus from '@/classes/RequestStatus'
import { required } from '@/data/rules'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form: {
        name: {
          component: () => import('element-ui/lib/input'),
          value: this.item.name,
          label: 'Назва',
          rules: required
        },
        color: {
          component: () => import('element-ui/lib/color-picker'),
          value: this.item.color,
          label: 'Колір'
        },
        description: {
          component: () => import('element-ui/lib/input'),
          value: this.item.description,
          label: 'Опис',
          attrs: {
            type: 'textarea',
            autosize: { minRows: 3 }
          }
        },
        default: {
          component: () => import('element-ui/lib/checkbox'),
          value: this.item.default,
          attrs: {
            label: 'За замовчуванням'
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

      RequestStatus.fetchEdit(this.item.id, form)
        .then(() => {
          this.$emit('edit')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
