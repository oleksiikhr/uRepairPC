<template>
  <div>
    <el-table
      ref="table"
      v-loading="loading"
      :data="list.data || []"
      :row-class-name="tableRowClassName"
      v-bind="$attrs"
      v-on="$listeners"
    >
      <el-table-column
        v-for="(column, index) in columns"
        :key="index"
        v-bind="column"
      >
        <template slot-scope="{ row }">
          <column-data
            :column="column"
            :value="row[column.prop]"
          >
            <slot
              :column="column"
              :row="row"
              :data="row[column.prop]"
            >
              {{ isEmpty(row[column.prop]) ? null : row[column.prop] }}
            </slot>
          </column-data>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      background
      layout="prev, pager, next"
      hide-on-single-page
      :current-page="list.current_page"
      :page-count="list.last_page"
      @current-change="onCurrentChange"
    />
  </div>
</template>

<script>
import { isEmpty } from '@/scripts/helpers'

export default {
  components: {
    ElTableColumn: () => import('element-ui/lib/table-column'),
    ElPagination: () => import('element-ui/lib/pagination'),
    ColumnData: () => import('@/components/ColumnData'),
    ElTable: () => import('element-ui/lib/table')
  },
  props: {
    list: {
      type: Object,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      wrapperEl: null
    }
  },
  watch: {
    list() {
      window.scrollTo(0, 0)
    }
  },
  methods: {
    isEmpty,
    onCurrentChange(page) {
      this.$emit('fetch', page)
    },
    tableRowClassName({ row }) {
      return row._is_new ? 'is--new' : '' // vuex APPEND_DATA
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

/deep/ .el-table__row {
  cursor: pointer;
  &.is--new {
    background: #fffcdd;
  }
}

/deep/ th .cell {
  white-space: nowrap;
}

.el-pagination {
  text-align: center;
  padding: 20px;
  background: #fff;
  border-bottom: 1px solid $defaultBorder;
}
</style>
