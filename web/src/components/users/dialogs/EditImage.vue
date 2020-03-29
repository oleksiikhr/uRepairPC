<template>
  <basic-edit
    title="Редагування зображення"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-upload
      ref="upload"
      drag
      :http-request="onHttpRequest"
      :on-change="onChange"
      :auto-upload="false"
      list-type="picture"
      :limit="1"
      accept="image/jpeg, image/jpg, image/png"
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
        jpeg/jpg/png зображення повинне мати не більше 2мб.
      </div>
    </el-upload>
  </basic-edit>
</template>

<script>
import User from '@/classes/User'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    ElUpload: () => import('element-ui/lib/upload')
  },
  inheritAttrs: false,
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      file: null,
      loading: false
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.save
      }
    }
  },
  methods: {
    onHttpRequest() {
      this.loading = true

      const fd = new FormData
      fd.append('image', this.file.raw)

      User.fetchEditImage(this.user.id, fd)
        .then(({ data }) => {
          this.$store.commit('users/UPDATE_ITEM', data.user)
          this.$emit('edit-image')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    },
    save() {
      this.$refs.upload.submit()
    },
    onChange(file) {
      this.file = file
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
