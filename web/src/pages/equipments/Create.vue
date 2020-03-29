<template>
  <div class="equipment">
    <div class="wrap">
      <div class="title">
        {{ titlePage }}
      </div>
      <generate-form
        :form="form"
        :loading="loading"
        @submit="fetchRequest"
      >
        <template slot="button">
          Створити
        </template>
      </generate-form>
    </div>
  </div>
</template>

<script>
import breadcrumbs from '@/mixins/breadcrumbs'
import Equipment from '@/classes/Equipment'
import { required } from '@/data/rules'
import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import menu from '@/data/menu'

export default {
  breadcrumbs: [
    { title: menu[sections.equipments].title, routeName: sections.equipments },
    { title: menu[sections.equipments].children.add.title }
  ],
  components: {
    GenerateForm: () => import('@/components/GenerateForm')
  },
  mixins: [
    breadcrumbs
  ],
  data() {
    return {
      loading: false,
      form: {
        equipment: {
          component: () => import('@/components/equipments/Cascader'),
          permissions: perm.EQUIPMENTS_CONFIG_VIEW_ALL,
          value: [],
          label: 'Тип, Виробник, Модель',
          rules: required
        },
        serial_number: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Серійний номер'
        },
        inventory_number: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Інвертарний номер'
        },
        description: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Опис',
          attrs: {
            type: 'textarea',
            autosize: { minRows: 3 }
          }
        }
      }
    }
  },
  computed: {
    titlePage() {
      return menu[sections.equipments].children.add.title
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      Equipment.fetchStore({
        ...form,
        type_id: form.equipment[0] || null,
        manufacturer_id: form.equipment[1] || null,
        model_id: form.equipment[2] || null
      })
        .then(({ data }) => {
          this.$store.commit('equipments/APPEND_DATA', data.equipment)
          this.$router.push({ name: `${sections.equipments}-id`, params: { id: data.equipment.id } })
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap {
	padding: 10px 20px 40px;
	max-width: 550px;
	margin: 0 auto;
}

.title {
	text-align: center;
	font-size: 1.5rem;
	font-weight: bold;
	margin: 25px 15px;
}

.el-select {
	width: 100%;
}

.btn-wrap {
	text-align: center;
	button {
		max-width: 200px;
		width: 100%;
	}
}

/deep/ .el-form-item__label {
	float: none;
}
</style>
