<template>
  <div class="history">
    <div
      class="history-title"
      @click="onClickTitle"
    >
      <span>Відкриті сторінки</span>
      <i class="material-icons">turned_in</i>
    </div>
    <history-collapse />
  </div>
</template>

<script>
export default {
  components: {
    HistoryCollapse: () => import('@/components/history/Collapse')
  },
  methods: {
    onClickTitle() {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => import('@/components/history/dialogs/History')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

.history-title {
  display: flex;
  align-items: center;
  justify-content: center;
  border-top: 1px solid $defaultBorder;
  border-bottom: 1px solid $defaultBorder;
  color: rgba(0, 0, 0, .5);
  background: #f7f7f7;
  padding: 10px 0;
  font-weight: bold;
  font-size: .9em;
  transition: .25s;
  cursor: pointer;
  user-select: none;
  &:hover {
    color: #000;
  }
  > i {
    display: none;
  }
}

@media only screen and (max-width: $laptop) {
  .history {
    /deep/ > .el-collapse {
      display: none;
    }
    .history-title {
      height: 56px;
      > span {
        display: none;
      }
      > i {
        display: block;
      }
    }
  }
}
</style>
