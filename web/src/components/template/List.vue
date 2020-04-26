<template>
  <transition
    name="anim"
    appear
  >
    <div class="template template-page">
      <div class="left-column">
        <slot name="left-column" />
      </div>
      <div class="right-column">
        <slot name="right-column" />
      </div>
    </div>
  </transition>
</template>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

$rightColumnWidth: 300px;

.template-page {
  display: flex;
  flex-direction: row;
  > .left-column {
    flex: 1 1 auto;
    width: calc(100% - #{$rightColumnWidth});
  }
  > .right-column {
    min-width: $rightColumnWidth;
    width: $rightColumnWidth;
    border-left: 1px solid $defaultBorder;
    background: #fff;
    /deep/ .el-collapse-item__wrap {
      border-bottom: 0;
    }
  }
}

.anim-enter-active {
  transition: .25s opacity;
  opacity: 0;
}

.anim-enter-to {
  opacity: 1;
}

.anim-leave-active,
.anim-leave-to {
  display: none;
}

@media only screen and (max-width: $laptop) {
  .template-page {
    flex-direction: column-reverse;
    > .left-column {
      width: 100%;
    }
    > .right-column {
      background: #fbfbfb;
      width: 100%;
      min-width: auto;
      border-left: 0;
      border-bottom: 1px solid $defaultBorder;
      padding: 15px;
    }
  }
}
</style>
