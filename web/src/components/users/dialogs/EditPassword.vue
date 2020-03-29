<template>
  <basic-edit
    title="Редагування пароля"
    :loading="loading"
    save-btn="Скинути пароль"
    v-bind="$attrs"
    v-on="listeners"
  >
    <el-form
      v-if="profile.id === user.id"
      ref="form"
      :model="form"
      :rules="rules"
      status-icon
      @submit.native.prevent="onSubmit"
    >
      <el-form-item prop="password">
        <el-input
          v-model="form.password"
          type="password"
          placeholder="Пароль"
        >
          <i
            slot="prepend"
            class="material-icons"
          >
            lock
          </i>
        </el-input>
      </el-form-item>
      <el-form-item prop="repeat_password">
        <el-input
          v-model="form.repeat_password"
          type="password"
          placeholder="Повторити пароль"
        >
          <i
            slot="prepend"
            class="material-icons"
          >
            repeat
          </i>
        </el-input>
      </el-form-item>
      <button
        class="hide"
        type="submit"
      />
    </el-form>
    <template v-else>
      Ви дійсно хочете згенерувати новий пароль і відправити його на пошту користувача?
    </template>
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
        password: '',
        repeat_password: ''
      },
      rules: {
        password: rules.password,
        repeat_password: [
          ...rules.password,
          {
            validator: (rule, value, callback) => {
              if (this.passwordEqual) {
                callback()
              } else {
                callback(new Error('Паролі не співпадають'))
              }
            }
          }
        ]
      }
    }
  },
  computed: {
    profile() {
      return this.$store.state.profile.user
    },
    listeners() {
      return {
        ...this.$listeners,
        submit: this.onSubmit
      }
    },
    passwordEqual() {
      return this.form.password === this.form.repeat_password
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      User.fetchEditPassword(this.user.id, this.form)
        .then(() => {
          this.$emit('edit-password')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    },
    onSubmit() {
      if (this.profile.id === this.user.id) {
        this.$refs.form.validate((valid) => {
          if (!valid) {
            return
          }

          this.fetchRequest()
        })
      } else {
        this.fetchRequest()
      }
    }
  }
}
</script>
