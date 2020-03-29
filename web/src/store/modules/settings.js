'use strict'

import SettingsManifest from '@/classes/SettingsManifest'
import SettingsGlobal from '@/classes/SettingsGlobal'
import Vue from 'vue'

const state = {
  // Global settings for the website (logo, title, etc)
  global: {
    init: true,
    loading: false,
    data: {}
  },
  // PWA
  manifest: {
    init: true,
    loading: false,
    data: {}
  }
}

const mutations = {
  SET_GLOBAL(state, obj) {
    Vue.set(state.global, 'init', false)
    Vue.set(state.global, 'data', obj)
  },
  SET_LOADING_GLOBAL(state, bool) {
    Vue.set(state.global, 'loading', bool)
  },
  SET_MANIFEST(state, obj) {
    Vue.set(state.manifest, 'init', false)
    Vue.set(state.manifest, 'data', obj)
  },
  SET_LOADING_MANIFEST(state, bool) {
    Vue.set(state.manifest, 'loading', bool)
  }
}

const actions = {
  fetchGlobal({ commit }, params) {
    commit('SET_LOADING_GLOBAL', true)

    return SettingsGlobal.fetchGet({ params })
      .then(({ data }) => {
        commit('SET_GLOBAL', data)
      })
      .finally(() => {
        commit('SET_LOADING_GLOBAL', false)
      })
  },
  fetchManifest({ commit }, params) {
    commit('SET_LOADING_MANIFEST', true)

    return SettingsManifest.fetchGet({ params })
      .then(({ data }) => {
        commit('SET_MANIFEST', data)
      })
      .finally(() => {
        commit('SET_LOADING_MANIFEST', false)
      })
  }
}

export default {
  state,
  mutations,
  actions,
  namespaced: true
}
