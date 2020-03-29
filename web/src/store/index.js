'use strict'

import { isDev } from '@/data/env'
import modules from './modules'
import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

export default new Vuex.Store({
  modules,
  strict: isDev
})
