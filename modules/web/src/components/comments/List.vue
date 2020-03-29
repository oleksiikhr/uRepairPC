<template>
  <div class="comments-list">
    <div class="actions">
      <div
        class="refresh"
        @click="onRefresh"
      >
        <i class="material-icons">refresh</i>
        Оновити
      </div>
    </div>
    <loading-comments v-if="loading" />
    <div
      v-else
      class="comments"
    >
      <comment-one
        v-for="(comment, index) in comments"
        :key="index"
        :comment="comment"
        :permission-edit="permissionEdit"
        :permission-delete="permissionDelete"
        @edit="onEdit($event, index)"
        @delete="onDelete($event, index)"
      />
    </div>
  </div>
</template>

<script>
export default {
  components: {
    LoadingComments: () => import('@/components/comments/Loading'),
    CommentOne: () => import('@/components/comments/One')
  },
  props: {
    comments: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    permissionEdit: {
      type: [String, Boolean, Function],
      default: null
    },
    permissionDelete: {
      type: [String, Boolean, Function],
      default: null
    }
  },
  methods: {
    onRefresh() {
      this.$emit('refresh')
    },
    onEdit(...data) {
      this.$emit('edit', ...data)
    },
    onDelete(...data) {
      this.$emit('delete', ...data)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.comments {
  background: #fff;
  border: 1px solid $defaultBorder;
}

.actions {
  display: flex;
  justify-content: flex-end;
  .refresh {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 5px 10px;
    font-size: .9rem;
    color: $secondaryText;
    transition: color .15s;
    cursor: pointer;
    &:hover {
      color: $primary;
    }
    > i {
      margin-right: 5px;
    }
  }
}
</style>
