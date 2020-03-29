<template>
  <div class="logo">
    <img
      v-if="settings.logo_auth"
      :src="logo"
      :alt="settings.app_name || 'logo'"
    >
    <span
      v-else-if="settings.app_name"
      class="title"
    >
      {{ settings.app_name }}
    </span>
    <logo-svg
      v-else
      class="custom"
    />
  </div>
</template>

<script>
export default {
  components: {
    LogoSvg: () => import('@/components/svg/Logo')
  },
  computed: {
    settings() {
      return this.$store.state.settings.global.data
    },
    logo() {
      return this.settings.logo_auth
    }
  }
}
</script>

<style lang="scss" scoped>
.logo {
  text-align: center;
  margin-bottom: 30px;
  > img {
    max-width: 250px;
    max-height: 200px;
  }
  .title {
    display: block;
    font-weight: bold;
    font-size: 1.5rem;
    margin: 30px 0 70px;
  }
  .custom {
    height: 170px;
  }
}
</style>
