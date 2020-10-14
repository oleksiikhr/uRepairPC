'use strict'

import Vue from 'vue'

const state = {
  init: true,
  loading: false,
  // Paginate from server
  list: {}
}

const mutations = {
  SET_LOADING(state, toggle) {
    state.loading = toggle
  },
  SET_LIST(state, obj) {
    state.init = false
    state.list = obj
  },
  APPEND_DATA(state, ...data) {
    if (state.list && state.list.data) {
      state.list.data.unshift(...data.map(i => ({ ...i, _is_new: true })))
    }
  },
  UPDATE_ITEM(state, data) {
    if (state.list && state.list.data) {
      state.list.data.forEach((item, index) => {
        if (item.id === data.id) {
          Vue.set(state.list.data, index, { ...item, ...data })
        }
      })
    }
  },
  DELETE_ITEM(state, id) {
    if (state.list && state.list.data) {
      state.list.data.forEach((item, index) => {
        if (item.id === id) {
          state.list.data.splice(index, 1)
        }
      })
    }
  },
  CLEAR_ALL(state) {
    state.init = true
    state.loading = false
    state.list = {}
  }
}

const actions = {
  //
}

export default {
  state, mutations, actions
}
