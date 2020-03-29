<template>
  <div class="filter">
    <div
      v-if="title"
      class="filter-title"
    >
      {{ title }}
    </div>
    <div class="filter__content">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'FilterBasic',
  props: {
    title: {
      type: [String, Number],
      default: ''
    }
  }
}
</script>
