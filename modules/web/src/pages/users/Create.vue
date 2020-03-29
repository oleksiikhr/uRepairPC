<template>
  <div class="user">
    <div class="wrap">
      <div class="title">
        {{ titlePage }}
      </div>
      <el-alert type="info">
        Фотографію, роль або ж інші дані можно будет змінити після створення.<br>
        Роль за умовчанням буде присвоєно в залежності від налаштувань.
      </el-alert>
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
import sections from '@/enum/sections'
import * as rules from '@/data/rules'
import User from '@/classes/User'
import menu from '@/data/menu'

export default {
  breadcrumbs: [
    { title: menu[sections.users].title, routeName: sections.users },
    { title: menu[sections.users].children.add.title }
  ],
  components: {
    GenerateForm: () => import('@/components/GenerateForm'),
    ElAlert: () => import('element-ui/lib/alert')
  },
  mixins: [
    breadcrumbs
  ],
  data() {
    return {
      loading: false,
      form: {
        email: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'E-mail',
          rules: rules.email
        },
        first_name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Ім\'я',
          rules: rules.required
        },
        middle_name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'По-батькові'
        },
        last_name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Прізвище',
          rules: rules.required
        },
        phone: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Телефон'
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
      return menu[sections.users].children.add.title
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      User.fetchStore(form)
        .then(({ data }) => {
          this.$store.commit('users/APPEND_DATA', data.user)
          this.$router.push({ name: `${sections.users}-id`, params: { id: data.user.id } })
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

.el-alert {
	margin-bottom: 20px;
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
