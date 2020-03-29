'use strict'

import breadcrumbs from '@/mixins/breadcrumbs'
import menu from '@/data/menu'

/*
 * NOTE: fetchData function is reserved!
 */

export default (section) => {
  return {
    breadcrumbs: [
      { title: menu[section].title, routeName: section },
      { title: route => `ID: ${route.params.id || -1}` }
    ],
    mixins: [
      breadcrumbs
    ],
    data() {
      return {
        pageId: +this.$route.params.id
      }
    },
    computed: {
      model() {
        const list = this.$store.state.template.sidebar[section]

        if (list && list[this.pageId]) {
          return list[this.pageId]
        }

        return {}
      },
      hasFetchDataFunc() {
        return typeof this.fetchData === 'function'
      }
    },
    watch: {
      '$route'() {
        if (this.hasFetchDataFunc) {
          this.fetchData()
        }
      }
    },
    created() {
      if (this.hasFetchDataFunc) {
        this.fetchData()
      }
    }
  }
}
