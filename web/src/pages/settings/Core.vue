<template>
  <el-tabs
    :value="routeName"
    @tab-click="onTabClick"
  >
    <el-tab-pane
      v-for="(tab, index) in sectionMenu"
      :key="index"
      :label="tab.label"
      :name="tab.name"
    />
    <transition
      name="anim"
      mode="out-in"
      appear
    >
      <keep-alive>
        <router-view :key="$route.name" />
      </keep-alive>
    </transition>
  </el-tabs>
</template>

<script>
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'

export default {
  name: 'SettingsCore',
  components: {
    ElTabPane: () => import('element-ui/lib/tab-pane'),
    ElTabs: () => import('element-ui/lib/tabs')
  },
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    sectionMenu() {
      const menu = this.menu[sections.settings]

      if (!menu) {
        return {}
      }

      const actions = menu.children

      const arr = [
        { label: 'Головна', name: sections.settings }
      ]

      Object.entries(actions).forEach(([key, obj]) => {
        if (obj.tag === 'page') {
          arr.push({ label: obj.title, name: key })
        }
      })

      return arr
    },
    routeName() {
      return this.$route.name
    }
  },
  methods: {
    onTabClick(component) {
      this.$router.push({ name: component.name })
    }
  }
}
</script>

<style lang="scss" scoped>
/deep/ .el-tabs__content {
	padding: 0 20px 50px;
}

/deep/ .el-tabs__item {
	user-select: none;
}

/deep/ .el-tabs__nav-wrap {
	background-color: #f5f7fa;
	padding: 0 30px;
}

/deep/ .el-tabs__nav-prev {
	left: 5px;
	padding: 3px 5px;
}

/deep/ .el-tabs__nav-next {
	right: 5px;
	padding: 3px 5px;
}

// <animation>
.anim-enter-active {
	transition: .25s transform;
	opacity: 0;
	transform: translateY(-10px);
}

.anim-enter-to {
	opacity: 1;
	transform: translateY(0);
}

.anim-leave-active,
.anim-leave-to {
	display: none;
}
</style>
