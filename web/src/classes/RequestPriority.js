'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class RequestPriority extends ApiSettingsList {

  static get __API_POINT() {
    return 'requests/priorities'
  }

  static get __STORE() {
    return 'requestPriorities'
  }

  static get __JSON_ATTR() {
    return 'request_priority'
  }
}
