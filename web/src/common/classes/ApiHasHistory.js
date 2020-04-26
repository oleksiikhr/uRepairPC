'use strict'

import Api from '@/common/classes/Api'
import store from '@/store'

export default class ApiHasHistory extends Api {

  /**
   * Required for use in child classes
   * @return {string} - sections enum
   */
  static get __SECTION() {
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
	 * | - Sidebar History -
	 * | ------------------------------------------------------------------------------------------------
	 */

  static sidebar() {
    const self = this

    return {
      get(id) {
        const requests = store.state.template.sidebar[self.__SECTION] || {}
        return requests[id]
      },
      add(data) {
        return store.commit('template/ADD_SIDEBAR_ITEM', {
          section: self.__SECTION,
          data
        })
      },
      remove(id) {
        return store.commit('template/REMOVE_SIDEBAR_ITEM', {
          section: self.__SECTION,
          id
        })
      }
    }
  }

  /**
   * @param {Promise} promise
   * @return {Promise}
   */
  static _appendToSidebar(promise) {
    return promise.then((response) => {
      this.sidebar().add(response.data[this.__JSON_ATTR])
      return response
    })
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
    return this._appendToSidebar(super.fetchOne(id, config))
      .catch((err) => {
        this.sidebar().remove(id)
        throw err
      })
  }

  /**
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEdit(id, data = null, config = null) {
    return this._appendToSidebar(super.fetchEdit(id, data, config))
  }

  /**
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(data = null, config = null) {
    return this._appendToSidebar(super.fetchStore(data, config))
  }

  /**
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDelete(id, config = null) {
    return super.fetchDelete(id, config)
      .then((response) => {
        this.sidebar().remove(id)
        return response
      })
  }
}
