<template>
  <el-select
    v-bind="$attrs"
    v-on="$listeners"
  >
    <el-option
      v-for="item in items"
      :key="item[valueAttr]"
      :label="item[labelAttr]"
      :value="item[valueAttr]"
    />
  </el-select>
</template>

<script>
export default {
  components: {
    ElSelect: () => import('element-ui/lib/select'),
    ElOption: () => import('element-ui/lib/option')
  },
  props: {
    items: {
      type: Array,
      default: () => []
    },
    valueAttr: {
      type: String,
      default: 'value'
    },
    labelAttr: {
      type: String,
      default: 'label'
    }
  }
}
</script>
