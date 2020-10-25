<template>
  <el-dialog
    class="dialog--default"
    :title="`UUID: ${uuid}`"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <div class="content">
      <div
        v-for="(val, key) in job"
        :key="key"
        class="mb-5"
      >
        <strong class="content--key">{{ key }}</strong>:
        <template v-if="key === 'payload'">
          <div
            v-for="(subVal, subKey) in payload"
            :key="`sub-${subKey}`"
            class="ml-15 mt-5"
          >
            <strong class="content--key">{{ subKey }}</strong>: {{ subVal }}
          </div>
        </template>
        <pre v-else>{{ val }}</pre>
      </div>
    </div>
    <span slot="footer">
      <el-button
        v-if="hasPerm(perm.JOBS_DELETE_QUEUE)"
        :loading="loading"
        :disabled="loading"
        type="danger"
        @click="onDelete"
      >
        Видалити
      </el-button>
      <el-button @click="onClose">Закрити</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { hasPerm } from '@/scripts/utils'
import * as perm from '@/enum/perm'
import Job from '@/classes/Job'

export default {
  components: {
    ElDialog: () => import('element-ui/lib/dialog'),
    ElButton: () => import('element-ui/lib/button')
  },
  inheritAttrs: false,
  props: {
    job: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      perm,
      loading: false,
    }
  },
  computed: {
    payload() {
      return JSON.parse(this.job.payload || {})
    },
    uuid() {
      return this.payload.uuid
    }
  },
  methods: {
    hasPerm,
    fetchDelete() {
      this.loading = true

      Job.fetchDelete(this.job.id)
        .then(() => {
          this.$emit('delete')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    },
    onDelete() {
      if (confirm('Ви дійсно хочете видалити ці дані?')) {
        this.fetchDelete()
      }
    },
    onClose() {
      this.$emit('close')
    }
  }
}
</script>

<style lang="scss" scoped>
/deep/ .el-dialog {
  max-width: 800px;
}

pre {
  display: inline-block;
  margin: 0;
}

.content--key {
  font-size: 1rem;
}
</style>
