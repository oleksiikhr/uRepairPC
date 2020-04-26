'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class RequestStatus extends ApiSettingsList {

  static get __API_POINT() {
    return 'requests/statuses'
  }

  static get __STORE() {
    return 'requestStatuses'
  }

  static get __JSON_ATTR() {
    return 'request_status'
  }
}
