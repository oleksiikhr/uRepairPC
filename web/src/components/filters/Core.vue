<template>
  <div class="filters-core">
    <el-collapse v-model="collapse">
      <el-collapse-item
        title="Налаштування"
        name="content"
      >
        <div class="filters-core__content">
          <slot />
        </div>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
export default {
  name: 'FilterCore',
  components: {
    ElCollapseItem: () => import('element-ui/lib/collapse-item'),
    ElCollapse: () => import('element-ui/lib/collapse')
  },
  data() {
    return {
      collapse: []
    }
  }
}
</script>
