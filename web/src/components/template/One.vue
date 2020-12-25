<template>
  <transition
    name="anim"
    :duration="250"
    appear
  >
    <div class="template template-one">
      <top-buttons
        v-if="buttons"
        :buttons="buttons"
        :disabled="loading"
      />
      <div class="template__wrap">
        <div
          v-if="!!$slots.header"
          class="template-header page--width"
        >
          <slot name="header" />
        </div>
        <div
          v-if="tableData"
          v-loading="loading"
          class="template-table page--width"
        >
          <el-table
            :data="tableData"
            style="width: 100%"
          >
            <el-table-column
              prop="label"
              label="Назва"
              width="200"
            />
            <el-table-column
              prop="value"
              label="Значення"
              min-width="200"
            >
              <template slot-scope="{ row }">
                <column-data
                  :column="row"
                  :value="row.value"
                >
                  <slot
                    name="table"
                    :row="row"
                  >
                    <pre class="default">{{ row.value }}</pre>
                  </slot>
                </column-data>
              </template>
            </el-table-column>
          </el-table>
        </div>
        <slot />
      </div>
    </div>
  </transition>
</template>

<script>
import { hasPerm } from '@/scripts/utils'

export default {
  components: {
    ElTableColumn: () => import('element-ui/lib/table-column'),
    TopButtons: () => import('@/components/TopButtons'),
    ColumnData: () => import('@/components/ColumnData'),
    ElTable: () => import('element-ui/lib/table')
  },
  props: {
    buttons: {
      type: Array,
      default: null
    },
    loading: {
      type: Boolean,
      default: false
    },
    tableData: {
      type: Array,
      default: null
    }
  },
  computed: {
    columns() {
      return this.tableData
        .filter(obj => hasPerm(obj.permissions))
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

pre {
  white-space: pre-wrap;
  word-break: break-word;
}

.template__wrap {
  margin: 0 20px 50px;
}

.template-header,
.template-table {
  margin-top: 20px;
  padding: 20px;
}

.template-header {
  padding: 30px;
  text-align: center;
}

.template-table {
  background: #fff;
  border: 1px solid $defaultBorder;
}

// <animation>
$transition: .25s;

.anim-enter-active {
  .top-buttons {
    transition: $transition transform;
    transform: translateY(-10px);
  }
  .template-header {
    transition: $transition transform;
    transform: scale(.9);
  }
}

.anim-enter-to {
  .top-buttons {
    transform: translateY(0);
  }
  .template-header {
    transform: scale(1);
  }
}

.anim-leave-active,
.anim-leave-to {
  display: none;
}
</style>
