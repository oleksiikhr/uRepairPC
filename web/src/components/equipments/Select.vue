<template>
  <el-select
    filterable
    remote
    :remote-method="remoteMethod"
    automatic-dropdown
    :loading="loading || loadingStore"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-option
      v-for="item in list"
      :key="item.id"
      :label="`${item.model_name || ''}, ${item.serial_number || '-'} / ${item.inventory_number || '-'}`"
      :value="item.id"
    />
  </el-select>
</template>

<script>
import Equipment from '@/classes/Equipment'
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
      list
    }
  },
  computed: {
    ...mapState({
      listStore: state => state.equipments.list.data || [],
      loadingStore: state => state.equipments.loading,
      init: state => state.equipments.init
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

      Equipment.fetchAll({
        params: {
          search: search || undefined,
          columns: ['model_name', 'inventory_number', 'serial_number']
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
        await this.$store.dispatch('equipments/fetchList')
      }

      this.list = [...this.listStore]
    }
  }
}
</script>
