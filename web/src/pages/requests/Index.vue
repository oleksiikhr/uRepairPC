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
        <template slot-scope="{ column, row, data }">
          <template v-if="column.prop === 'priority_name'">
            <table-cell-color
              :value="data"
              :color="row.priority_color"
            />
          </template>
          <template v-else-if="column.prop === 'status_name'">
            <table-cell-color
              :value="data"
              :color="row.status_color"
            />
          </template>
        </template>
      </table-component>
    </template>
    <filter-core slot="right-column">
      <filter-table-buttons
        ref="buttons"
        :section="sections.requests"
        @update="() => fetchList(+list.current_page || 1)"
      />
      <filter-action
        :section="sectionName"
      />
      <filter-basic title="Фільтри">
        <request-priority-select
          v-model="filters.priority_id"
          placeholder="Пріорітет"
          size="small"
          class="mb-10"
          clearable
        />
        <request-status-select
          v-model="filters.status_id"
          placeholder="Статус"
          size="small"
          class="mb-10"
          clearable
        />
        <request-type-select
          v-model="filters.type_id"
          placeholder="Тип"
          size="small"
          clearable
        />
      </filter-basic>
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
import Request from '@/classes/Request'
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'Requests',
  breadcrumbs: [
    { title: menu[sections.requests].title }
  ],
  components: {
    RequestPrioritySelect: () => import('@/components/requests/priorities/Select'),
    RequestStatusSelect: () => import('@/components/requests/statuses/Select'),
    FilterTableButtons: () => import('@/components/filters/TableButtons'),
    RequestTypeSelect: () => import('@/components/requests/types/Select'),
    FilterPagination: () => import('@/components/filters/Pagination'),
    TableCellColor: () => import('@/components/TableCellColor'),
    FilterColumns: () => import('@/components/filters/Columns'),
    FilterAction: () => import('@/components/filters/Action'),
    FilterSearch: () => import('@/components/filters/Search'),
    TemplateList: () => import('@/components/template/List'),
    FilterBasic: () => import('@/components/filters/Basic'),
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
      sectionName: sections.requests,
      filters: {
        priority_id: null,
        status_id: null,
        type_id: null
      },
      columns: [],
      fixed: null,
      search: '',
      sort: {}
    }
  },
  computed: {
    ...mapGetters({
      'requestColumns': 'requests/columns'
    }),
    list() {
      return this.$store.state.requests.list
    },
    requests() {
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
      return this.$store.state.requests.loading
    },
    activeColumnProps() {
      return this.filterColumns
        .filter(c => !c.disableSearch)
        .map(c => c.prop)
    }
  },
  watch: {
    requestColumns: {
      handler(arr) {
        this.columns = arr
          .filter(obj => !obj.hideList)
      },
      immediate: true
    },
    filters: {
      handler() {
        this.fetchList()
      },
      deep: true
    }
  },
  mounted() {
    this.fetchList()
  },
  methods: {
    fetchList(page = 1) {
      const data = {
        page,
        sortColumn: this.sort.column,
        sortOrder: this.sort.order,
        columns: this.activeColumnProps,
        search: this.search || null
      }

      // Add filters if selected
      Object.entries(this.filters).forEach(([key, obj]) => {
        if (obj) {
          data[key] = obj
        }
      })

      this.$store.dispatch('requests/fetchList', data)
    },
    onChangeColumn() {
      StorageData.columnRequests = this.filterColumns.map(i => i.prop)
    },
    onRowClick(request) {
      Request.sidebar().add(request)
      this.$router.push({ name: `${sections.requests}-id`, params: { id: request.id } })
    },
    onSortChange({ prop: column, order }) {
      this.sort = { column, order }
      this.fetchList()
    }
  }
}
</script>
