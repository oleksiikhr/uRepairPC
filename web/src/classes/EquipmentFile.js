'use strict'

import axios from 'axios'

export default class EquipmentFile {

  static __API_POINT(equipmentId) {
    return `equipments/${equipmentId}/files`
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {number} equipmentId
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchAll(equipmentId, config = null) {
    return axios.get(this.__API_POINT(equipmentId), config)
  }

  /**
   * @param {number} equipmentId
   * @param {number} id
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchEdit(equipmentId, id, data = null, config = null) {
    return axios.put(`${this.__API_POINT(equipmentId)}/${id}`, data, config)
  }

  /**
   * @param {number} equipmentId
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(equipmentId, data = null, config = null) {
    return axios.post(this.__API_POINT(equipmentId), data, config)
  }

  /**
   * @param {number} equipmentId
   * @param {number} id
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchDelete(equipmentId, id, config = null) {
    return axios.delete(`${this.__API_POINT(equipmentId)}/${id}`, config)
  }
}
