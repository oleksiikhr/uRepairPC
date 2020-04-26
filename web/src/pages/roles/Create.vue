<template>
  <div class="role">
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
import { required } from '@/data/rules'
import sections from '@/enum/sections'
import Role from '@/classes/Role'
import menu from '@/data/menu'

export default {
  breadcrumbs: [
    { title: menu[sections.roles].title, routeName: sections.roles },
    { title: menu[sections.roles].children.add.title }
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
        name: {
          component: () => import('element-ui/lib/input'),
          value: '',
          label: 'Ім\'я',
          rules: required
        },
        color: {
          component: () => import('element-ui/lib/color-picker'),
          value: '',
          label: 'Колір'
        },
        default: {
          component: () => import('element-ui/lib/checkbox'),
          value: false,
          attrs: {
            label: 'За замовчуванням'
          }
        }
      }
    }
  },
  computed: {
    titlePage() {
      return menu[sections.roles].children.add.title
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      Role.fetchStore(form)
        .then(({ data }) => {
          this.$store.commit('roles/APPEND_DATA', data.role)
          this.$router.push({ name: `${sections.roles}-id`, params: { id: data.role.id } })
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
</style>
