<template>
  <transition
    name="tips-anim"
    mode="out-in"
  >
    <!--eslint-disable vue/no-v-html-->
    <div
      :key="index"
      class="tips"
      @click.prevent="onClick"
      v-html="currentTip.text"
    />
  </transition>
</template>

<script>
import { getRndInteger } from '@/scripts/helpers'
import { hasPerm } from '@/scripts/utils'
import tips from '@/data/tips'

export default {
  props: {
    nextTipCount: {
      type: Number,
      default: 8
    }
  },
  data() {
    return {
      index: 0,
      currentTip: '',
      nextTip: this.nextTipCount
    }
  },
  computed: {
    profile() {
      return this.$store.state.profile.user
    },
    len() {
      return this.tipsList.length
    },
    tipsList() {
      return tips.filter(obj => hasPerm(obj.permissions))
    }
  },
  watch: {
    '$route'() {
      if (--this.nextTip <= 0) {
        this.changeTip()
        this.nextTip = this.nextTipCount
      }
    }
  },
  created() {
    this.changeTip()
  },
  methods: {
    onClick() {
      this.changeTip()
    },
    changeTip() {
      let newIndex = getRndInteger(0, this.len - 1)

      if (newIndex === this.index) {
        newIndex = ++newIndex >= this.len ? 0 : newIndex
      }

      this.index = newIndex
      this.currentTip = this.tipsList[this.index]
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.tips {
  color: $regularText;
  font-size: .9rem;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow-x: hidden;
  cursor: pointer;
  /deep/ code {
    display: inline-block;
    background: #f7f7f7;
    padding: 3px 10px;
    font-weight: bold;
    border-radius: 5px;
    color: #333;
    border: 1px solid #dadada;
  }
}

// <animation>

.tips-anim-enter-active {
  opacity: 0;
  transform: translateY(-10px);
  transition: .3s;
}

.tips-anim-enter-to {
  transform: translateY(0);
  opacity: 1;
}

.tips-anim-leave-active {
  transform: translateY(0);
  opacity: 1;
  transition: .3s;
}

.tips-anim-leave-to {
  transform: translateY(10px);
  opacity: 0;
}
</style>
