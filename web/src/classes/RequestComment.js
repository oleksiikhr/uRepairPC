'use strict'

import axios from 'axios'

export default class RequestComment {

  static __API_POINT(requestId) {
    return `requests/${requestId}/comments`
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {number} requestId
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchAll(requestId, config = null) {
    return axios.get(this.__API_POINT(requestId), config)
  }

  /**
   * @param {number} requestId
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEdit(requestId, id, data = null, config = null) {
    return axios.put(`${this.__API_POINT(requestId)}/${id}`, data, config)
  }

  /**
   * @param {number} requestId
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(requestId, data = null, config = null) {
    return axios.post(this.__API_POINT(requestId), data, config)
  }

  /**
   * @param {number} requestId
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDelete(requestId, id, config = null) {
    return axios.delete(`${this.__API_POINT(requestId)}/${id}`, config)
  }
}
