'use strict'

import VueRouter from 'vue-router'
import { isDev } from '@/data/env'
import routes from './routes'
import Vue from 'vue'

Vue.use(VueRouter)

export default new VueRouter({
  mode: isDev ? 'hash' : 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes
})
