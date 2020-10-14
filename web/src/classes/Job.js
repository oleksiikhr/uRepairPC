'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import sections from '@/enum/sections'

export default class Request extends ApiHasHistory {

  static get __API_POINT() {
    return 'jobs'
  }

  static get __SECTION() {
    return sections.requests
  }

  static get __JSON_ATTR() {
    return 'job'
  }
}
