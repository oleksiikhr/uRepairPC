'use strict'

import { TYPE_DATETIME } from '@/enum/columnTypes'
import commonStore from '@/common/store/section'
import StorageData from '@/classes/StorageData'
import FailedJob from '@/classes/FailedJob'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)

    return FailedJob.fetchAll({ params })
      .then(({ data }) => {
        commit('SET_LIST', data)
      })
      .finally(() => {
        commit('SET_LOADING', false)
      })
  }
}

const getters = {
  /**
   * Display on table.
   * Attributes:
   *  - disableSearch |Boolean| - disable send column on list of resources
   *  - customType |String| - transform value depends on type (bool, timestamp)
   *  - hideList |Boolean| - display column on page (Index)
   * @returns {(*|{model: boolean})[]}
   */
  columns() {
    const defaultActive = ['uuid', 'connection', 'queue', 'failed_at']

    const columns = [
      { prop: 'id', label: 'ID', 'min-width': 70, sortable: 'custom' },
      { prop: 'uuid', label: 'UUID', 'min-width': 150, sortable: 'custom' },
      { prop: 'connection', label: 'З\'єднання', 'min-width': 150, sortable: 'custom' },
      { prop: 'queue', label: 'Черга', 'min-width': 150, sortable: 'custom' },
      { prop: 'payload', label: 'Payload', 'min-width': 200, disableSearch: true },
      { prop: 'exception', label: 'Помилка', 'min-width': 200, disableSearch: true },
      { prop: 'failed_at', label: 'Дата помилки', 'min-width': 150, sortable: 'custom', customType: TYPE_DATETIME }
    ]

    const data = StorageData.columnFailedJobs.length ? StorageData.columnFailedJobs : defaultActive

    return columns
      .map((column) => {
        return { ...column, model: data.includes(column.prop) }
      })
  }
}

export default {
  state: { ...state, ...commonStore.state },
  mutations: { ...mutations, ...commonStore.mutations },
  actions: { ...actions, ...commonStore.actions },
  getters,
  namespaced: true
}
