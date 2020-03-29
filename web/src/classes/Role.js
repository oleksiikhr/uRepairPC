'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import sections from '@/enum/sections'
import axios from 'axios'

/** @type {string} */
export const API_POINT = 'roles'

export default class Role extends ApiHasHistory {

  static get __API_POINT() {
    return 'roles'
  }

  static get __SECTION() {
    return sections.roles
  }

  static get __JSON_ATTR() {
    return 'role'
  }

  /**
   * Edit permissions for role by id.
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEditPermissions(id, data = null, config = null) {
    return axios.put(`${API_POINT}/${id}/permissions`, data, config)
      .then((response) => {
        Role.sidebar().add(response.data[this.__JSON_ATTR])
        return response
      })
  }
}
