<template>
  <div class="permissions-list-checkboxes">
    <!--last div element - loading mask-->
    <div>
      <group-checkbox
        v-for="(permissions, groupName) in permissionsList"
        :key="groupName"
        :value="value"
        :group-name="groupName"
        :permissions="permissions"
        :only-view="onlyView"
        @input="onChange"
      />
    </div>
  </div>
</template>

<script>
export default {
  components: {
    GroupCheckbox: () => import('@/components/permissions/GroupCheckbox')
  },
  inheritAttrs: false,
  props: {
    // active names of permissions
    value: {
      type: Array,
      default: () => []
    },
    permissionsList: {
      type: Object,
      default: () => {}
    },
    onlyView: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    onChange(val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style lang="scss" scoped>
.permissions-list-checkboxes {
  min-height: 200px;
}
</style>
