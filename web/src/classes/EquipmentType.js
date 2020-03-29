'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class EquipmentType extends ApiSettingsList {

  static get __API_POINT() {
    return 'equipments/types'
  }

  static get __STORE() {
    return 'equipmentTypes'
  }

  static get __JSON_ATTR() {
    return 'equipment_type'
  }
}
