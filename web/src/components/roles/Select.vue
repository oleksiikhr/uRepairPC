<template>
  <el-select
    :value="value"
    multiple
    filterable
    remote
    reserve-keyword
    default-first-option
    placeholder="Введіть текст для отримання списку"
    :remote-method="fetchRequest"
    :loading="loading"
    v-bind="$attrs"
    @focus="onFocus"
    v-on="$listeners"
  >
    <el-option
      v-for="item in list"
      :key="item.name"
      :label="item.name"
      :value="item.id"
      :style="{
        'background-color': item.color + '10',
        color: item.color
      }"
    />
  </el-select>
</template>

<script>
import Role from '@/classes/Role'

export default {
  components: {
    ElSelect: () => import('element-ui/lib/select'),
    ElOption: () => import('element-ui/lib/option')
  },
  props: {
    value: {
      type: Array,
      default: () => []
    },
    defaultRoles: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      list: [],
      loading: false,
      init: true
    }
  },
  created() {
    this.list = this.defaultRoles
    this.$emit('input', this.list.map(item => item.id))
  },
  methods: {
    fetchRequest(query) {
      const params = { count: 10 }
      this.loading = true

      if (query && query.trim()) {
        params.search = query
        params.columns = ['name']
      }

      Role.fetchAll({ params })
        .then(({ data }) => {
          this.list = data.data
        })
        .finally(() => {
          this.loading = false
        })
    },
    onFocus() {
      if (this.init) {
        this.fetchRequest()
        this.init = false
      }
    }
  }
}
</script>
