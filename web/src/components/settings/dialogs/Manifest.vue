<template>
  <basic-edit
    :title="title"
    :loading="loadingFetch"
    v-bind="$attrs"
    v-on="listeners"
  >
    <generate-form
      ref="form"
      v-loading="loading || init"
      :form="form"
      @submit="fetchRequest"
    >
      <el-form-item
        prop="upload_icons.value"
        label="Іконки"
      >
        <el-alert
          title="Інформація"
          type="warning"
          class="mb-10"
        >
          <ul>
            <li>Для встановлення сайту на робочий стол, необхідний хоча б один файл на 192x192 і формату .PNG</li>
            <li>Для оптимізації зображения, завантажте файл, який більше 512px</li>
          </ul>
        </el-alert>
        <el-upload
          ref="upload"
          :auto-upload="false"
          accept="image/jpeg, image/jpg, image/png"
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
            файл повинен мати не більше 1мб.
          </div>
        </el-upload>
      </el-form-item>
      <div
        v-for="(item, index) in settings.icons"
        :key="index"
        class="mb-20"
      >
        <el-button
          type="danger"
          size="mini"
          :disabled="form.remove_icons.value.includes(item.src)"
          @click="onClickRemoveIcon(item.src)"
        >
          Видалити - {{ item.type }}, {{ item.sizes }}
        </el-button>
      </div>
    </generate-form>
  </basic-edit>
</template>

<script>
import SettingsManifest from '@/classes/SettingsManifest'
import { required } from '@/data/rules'
import sections from '@/enum/sections'
import { mapState } from 'vuex'
import menu from '@/data/menu'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm'),
    ElFormItem: () => import('element-ui/lib/form-item'),
    ElUpload: () => import('element-ui/lib/upload'),
    ElButton: () => import('element-ui/lib/button'),
    ElAlert: () => import('element-ui/lib/alert')
  },
  inheritAttrs: false,
  data() {
    return {
      required,
      loadingFetch: false,
      form: {
        name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Назва',
          rules: required
        },
        short_name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Коротке ім\'я',
          rules: required
        },
        orientation: {
          component: () => import('@/components/SelectSimple'),
          value: '',
          label: 'Орієнтаця',
          rules: required,
          attrs: {
            items: [
              { label: 'Any', value: 'any' },
              { label: 'Landscape', value: 'landscape' },
              { label: 'Portrait', value: 'portrait' }
            ]
          }
        },
        display: {
          component: () => import('@/components/SelectSimple'),
          value: '',
          label: 'Режим відображення',
          rules: required,
          attrs: {
            items: [
              { label: 'Fullscreen', value: 'fullscreen' },
              { label: 'Standalone', value: 'standalone' },
              { label: 'Minimal-ui', value: 'minimal-ui' },
              { label: 'Browser', value: 'browser' }
            ]
          }
        },
        background_color: {
          component: () => import('element-ui/lib/color-picker'),
          value: '',
          label: 'Колір фону',
          rules: required
        },
        theme_color: {
          component: () => import('element-ui/lib/color-picker'),
          value: '',
          label: 'Колір теми',
          rules: required
        },
        upload_icons: {
          value: [],
          hide: true
        },
        remove_icons: {
          value: [],
          hide: true
        }
      }
    }
  },
  computed: {
    ...mapState({
      settings: state => state.settings.manifest.data,
      loading: state => state.settings.manifest.loading,
      init: state => state.settings.manifest.init
    }),
    listeners() {
      return {
        ...this.$listeners,
        submit: () => {
          this.$refs.form.onSubmit()
        }
      }
    },
    title() {
      return menu[sections.settings].children[sections.settingsManifest].title
    }
  },
  watch: {
    settings: {
      handler(data) {
        const settingsData = Object.assign({}, data, { icons: undefined })

        Object.entries(settingsData).forEach(([key, item]) => {
          if (this.form[key]) {
            this.$set(this.form[key], 'value', item)
          }
        })
      },
      immediate: true
    }
  },
  mounted() {
    if (this.init) {
      this.$store.dispatch('settings/fetchManifest')
    }
  },
  methods: {
    fetchRequest(form) {
      const fd = new FormData
      this.loadingFetch = true

      Object.entries(form).forEach(([key, data]) => {
        fd.append(key, data)
      })

      // Add src icons for delete
      fd.delete('remove_icons')
      form.remove_icons.forEach(src => fd.append('remove_icons[]', src))

      // Add files for save
      fd.delete('upload_icons')
      this.$refs.upload.uploadFiles.forEach(file => fd.append('upload_icons[]', file.raw))

      SettingsManifest.fetchStore(fd)
        .then(() => {
          this.$emit('store')
          this.$emit('close')
        })
        .finally(() => {
          this.loadingFetch = false
        })
    },
    onClickRemoveIcon(iconSrc) {
      this.form.remove_icons.value.push(iconSrc)
    }
  }
}
</script>
