<template>
  <el-header height="50px">
    <div class="header--left">
      <header-logo />
      <el-button
        v-if="sectionRequestMenuActions.add"
        :type="sectionRequestMenuActions.add.type"
        size="mini"
        @click="onClickCreateRequest"
      >
        <i class="material-icons">
          {{ sectionRequestMenuActions.add.icon }}
        </i>
        <span>{{ sectionRequestMenuActions.add.title }}</span>
      </el-button>
    </div>
    <div class="header--center">
      <tips />
    </div>
    <div class="header--right">
      <div
        class="user"
        @click="onClickEmail"
      >
        <i class="material-icons">
          person
        </i>
        <span>{{ user.email }}</span>
      </div>
      <div
        class="quit"
        @click="onClickLogout"
      >
        <i class="material-icons">
          exit_to_app
        </i>
      </div>
    </div>
  </el-header>
</template>

<script>
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'
import User from '@/classes/User'

export default {
  components: {
    HeaderLogo: () => import('@/components/root/HeaderLogo'),
    ElHeader: () => import('element-ui/lib/header'),
    ElButton: () => import('element-ui/lib/button'),
    Tips: () => import('@/components/root/Tips')
  },
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    user() {
      return this.$store.state.profile.user
    },
    sectionRequestMenuActions() {
      const section = this.menu[sections.requests] || {}

      return section.children || {}
    }
  },
  methods: {
    onClickLogout() {
      this.$store.dispatch('profile/logout')
    },
    onClickCreateRequest() {
      this.$router.push({ name: `${sections.requests}-create` })
    },
    onClickEmail() {
      User.sidebar().add(this.user)
      this.$router.push({ name: `${sections.users}-id`, params: { id: this.user.id } })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

.el-header {
  position: fixed;
  width: 100%;
  display: flex;
  align-items: center;
  background: #fff;
  border-bottom: 1px solid $defaultBorder;
  padding: 0 15px;
  user-select: none;
  overflow: hidden;
  z-index: 1600; // v-loading 1500
}

.el-button {
  > /deep/ span {
    display: flex;
    align-items: center;
    justify-content: center;
    i {
      margin-right: 5px;
    }
  }
}

.header--left {
  display: flex;
  align-items: center;
  justify-content: center;
  .el-button {
    margin-left: 25px;
  }
}

.header--center {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1 1 auto;
  padding: 0 15px;
  height: 100%;
  overflow-x: hidden;
  overflow-y: visible;
}

.header--right {
  display: flex;
  align-items: center;
  justify-content: center;
  .el-button {
    i {
      font-size: .9rem;
    }
  }
}

.user, .quit {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 20px;
  margin-left: 20px;
  cursor: pointer;
}

.user {
  white-space: nowrap;
  > i {
    margin-right: 5px;
  }
}

.quit {
  color: #555;
}

@media only screen and (max-width: $laptop) {
  .el-button {
    /deep/ span {
      i {
        margin-right: 0;
      }
      span {
        display: none;
      }
    }
  }
  .user {
    height: auto;
    padding: 7px 15px;
    margin-left: 10px;
    > i {
      margin-right: 0;
    }
    span {
      display: none;
    }
  }
}

@media only screen and (max-width: $tablet) {
  .header--left {
    .el-button {
      display: none;
    }
  }
  .header--center {
    display: none;
  }
  .header--right {
    flex: 1 1 auto;
    justify-content: flex-end;
    margin-left: 20px;
  }
}
</style>
