<template>
  <div v-if="imgApi">
    <img
      v-if="value"
      :src="imgApi"
      :alt="attr"
    >
    <span v-else>-</span>
  </div>
  <div v-else-if="type === 'bool'">
    {{ value ? 'Так' : 'Ні' }}
  </div>
  <div v-else-if="type === 'color'">
    <div
      class="cell--color"
      :style="{ 'background-color': value }"
    />
  </div>
  <div v-else>
    {{ value }}
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Number, Boolean],
      default: null
    },
    type: {
      type: String,
      required: true,
      validator: (val) => {
        return ~['text', 'img', 'bool', 'color'].indexOf(val)
      }
    },
    attr: {
      type: String,
      required: true
    }
  },
  computed: {
    imgApi() {
      if (this.type !== 'img') {
        return null
      }

      return this.value
    }
  }
}
</script>
