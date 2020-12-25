<template>
  <el-collapse
    v-model="activeNames"
    class="history-collapse"
  >
    <el-collapse-item
      v-for="(obj, section) in historyMenu"
      v-show="activeSections[obj.route.name]"
      :key="section"
      :name="section"
    >
      <template slot="title">
        <i class="material-icons">{{ obj.icon }}</i>
        <span class="history-collapse-title">{{ obj.title }}</span>
      </template>
      <div
        v-for="(historyItem, j) in sidebar[obj.route.name]"
        :key="j"
        class="history-collapse-item"
      >
        <span @click="onClickItem(historyItem, obj.route.name)">{{ getText(obj, historyItem) }}</span>
        <i
          class="material-icons"
          @click="removeHistoryItem(section, historyItem)"
        >
          clear
        </i>
      </div>
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  components: {
    ElCollapseItem: () => import('element-ui/lib/collapse-item'),
    ElCollapse: () => import('element-ui/lib/collapse')
  },
  data() {
    return {
      activeNames: []
    }
  },
  computed: {
    ...mapGetters({
      historyMenu: 'template/historyMenu'
    }),
    sidebar() {
      return this.$store.state.template.sidebar
    },
    activeSections() {
      const sections = {}

      Object.entries(this.sidebar).forEach(([key, val]) => {
        if (Object.keys(val).length) {
          sections[key] = true
        }
      })

      return sections
    }
  },
  methods: {
    removeHistoryItem(section, historyItem) {
      const routeId = +this.$route.params.id

      this.$store.commit(`template/REMOVE_SIDEBAR_ITEM`, {
        section,
        id: historyItem.id
      })

      if (this.$route.name === `${section}-id` && routeId === historyItem.id) {
        this.$router.push({ name: section })
      }
    },
    getText(obj, historyItem) {
      if (obj.history.callback && typeof obj.history.callback === 'function') {
        return obj.history.callback(historyItem)
      }

      return `[${historyItem.id}] ${historyItem.name}` || historyItem.id
    },
    onClickItem(obj, route) {
      this.$emit('click-item', obj, route)
      this.$router.push({ name: `${route}-id`, params: { id: obj.id } })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

.history-collapse {
  border: 0;
}

.history-collapse-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 5px;
  cursor: pointer;
  &:hover {
    > i {
      opacity: 1;
    }
  }
  > i,
  > span {
    padding: 3px 0;
    transition: .2s;
  }
  > span {
    flex: 1 1 auto;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    &:hover {
      color: $primary;
    }
  }
  > i {
    width: 20px;
    text-align: center;
    color: $info;
    opacity: 0;
    &:hover {
      color: $danger;
    }
  }
}

/deep/ .el-collapse-item__header {
  padding: 0 5px 0 15px;
  > i {
    color: #777;
    margin-right: 10px;
  }
}

/deep/ .el-collapse-item__content {
  padding: 0 15px;
}
</style>
