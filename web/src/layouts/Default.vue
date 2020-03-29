<template>
  <div
    id="layout"
    class="layout_default"
  >
    <el-container direction="vertical">
      <header-box />
      <el-container class="container">
        <sidebar-box />
        <el-main>
          <breadcrumbs-box />
          <keep-alive :include="keepAliveRoutesName">
            <router-view
              ref="content"
              :key="$route.params.id"
              class="page"
            />
          </keep-alive>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import { root as keepAliveRoutesName } from '@/data/keepAliveComponents'

export default {
  components: {
    BreadcrumbsBox: () => import('@/components/root/Breadcrumbs'),
    ElContainer: () => import('element-ui/lib/container'),
    SidebarBox: () => import('@/components/root/Sidebar'),
    HeaderBox: () => import('@/components/root/Header'),
    ElMain: () => import('element-ui/lib/main')
  },
  data() {
    return {
      keepAliveRoutesName
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";

.el-main {
  padding: 1px;
}

.container {
  padding-top: 50px;
}
</style>
