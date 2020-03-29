<template>
  <div class="home">
    <section-menu
      :menu="menuWithoutHomePage"
    />
  </div>
</template>

<script>
import breadcrumbs from '@/mixins/breadcrumbs'
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'

export default {
  name: 'Home',
  components: {
    SectionMenu: () => import('@/components/sections/SectionMenu')
  },
  mixins: [
    breadcrumbs
  ],
  computed: {
    ...mapGetters({
      menu: 'template/menu'
    }),
    menuWithoutHomePage() {
      const list = { ...this.menu }
      delete list[sections.home]
      return list
    }
  }
}
</script>

<style lang="scss" scoped>
.home {
  padding: 20px;
}
</style>
