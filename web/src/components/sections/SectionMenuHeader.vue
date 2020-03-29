<template>
  <div
    class="section-menu-item-header"
    @click="onClickHeader(item)"
  >
    <div class="section-menu-item-header__left">
      <i class="material-icons">
        {{ item.icon }}
      </i>
    </div>
    <div class="section-menu-item-header__right">
      <div class="item--title">
        {{ item.title }}
      </div>
      <div
        v-if="item.route"
        class="item--route"
      >
        {{ item.route.name }}
      </div>
    </div>
    <div class="section-menu-item-header__icon">
      <i class="material-icons">
        chevron_right
      </i>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClickHeader(item) {
      this.$router.push(item.route)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.section-menu-item-header {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  box-shadow: $basicShadow;
  background: #fff;
  cursor: pointer;
  &:hover .section-menu-item-header__icon {
    opacity: 1;
    transform: translateX(0);
  }
}

.section-menu-item-header__left {
  display: flex;
  width: 30px;
  text-align: center;
  margin-right: 10px;
  font-size: 1.3rem;
}

.section-menu-item-header__right {
  flex: 1 1 auto;
}

.section-menu-item-header__icon {
  display: flex;
  transform: translateX(-10px);
  font-size: 1.2rem;
  margin-left: 10px;
  transition: .2s;
  opacity: 0;
}

.item--title {
  font-weight: bold;
  font-size: 1.1rem;
}

.item--route {
  color: $info;
  font-size: .8rem;
}
</style>
