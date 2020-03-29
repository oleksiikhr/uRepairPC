<template>
  <basic-edit
    title="Редагування ролей"
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
import User from '@/classes/User'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    GenerateForm: () => import('@/components/GenerateForm')
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
      loading: false,
      form: {
        roles: {
          component: () => import('@/components/roles/Select'),
          value: [],
          label: 'Оберіть ролі (введіть текст для отримання списку)',
          attrs: {
            defaultRoles: this.user.roles
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

      User.fetchEditRoles(this.user.id, form)
        .then(({ data }) => {
          this.$store.commit('users/UPDATE_ITEM', data.user)
          this.$emit('edit-roles')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
