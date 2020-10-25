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
      />
    </template>
    <filter-core slot="right-column">
      <filter-table-buttons
        ref="buttons"
        :section="sections.roles"
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
import Role from '@/classes/Role'
import { mapGetters } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'Roles',
  breadcrumbs: [
    { title: menu[sections.roles].title }
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
    TableComponent: () => import('@/components/Table')
  },
  mixins: [
    scrollTableMixin, breadcrumbs
  ],
  data() {
    return {
      sections,
      sectionName: sections.roles,
      columns: [],
      fixed: null,
      search: '',
      sort: {}
    }
  },
  computed: {
    ...mapGetters({
      'roleColumns': 'roles/columns'
    }),
    list() {
      return this.$store.state.roles.list
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
      return this.$store.state.roles.loading
    },
    activeColumnProps() {
      return this.filterColumns
        .filter(c => !c.disableSearch)
        .map(c => c.prop)
    }
  },
  watch: {
    roleColumns: {
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
      this.$store.dispatch('roles/fetchList', {
        page,
        sortColumn: this.sort.column,
        sortOrder: this.sort.order,
        columns: this.activeColumnProps,
        search: this.search || null
      })
    },
    onChangeColumn() {
      StorageData.columnRoles = this.filterColumns.map(i => i.prop)
    },
    onRowClick(role) {
      Role.sidebar().add(role)
      this.$router.push({ name: `${sections.roles}-id`, params: { id: role.id } })
    },
    onSortChange({ prop: column, order }) {
      this.sort = { column, order }
      this.fetchList()
    }
  }
}
</script>
