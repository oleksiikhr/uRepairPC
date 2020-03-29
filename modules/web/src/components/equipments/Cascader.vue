<template>
  <el-cascader
    :options="options"
    :props="{ label: 'name', value: 'id', checkStrictly: true }"
    :placeholder="loading ? 'Завантаження..' : 'Тип, Виробник, Модель'"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  components: {
    ElCascader: () => import('element-ui/lib/cascader')
  },
  inheritAttrs: false,
  data() {
    return {
      loading: true
    }
  },
  computed: {
    ...mapState({
      listTypes: state => state.equipmentTypes.list,
      listModels: state => state.equipmentModels.list
    }),
    ...mapGetters({
      options: 'equipments/cascaderOptions'
    }),
    listeners() {
      return {
        ...this.$listeners,
        input: (val) => {
          this.$emit('input', val)
        }
      }
    }
  },
  async mounted() {
    if (!this.listTypes.length) {
      await this.$store.dispatch('equipmentTypes/fetchList')
    }

    if (!this.listModels.length) {
      await this.$store.dispatch('equipmentModels/fetchList')
    }

    this.loading = false
  }
}
</script>
