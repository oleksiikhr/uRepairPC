'use strict'

import store from '@/store'
import axios from 'axios'

export default class SettingsManifest {

  static get __API_POINT() {
    return 'settings/manifest'
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Requests -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /**
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchGet(config = null) {
    return axios.get(this.__API_POINT, config)
  }

  /**
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(data = null, config = null) {
    return axios.post(this.__API_POINT, data, config)
      .then((res) => {
        store.commit('settings/SET_MANIFEST', res.data.data)
        return res
      })
  }

  /* | -------------------------------------------------------------------------------------
	 * | - Getters -
	 * | -------------------------------------------------------------------------------------
	 */

  static get rows() {
    return [
      { title: 'Назва', attr: 'name', type: 'text' },
      { title: 'Коротке ім\'я', attr: 'short_name', type: 'text' },
      { title: 'Орієнтаця', attr: 'orientation', type: 'text' },
      { title: 'Режим відображення', attr: 'display', type: 'text' },
      { title: 'Колір фону', attr: 'background_color', type: 'color' },
      { title: 'Колір теми', attr: 'theme_color', type: 'color' }
      // icons add custom
    ]
  }
}
