'use strict'

import axios from 'axios'

export default class Api {

  /**
   * Required for use in child classes
   * @return {string}
   */
  static get __API_POINT() {
    return ''
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchAll(config = null) {
    return axios.get(this.__API_POINT, config)
  }

  /**
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchOne(id, config = null) {
    return axios.get(`${this.__API_POINT}/${id}`, config)
  }

  /**
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEdit(id, data = null, config = null) {
    return axios.put(`${this.__API_POINT}/${id}`, data, config)
  }

  /**
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(data = null, config = null) {
    return axios.post(this.__API_POINT, data, config)
  }

  /**
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDelete(id, config = null) {
    return axios.delete(`${this.__API_POINT}/${id}`, config)
  }
}
