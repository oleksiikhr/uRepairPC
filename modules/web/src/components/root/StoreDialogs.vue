<template>
  <component
    :is="component"
    :visible.sync="visible"
    v-bind="attrs"
    v-on="listeners"
  />
</template>

<script>
export default {
  data() {
    return {
      component: null,
      visible: false
    }
  },
  computed: {
    dialog() {
      return this.$store.state.template.dialog
    },
    listeners() {
      return {
        ...this.dialog.events,
        close: this.closeDialog
      }
    },
    attrs() {
      return {
        // 'close-on-click-modal': false,
        ...this.dialog.attrs
      }
    }
  },
  watch: {
    dialog(val) {
      if (val.component) {
        this.$set(this, 'component', val.component)
        this.visible = true
      } else {
        this.$set(this, 'component', null)
      }
    }
  },
  methods: {
    closeDialog() {
      this.$store.commit('template/CLOSE_DIALOG')
    }
  }
}
</script>
