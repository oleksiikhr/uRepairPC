<template>
  <div class="section-menu-item-action-list">
    <div
      v-for="(item, key) in list"
      :key="key"
      class="section-menu-item-action"
      @click="onClick(item)"
    >
      <div class="section-menu-item-action-wrap">
        <i class="section-menu-item-action--icon material-icons">
          <template v-if="item.tag === 'page'">
            chevron_right
          </template>
          <template v-else-if="item.icon">
            {{ item.icon }}
          </template>
          <template v-else>
            change_history
          </template>
        </i>
        <div class="section-menu-item-action--title">
          {{ item.title }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    list: {
      type: [Array, Object],
      required: true
    }
  },
  methods: {
    onClick(item) {
      if (item.route) {
        this.$router.push(item.route)
      } else if (item.action) {
        item.action()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.section-menu-item-action {
  position: relative;
  margin-top: 15px;
  padding: 5px 10px 5px 0;
  color: $regularText;
  transition: color .2s;
  cursor: pointer;
  &:hover {
    color: #000;
  }
}

.section-menu-item-action-wrap {
  display: flex;
  align-items: center;
}

.section-menu-item-action--icon {
  width: 30px;
  text-align: center;
  margin-right: 5px;
}
</style>
