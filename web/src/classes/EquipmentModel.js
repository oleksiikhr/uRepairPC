'use strict'

import ApiSettingsList from '@/common/classes/ApiSettingsList'

export default class EquipmentModel extends ApiSettingsList {

  static get __API_POINT() {
    return 'equipments/models'
  }

  static get __STORE() {
    return 'equipmentModels'
  }

  static get __JSON_ATTR() {
    return 'equipment_model'
  }
}
