'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import sections from '@/enum/sections'

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
}
