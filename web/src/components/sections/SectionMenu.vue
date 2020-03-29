<template>
  <div class="section-menu">
    <div
      v-for="(item, index) in menu"
      :key="index"
      class="section-menu-item"
    >
      <section-menu-header :item="item" />
      <section-menu-action-list
        v-if="item.children"
        :list="item.children"
      />
    </div>
  </div>
</template>

<script>
export default {
  components: {
    SectionMenuActionList: () => import('@/components/sections/SectionMenuActionList'),
    SectionMenuHeader: () => import('@/components/sections/SectionMenuHeader')
  },
  props: {
    menu: {
      type: [Array, Object],
      required: true
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";

.section-menu {
  display: flex;
  flex-wrap: wrap;
  user-select: none;
}

.section-menu-item {
  width: 340px;
  padding: 10px;
  margin: 10px;
}

@media only screen and (max-width: $mobileL) {
  .section-menu-item {
    width: 100%;
    margin: 10px 0;
    padding: 0;
  }
}
</style>
