'use strict'

import { TYPE_DATETIME } from '@/enum/columnTypes'
import commonStore from '@/common/store/section'
import StorageData from '@/classes/StorageData'
import Request from '@/classes/Request'

const state = {
  //
}

const mutations = {
  //
}

const actions = {
  fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)

    return Request.fetchAll({ params })
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
    const defaultActive = ['title', 'location', 'user_name', 'assign_name', 'type_name', 'priority_name', 'status_name']

    const columns = [
      { prop: 'id', label: 'ID', 'min-width': 70, sortable: 'custom' },
      { prop: 'title', label: 'Назва', 'min-width': 150, sortable: 'custom' },
      { prop: 'location', label: 'Розташування', 'min-width': 150, sortable: 'custom' },
      { prop: 'user_name', label: 'Створив', 'min-width': 150 },
      { prop: 'assign_name', label: 'Виконує', 'min-width': 150 },
      { prop: 'type_name', label: 'Тип', 'min-width': 120, disableSearch: true },
      { prop: 'priority_name', label: 'Пріорітет', 'min-width': 120, disableSearch: true, sortable: 'custom' },
      { prop: 'status_name', label: 'Статус', 'min-width': 120, disableSearch: true },
      { prop: 'equipment_name', label: 'Обладнання', 'min-width': 150 },
      { prop: 'equipment_serial_number', label: 'Серійний номер', 'min-width': 120 },
      { prop: 'equipment_inventory_number', label: 'Інвертарний номер', 'min-width': 120 },
      { prop: 'description', label: 'Опис', 'min-width': 250, disableSearch: true, hideList: true },
      { prop: 'updated_at', label: 'Оновлено', 'min-width': 150, sortable: 'custom', customType: TYPE_DATETIME },
      { prop: 'created_at', label: 'Створений', 'min-width': 150, sortable: 'custom', customType: TYPE_DATETIME }
    ]

    const data = StorageData.columnRequests.length ? StorageData.columnRequests : defaultActive

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
