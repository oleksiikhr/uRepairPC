<template>
  <select-simple
    :items="items"
    label-attr="name"
    value-attr="id"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
export default {
  components: {
    SelectSimple: () => import('@/components/SelectSimple')
  },
  inheritAttrs: false,
  props: {
    defaultValue: {
      type: Object,
      default: null
    }
  },
  computed: {
    items() {
      if (this.init && this.defaultValue) {
        return [this.defaultValue]
      }

      return this.list
    },
    list() {
      return this.$store.state.requestTypes.list
    },
    init() {
      return this.$store.state.requestTypes.init
    },
    listeners() {
      return {
        ...this.$listeners,
        focus: () => {
          if (this.init) {
            this.$store.dispatch('requestTypes/fetchList')
          }
        }
      }
    }
  }
}
</script>
