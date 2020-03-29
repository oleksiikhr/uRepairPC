<template>
  <basic-edit
    :title="userObj.fullName"
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
        first_name: {
          component: () => import('element-ui/lib/input'),
          value: this.user.first_name,
          label: 'Ім\'я',
          rules: required
        },
        middle_name: {
          component: () => import('element-ui/lib/input'),
          value: this.user.middle_name,
          label: 'По-батькові'
        },
        last_name: {
          component: () => import('element-ui/lib/input'),
          value: this.user.last_name,
          label: 'Прізвище',
          rules: required
        },
        phone: {
          component: () => import('element-ui/lib/input'),
          value: this.user.phone,
          label: 'Телефон'
        },
        description: {
          component: () => import('element-ui/lib/input'),
          value: this.user.description,
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
    listeners() {
      return {
        ...this.$listeners,
        submit: () => {
          this.$refs.form.onSubmit()
        }
      }
    },
    userObj() {
      return new User(this.user)
    }
  },
  methods: {
    fetchRequest(form) {
      this.loading = true

      User.fetchEdit(this.user.id, form)
        .then(({ data }) => {
          this.$store.commit('users/UPDATE_ITEM', data.user)
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
