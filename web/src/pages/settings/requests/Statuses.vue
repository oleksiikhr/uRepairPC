<template>
  <basic-table
    :list="list"
    :loading="loading"
    :columns="columns"
    :dialogs="dialogs"
    v-bind="permissions"
    @update="fetchRequest"
  />
</template>

<script>
import { requestStatuses as columns } from '@/data/columns'
import breadcrumbs from '@/mixins/breadcrumbs'
import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import { mapState } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'RequestStatuses',
  breadcrumbs: [
    { title: menu[sections.settings].title, routeName: sections.settings },
    { title: menu[sections.settings].children[sections.requestsStatuses].title }
  ],
  components: {
    BasicTable: () => import('@/components/settings/BasicTable')
  },
  mixins: [
    breadcrumbs
  ],
  data() {
    return {
      perm,
      columns,
      dialogs: {
        create: () => import('@/components/requests/statuses/dialogs/Create'),
        edit: () => import('@/components/requests/statuses/dialogs/Edit'),
        delete: () => import('@/components/requests/statuses/dialogs/Delete')
      }
    }
  },
  computed: {
    ...mapState({
      loading: state => state.requestStatuses.loading,
      list: state => state.requestStatuses.list
    }),
    profile() {
      return this.$store.state.profile.user
    },
    permissions() {
      return {
        'permission-create': perm.REQUESTS_CONFIG_CREATE,
        'permission-edit': (obj) => hasPerm(perm.REQUESTS_CONFIG_EDIT_ALL)
          || (hasPerm(perm.REQUESTS_CONFIG_EDIT_OWN) && obj.user_id === this.profile.id),
        'permission-delete': (obj) => hasPerm(perm.REQUESTS_CONFIG_DELETE_ALL)
          || (hasPerm(perm.REQUESTS_CONFIG_DELETE_OWN) && obj.user_id === this.profile.id)
      }
    }
  },
  mounted() {
    if (!this.list.length) {
      this.fetchRequest()
    }
  },
  methods: {
    fetchRequest() {
      this.$store.dispatch('requestStatuses/fetchList')
    }
  }
}
</script>
