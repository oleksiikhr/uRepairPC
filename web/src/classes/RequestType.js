'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class RequestType extends ApiSettingsList {

  static get __API_POINT() {
    return 'requests/types'
  }

  static get __STORE() {
    return 'requestTypes'
  }

  static get __JSON_ATTR() {
    return 'request_type'
  }
}
