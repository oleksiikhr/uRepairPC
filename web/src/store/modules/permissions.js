'use strict'

import Permission from '@/classes/Permission'

const state = {
  list: [],
  loading: false
}

const mutations = {
  SET_LIST(state, arr) {
    state.list = arr
  },
  SET_LOADING(state, bool) {
    state.loading = bool
  },
  CLEAR_ALL(state) {
    state.loading = false
    state.list = []
  }
}

const actions = {
  fetchList({ commit }) {
    commit('SET_LOADING', true)

    return Permission.fetchAll()
      .then(({ data }) => {
        commit('SET_LIST', data)
      })
      .finally(() => {
        commit('SET_LOADING', false)
      })
  }
}

export default {
  state,
  mutations,
  actions,
  namespaced: true
}
