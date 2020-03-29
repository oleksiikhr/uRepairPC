<template>
  <div
    :class="['permission-group', {
      'only-view': onlyView
    }]"
  >
    <el-checkbox
      v-model="checkAll"
      :indeterminate="isIndeterminate"
      class="title"
      :disabled="onlyView"
      @change="handleCheckAllChange"
    >
      <strong>{{ groupName }}</strong>
    </el-checkbox>
    <el-checkbox-group :value="value">
      <el-checkbox
        v-for="(permission, index) in permissions"
        :key="index"
        :label="permission.name"
        :disabled="onlyView"
        @change="onChange($event, permission.name, index)"
      >
        {{ permission.action }}
      </el-checkbox>
    </el-checkbox-group>
  </div>
</template>

<script>
export default {
  components: {
    ElCheckboxGroup: () => import('element-ui/lib/checkbox-group'),
    ElCheckbox: () => import('element-ui/lib/checkbox')
  },
  props: {
    value: {
      type: Array,
      default: () => []
    },
    groupName: {
      type: String,
      default: ''
    },
    permissions: {
      type: Array,
      default: () => []
    },
    onlyView: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      set: new Set,
      checkAll: false,
      isIndeterminate: false
    }
  },
  watch: {
    value: {
      handler(permissionNames) {
        this.set = new Set(permissionNames)
        this.checkAll = this.permissions.every(p => this.set.has(p.name))
        this.isIndeterminate = !this.checkAll && this.permissions.some(p => this.set.has(p.name))
      },
      immediate: true
    }
  },
  methods: {
    handleCheckAllChange(allSelected) {
      if (this.onlyView) {
        return
      }

      if (allSelected) {
        this.permissions.forEach(p => this.set.add(p.name))
      } else {
        this.permissions.forEach(p => this.set.delete(p.name))
      }

      this.$emit('input', [...this.set])
    },
    onChange(isSelected, name) {
      if (this.onlyView) {
        return
      }

      if (isSelected) {
        this.set.add(name)
      } else {
        this.set.delete(name)
      }

      this.$emit('input', [...this.set])
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.permission-group {
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid $defaultBorder;
  &:last-child {
    border-bottom: 0;
    margin-bottom: 0;
    padding-bottom: 0;
  }
  &.only-view {
    .el-checkbox {
      user-select: auto;
    }
    .el-checkbox,
    /deep/ .el-checkbox__input,
    /deep/ .el-checkbox__label,
    /deep/ .el-checkbox__inner {
      color: inherit;
      cursor: auto;
    }
    /deep/ .el-checkbox__input {
      &.is-checked {
        .el-checkbox__inner {
          background-color: $primary;
          border-color: $primary;
        }
      }
      .el-checkbox__inner {
        &:after {
          border-color: #fff;
          cursor: context-menu;
        }
      }
    }
  }
}

.el-checkbox {
  margin-top: 5px;
  margin-bottom: 5px;
  &.title {
    margin-bottom: 10px;
  }
}

.el-checkbox-group {
  margin-left: 15px;
}
</style>
