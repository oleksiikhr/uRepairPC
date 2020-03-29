'use strict'

import keepAliveRoutesName from '@/data/keepAliveComponents'
import { DEFAULT_ROUTE_NAME } from '@/router'
import sections from '@/enum/sections'
import menu from '@/data/menu'

/**
 * Get first item - Home page
 * @param {Boolean} hasLink
 * @return {Object}
 * @example
 *  - title {String}
 *  - routeName - RouterLink
 */
function getFirstBreadcrumb(hasLink) {
  const title = menu[sections.home].title

  if (hasLink) {
    return { title, routeName: DEFAULT_ROUTE_NAME }
  }

  return { title }
}

export default {
  /*
	 * When page is activated - we update breadcrumbs
	 * in Default layout.
	 */
  activated() {
    this.$nextTick(this.updateBreadcrumbs)
  },
  mounted() {
    if (!this.$options.name || !keepAliveRoutesName.includes(this.$options.name)) {
      this.$nextTick(this.updateBreadcrumbs)
    }
  },
  methods: {
    updateBreadcrumbs() {
      if (!this.$options.breadcrumbs) {
        this.$store.commit('template/SET_BREADCRUMBS', [getFirstBreadcrumb(false)])
        return
      }

      this.$store.commit('template/SET_BREADCRUMBS', [
        getFirstBreadcrumb(true),
        ...this.$options.breadcrumbs
      ])
    }
  }
}
