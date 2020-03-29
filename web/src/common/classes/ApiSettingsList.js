'use strict'

import Api from '@/common/classes/Api'
import store from '@/store'

export default class ApiSettingsList extends Api {

  /**
   * Vuex store name
   * @return {string}
   */
  static get __STORE() {
    return ''
  }

  /**
   * Required for use in child classes
   * @return {string}
   * @example { message: '', model: {} }
   */
  static get __JSON_ATTR() {
    return ''
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEdit(id, data = null, config = null) {
    return super.fetchEdit(id, data, config)
      .then((response) => {
        store.dispatch(`${this.__STORE}/findReplace`, response.data[this.__JSON_ATTR])
        return response
      })
  }

  /**
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(data = null, config = null) {
    return super.fetchStore(data, config)
      .then((response) => {
        store.commit(`${this.__STORE}/APPEND_ITEM`, response.data[this.__JSON_ATTR])
        return response
      })
  }

  /**
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDelete(id, config = null) {
    return super.fetchDelete(id, config)
      .then((response) => {
        store.dispatch(`${this.__STORE}/findDelete`, id)
        return response
      })
  }
}
