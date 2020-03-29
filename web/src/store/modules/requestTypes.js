'use strict'

import settingsStore from '@/common/store/settings'
import RequestType from '@/classes/RequestType'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }) {
    commit('SET_LOADING', true)

    return RequestType.fetchAll()
      .then(({ data }) => {
        commit('SET_LIST', data)
      })
      .finally(() => {
        commit('SET_LOADING', false)
      })
  }
}

export default {
  state: { ...state, ...settingsStore.state },
  mutations: { ...mutations, ...settingsStore.mutations },
  actions: { ...actions, ...settingsStore.actions },
  namespaced: true
}
