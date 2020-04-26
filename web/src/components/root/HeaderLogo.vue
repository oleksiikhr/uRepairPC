<template>
  <div
    class="logo"
    @click="onClickLogo"
  >
    <logo-left-font-svg
      v-if="!settings.logo_header && !settings.app_name"
      class="custom"
    />
    <template v-else>
      <img
        v-if="settings.logo_header"
        :src="logo"
        :alt="settings.app_name || 'logo'"
      >
      <div v-if="hasAppName">
        {{ settings.app_name }}
      </div>
    </template>
  </div>
</template>

<script>
import { DEFAULT_ROUTE_NAME } from '@/router'

export default {
  components: {
    LogoLeftFontSvg: () => import('@/components/svg/LogoLeftFont')
  },
  computed: {
    settings() {
      return this.$store.state.settings.global.data
    },
    logo() {
      return this.settings.logo_header
    },
    hasAppName() {
      if (!this.settings.logo_header && this.settings.app_name) {
        return true
      }

      return this.settings.logo_header && this.settings.app_name && this.settings.name_and_logo
    }
  },
  methods: {
    onClickLogo() {
      this.$router.push({ name: DEFAULT_ROUTE_NAME })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_variables";
@import "~scss/_colors";

.logo {
  display: flex;
  align-items: center;
  cursor: pointer;
  width: 210px;
  overflow: hidden;
  &:hover {
    > div {
      opacity: 1;
    }
    .custom /deep/ .logo {
      stroke: $primary;
    }
  }
  > img {
    height: 40px;
    width: auto;
    + div {
      margin-left: 15px;
    }
  }
  > div {
    width: 100%;
    font-weight: bold;
    font-size: 1.2em;
    color: #333;
    opacity: .7;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: opacity .2s;
  }
  .custom {
    width: 170px;
    height: 40px;
    /deep/ .logo {
      transition: stroke .25s;
      stroke: #fff;
    }
  }
}

@media only screen and (max-width: $mobileL) {
  .logo {
    width: auto;
    /deep/ img + div {
      display: none;
    }
    .custom {
      width: 40px;
      /deep/ g.text {
        display: none;
      }
      /deep/ g.logo {
        transform: matrix(0.5, 0, 0, 0.5, 10, 12) !important;
      }
    }
  }
}
</style>
