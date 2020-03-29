<template>
  <div class="circle-progress">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="23.5 23.5 47 47"
    >
      <circle
        fill="transparent"
        cx="47"
        cy="47"
        r="20"
        stroke-width="3"
        :stroke-dasharray="dasharray"
        stroke-dashoffset="0"
        class="top"
      />
      <circle
        fill="transparent"
        cx="47"
        cy="47"
        r="20"
        stroke-width="3"
        :stroke-dasharray="dasharray"
        :stroke-dashoffset="dashOffset"
        class="bottom"
      />
    </svg>
    <slot>
      <div class="circle-progress__text">
        <div class="top">
          {{ text }}
        </div>
        <div class="bottom">
          {{ subText }}
        </div>
      </div>
    </slot>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
      default: 0
    },
    text: {
      type: [String, Number],
      default: ''
    },
    subText: {
      type: [String, Number],
      default: ''
    }
  },
  data() {
    return {
      dasharray: 126
    }
  },
  computed: {
    normalizedValue() {
      if (this.value < 0) {
        return 0
      }

      if (this.value > 100) {
        return 100
      }

      return parseFloat(this.value)
    },
    dasharrayRest() {
      return this.normalizedValue * ((this.dasharray - 100) / 100)
    },
    dashOffset() {
      return this.dasharray - this.normalizedValue - this.dasharrayRest
    }
  }
}
</script>

<style lang="scss" scoped>
.circle-progress {
  position: relative;
}

.circle-progress__text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  .top {
    font-weight: bold;
    + .bottom {
      margin-top: 5px;
    }
  }
  .bottom {
    font-size: 13px;
  }
}

svg {
  width: 100%;
  height: 100%;
  transform: rotate(-90deg);
}

.top {
  stroke: rgba(0, 0, 0, .1);
  z-index: 1;
}

.bottom {
  stroke: currentColor;
  z-index: 2;
  transition: all .5s ease-in-out;
}
</style>
