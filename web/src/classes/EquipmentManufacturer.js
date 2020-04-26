'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class EquipmentManufacturer extends ApiSettingsList {

  static get __API_POINT() {
    return 'equipments/manufacturers'
  }

  static get __STORE() {
    return 'equipmentManufacturers'
  }

  static get __JSON_ATTR() {
    return 'equipment_manufacturer'
  }
}
