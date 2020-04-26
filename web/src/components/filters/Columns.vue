<template>
  <filter-basic
    title="Колонки"
    class="filter-columns"
  >
    <el-alert
      title="Фільтр колонок в таблиці"
      type="info"
    />
    <el-checkbox-group
      v-for="(column, index) in columns"
      :key="index"
      :value="listSelected"
    >
      <el-checkbox
        :label="column.label"
        @change="onChange(column, $event)"
      >
        <span>{{ column.label }}</span>
        <span
          v-if="!column.disableSearch"
          class="support-search"
        >
          пошук
        </span>
      </el-checkbox>
    </el-checkbox-group>
  </filter-basic>
</template>

<script>
export default {
  name: 'FilterColumns',
  components: {
    ElCheckboxGroup: () => import('element-ui/lib/checkbox-group'),
    FilterBasic: () => import('@/components/filters/Basic'),
    ElCheckbox: () => import('element-ui/lib/checkbox'),
    ElAlert: () => import('element-ui/lib/alert')
  },
  props: {
    columns: {
      type: Array,
      required: true
    }
  },
  computed: {
    listSelected() {
      return this.columns
        .filter(c => !!c.model)
        .map(c => c.label)
    }
  },
  methods: {
    onChange(column, toggle) {
      this.$set(column, 'model', toggle)
      this.$emit('change', column, toggle)
    }
  }
}
</script>
