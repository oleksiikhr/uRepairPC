<template>
  <div class="comments-loading">
    <div
      v-for="i in 6"
      :key="i"
      class="comment-loading"
    >
      <div class="comment-loading-content">
        <div
          class="top"
          :style="{
            width: getRndInteger(30, 50) + '%'
          }"
        />
        <div
          class="bottom"
          :style="{
            width: getRndInteger(20, 30) + '%'
          }"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { getRndInteger } from '@/scripts/helpers'

export default {
  methods: { getRndInteger }
}
</script>

<style lang="scss" scoped>
@import "~scss/_keyframes";
@import "~scss/_colors";

.comments-loading {
  border: 1px solid $defaultBorder;
  background: #fff;
}

.comment-loading {
  display: flex;
  align-items: center;
  height: 58px;
  padding: 10px 15px;
  border-bottom: 1px solid $defaultBorder;
  &:last-child {
    border-bottom: 0;
  }
}

.comment-loading-content {
  width: 100%;
  .top {
    width: 20%;
    height: 10px;
    margin-bottom: 5px;
    @include contentLoader;
  }
  .bottom {
    width: 20%;
    height: 10px;
    @include contentLoader;
  }
}
</style>
