<template>
  <basic-edit
    :title="title"
    :loading="loadingFetch"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-form
      ref="form"
      v-loading="loading"
      :model="form"
      status-icon
      class="form--full"
      @submit.native.prevent="fetchRequest"
    >
      <el-form-item
        v-for="(row, index) in rows"
        :key="index"
        :prop="row.attr"
        :label="row.type !== 'bool' ? row.title : null"
      >
        <el-upload
          v-if="row.type === 'img'"
          ref="upload"
          :limit="1"
          :auto-upload="false"
          :accept="row.mimes"
          :name="row.attr"
          action
        >
          <el-button
            slot="trigger"
            size="small"
          >
            Оберіть файл
          </el-button>
          <el-button
            v-if="form[row.attr]"
            type="danger"
            size="small"
            :disabled="!form[row.attr]"
            @click="deleteFile(row.attr)"
          >
            Видалити
          </el-button>
        </el-upload>
        <el-checkbox
          v-else-if="row.type === 'bool'"
          v-model="form[row.attr]"
        >
          {{ row.title }}
        </el-checkbox>
        <el-input
          v-else
          v-model="form[row.attr]"
          :placeholder="row.title"
        />
      </el-form-item>
      <button
        class="hide"
        type="submit"
      />
    </el-form>
  </basic-edit>
</template>

<script>
import SettingsGlobal from '@/classes/SettingsGlobal'
import sections from '@/enum/sections'
import menu from '@/data/menu'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    ElFormItem: () => import('element-ui/lib/form-item'),
    ElCheckbox: () => import('element-ui/lib/checkbox'),
    ElButton: () => import('element-ui/lib/button'),
    ElUpload: () => import('element-ui/lib/upload'),
    ElInput: () => import('element-ui/lib/input'),
    ElForm: () => import('element-ui/lib/form')
  },
  inheritAttrs: false,
  data() {
    return {
      rows: SettingsGlobal.rows,
      loadingFetch: false,
      form: {}
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    },
    title() {
      return menu[sections.settings].children[sections.settingsGlobal].title
    },
    settings() {
      return this.$store.state.settings.global.data
    },
    loading() {
      return this.$store.state.settings.global.init || this.$store.state.settings.global.loading
    }
  },
  watch: {
    settings: {
      handler(data) {
        this.form = { ...data }
      },
      immediate: true
    }
  },
  methods: {
    fetchRequest() {
      const fd = new FormData
      this.loadingFetch = true

      Object.entries(this.form).forEach(([key, val]) => {
        if (typeof val === 'boolean') {
          fd.append(key, +val)
        } else {
          fd.append(key, val || '')
        }
      })

      this.$refs.upload.forEach((component) => {
        fd.delete(component.name)

        if (!component.disabled && component.uploadFiles && component.uploadFiles.length) {
          fd.append(component.name, component.uploadFiles[0].raw)
        } else if (!this.form[component.name]) {
          fd.append(component.name, '')
        }
      })

      SettingsGlobal.fetchStore(fd)
        .then(() => {
          this.$emit('store')
          this.$emit('close')
        })
        .finally(() => {
          this.loadingFetch = false
        })
    },
    deleteFile(attr) {
      // Remove url from data
      this.$set(this.form, attr, '')

      // Clear active upload files before delete
      this.$refs.upload.forEach((component) => {
        if (component.name === attr) {
          component.clearFiles()
        }
      })
    }
  }
}
</script>
