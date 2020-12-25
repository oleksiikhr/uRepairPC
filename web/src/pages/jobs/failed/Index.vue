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
        :section="sections.failedJobs"
        @update="() => fetchList(+list.current_page || 1)"
      >
        <el-button
          v-if="hasPerm(perm.JOBS_RETRY)"
          :loading="loadingRefresh"
          :disabled="loadingRefresh"
          size="small"
          icon="el-icon-refresh-right"
          type="primary"
          circle
          @click="onRefreshFailedJobs"
        />
        <el-button
          v-if="hasPerm(perm.JOBS_DELETE_FAILED_QUEUE)"
          :loading="loadingDestroy"
          :disabled="loadingDestroy"
          size="small"
          icon="el-icon-delete"
          type="danger"
          circle
          @click="onDeleteFailedJobs"
        />
        <el-button
          size="small"
          icon="el-icon-folder-opened"
          type="info"
          circle
          @click="routeJobsPage"
        />
      </filter-table-buttons>
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
import FailedJob from '@/classes/FailedJob'
import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import { mapGetters } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'FailedJobs',
  breadcrumbs: [
    { title: menu[sections.jobs].children[sections.failedJobs].title }
  ],
  components: {
    FilterTableButtons: () => import('@/components/filters/TableButtons'),
    FilterPagination: () => import('@/components/filters/Pagination'),
    FilterColumns: () => import('@/components/filters/Columns'),
    FilterSearch: () => import('@/components/filters/Search'),
    TemplateList: () => import('@/components/template/List'),
    FilterFixed: () => import('@/components/filters/Fixed'),
    FilterCore: () => import('@/components/filters/Core'),
    TableComponent: () => import('@/components/Table'),
    ElButton: () => import('element-ui/lib/button')
  },
  mixins: [
    scrollTableMixin, breadcrumbs
  ],
  data() {
    return {
      sections,
      perm,
      loadingRefresh: false,
      loadingDestroy: false,
      columns: [],
      fixed: null,
      search: '',
      sort: {}
    }
  },
  computed: {
    ...mapGetters({
      jobColumns: 'failedJobs/columns'
    }),
    list() {
      return this.$store.state.failedJobs.list
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
      return this.$store.state.failedJobs.loading
    },
    activeColumnProps() {
      return this.filterColumns
        .filter(c => !c.disableSearch)
        .map(c => c.prop)
    }
  },
  watch: {
    jobColumns: {
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
    hasPerm,
    fetchList(page = 1) {
      this.$store.dispatch('failedJobs/fetchList', {
        page,
        sortColumn: this.sort.column,
        sortOrder: this.sort.order,
        columns: this.activeColumnProps,
        search: this.search || null
      })
    },
    fetchRefresh() {
      this.loadingRefresh = true

      FailedJob.fetchRefresh()
        .then(() => {
          this.fetchList()
        })
        .finally(() => {
          this.loadingRefresh = false
        })
    },
    fetchDelete() {
      this.loadingDestroy = true

      FailedJob.fetchDeleteAll()
        .then(() => {
          this.fetchList()
        })
        .finally(() => {
          this.loadingDestroy = false
        })
    },
    onChangeColumn() {
      StorageData.columnFailedJobs = this.filterColumns.map(i => i.prop)
    },
    onRowClick(job) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => import('@/components/jobs/dialogs/FailedJobView'),
        attrs: {
          job
        },
        events: {
          delete: () => {
            this.fetchList(this.list.current_page || 1)
          }
        }
      })
    },
    onSortChange({ prop: column, order }) {
      this.sort = { column, order }
      this.fetchList()
    },
    onRefreshFailedJobs() {
      if (confirm('Ви дійсно хочете обробити невдалі задачі заново?')) {
        this.fetchRefresh()
      }
    },
    onDeleteFailedJobs() {
      if (confirm('Ви дійсно хочете видалити всі дані?')) {
        this.fetchDelete()
      }
    },
    routeJobsPage() {
      this.$router.push({ name: 'jobs' })
    }
  }
}
</script>
