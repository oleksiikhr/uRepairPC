'use strict'

import axios from 'axios'

export default class Permission {

  static get __API_POINT() {
    return 'permissions'
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
}
