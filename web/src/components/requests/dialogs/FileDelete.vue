<template>
  <basic-delete
    :title="file.name"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
import RequestFile from '@/classes/RequestFile'

export default {
  components: {
    BasicDelete: () => import('@/common/components/dialogs/BasicDelete')
  },
  inheritAttrs: false,
  props: {
    request: {
      type: Object,
      required: true
    },
    file: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      loading: false
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

      RequestFile.fetchDelete(this.request.id, this.file.id)
        .then(() => {
          this.$emit('file-delete', this.index)
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
