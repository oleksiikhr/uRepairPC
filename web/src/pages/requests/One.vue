<template>
  <template-one
    :buttons="buttons"
    :table-data="tableData"
    :loading="loading"
  >
    <div
      slot="header"
      class="title"
    >
      {{ model.title }}
    </div>
    <template
      slot="table"
      slot-scope="{ row }"
    >
      <table-cell-color
        v-if="row.prop === 'priority_name'"
        :value="row.value"
        :color="model.priority_color"
      />
      <table-cell-color
        v-else-if="row.prop === 'status_name'"
        :value="row.value"
        :color="model.status_color"
      />
      <span v-else>{{ row.value }}</span>
    </template>
    <div
      v-if="model.files && (model.files.length || loadingFiles)"
      class="page--width"
    >
      <div class="divider">
        <span>Файли</span>
      </div>
      <div>
        <files-list
          :files="model.files"
          :loading="loadingFiles"
          :url-download="(file) => `requests/${model.id}/files/${file.id}`"
          v-bind="filesPermissions"
          @add="onAddFile"
          @edit="onEditFile"
          @delete="onDeleteFile"
          @refresh="fetchRequestFiles"
        />
      </div>
    </div>
    <div
      v-if="model.comments && (model.comments.length || loadingComments)"
      class="page--width"
    >
      <div class="divider">
        <span>Коментарії</span>
      </div>
      <comments-list
        class="mb-20"
        :comments="model.comments"
        :loading="loadingComments"
        v-bind="commentsPermissions"
        @edit="onEditComment"
        @delete="onDeleteComment"
        @refresh="fetchRequestComments"
      />
      <comment-create
        v-if="canCreateComment"
        :request="model"
        @comment-create="onAddComment"
      />
    </div>
  </template-one>
</template>

<script>
import RequestComment from '@/classes/RequestComment'
import RequestFile from '@/classes/RequestFile'
import { hasPerm } from '@/scripts/utils'
import Request from '@/classes/Request'
import sections from '@/enum/sections'
import onePage from '@/mixins/onePage'
import * as perm from '@/enum/perm'
import types from '@/enum/types'

