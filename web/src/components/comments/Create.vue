<template>
  <div class="comments-create">
    <el-input
      v-model="message"
      type="textarea"
      :autosize="{ minRows: 3 }"
      placeholder="Коментарій"
    />
    <el-button
      size="small"
      type="primary"
      :loading="loading"
      :disabled="loading"
      @click="onClick"
    >
      Додати коментарій
    </el-button>
  </div>
</template>

<script>
import RequestComment from '@/classes/RequestComment'

export default {
  components: {
    ElButton: () => import('element-ui/lib/button'),
    ElInput: () => import('element-ui/lib/input')
  },
  props: {
    request: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      message: ''
    }
  },
  methods: {
    onClick() {
      this.loading = true

      RequestComment.fetchStore(this.request.id, {
        message: this.message
      })
        .then(({ data }) => {
          this.$emit('comment-create', data.request_comment)
          this.message = ''
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.comments-create {
  text-align: right;
}

.el-button {
  margin: 10px 0;
}
</style>
