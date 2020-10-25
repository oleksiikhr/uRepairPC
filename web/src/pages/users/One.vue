<template>
  <template-one
    :buttons="buttons"
    :table-data="tableData"
    :loading="loading"
  >
    <user-image
      slot="header"
      :user="model"
    />
    <div
      slot="table"
      slot-scope="{ row }"
    >
      <span v-if="row.prop === 'roles'">
        <role-tag
          v-for="(role, index) in row.value"
          :key="index"
          :role="role"
        />
      </span>
      <span v-else-if="row.prop === 'email'">
        <a :href="`mailto:${row.value}`">{{ row.value }}</a>
      </span>
      <pre
        v-else
        class="default"
      >{{ row.value }}</pre>
    </div>
  </template-one>
</template>

<script>
import sections from '@/enum/sections'
import onePage from '@/mixins/onePage'
import * as perm from '@/enum/perm'
import User from '@/classes/User'
import types from '@/enum/types'

export default {
  components: {
    TemplateOne: () => import('@/components/template/One'),
    UserImage: () => import('@/components/users/Image'),
    RoleTag: () => import('@/components/roles/Tag')
  },
  mixins: [
    onePage(sections.users)
  ],
  data() {
    return {
      loading: false
    }
  },
  computed: {
    profile() {
      return this.$store.state.profile.user
    },
    buttons() {
      const ownProfile = this.profile.id === this.model.id

      return [
        {
          title: 'Оновити',
          type: types.SUCCESS,
          action: this.fetchRequest,
          disabled: this.loading
        },
        {
          title: 'Редагувати дані',
          type: types.PRIMARY,
          permissions: [ownProfile && perm.PROFILE_EDIT, perm.USERS_EDIT_ALL],
          action: () => this.openDialog(import('@/components/users/dialogs/Edit'))
        },
        {
          title: 'Редагувати пароль',
          type: types.PRIMARY,
          permissions: [ownProfile && perm.PROFILE_EDIT, perm.USERS_EDIT_ALL],
          action: () => this.openDialog(import('@/components/users/dialogs/EditPassword'))
        },
        {
          title: 'Редагувати зображення',
          type: types.PRIMARY,
          permissions: [ownProfile && perm.PROFILE_EDIT, perm.USERS_EDIT_ALL],
          action: () => this.openDialog(import('@/components/users/dialogs/EditImage'))
        },
        {
          title: 'Редагувати email',
          type: types.PRIMARY,
          permissions: [ownProfile && perm.PROFILE_EDIT, perm.USERS_EDIT_ALL],
          action: () => this.openDialog(import('@/components/users/dialogs/EditEmail'))
        },
        {
          title: 'Редагування ролей',
          type: types.PRIMARY,
          disabled: ownProfile,
          permissions: perm.ROLES_EDIT_ALL,
          action: () => this.openDialog(import('@/components/users/dialogs/EditRoles'))
        },
        {
          title: 'Видалити зображення',
          type: types.WARNING,
          disabled: !this.model.image_id,
          permissions: [ownProfile && perm.PROFILE_EDIT, perm.USERS_EDIT_ALL],
          action: () => this.openDialog(import('@/components/users/dialogs/DeleteImage'))
        },
        {
          title: 'Видалити користувача',
          type: types.DANGER,
          disabled: ownProfile || this.model.id === 1,
          permissions: perm.USERS_DELETE_ALL,
          action: () => this.openDialog(import('@/components/users/dialogs/Delete'))
        }
      ]
    },
    tableData() {
      const props = ['roles', 'email', 'description', 'phone', 'created_at', 'updated_at']
      const result = []

      this.$store.getters['users/columns']
        .forEach((obj) => {
          if (props.includes(obj.prop)) {
            result.push({ ...obj, value: this.model[obj.prop] })
          }
        })

      return result
    }
  },
  methods: {
    fetchData() {
      if (!this.model.id) {
        this.fetchRequest()
      }
    },
    fetchRequest() {
      this.loading = true

      User.fetchOne(this.pageId)
        .catch(() => {
          this.$router.push({ name: sections.users })
        })
        .finally(() => {
          this.loading = false
        })
    },
    openDialog(component) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => component,
        attrs: {
          user: this.model
        },
        events: {
          delete: () => {
            this.$router.push({ name: sections.users })
          }
        }
      })
    }
  }
}
</script>
