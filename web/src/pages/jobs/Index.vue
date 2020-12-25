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
        :section="sections.jobs"
        @update="() => fetchList(+list.current_page || 1)"
      >
        <el-button
          v-if="hasPerm(perm.JOBS_DELETE_QUEUE)"
          :loading="loadingDestroy"
          :disabled="loadingDestroy"
          size="small"
          icon="el-icon-delete"
          type="danger"
          circle
          @click="onDeleteAllJobs"
        />
        <el-button
          size="small"
          icon="el-icon-folder-remove"
          type="info"
          circle
          @click="routeFailedJobsPage"
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
import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import { mapGetters } from 'vuex'
import Job from '@/classes/Job'
import menu from '@/data/menu'

export default {
  name: 'Jobs',
  breadcrumbs: [
    { title: menu[sections.jobs].title }
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
      loadingDestroy: false,
      columns: [],
      fixed: null,
      search: '',
      sort: {}
    }
  },
  computed: {
    ...mapGetters({
      jobColumns: 'jobs/columns'
    }),
    list() {
      return this.$store.state.jobs.list
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
      return this.$store.state.jobs.loading
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
      this.$store.dispatch('jobs/fetchList', {
        page,
        sortColumn: this.sort.column,
        sortOrder: this.sort.order,
        columns: this.activeColumnProps,
        search: this.search || null
      })
    },
    fetchDelete() {
      this.loadingDestroy = true

      Job.fetchDeleteAll()
        .then(() => {
          this.fetchList()
        })
        .finally(() => {
          this.loadingDestroy = false
        })
    },
    onChangeColumn() {
      StorageData.columnJobs = this.filterColumns.map(i => i.prop)
    },
    onRowClick(job) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => import('@/components/jobs/dialogs/JobView'),
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
    onDeleteAllJobs() {
      if (confirm('Ви дійсно хочете видалити всі дані?')) {
        this.fetchDelete()
      }
    },
    onSortChange({ prop: column, order }) {
      this.sort = { column, order }
      this.fetchList()
    },
    routeFailedJobsPage() {
      this.$router.push({ name: 'failed-jobs' })
    }
  }
}
</script>
