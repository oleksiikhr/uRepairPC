<template>
  <div class="comment-item">
    <div class="top">
      <div class="message">
        {{ comment.message }}
      </div>
    </div>
    <div class="bottom">
      <div class="user">
        <router-link :to="userPage">
          <i class="material-icons">person</i>
          <span>{{ `${comment.last_name} ${comment.first_name},` }}</span>
        </router-link>
      </div>
      <div class="date">
        {{ createdAt }}
      </div>
      <div
        v-if="canEdit || canDelete"
        class="actions"
      >
        <i
          v-if="canEdit"
          class="material-icons edit"
          title="Редагувати"
          @click="onClickEdit"
        >
          edit
        </i>
        <i
          v-if="canDelete"
          class="material-icons delete"
          title="Видалити"
          @click="onClickDelete"
        >
          delete
        </i>
      </div>
    </div>
  </div>
</template>

<script>
import { hasPerm } from '@/scripts/utils'
import { getDate } from '@/scripts/date'
import sections from '@/enum/sections'

export default {
  props: {
    comment: {
      type: Object,
      required: true
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
  computed: {
    userPage() {
      return { name: `${sections.users}-id`, params: { id: this.comment.user_id } }
    },
    canEdit() {
      return hasPerm(this.permissionEdit, this.comment)
    },
    canDelete() {
      return hasPerm(this.permissionDelete, this.comment)
    },
    createdAt() {
      return getDate(this.comment.created_at)
    }
  },
  methods: {
    onClickEdit() {
      this.$emit('edit', this.comment)
    },
    onClickDelete() {
      this.$emit('delete', this.comment)
    }
  }
}
</script>


<style lang="scss" scoped>
@import "~scss/_colors";

.comment-item {
  padding: 10px 15px;
  border-bottom: 1px solid $defaultBorder;
  transition: 0.15s;
  &:hover {
    box-shadow: 0 0 10px 2px rgba(0, 0, 0, .12);
    .actions {
      opacity: 1;
    }
  }
  &:last-child {
    border-bottom: 0;
  }
}

.top {
  color: $primaryText;
  margin-bottom: 7px;
}

.bottom {
  display: flex;
  align-items: center;
  font-size: .7rem;
  color: $secondaryText;
}

.user a {
  display: flex;
  align-items: center;
  i {
    margin-right: 4px;
  }
}

.message {
  font-size: .9rem;
  color: #000;
  line-height: 1.3;
}

.date {
  margin-left: 5px;
  flex: 1 1 auto;
}

.actions {
  display: flex;
  align-items: center;
  transition: .15s;
  user-select: none;
  opacity: 0;
  i {
    margin-right: 5px;
    padding: 3px 5px;
    cursor: pointer;
    color: $secondaryText;
    transition: 0.15s;
    &:last-child {
      margin-right: 0;
    }
    &:hover {
      &.edit {
        color: $primary;
      }
      &.delete {
        color: $danger;
      }
    }
  }
}
</style>
