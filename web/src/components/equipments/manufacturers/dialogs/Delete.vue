<template>
  <basic-delete
    :title="item.name"
    :confirm="item.id"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
import EquipmentManufacturer from '@/classes/EquipmentManufacturer'

export default {
  components: {
    BasicDelete: () => import('@/common/components/dialogs/BasicDelete')
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
      loading: false
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      EquipmentManufacturer.fetchDelete(this.item.id)
        .then(() => {
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
