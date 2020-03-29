<template>
  <basic-delete
    :title="comment.message"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
import RequestComment from '@/classes/RequestComment'

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
    comment: {
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

      RequestComment.fetchDelete(this.request.id, this.comment.id)
        .then(() => {
          this.$emit('comment-delete', this.index)
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
