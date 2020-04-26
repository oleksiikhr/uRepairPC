<template>
  <div id="auth">
    <div class="auth-wrap">
      <big-logo />
      <el-form
        ref="form"
        :model="form"
        :rules="rules"
        status-icon
        @submit.native.prevent="onSubmit"
      >
        <el-form-item prop="email">
          <el-input
            ref="email"
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
        <el-form-item class="mb-0">
          <el-button
            native-type="submit"
            type="primary"
            :loading="loading"
          >
            Увійти
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <div id="copyright">
      <a
        href="https://github.com/alexeykhr"
        target="_blank"
      >❤️ Made by Alexey Khrushch</a>
    </div>
  </div>
</template>

<script>
import * as rules from '@/data/rules'

export default {
  components: {
    ElFormItem: () => import('element-ui/lib/form-item'),
    BigLogo: () => import('@/components/root/BigLogo'),
    ElButton: () => import('element-ui/lib/button'),
    ElInput: () => import('element-ui/lib/input'),
    ElForm: () => import('element-ui/lib/form')
  },
  data() {
    return {
      form: {
        email: process.env.NODE_ENV === 'development' ? 'admin@example.com' : '',
        password: process.env.NODE_ENV === 'development' ? 'admin123' : ''
      },
      rules: {
        email: rules.email,
        password: rules.password
      }
    }
  },
  computed: {
    loading() {
      return this.$store.state.profile.loading
    }
  },
  methods: {
    onSubmit() {
      this.$refs.form.validate((valid) => {
        if (!valid) {
          return
        }

        this.$store.dispatch('profile/auth', this.form)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";

#auth {
  display: flex;
  flex-direction: column;
  width: 100%;
  min-height: 100vh;
}

.auth-wrap {
  width: 100%;
  max-width: 450px;
  margin: 50px auto;
  padding: 35px;
  background: #fff;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .2), 0 1px 1px 0 rgba(0, 0, 0, .14), 0 2px 1px -1px rgba(0, 0, 0, .12);
}

.el-button {
  width: 100%;
}

#copyright {
  display: flex;
  align-items: flex-end;
  justify-content: center;
  flex: 1 1 auto;
  margin-bottom: 20px;
  text-align: center;
  cursor: context-menu;
  > a {
    font-size: .75rem;
    color: #909090;
    font-weight: bold;
    letter-spacing: .3px;
    &:hover {
      color: #5d5d5d;
    }
  }
}

@media only screen and (max-width: $mobileL) {
  .auth-wrap {
    background: none;
    box-shadow: none;
    margin: 20px auto;
  }
}
</style>
