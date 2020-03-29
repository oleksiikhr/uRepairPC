'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import StorageData from '@/classes/StorageData'
import sections from '@/enum/sections'
import { server } from '@/data/env'
import store from '@/store'
import axios from 'axios'

export default class User extends ApiHasHistory {

  static get __API_POINT() {
    return 'users'
  }

  static get __SECTION() {
    return sections.users
  }

  static get __JSON_ATTR() {
    return 'user'
  }

  /**
   * @param {object} obj - User object
   */
  constructor(obj) {
    super()
    this.user = obj
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchOne(id, config = null) {
    return super.fetchOne(id, config)
      .then((response) => {
        // Update for current user new permissions
        if (store.state.profile.user.id === id && response.data.user.permissions) {
          store.commit('profile/SET_PERMISSIONS', response.data.user.permissions)
        }
        return response
      })
  }

  /**
   * Update email for user by id.
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEditEmail(id, data = null, config = null) {
    return this._appendToSidebar(axios.put(`${this.__API_POINT}/${id}/email`, data, config))
  }

  /**
   * Update image for user by id.
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEditImage(id, data = null, config = null) {
    return this._appendToSidebar(axios.post(`${this.__API_POINT}/${id}/image`, data, config))
  }

  /**
   * Update password for user by id.
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEditPassword(id, data = null, config = null) {
    return axios.put(`${this.__API_POINT}/${id}/password`, data, config)
  }

  /**
   * Update roles for user by id.
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEditRoles(id, data = null, config = null) {
    return this._appendToSidebar(axios.put(`${this.__API_POINT}/${id}/roles`, data, config))
  }

  /**
   * Update image for user by id.
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDeleteImage(id, config = null) {
    return this._appendToSidebar(axios.delete(`${this.__API_POINT}/${id}/image`, config))
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Getters -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /** @return {string} */
  get fullName() {
    const name = `${this.user.last_name} ${this.user.first_name}`

    if (this.user.middle_name) {
      return `${name} ${this.user.middle_name}`
    }

    return name
  }

  /** @return {string|null} */
  get initials() {
    if (this.user.last_name && this.user.first_name) {
      return `${this.user.last_name[0].toUpperCase()}. ${this.user.first_name[0].toUpperCase()}.`
    }

    return null
  }

  /** @return {string|null} */
  get backgroundImage() {
    if (this.user.image_id) {
      const token = StorageData.token
      const time = new Date(this.user.updated_at).getTime()
      return `background-image: url(${server}/api/users/${this.user.id}/image?time=${time}&token=${token})`
    }

    return null
  }
}
