'use strict'

import defaultFavicon from '@/images/icon.png'
import { setFavicon } from '@/scripts/dom'
import store from '@/store'
import axios from 'axios'

export default class SettingsGlobal {

  static get __API_POINT() {
    return 'settings/global'
  }

  static init() {
    store.dispatch('settings/fetchGlobal')
  }

  static updateDOM(data) {
    // Favicon
    setFavicon(data.favicon || defaultFavicon)

    // Meta title
    const el = document.querySelector('head title')
    if (el) {
      el.innerText = data.meta_title || 'uRepairPC'
    }
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
      .then((res) => {
        SettingsGlobal.updateDOM(res.data)
        return res
      })
  }

  /**
   * @param {*} data
   * @param {AxiosRequestConfig} config
   * @return {Promise<AxiosPromise<any>>}
   */
  static fetchStore(data = null, config = null) {
    return axios.post(this.__API_POINT, data, config)
      .then((res) => {
        store.commit('settings/SET_GLOBAL', res.data.data)
        SettingsGlobal.updateDOM(res.data.data)
        return res
      })
  }

  /* | -------------------------------------------------------------------------------------
	 * | - Getters -
	 * | -------------------------------------------------------------------------------------
	 */

  static get rows() {
    return [
      { title: 'Назва', attr: 'app_name', type: 'text' },
      { title: 'Favicon', attr: 'favicon', type: 'img', mimes: 'image/png, image/x-icon' },
      { title: 'Назва вкладки', attr: 'meta_title', type: 'text' },
      {
        title: 'Фотографія при авторізації',
        attr: 'logo_auth',
        type: 'img',
        mimes: 'image/jpeg, image/jpg, image/png'
      },
      { title: 'Фотографія в шапці', attr: 'logo_header', type: 'img', mimes: 'image/jpeg, image/jpg, image/png' },
      { title: 'Фотографія в шапці і назва - разом', attr: 'name_and_logo', type: 'bool' }
    ]
  }
}
