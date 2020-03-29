<template>
  <template-one
    :buttons="buttons"
    :table-data="tableData"
    :loading="loading"
  >
    <div
      slot="header"
      class="header__wrap"
    >
      <div class="header-item">
        <div class="header-item__title">
          Серійний номер
        </div>
        <div
          :class="['header-item__value', {
            'has_value': !!model.serial_number
          }]"
          @click="copy($event, model.serial_number)"
        >
          <span>{{ model.serial_number }}</span>
        </div>
      </div>
      <div class="header-item">
        <div class="header-item__title">
          Інвертарний номер
        </div>
        <div
          :class="['header-item__value', {
            'has_value': !!model.inventory_number
          }]"
          @click="copy($event, model.inventory_number)"
        >
          <span>{{ model.inventory_number }}</span>
        </div>
      </div>
    </div>
    <div
      v-if="model.files && (model.files.length || loadingFiles)"
      class="page--width"
    >
      <div class="divider">
        <span>Файли</span>
      </div>
      <div>
        <files-list
          :files="model.files"
          :loading="loadingFiles"
          :url-download="(file) => `equipments/${model.id}/files/${file.id}`"
          v-bind="filesPermissions"
          @add="onAdd"
          @edit="onEdit"
          @delete="onDelete"
          @refresh="fetchRequestFiles"
        />
      </div>
    </div>
  </template-one>
</template>

<script>
import EquipmentFile from '@/classes/EquipmentFile'
import Equipment from '@/classes/Equipment'
import { hasPerm } from '@/scripts/utils'
import { copyNode } from '@/scripts/dom'
import sections from '@/enum/sections'
import onePage from '@/mixins/onePage'
import * as perm from '@/enum/perm'
import types from '@/enum/types'

export default {
  components: {
    TemplateOne: () => import('@/components/template/One'),
    FilesList: () => import('@/components/files/List')
  },
  mixins: [
    onePage(sections.equipments)
  ],
  data() {
    return {
      perm,
      loading: false,
      loadingFiles: false
    }
  },
  computed: {
    buttons() {
      return [
        {
          title: 'Оновити',
          type: types.SUCCESS,
          disabled: this.loading,
          action: () => {
            this.fetchRequest()
            this.fetchRequestFiles()
          }
        },
        {
          title: 'Редагувати',
          type: types.PRIMARY,
          permissions: [
            perm.EQUIPMENTS_EDIT_ALL,
            this.model.user_id === this.profile.id && perm.EQUIPMENTS_EDIT_OWN
          ],
          action: () => this.openDialog(import('@/components/equipments/dialogs/Edit'))
        },
        {
          title: 'Завантажити файл',
          type: types.PRIMARY,
          permissions: perm.EQUIPMENTS_FILES_CREATE,
          action: () => this.openDialog(import('@/components/equipments/dialogs/FilesUpload'))
        },
        {
          title: 'Видалили',
          type: types.DANGER,
          permissions: [
            perm.EQUIPMENTS_DELETE_ALL,
            this.model.user_id === this.profile.id && perm.EQUIPMENTS_DELETE_OWN
          ],
          action: () => this.openDialog(import('@/components/equipments/dialogs/Delete'))
        }
      ]
    },
    tableData() {
      const props = ['type_name', 'manufacturer_name', 'model_name', 'description', 'created_at', 'updated_at']
      const result = []

      this.$store.getters['equipments/columns']
        .forEach((obj) => {
          if (props.includes(obj.prop)) {
            result.push({ ...obj, value: this.model[obj.prop] })
          }
        })

      return result
    },
    profile() {
      return this.$store.state.profile.user
    },
    filesPermissions() {
      return {
        'permission-create': perm.EQUIPMENTS_FILES_CREATE,
        'permission-download': (file) => hasPerm(perm.EQUIPMENTS_FILES_DOWNLOAD_ALL) ||
          (hasPerm(perm.EQUIPMENTS_FILES_DOWNLOAD_OWN) && file.user_id === this.profile.id),
        'permission-edit': (file) => hasPerm(perm.EQUIPMENTS_FILES_EDIT_ALL) ||
          (hasPerm(perm.EQUIPMENTS_EDIT_OWN) && file.user_id === this.profile.id),
        'permission-delete': (file) => hasPerm(perm.EQUIPMENTS_FILES_DELETE_ALL) ||
          (hasPerm(perm.EQUIPMENTS_DELETE_OWN) && file.user_id === this.profile.id)
      }
    },
    canSeeFiles() {
      return hasPerm([perm.EQUIPMENTS_FILES_VIEW_ALL, perm.EQUIPMENTS_FILES_VIEW_OWN])
    }
  },
  methods: {
    fetchData() {
      if (!this.model.id) {
        this.fetchRequest()
      }
      if (!this.model.files) {
        this.fetchRequestFiles()
      }
    },
    fetchRequest() {
      this.loading = true

      Equipment.fetchOne(this.pageId)
        .catch(() => {
          this.$router.push({ name: sections.equipments })
        })
        .finally(() => {
          this.loading = false
        })
    },
    fetchRequestFiles() {
      if (!this.canSeeFiles) {
        return
      }

      this.loadingFiles = true
      this.updateFiles([])

      EquipmentFile.fetchAll(this.pageId)
        .then(({ data }) => {
          this.updateFiles(data.equipment_files)
        })
        .finally(() => {
          this.loadingFiles = false
        })
    },
    copy(evt, val) {
      if (!val) {
        return
      }

      copyNode(evt.target)
    },
    openDialog(component, attrs = {}) {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => component,
        attrs: {
          equipment: this.model,
          ...attrs
        },
        events: {
          delete: () => {
            this.$router.push({ name: sections.equipments })
          },
          'files-upload': (uploadFiles) => {
            this.updateFiles([...uploadFiles, ...this.model.files])
          },
          'file-update': (file, index) => {
            const files = [...this.model.files]
            files[index] = file
            this.updateFiles(files)
          },
          'file-delete': (index) => {
            const files = [...this.model.files]
            files.splice(index, 1)
            this.updateFiles(files)
          }
        }
      })
    },
    updateFiles(files) {
      Equipment.sidebar().add({ id: this.pageId, files })
    },
    onAdd() {
      this.openDialog(import('@/components/equipments/dialogs/FilesUpload'))
    },
    onEdit(file, index) {
      this.openDialog(import('@/components/equipments/dialogs/FileEdit'), { file, index })
    },
    onDelete(file, index) {
      this.openDialog(import('@/components/equipments/dialogs/FileDelete'), { file, index })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.header__wrap {
	display: flex;
	align-items: center;
	justify-content: space-around;
}

.header-item {
	width: 250px;
	&:last-child {
		margin-left: 20px;
	}
}

.header-item__title {
	font-weight: bold;
	margin-bottom: 10px;
	text-align: left;
	user-select: none;
}

.header-item__value {
	display: flex;
	align-items: center;
	justify-content: center;
	flex-wrap: wrap;
	border: 1px solid $defaultBorder;
	background: #fff;
	padding: 10px 20px;
	box-shadow: $lightShadow;
	height: 50px;
	transition: .2s;
	&.has_value {
		cursor: pointer;
	}
	&:hover {
		box-shadow: $basicShadow;
	}
	> span {
		overflow: hidden;
		text-overflow: ellipsis;
	}
}
</style>
