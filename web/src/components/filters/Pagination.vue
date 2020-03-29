<template>
  <filter-basic
    v-if="hasPagination"
    title="Дані"
    class="filter-pagination"
  >
    <el-alert
      title="Кількість даних, які є на сервері."
      type="info"
    />
    <div class="filter-pagination__circles">
      <circle-progress
        class="success"
        :text="`${pagination.current_page} / ${pagination.last_page}`"
        sub-text="Сторінок"
        :value="100 * (pagination.current_page / pagination.last_page)"
      />
      <circle-progress
        class="danger"
        :text="textData"
        sub-text="Даних"
        :value="100 * ((pagination.current_page * pagination.per_page) / pagination.total)"
      />
    </div>
  </filter-basic>
</template>

<script>
export default {
  name: 'FilterPagination',
  components: {
    CircleProgress: () => import('@/components/CircleProgress'),
    FilterBasic: () => import('@/components/filters/Basic'),
    ElAlert: () => import('element-ui/lib/alert')
  },
  props: {
    pagination: {
      type: Object,
      required: true
    }
  },
  computed: {
    hasPagination() {
      return !!Object.keys(this.pagination).length
    },
    textData() {
      let val = this.pagination.current_page * this.pagination.per_page

      if (val > this.pagination.total) {
        val = this.pagination.total
      }

      return `${val} / ${this.pagination.total}`
    }
  }
}
</script>
