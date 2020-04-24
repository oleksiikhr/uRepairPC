'use strict'

import Notification from 'element-ui/lib/notification'
import { isArray, isObject } from '@/scripts/helpers'
import { runLoadingService } from '@/scripts/dom'
import StorageData from '@/classes/StorageData'
import Message from 'element-ui/lib/message'
import logout from '@/scripts/logout'
import types from '@/enum/types'
import store from '@/store'
import axios from 'axios'

/*
 * In dev mode, all requests are sent to the server via
 * proxy target in webpack to bypass CORS.
 */
axios.defaults.baseURL = '/api'

/** @type {Array} */
let requestsToRefresh = []

/** @type {boolean} */
let isRequestToRefresh = false

axios.interceptors.response.use(
  (resp) => {
    // Notification
    if (resp.config.method !== 'get' && resp.data.message) {
      Message({ message: resp.data.message, type: types.SUCCESS })
    }

    return resp
  },
  async (err) => {
    const { response, config } = err

    if (response.status === 401) {
      // If we have not logged in before, it makes no sense
      // to try to get a new token
      if (!store.state.profile.isLogin) {
        return Promise.reject(err)
      }

      // User is auth, probably token is expired, try renew
      // And send all failed requests again
      if (!isRequestToRefresh) {
        isRequestToRefresh = true

        // Disable all interface
        const loadingService = runLoadingService('Оновлюється токен безпеки')

        // Send request to refresh token
        axios.post('auth/refresh')
          .then(({ data }) => {
            axios.defaults.headers['Authorization'] = 'Bearer ' + data.token
            StorageData.token = data.token
            requestsToRefresh.forEach((cb) => cb(data.token))
          })
          .catch(() => {
            logout()
            requestsToRefresh.forEach((cb) => cb(null))
          })
          .finally(() => {
            loadingService.close()
            requestsToRefresh = []
            isRequestToRefresh = false
          })
      }

      return new Promise((resolve, reject) => {
        // In our variable (requests that expect a new token
        // from the first request), we add a callback,
        // which the first request to execute
        requestsToRefresh.push((token) => {
          if (token) {
            config.headers.Authorization = 'Bearer ' + token
            resolve(axios(config))
          }

          // If the first request could not update the token, we
          // must return the basic request processing logic
          reject(Promise.reject(err))
        })
      })
    }

    // No access, etc
    if (response.status === 403) {
      store.dispatch('profile/update')
    }

    // Notification / Message
    if (isObject(response.data)) {
      if (response.data.message) {
        Message({ message: response.data.message, type: types.ERROR })
      }

      // Show validate form if exists from backend
      if (isObject(response.data.errors)) {
        let message = ''

        /**
         * @example {'image.png': ['File not saved']}
         *  <strong>image.png</strong>
         *  <ul>
         *    <li>File not saved<li>
         *    ...
         *  </ul>
         */
        Object.entries(response.data.errors).forEach(([key, val]) => {
          message += `<strong>${key}</strong>:<br>`
          if (isArray(val)) {
            message += '<ul>'
            val.forEach((error) => {
              message += '<li>' + error.replace(/<(?:.|\n)*?>/gm, '') + '</li>'
            })
            message += '</ul>'
          }
        })

        Notification.error({ title: 'Помилка валідації', duration: 6000, dangerouslyUseHTMLString: true, message })
      }
    }

    return Promise.reject(err)
  }
)

export default (Vue) => Vue.prototype.$axios = axios
