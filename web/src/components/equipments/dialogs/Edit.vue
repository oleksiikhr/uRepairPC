<template>
  <basic-edit
    :title="equipmentObj.title"
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
import { isArray } from '@/scripts/helpers'
import Equipment from '@/classes/Equipment'
import { required } from '@/data/rules'
import * as perm from '@/enum/perm'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  props: {
    equipment: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form: {
        equipment: {
          component: () => import('@/components/equipments/Cascader'),
          value: [this.equipment.type_id, this.equipment.manufacturer_id, this.equipment.model_id].filter(v => !!v),
          label: 'Тип, Виробник, Модель',
          rules: required,
          permissions: perm.EQUIPMENTS_CONFIG_VIEW_ALL
        },
        serial_number: {
          component: () => import('element-ui/lib/input'),
          value: this.equipment.serial_number,
          label: 'Серійний номер'
        },
        inventory_number: {
          component: () => import('element-ui/lib/input'),
          value: this.equipment.inventory_number,
          label: 'Інвертарний номер'
        },
        description: {
          component: () => import('element-ui/lib/input'),
          value: this.equipment.description,
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
    },
    equipmentObj() {
      return new Equipment(this.equipment)
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      if (isArray(form.equipment)) {
        form.type_id = form.equipment[0] || null
        form.manufacturer_id = form.equipment[1] || null
        form.model_id = form.equipment[2] || null
      }

      Equipment.fetchEdit(this.equipment.id, form)
        .then(({ data }) => {
          this.$store.commit('equipments/UPDATE_ITEM', data.equipment)
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
