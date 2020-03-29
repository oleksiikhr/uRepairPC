<template>
  <el-form
    ref="form"
    :model="formFilterPermissions"
    class="form--full"
    status-icon
    @submit.native.prevent="onSubmit"
  >
    <el-form-item
      v-for="(item, key) in formFilterPermissionsAndHide"
      :key="key"
      :prop="`${key}.value`"
      :rules="item.rules"
      :label="item.label"
    >
      <component
        :is="item.component"
        v-model="item.value"
        v-bind="item.attrs"
        v-on="item.events"
      />
    </el-form-item>
    <slot />
    <div
      v-if="hasBtn"
      class="wrap-btn"
    >
      <el-button
        native-type="submit"
        type="primary"
        :loading="loading"
        :disabled="loading"
      >
        <slot name="button" />
      </el-button>
    </div>
    <button
      v-else
      class="hide"
      type="submit"
    />
  </el-form>
</template>

<script>
import { hasPerm } from '@/scripts/utils'

export default {
  components: {
    ElFormItem: () => import('element-ui/lib/form-item'),
    ElButton: () => import('element-ui/lib/button'),
    ElForm: () => import('element-ui/lib/form')
  },
  inheritAttrs: false,
  props: {
    /**
     * @example {
     *   name: {
     *     component: () => import('element-ui/lib/input),
     *     value: '', // v-model
     *     permissions: [], // permissions enum (String, Array)
     *     hide: false, // hide this properties from loop
     *     label: 'Label', // <el-form-item />
     *     rules: { ... }, // <el-form-item />
     *     attrs: { ... }, // v-bind to component
     *     events: { ... } // v-on to component
     *   },
     *   ...
     * }
     */
    form: {
      type: Object,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    hasBtn() {
      return !!this.$slots.button
    },
    formFilterPermissions() {
      const result = {}

      Object.entries(this.form).forEach(([key, obj]) => {
        if (hasPerm(obj.permissions)) {
          result[key] = obj
        }
      })

      return result
    },
    formFilterPermissionsAndHide() {
      const result = {}

      Object.entries(this.formFilterPermissions).forEach(([key, obj]) => {
        if (!obj.hide) {
          result[key] = obj
        }
      })

      return result
    }
  },
  methods: {
    onSubmit() {
      if (this.loading) {
        return
      }

      this.$refs.form.validate((valid) => {
        if (!valid) {
          this.$emit('failed')
          return
        }

        this.$emit('submit', this.getValues())
      })
    },
    getValues() {
      const result = {}

      Object.entries(this.formFilterPermissions).forEach(([key, obj]) => {
        result[key] = obj.value
      })

      return result
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap-btn {
  text-align: center;
  button {
    max-width: 200px;
    width: 100%;
  }
}

/deep/ .el-form-item__content {
  line-height: inherit;
}
</style>
