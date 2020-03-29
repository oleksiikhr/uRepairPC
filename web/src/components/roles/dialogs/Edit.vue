<template>
  <basic-edit
    title="Редагування ролі"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <generate-form
      ref="form"
      :form="form"
      :loading="loading"
      @submit="fetchRequest"
    />
  </basic-edit>
</template>

<script>
import { required } from '@/data/rules'
import Role from '@/classes/Role'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm')
  },
  inheritAttrs: false,
  props: {
    role: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form: {
        name: {
          component: () => import('element-ui/lib/input'),
          value: this.role.name,
          label: 'Ім\'я',
          rules: required
        },
        color: {
          component: () => import('element-ui/lib/color-picker'),
          value: this.role.color,
          label: 'Колір'
        },
        default: {
          component: () => import('element-ui/lib/checkbox'),
          value: this.role.default,
          attrs: {
            label: 'За замовчуванням'
          }
        }
      }
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: () => {
          this.$refs.form.onSubmit()
        }
      }
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      Role.fetchEdit(this.role.id, form)
        .then(({ data }) => {
          this.$store.commit('roles/UPDATE_ITEM', data.role)
          this.$emit('edit')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
