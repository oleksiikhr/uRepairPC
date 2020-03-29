<template>
  <template-list>
    <template slot="left-column">
      <table-component
        slot="left-column"
        :columns="filterColumns"
        :list="list"
        :loading="loading"
        @fetch="fetchList"
        @row-click="onRowClick"
        @sort-change="onSortChange"
      >
        <template slot-scope="{ column, data }">
          <template v-if="column.prop === 'roles'">
            <role-tag
              v-for="(role, index) in data"
              :key="index"
              :role="role"
            />
          </template>
        </template>
      </table-component>
    </template>
    <filter-core slot="right-column">
      <filter-table-buttons
        ref="buttons"
        :section="sections.users"
        @update="() => fetchList(+list.current_page || 1)"
      />
      <filter-action
        :section="sectionName"
      />
      <filter-search
        v-model="search"
        @submit="fetchList"
      />
      <filter-pagination
        :pagination="list"
      />
      <filter-columns
        :columns="columns"
        @change="onChangeColumn"
      />
      <filter-fixed
        v-model="fixed"
        :columns="columns"
      />
    </filter-core>
  </template-list>
</template>

<script>
import scrollTableMixin from '@/mixins/scrollTable'
import StorageData from '@/classes/StorageData'
import breadcrumbs from '@/mixins/breadcrumbs'
import sections from '@/enum/sections'
import User from '@/classes/User'
import { mapGetters } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'Users',
  breadcrumbs: [
    { title: menu[sections.users].title }
  ],
  components: {
    FilterTableButtons: () => import('@/components/filters/TableButtons'),
    FilterPagination: () => import('@/components/filters/Pagination'),
    FilterColumns: () => import('@/components/filters/Columns'),
    FilterAction: () => import('@/components/filters/Action'),
    FilterSearch: () => import('@/components/filters/Search'),
    TemplateList: () => import('@/components/template/List'),
    FilterFixed: () => import('@/components/filters/Fixed'),
    FilterCore: () => import('@/components/filters/Core'),
    TableComponent: () => import('@/components/Table'),
    RoleTag: () => import('@/components/roles/Tag')
  },
  mixins: [
    scrollTableMixin, breadcrumbs
  ],
  data() {
    return {
      sections,
      sectionName: sections.users,
      columns: [],
      fixed: null,
      search: '',
      sort: {}
    }
  },
  computed: {
    ...mapGetters({
      'userColumns': 'users/columns'
    }),
    list() {
      return this.$store.state.users.list
    },
    users() {
      return this.list.data || []
    },
    filterColumns() {
      const columns = []

      for (const column of this.columns) {
        if (column.model) {
          columns.push({ ...column, fixed: this.fixed === column.prop })
        }
      }

      return columns
    },
    loading() {
      return this.$store.state.users.loading
    },
    activeColumnProps() {
      return this.filterColumns
        .filter(c => !c.disableSearch)
        .map(c => c.prop)
    }
  },
  watch: {
    userColumns: {
      handler(arr) {
        this.columns = arr
          .filter(obj => !obj.hideList)
      },
      immediate: true
    }
  },
  mounted() {
    this.fetchList()
  },
  methods: {
    fetchList(page = 1) {
      this.$store.dispatch('users/fetchList', {
        page,
        sortColumn: this.sort.column,
        sortOrder: this.sort.order,
        columns: this.activeColumnProps,
        search: this.search || null
      })
    },
    onChangeColumn() {
      StorageData.columnUsers = this.filterColumns.map(i => i.prop)
    },
    onRowClick(user) {
      User.sidebar().add(user)
      this.$router.push({ name: `${sections.users}-id`, params: { id: user.id } })
    },
    onSortChange({ prop: column, order }) {
      this.sort = { column, order }
      this.fetchList()
    }
  }
}
</script>
