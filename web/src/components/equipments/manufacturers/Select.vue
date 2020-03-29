<template>
  <el-select
    :value="displayValue"
    :loading="loading"
    popper-class="select--refresh"
    placeholder="Обрати виробника"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-option
      v-for="item in list"
      :key="item.id"
      :label="item.name"
      :value="item.id"
    />
    <el-button
      type="text"
      class="btn-refresh"
      @click="fetchList"
    >
      Оновити
    </el-button>
  </el-select>
</template>

<script>
import { mapState } from 'vuex'

export default {
  components: {
    ElSelect: () => import('element-ui/lib/select'),
    ElOption: () => import('element-ui/lib/option'),
    ElButton: () => import('element-ui/lib/button')
  },
  inheritAttrs: false,
  props: {
    value: {
      type: Number,
      default: null
    }
  },
  computed: {
    ...mapState({
      list: state => state.equipmentManufacturers.list,
      loading: state => state.equipmentManufacturers.loading
    }),
    displayValue() {
      if (this.value && !this.hasItems && this.loading) {
        return 'Завантаження'
      }

      return this.value
    },
    hasItems() {
      return this.list.length
    },
    listeners() {
      return {
        ...this.$listeners,
        input: (val) => {
          this.$emit('input', val)
        },
        focus: () => {
          if (!this.hasItems) {
            this.fetchList()
          }
        }
      }
    }
  },
  mounted() {
    if (this.value && !this.hasItems) {
      this.fetchList()
    }
  },
  methods: {
    fetchList() {
      this.$store.dispatch('equipmentManufacturers/fetchList')
    }
  }
}
</script>
