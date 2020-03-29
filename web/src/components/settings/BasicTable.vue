<template>
  <div>
    <div class="header">
      <div class="title">
        {{ title }}
      </div>
      <div class="actions">
        <el-button
          :loading="loading"
          :disabled="loading"
          size="small"
          @click="onUpdate"
        >
          <span>Оновити</span>
          <i class="material-icons">refresh</i>
        </el-button>
        <el-button
          v-if="hasPerm(permissionCreate)"
          size="small"
          type="primary"
          @click="openDialog('create')"
        >
          <span>Додати</span>
          <i class="material-icons">add</i>
        </el-button>
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="listCut"
      stripe
    >
      <el-table-column
        v-for="(column, index) in columns"
        :key="index"
        v-bind="column"
      >
        <column-data
          slot-scope="{ row }"
          :column="column"
          :value="row[column.prop]"
        />
      </el-table-column>
      <el-table-column width="200">
        <template slot-scope="scope">
          <el-button
            v-if="hasPerm(permissionEdit, scope.row)"
            type="text"
            size="small"
            @click="openDialog('edit', scope.row)"
          >
            Редагувати
          </el-button>
          <el-button
            v-if="hasPerm(permissionDelete, scope.row)"
            type="text"
            size="small"
            class="danger"
            @click="openDialog('delete', scope.row)"
          >
            Видалити
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      background
      layout="prev, pager, next"
      hide-on-single-page
      :page-size="pageSize"
      :current-page.sync="page"
      :total="list.length"
    />
  </div>
</template>

<script>
import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'

export default {
  components: {
    ElTableColumn: () => import('element-ui/lib/table-column'),
    ElPagination: () => import('element-ui/lib/pagination'),
    ColumnData: () => import('@/components/ColumnData'),
    ElButton: () => import('element-ui/lib/button'),
    ElTable: () => import('element-ui/lib/table')
  },
  props: {
    loading: {
      type: Boolean,
      required: true
    },
    list: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    dialogs: {
      type: Object,
      required: true
    },
    permissionCreate: {
      type: [String, Boolean, Function],
      default: null
    },
    permissionEdit: {
      type: [String, Boolean, Function],
      default: null
    },
    permissionDelete: {
      type: [String, Boolean, Function],
      default: null
    }
  },
  data() {
    return {
      page: 1,
      pageSize: 20
    }
  },
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    title() {
      const menu = this.menu[sections.settings]

      if (!menu) {
        return ''
      }

      const action = this.menu[sections.settings].children[this.$route.name]
      return action ? action.title : ''
    },
    listCut() {
      const currentSize = (this.page - 1) * this.pageSize

      return this.list.slice(currentSize, currentSize + this.pageSize)
    }
  },
  watch: {
    page() {
      window.scrollTo(0, 0)
    }
  },
  methods: {
    hasPerm,
    openDialog(dialogProperty, item = null) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: this.dialogs[dialogProperty],
        attrs: { item }
      })
    },
    onUpdate() {
      this.$emit('update')
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.title {
  font-size: 1.2rem;
  font-weight: bold;
  margin-right: 15px;
}

.actions {
  display: flex;
  button {
    min-width: 120px;
    .material-icons {
      display: none;
    }
  }
}

.el-pagination {
  margin-top: 20px;
  text-align: center;
  background: #fff;
  padding: 20px;
}

@media only screen and (max-width: $tablet) {
  .actions {
    button {
      width: 45px;
      min-width: auto;
      &.is-loading {
        .material-icons {
          display: none;
        }
      }
      span {
        display: none;
      }
      .material-icons {
        display: block;
      }
    }
  }
}

@media only screen and (max-width: $mobileL) {
  .actions {
    flex-direction: column-reverse;
    button {
      margin: 0 0 10px;
      &:first-child {
        margin-bottom: 0;
      }
    }
  }
}
</style>
