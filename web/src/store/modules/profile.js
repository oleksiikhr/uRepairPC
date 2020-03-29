'use strict'

import router, { DEFAULT_ROUTE_NAME } from '@/router'
import { runLoadingService } from '@/scripts/dom'
import StorageData from '@/classes/StorageData'
import { syncEvents } from '@/socket/functions'
import logout from '@/scripts/logout'
import axios from 'axios'

const state = {
  loading: false,
  isLogin: false,
  user: {},
  permissions: [],
  permissionsObj: {}
}

const mutations = {
  SET_USER(state, obj) {
    const data = { ...state.user, ...obj }
    state.user = data
    StorageData.profile = data
  },
  SET_PERMISSIONS(state, arr) {
    state.permissions = arr
    StorageData.permissions = arr

    const permissionsObj = {}
    arr.forEach(name => permissionsObj[name] = true)
    state.permissionsObj = permissionsObj
  },
  SET_LOADING(state, toggle) {
    state.loading = toggle
  },
  SET_IS_LOGIN(state, toggle) {
    state.isLogin = toggle
  }
}

const actions = {
  /**
   * Get data from localStorage and set init config.
   */
  init({ commit }) {
    const profile = StorageData.profile
    const token = StorageData.token

    // Check if values is exists on localStorage
    // permissions can be empty
    if (!profile.id || !token) {
      return logout()
    }

    axios.defaults.headers['Authorization'] = `Bearer ${token}`

    commit('SET_USER', profile)
    commit('SET_PERMISSIONS', StorageData.permissions)
    commit('SET_IS_LOGIN', true)
  },
  auth({ commit }, data) {
    commit('SET_LOADING', true)

    return axios.post('auth/login', data)
      .then(({ data }) => {
        // Update axios and localStorage
        axios.defaults.headers['Authorization'] = `Bearer ${data.token}`
        StorageData.token = data.token

        // Sync rooms by permissions on socket server
        syncEvents()

        // Update store
        commit('SET_USER', data.user)
        commit('SET_PERMISSIONS', data.permissions)
        commit('SET_IS_LOGIN', true)

        router.push({ name: DEFAULT_ROUTE_NAME })
      })
      .finally(() => {
        commit('SET_LOADING', false)
      })
  },
  update({ state, commit }) {
    if (!state.user.id) {
      return
    }

    return axios.get(`users/${state.user.id}`)
      .then(({ data }) => {
        // Sync rooms by permissions on socket server
        syncEvents()

        commit('SET_USER', data.user)
        commit('SET_PERMISSIONS', data.user.permissions)
      })
      .finally(() => {
        commit('SET_LOADING', false)
      })
  },
  logout() {
    const loadingService = runLoadingService('Виходимо з системи')

    return axios.post('auth/logout')
      .finally(() => {
        logout()
        loadingService.close()
      })
  }
}

export default {
  state,
  mutations,
  actions,
  namespaced: true
}
