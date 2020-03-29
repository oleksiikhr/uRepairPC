'use strict'

import RequestStatus from '@/classes/RequestStatus'
import settingsStore from '@/common/store/settings'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }) {
    commit('SET_LOADING', true)

    return RequestStatus.fetchAll()
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
