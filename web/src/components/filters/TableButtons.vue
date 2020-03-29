<template>
  <filter-basic
    title="Основні дії"
    class="filter-table-buttons"
  >
    <el-button
      size="small"
      icon="el-icon-refresh"
      type="success"
      circle
      @click="onUpdateClick"
    />
    <el-button
      v-if="actionAdd"
      size="small"
      icon="el-icon-plus"
      type="primary"
      circle
      @click="actionAdd.action"
    />
    <slot />
  </filter-basic>
</template>

<script>
import { isObject } from '@/scripts/helpers'

export default {
  name: 'FilterTableButtons',
  components: {
    FilterBasic: () => import('@/components/filters/Basic'),
    ElButton: () => import('element-ui/lib/button')
  },
  props: {
    section: {
      type: String,
      default: ''
    }
  },
  computed: {
    actionAdd() {
      if (!this.section) {
        return null
      }

      const storeMenu = this.$store.getters['template/menu']

      if (!isObject(storeMenu[this.section])) {
        return null
      }

      const childrenItems = storeMenu[this.section].children || {}

      return childrenItems.add
    }
  },
  methods: {
    onUpdateClick() {
      this.$emit('update')
    }
  }
}
</script>
