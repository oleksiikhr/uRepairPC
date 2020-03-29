<template>
  <div v-if="isTimestamp">
    {{ timestamp }}
  </div>
  <div v-else-if="isBool">
    {{ value ? 'Так' : 'Ні' }}
  </div>
  <div
    v-else-if="isColor"
    class="cell--color"
    :style="{ 'background-color': value }"
  />
  <div v-else>
    <slot>
      {{ value }}
    </slot>
  </div>
</template>

<script>
import columnTypes from '@/enum/columnTypes'
import { getDate } from '@/scripts/date'

export default {
  props: {
    column: {
      type: Object,
      required: true
    },
    value: {
      type: [Array, Object, Number, String, Boolean],
      default: null
    }
  },
  computed: {
    isBool() {
      return this.column.customType === columnTypes.TYPE_BOOL
    },
    isColor() {
      return this.column.customType === columnTypes.TYPE_COLOR
    },
    isTimestamp() {
      return this.column.customType === columnTypes.TYPE_TIMESTAMP
    },
    timestamp() {
      return getDate(this.value)
    }
  }
}
</script>
