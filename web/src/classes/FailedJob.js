'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import sections from '@/enum/sections'
import axios from 'axios'

export default class FailedJob extends ApiHasHistory {

  static get __API_POINT() {
    return 'jobs/failed'
  }

  static get __SECTION() {
    return sections.failedJobs
  }

  static get __JSON_ATTR() {
    return 'failed_job'
  }

  static fetchDeleteAll(config = {}) {
    return axios.delete(`${this.__API_POINT}/destroy-all`, config)
  }

  static fetchRefresh(config = {}) {
    return axios.post(`${this.__API_POINT}/retry`, config)
  }
}
