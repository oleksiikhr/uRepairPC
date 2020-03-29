<template>
  <basic-delete
    :title="request.title"
    :confirm="request.id"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-checkbox
      slot="content-bottom"
      v-model="filesDelete"
    >
      Видалити файли?
    </el-checkbox>
  </basic-delete>
</template>

<script>
import Request from '@/classes/Request'

export default {
  components: {
    BasicDelete: () => import('@/common/components/dialogs/BasicDelete'),
    ElCheckbox: () => import('element-ui/lib/checkbox')
  },
  inheritAttrs: false,
  props: {
    request: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      filesDelete: true
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      Request.fetchDelete(this.request.id, {
        data: {
          files_delete: this.filesDelete
        }
      })
        .then(() => {
          this.$store.commit('requests/DELETE_ITEM', this.request.id)
          this.$emit('delete')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
