<template>
  <filter-basic
    title="Зафіксувати колонку"
    class="filter-fixed"
  >
    <el-select
      v-bind="$attrs"
      clearable
      v-on="$listeners"
    >
      <el-option
        v-for="(column, index) in columns"
        :key="index"
        :label="column.label"
        :value="column.prop"
      />
    </el-select>
  </filter-basic>
</template>

<script>
export default {
  name: 'FilterFixed',
  components: {
    FilterBasic: () => import('@/components/filters/Basic'),
    ElSelect: () => import('element-ui/lib/select'),
    ElOption: () => import('element-ui/lib/option')
  },
  inheritAttrs: false,
  props: {
    columns: {
      type: Array,
      required: true
    }
  }
}
</script>
