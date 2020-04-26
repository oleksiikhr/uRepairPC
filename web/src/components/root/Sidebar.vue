<template>
  <el-aside width="250px">
    <div class="aside-wrap">
      <el-menu
        ref="menu"
        router
        :default-active="defaultRoute"
      >
        <el-menu-item
          v-for="(item, index) in menu"
          :key="index"
          :index="item.route.name"
          :route="item.route"
        >
          <i class="material-icons">
            {{ item.icon }}
          </i>
          <span>{{ item.title }}</span>
        </el-menu-item>
      </el-menu>
      <history />
    </div>
  </el-aside>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  components: {
    ElMenuItem: () => import('element-ui/lib/menu-item'),
    History: () => import('@/components/root/History'),
    ElAside: () => import('element-ui/lib/aside'),
    ElMenu: () => import('element-ui/lib/menu')
  },
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    defaultRoute() {
      return this.$route.name
    },
    routeNames() {
      return Object.values(this.menu).map(m => m.route.name)
    }
  },
  watch: {
    '$route'() {
      if (!this.routeNames.includes(this.defaultRoute)) {
        this.$refs.menu.activeIndex = null
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

.el-aside {
  position: sticky;
  top: $headerHeight;
  height: calc(100vh - #{$headerHeight});
  background: #fff;
  user-select: none;
  border-right: 1px solid $defaultBorder;
}

.el-menu {
  border-right: 0;
}

.el-menu-item {
  display: flex;
  align-items: center;
  > .material-icons {
    margin-right: 10px;
    font-size: 1.2em;
  }
}

@media only screen and (max-width: $laptop) {
  .el-aside {
    width: 64px !important;
  }
  .el-menu-item {
    justify-content: center;
    > .material-icons {
      margin-right: 0;
    }
    > span {
      display: none;
    }
  }
}
</style>
