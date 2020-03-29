<template>
  <el-dialog
    class="dialog--default edit"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <div class="content">
      <slot />
    </div>
    <span slot="footer">
      <el-button @click="onClose">Закрити</el-button>
      <el-button
        type="primary"
        :loading="loading"
        :disabled="loading"
        @click="onSubmit"
      >
        {{ saveBtn }}
      </el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  components: {
    ElDialog: () => import('element-ui/lib/dialog'),
    ElButton: () => import('element-ui/lib/button')
  },
  inheritAttrs: false,
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    saveBtn: {
      type: String,
      default: 'Зберегти'
    }
  },
  methods: {
    onSubmit() {
      this.$emit('submit')
    },
    onClose() {
      this.$emit('close')
    }
  }
}
</script>
