<template>
  <el-popover
    ref="popover"
    placement="top-start"
    trigger="click"
    popper-class="popover-role"
    v-bind="$attrs"
    v-on="$listeners"
    @show="onPopoverShow"
  >
    <el-tag
      slot="reference"
      class="role-tag"
      size="medium"
      :style="styles"
    >
      {{ role.name }}
    </el-tag>
    <!--Content-->
    <div class="content">
      <div class="title">
        Доступи
      </div>
      <div
        v-if="roleStore"
        class="sections"
      >
        <div
          v-for="(section, key) in filteredActivePermissions"
          :key="key"
          class="section"
        >
          <div class="section-title">
            {{ key }}
          </div>
          <div
            v-for="(item, index) in section"
            :key="index"
            class="section-list"
          >
            {{ item.action }}
          </div>
        </div>
      </div>
      <div
        v-else
        v-loading="true"
        class="loading"
      />
    </div>
  </el-popover>
</template>

<script>
import Role from '@/classes/Role'

export default {
  components: {
    ElPopover: () => import('element-ui/lib/popover'),
    ElTag: () => import('element-ui/lib/tag'),
  },
  inheritAttrs: false,
  props: {
    role: {
      type: Object,
      required: true
    }
  },
  computed: {
    roleStore() {
      return Role.sidebar().get(this.role.id)
    },
    styles() {
      if (this.role.color) {
        return {
          'background-color': this.role.color + '10',
          'border-color': this.role.color + '20',
          color: this.role.color
        }
      }

      return {
        'background-color': 'transparent',
        'border-color': '#e6e6e6',
        color: 'inherit'
      }
    },
    filteredActivePermissions() {
      const result = {}

      Object.entries(this.roleStore.permissions_list).forEach(([section, arr]) => {
        const items = arr.filter(item => !!item.active)
        if (items.length) {
          result[section] = items
        }
      })

      return result
    }
  },
  methods: {
    onPopoverShow() {
      if (!this.roleStore) {
        Role.fetchOne(this.role.id)
          .then(() => {
            this.$refs.popover.updatePopper()
          })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.role-tag {
  margin: 2px 5px 2px 0;
  user-select: none;
  cursor: pointer;
}

.title {
  text-align: center;
  font-weight: bold;
  padding: 5px;
  border-bottom: 1px solid $baseBorder;
  box-shadow: $lightShadow;
}

.sections {
  padding: 12px;
  max-height: 300px;
  overflow: auto;
}

.section {
  margin-bottom: 10px;
  &:last-child {
    margin-bottom: 0;
  }
}

.section-title {
  font-weight: bold;
}

.section-list {
  margin-left: 10px;
}

.loading {
  height: 100px;
}
</style>
