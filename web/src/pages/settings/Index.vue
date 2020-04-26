<template>
  <div class="settings">
    <section-menu :menu="pages" />
  </div>
</template>

<script>
import breadcrumbs from '@/mixins/breadcrumbs'
import sections from '@/enum/sections'
import { mapGetters } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'Settings',
  breadcrumbs: [
    { title: menu[sections.settings].title }
  ],
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
    pages() {
      const section = this.menu[sections.settings] || {}

      return section.children || {}
    }
  }
}
</script>
