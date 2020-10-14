'use strict'

import { TYPE_TIMESTAMP } from '@/enum/columnTypes'
import commonStore from '@/common/store/section'
import StorageData from '@/classes/StorageData'
import { hasPerm } from '@/scripts/utils'
import * as perm from '@/enum/perm'
import User from '@/classes/User'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)

    return User.fetchAll({ params })
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
    const defaultActive = ['first_name', 'last_name', 'email', 'phone', 'created_at']

    const columns = [
      { prop: 'id', label: 'ID', 'min-width': 70, sortable: 'custom' },
      { prop: 'first_name', label: 'Ім\'я', 'min-width': 150, sortable: 'custom' },
      { prop: 'middle_name', label: 'По-батькові', 'min-width': 150, sortable: 'custom' },
      { prop: 'last_name', label: 'Прізвище', 'min-width': 150, sortable: 'custom' },
      { prop: 'roles', label: 'Ролі', 'min-width': 150, permissions: perm.ROLES_VIEW_ALL, disableSearch: true },
      { prop: 'email', label: 'E-mail', 'min-width': 250, sortable: 'custom' },
      { prop: 'phone', label: 'Телефон', 'min-width': 150, sortable: 'custom' },
      { prop: 'description', label: 'Опис', 'min-width': 250, disableSearch: true, hideList: true },
      { prop: 'updated_at', label: 'Оновлено', 'min-width': 150, sortable: 'custom', customType: TYPE_TIMESTAMP },
      { prop: 'created_at', label: 'Створений', 'min-width': 150, sortable: 'custom', customType: TYPE_TIMESTAMP }
    ]

    const data = StorageData.columnUsers.length ? StorageData.columnUsers : defaultActive

    return columns
      .filter(column => hasPerm(column.permissions))
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
