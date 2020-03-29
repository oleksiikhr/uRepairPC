<template>
  <filter-basic
    v-if="actions"
    title="Дії"
    class="filter-action"
  >
    <el-button
      v-for="(action, key) in actions"
      :key="key"
      size="small"
      :type="action.type"
      @click="action.action"
    >
      <i class="material-icons">
        {{ action.icon }}
      </i>
      <span>{{ action.title }}</span>
    </el-button>
  </filter-basic>
</template>

<script>
import { isEmpty } from '@/scripts/helpers'
import { mapGetters } from 'vuex'

export default {
  name: 'FilterAction',
  components: {
    FilterBasic: () => import('@/components/filters/Basic'),
    ElButton: () => import('element-ui/lib/button')
  },
  inheritAttrs: false,
  props: {
    section: {
      type: String,
      required: true
    },
    enableActionAdd: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    menuSection() {
      return this.menu[this.section] || {}
    },
    /**
     * @returns {object|null}
     */
    actions() {
      const actions = { ...this.menuSection.children }

      if (!this.enableActionAdd) {
        delete actions.add
      }

      if (isEmpty(actions)) {
        return null
      }

      return actions
    }
  }
}
</script>
