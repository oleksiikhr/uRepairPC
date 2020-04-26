'use strict'

import SettingsGlobal from '@/classes/SettingsGlobal'
import lang from 'element-ui/lib/locale/lang/ua'
import loading from 'element-ui/lib/loading'
import locale from 'element-ui/lib/locale'
import prototypes from '@/prototypes'
import router from '@/router'
import App from '@/App.vue'
import store from '@/store'
import Vue from 'vue'

// Import Service Worker and socket.io
import '@/scripts/sw'
import '@/socket'

// Prevent the production tip on Vue startup
Vue.config.productionTip = false

// Set language for element-ui library
locale.use(lang)

/**
 * Install global directives
 * @example v-loading="handle"
 */
Vue.use(loading)

/**
 * Install global prototypes
 * @var {Array} prototypes
 * @example Vue.$axios | this.$axios
 */
prototypes.forEach(prototype => Vue.use(prototype))

// Set init config
SettingsGlobal.init()
store.dispatch('profile/init')

new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App)
})
