<template>
  <basic-delete
    :title="equipmentObj.title"
    :confirm="equipment.id"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-checkbox
      slot="content-bottom"
      v-model="filesDelete"
    >
      Видалити всі файли
    </el-checkbox>
  </basic-delete>
</template>

<script>
import Equipment from '@/classes/Equipment'

export default {
  components: {
    BasicDelete: () => import('@/common/components/dialogs/BasicDelete'),
    ElCheckbox: () => import('element-ui/lib/checkbox')
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
      filesDelete: true
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    },
    equipmentObj() {
      return new Equipment(this.equipment)
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      Equipment.fetchDelete(this.equipment.id, {
        data: {
          files_delete: this.filesDelete
        }
      })
        .then(() => {
          this.$store.commit('equipments/DELETE_ITEM', this.equipment.id)
          this.$emit('delete')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
