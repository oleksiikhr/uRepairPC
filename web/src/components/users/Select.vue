<template>
  <el-select
    filterable
    remote
    :remote-method="remoteMethod"
    automatic-dropdown
    clearable
    :loading="loading || loadingStore"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-option
      v-for="item in list"
      :key="item.id"
      :label="`${item.last_name || ''} ${item.first_name || ''}`"
      :value="item.id"
    />
  </el-select>
</template>

<script>
import User from '@/classes/User'
import { mapState } from 'vuex'

export default {
  components: {
    ElSelect: () => import('element-ui/lib/select'),
    ElOption: () => import('element-ui/lib/option')
  },
  inheritAttrs: false,
  props: {
    defaultValue: {
      type: Object,
      default: null
    }
  },
  data() {
    const list = []

    if (this.defaultValue) {
      list.push(this.defaultValue)
    }

    return {
      loading: false,
      init: true,
      list
    }
  },
  computed: {
    ...mapState({
      listStore: state => state.users.list.data || [],
      loadingStore: state => state.users.loading
    }),
    listeners() {
      return {
        ...this.$listeners,
        focus: this.onFocus
      }
    }
  },
  methods: {
    remoteMethod(search) {
      this.loading = true

      User.fetchAll({
        params: {
          search: search || undefined,
          columns: ['last_name', 'first_name'],
          request_access: 1
        }
      })
        .then(({ data }) => {
          this.list = data.data || []
        })
        .finally(() => {
          this.loading = false
        })
    },
    async onFocus() {
      if (this.init) {
        this.remoteMethod()
        this.init = false
      }
    }
  }
}
</script>
