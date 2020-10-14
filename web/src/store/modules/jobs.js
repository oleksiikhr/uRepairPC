'use strict'

import { TYPE_TIMESTAMP } from '@/enum/columnTypes'
import commonStore from '@/common/store/section'
import StorageData from '@/classes/StorageData'
import Job from '@/classes/Job'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)

    return Job.fetchAll({ params })
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
    const defaultActive = ['queue', 'attempts', 'reserved_at', 'updated_at', 'created_at']

    const columns = [
      { prop: 'id', label: 'ID', 'min-width': 70, sortable: 'custom' },
      { prop: 'queue', label: 'Черга', 'min-width': 150, sortable: 'custom' },
      { prop: 'payload', label: 'Payload', 'min-width': 200, disableSearch: true },
      { prop: 'attempts', label: 'Спроб', 'min-width': 100, disableSearch: true },
      { prop: 'reserved_at', label: 'Зарезервовано', 'min-width': 150, sortable: 'custom' },
      { prop: 'updated_at', label: 'Оновлено', 'min-width': 150, sortable: 'custom', customType: TYPE_TIMESTAMP },
      { prop: 'created_at', label: 'Створений', 'min-width': 150, sortable: 'custom', customType: TYPE_TIMESTAMP }
    ]

    const data = StorageData.columnJobs.length ? StorageData.columnJobs : defaultActive

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
