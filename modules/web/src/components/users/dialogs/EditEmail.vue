<template>
  <basic-edit
    title="Редагування E-mail"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-form
      ref="form"
      :model="form"
      :rules="rules"
      status-icon
      @submit.native.prevent="onSubmit"
    >
      <el-form-item prop="email">
        <el-input
          v-model="form.email"
          placeholder="E-mail"
        >
          <i
            slot="prepend"
            class="material-icons"
          >
            email
          </i>
        </el-input>
      </el-form-item>
    </el-form>
  </basic-edit>
</template>

<script>
import * as rules from '@/data/rules'
import User from '@/classes/User'

export default {
  components: {
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit'),
    ElFormItem: () => import('element-ui/lib/form-item'),
    ElInput: () => import('element-ui/lib/input'),
    ElForm: () => import('element-ui/lib/form')
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
        email: ''
      },
      rules: {
        email: rules.email
      }
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.onSubmit
      }
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      User.fetchEditEmail(this.user.id, this.form)
        .then(({ data }) => {
          this.$store.commit('users/UPDATE_ITEM', data.user)
          this.$emit('edit-email')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    },
    onSubmit() {
      this.$refs.form.validate((valid) => {
        if (!valid) {
          return
        }

        this.fetchRequest()
      })
    }
  }
}
</script>
