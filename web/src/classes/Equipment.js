'use strict'

import ApiHasHistory from '@/common/classes/ApiHasHistory'
import sections from '@/enum/sections'

export default class Equipment extends ApiHasHistory {

  static get __API_POINT() {
    return 'equipments'
  }

  static get __SECTION() {
    return sections.equipments
  }

  static get __JSON_ATTR() {
    return 'equipment'
  }

  /**
   * @param {object} obj - Equipment object
   */
  constructor(obj) {
    super()
    this.equipment = obj
  }

  /* | ------------------------------------------------------------------------------------------------
	 * | - Getters -
	 * | ------------------------------------------------------------------------------------------------
	 */

  /** @return {string} */
  get title() {
    return `${this.equipment.serial_number || '-'} / ${this.equipment.inventory_number || '-'}`
  }
}
