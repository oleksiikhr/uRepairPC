<template>
  <el-dialog
    class="dialog--default delete"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <div class="content">
      <slot name="content-top" />
      <slot name="content-alert">
        <el-alert
          title="Ви дійсно хочете видалити ці дані?"
          :description="confirm ? 'Для підтвердження - введіть ID елемента.' : ''"
          :closable="false"
          type="error"
        />
      </slot>
      <slot name="content-after-alert" />
      <el-form
        v-if="confirm"
        @submit.native.prevent="onSubmit"
      >
        <el-input-number
          ref="input"
          v-model="input"
          :controls="false"
          :min="0"
        />
      </el-form>
      <slot name="content-bottom" />
    </div>
    <span slot="footer">
      <el-button @click="close">Відмінити</el-button>
      <el-button
        type="danger"
        :loading="loading"
        :disabled="btnDisabled"
        @click="onSubmit"
      >
        Видалити
      </el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  components: {
    ElInputNumber: () => import('element-ui/lib/input-number'),
    ElButton: () => import('element-ui/lib/button'),
    ElDialog: () => import('element-ui/lib/dialog'),
    ElAlert: () => import('element-ui/lib/alert'),
    ElForm: () => import('element-ui/lib/form')
  },
  inheritAttrs: false,
  props: {
    confirm: {
      type: [Boolean, Number, String],
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      input: ''
    }
  },
  computed: {
    btnDisabled() {
      if (this.loading) {
        return true
      }

      if (this.confirm) {
        return this.input !== this.confirm
      }

      return false
    }
  },
  methods: {
    onSubmit() {
      if (!this.btnDisabled) {
        this.$emit('submit')
      }
    },
    close() {
      this.$emit('close')
    }
  }
}
</script>

<style lang="scss" scoped>
.el-input-number {
  display: block;
}
</style>