export default {
  components: {
    TableCellColor: () => import('@/components/TableCellColor'),
    CommentCreate: () => import('@/components/comments/Create'),
    CommentsList: () => import('@/components/comments/List'),
    TemplateOne: () => import('@/components/template/One'),
    FilesList: () => import('@/components/files/List')
  },
  mixins: [
    onePage(sections.requests)
  ],
  data() {
    return {
      loading: false,
      loadingFiles: false,
      loadingComments: false
    }
  },
  computed: {
    buttons() {
      return [
        {
          title: 'Оновити',
          type: types.SUCCESS,
          action: () => this.fetchRequest('loading')
        },
        {
          title: 'Редагувати',
          type: types.PRIMARY,
          permissions: [
            perm.REQUESTS_EDIT_ALL,
            this.profile.id === this.model.user_id && perm.REQUESTS_EDIT_OWN,
            this.profile.id === this.model.assign_id && perm.REQUESTS_EDIT_ASSIGN
          ],
          action: () => this.openDialog(import('@/components/requests/dialogs/Edit'))
        },
        {
          title: 'Додати коментарій',
          type: types.PRIMARY,
          permissions: perm.REQUESTS_COMMENTS_CREATE,
          action: () => this.openDialog(import('@/components/requests/dialogs/CommentCreate'))
        },
        {
          title: 'Завантажити файл',
          type: types.PRIMARY,
          permissions: perm.REQUESTS_FILES_CREATE,
          action: () => this.openDialog(import('@/components/requests/dialogs/FilesUpload'))
        },
        {
          title: 'Видалити',
          type: types.DANGER,
          permissions: [
            perm.REQUESTS_DELETE_ALL,
            this.profile.id === this.model.user_id && perm.REQUESTS_DELETE_OWN,
            this.profile.id === this.model.assign_id && perm.REQUESTS_DELETE_ASSIGN
          ],
          action: () => this.openDialog(import('@/components/requests/dialogs/Delete'))
        }
      ]
    },
    tableData() {
      const props = [
        'location', 'user_name', 'assign_name', 'type_name', 'priority_name', 'status_name', 'equipment_name',
        'equipment_serial_number', 'equipment_inventory_number', 'description', 'created_at', 'updated_at'
      ]
      const result = []

      this.$store.getters['requests/columns']
        .forEach((obj) => {
          if (props.includes(obj.prop)) {
            result.push({ ...obj, value: this.model[obj.prop] })
          }
        })

      return result
    },
    profile() {
      return this.$store.state.profile.user
    },
    filesPermissions() {
      return {
        'permission-create': perm.REQUESTS_FILES_CREATE,
        'permission-download': (file) => hasPerm(perm.REQUESTS_FILES_DOWNLOAD_ALL) ||
          (hasPerm(perm.REQUESTS_FILES_DOWNLOAD_OWN) && file.user_id === this.profile.id),
        'permission-edit': (file) => hasPerm(perm.REQUESTS_FILES_EDIT_ALL) ||
          (hasPerm(perm.REQUESTS_EDIT_OWN) && file.user_id === this.profile.id),
        'permission-delete': (file) => hasPerm(perm.REQUESTS_FILES_DELETE_ALL) ||
          (hasPerm(perm.REQUESTS_DELETE_OWN) && file.user_id === this.profile.id)
      }
    },
    commentsPermissions() {
      return {
        'permission-edit': (comment) => hasPerm(perm.REQUESTS_COMMENTS_EDIT_ALL) ||
          (hasPerm(perm.REQUESTS_COMMENTS_EDIT_OWN) && comment.user_id === this.profile.id),
        'permission-delete': (comment) => hasPerm(perm.REQUESTS_COMMENTS_DELETE_ALL) ||
          (hasPerm(perm.REQUESTS_COMMENTS_DELETE_OWN) && comment.user_id === this.profile.id)
      }
    },
    canCreateComment() {
      return hasPerm(perm.REQUESTS_COMMENTS_CREATE)
    }
  },
  methods: {
    fetchData() {
      if (!this.model.id) {
        this.fetchRequest()
      }
      if (!this.model.files) {
        this.fetchRequestFiles()
      }
      if (!this.model.comments) {
        this.fetchRequestComments()
      }
    },
    fetchRequest() {
      this.loading = true

      Request.fetchOne(this.pageId)
        .catch(() => {
          this.$router.push({ name: sections.requests })
        })
        .finally(() => {
          this.loading = false
        })
    },
    fetchRequestFiles() {
      this.loadingFiles = true
      this.updateData({ files: [] })

      RequestFile.fetchAll(this.pageId)
        .then(({ data }) => {
          this.updateData({ files: data.request_files })
        })
        .finally(() => {
          this.loadingFiles = false
        })
    },
    fetchRequestComments() {
      this.loadingComments = true
      this.updateData({ comments: [] })

      RequestComment.fetchAll(this.pageId)
        .then(({ data }) => {
          this.updateData({ comments: data.request_comments })
        })
        .finally(() => {
          this.loadingComments = false
        })
    },
    openDialog(component, attrs = {}) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => component,
        attrs: {
          request: this.model,
          ...attrs
        },
        events: {
          delete: () => {
            this.$router.push({ name: sections.requests })
          },
          'comment-create': this.onAddComment,
          'comment-update': (comment, index) => {
            const comments = [...this.model.comments]
            comments[index] = comment
            this.updateData({ comments })
          },
          'comment-delete': (index) => {
            const comments = [...this.model.comments]
            comments.splice(index, 1)
            this.updateData({ comments })
          },
          'files-upload': (uploadFiles) => {
            this.updateData({ files: [...uploadFiles, ...this.model.files] })
          },
          'file-update': (file, index) => {
            const files = [...this.model.files]
            files[index] = file
            this.updateData({ files })
          },
          'file-delete': (index) => {
            const files = [...this.model.files]
            files.splice(index, 1)
            this.updateData({ files })
          }
        }
      })
    },
    updateData(data) {
      Request.sidebar().add({ id: this.pageId, ...data })
    },
    onAddFile() {
      this.openDialog(import('@/components/requests/dialogs/FilesUpload'))
    },
    onEditFile(file, index) {
      this.openDialog(import('@/components/requests/dialogs/FileEdit'), { file, index })
    },
    onDeleteFile(file, index) {
      this.openDialog(import('@/components/requests/dialogs/FileDelete'), { file, index })
    },
    onAddComment(comment) {
      this.updateData({ comments: [...this.model.comments, comment] })
    },
    onEditComment(comment, index) {
      this.openDialog(import('@/components/requests/dialogs/CommentEdit'), { comment, index })
    },
    onDeleteComment(comment, index) {
      this.openDialog(import('@/components/requests/dialogs/CommentDelete'), { comment, index })
    }
  }
}
</script>

<style lang="scss" scoped>
.title {
  font-size: 1.5rem;
  font-weight: bold;
  overflow-wrap: break-word;
}
</style>
