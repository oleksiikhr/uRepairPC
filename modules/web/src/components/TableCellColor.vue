<template>
  <div
    class="priority-cell"
    :style="styles"
  >
    {{ value }}
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: ''
    },
    color: {
      type: String,
      default: null
    },
    appendBackgroundColor: {
      type: [String, Number],
      default: '30'
    },
    appendBorderColor: {
      type: [String, Number],
      default: '40'
    }
  },
  computed: {
    styles() {
      if (this.color) {
        return {
          'background-color': this.color + this.appendBackgroundColor,
          'border-color': this.color + this.appendBorderColor,
          color: this.color
        }
      }

      return {
        'background-color': 'transparent',
        'border-color': '#e6e6e6',
        color: 'inherit'
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.priority-cell {
  max-width: 150px;
  padding: 2px 10px;
  border-radius: 5px;
}
</style>
