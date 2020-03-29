<template>
  <template-one
    :buttons="buttons"
    :table-data="tableData"
    :loading="loading"
  >
    <template slot="header">
      <div class="title">
        {{ model.name }}
      </div>
      <div
        v-if="model.color"
        class="color"
        :style="{ 'background-color': model.color }"
      />
    </template>
    <div class="page--width">
      <div class="divider">
        <span>Доступи</span>
      </div>
      <list-checkboxes
        v-loading="loading"
        :value="model.permissions_active"
        :permissions-list="model.permissions_list"
        class="list-checkboxes"
        only-view
      />
    </div>
  </template-one>
</template>

<script>
import sections from '@/enum/sections'
import onePage from '@/mixins/onePage'
import * as perm from '@/enum/perm'
import Role from '@/classes/Role'
import types from '@/enum/types'

export default {
  components: {
    ListCheckboxes: () => import('@/components/permissions/ListCheckboxes'),
    TemplateOne: () => import('@/components/template/One')
  },
  mixins: [
    onePage(sections.roles)
  ],
  data() {
    return {
      loading: false
    }
  },
  computed: {
    buttons() {
      return [
        {
          title: 'Оновити',
          type: types.SUCCESS,
          action: () => this.fetchRequest('loading'),
          disabled: this.loading
        },
        {
          title: 'Редагувати дані',
          type: types.PRIMARY,
          permissions: perm.ROLES_EDIT_ALL,
          action: () => this.openDialog(import('@/components/roles/dialogs/Edit'))
        },
        {
          title: 'Редагувати доступи',
          type: types.PRIMARY,
          disabled: this.model.id === 1,
          permissions: perm.ROLES_EDIT_ALL,
          action: () => this.openDialog(import('@/components/roles/dialogs/EditPermissions'))
        },
        {
          title: 'Видалити роль',
          type: types.DANGER,
          disabled: this.model.id === 1,
          permissions: perm.ROLES_EDIT_ALL,
          action: () => this.openDialog(import('@/components/roles/dialogs/Delete'))
        }
      ]
    },
    tableData() {
      const props = ['color', 'default', 'created_at', 'updated_at']
      const result = []

      this.$store.getters['roles/columns']
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

      Role.fetchOne(this.pageId)
        .catch(() => {
          this.$router.push({ name: sections.roles })
        })
        .finally(() => {
          this.loading = false
        })
    },
    openDialog(component, attrs = {}) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => component,
        attrs: {
          role: this.model,
          ...attrs
        },
        events: {
          delete: () => {
            this.$router.push({ name: sections.roles })
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.title {
	font-size: 1.5rem;
	font-weight: bold;
  overflow-wrap: break-word;
}

.color {
	width: 100px;
	height: 5px;
	margin: 15px auto 0;
	border-radius: 5px;
}

.list-checkboxes {
	padding: 30px;
	background: #fff;
	border: 1px solid $defaultBorder;
}
</style>
