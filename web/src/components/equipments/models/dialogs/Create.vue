<template>
  <basic-create
    title="Додати модель обладнання"
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
import EquipmentModel from '@/classes/EquipmentModel'
import { required } from '@/data/rules'

export default {
  components: {
    BasicCreate: () => import('@/common/components/dialogs/BasicCreate'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  data() {
    return {
      loading: false,
      form: {
        name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Назва',
          rules: required
        },
        type_id: {
          component: () => import('@/components/equipments/types/Select'),
          value: null,
          label: 'Тип обладнання',
          rules: required
        },
        manufacturer_id: {
          component: () => import('@/components/equipments/manufacturers/Select'),
          value: null,
          label: 'Виробник обладнання',
          rules: required
        },
        description: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Опис',
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

      EquipmentModel.fetchStore(form)
        .then(() => {
          this.$emit('create')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
