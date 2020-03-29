'use strict'

import commonStore from '@/common/store/section'
import StorageData from '@/classes/StorageData'
import columnTypes from '@/enum/columnTypes'
import Role from '@/classes/Role'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)

    return Role.fetchAll({ params })
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
    const defaultActive = ['color', 'name']

    const columns = [
      { prop: 'id', label: 'ID', 'min-width': 70, sortable: 'custom' },
      { prop: 'color', label: 'Колір', 'min-width': 100, disableSearch: true, customType: columnTypes.TYPE_COLOR },
      { prop: 'name', label: 'Ім\'я', 'min-width': 200, sortable: 'custom' },
      { prop: 'default', label: 'За замовчуванням', 'min-width': 150, sortable: 'custom', customType: columnTypes.TYPE_BOOL },
      { prop: 'updated_at', label: 'Оновлено', 'min-width': 150, sortable: 'custom', customType: columnTypes.TYPE_TIMESTAMP },
      { prop: 'created_at', label: 'Створений', 'min-width': 150, sortable: 'custom', customType: columnTypes.TYPE_TIMESTAMP }
    ]

    const data = StorageData.columnRoles.length ? StorageData.columnRoles : defaultActive

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
