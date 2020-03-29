<template>
  <basic-edit
    :title="equipmentObj.title"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-upload
      ref="upload"
      :auto-upload="false"
      drag
      multiple
      action
    >
      <i class="el-icon-upload" />
      <div class="el-upload__text">
        Перетягніть файл сюди або <em>натисніть, щоб завантажити</em>
      </div>
      <div
        slot="tip"
        class="el-upload__tip"
      >
        файл повинен мати не більше 20мб.
      </div>
    </el-upload>
  </basic-edit>
</template>

<script>
import { isArray, isObject } from '@/scripts/helpers'
import EquipmentFile from '@/classes/EquipmentFile'
import Equipment from '@/classes/Equipment'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    ElUpload: () => import('element-ui/lib/upload')
  },
  inheritAttrs: false,
  props: {
    equipment: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.onSubmit
      }
    },
    equipmentObj() {
      return new Equipment(this.equipment)
    }
  },
  methods: {
    onSubmit() {
      const files = this.$refs.upload.uploadFiles
      const fd = new FormData
      this.loading = true

      files.forEach((file) => {
        fd.append('files[]', file.raw, file.name)
      })

      EquipmentFile.fetchStore(this.equipment.id, fd)
        .then(({ data }) => {
          this.$emit('files-upload', data.equipment_files)
          this.$emit('close')
        })
        .catch(({ response: { data } }) => {
          const files = this.$refs.upload.uploadFiles

          // Delete success uploaded files from list.
          if (isObject(data.errors) && isArray(files)) {
            const filesErrorsNames = Object.keys(data.errors)

            files.forEach((file, key) => {
              if (!filesErrorsNames.includes(file.name)) {
                this.$delete(files, key)
              }
            })
          }
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
/deep/ .el-upload-dragger,
/deep/ .el-upload {
  width: 100%;
}
</style>
