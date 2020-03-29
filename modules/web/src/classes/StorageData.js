'use strict'

import { isArray, isObject } from '@/scripts/helpers'

/** @type {string} */
const COLUMN_REQUESTS = 'column_requests'

/** @type {string} */
const COLUMN_USERS = 'column_users'

/** @type {string} */
const COLUMN_EQUIPMENTS = 'column_equipments'

/** @type {string} */
const COLUMN_ROLES = 'column_roles'

/** @type {string} */
const TOKEN = 'token'

/** @type {string} */
const PROFILE = 'profile'

/** @type {string} */
const PERMISSIONS = 'permissions'

export default class StorageData {

  /* | -------------------------------------------------------------------------------------
	 * | - Columns -
	 * | -------------------------------------------------------------------------------------
	 */

  static get remove() {
    const self = this

    return {
      columnRequests() {
        return self._remove(COLUMN_REQUESTS)
      },
      columnUsers() {
        return self._remove(COLUMN_USERS)
      },
      columnEquipments() {
        return self._remove(COLUMN_EQUIPMENTS)
      },
      columnRoles() {
        return self._remove(COLUMN_ROLES)
      },
      token() {
        return self._remove(TOKEN)
      },
      profile() {
        return self._remove(PROFILE)
      },
      permissions() {
        return self._remove(PERMISSIONS)
      }
    }
  }

  /* Column Requests ----------------------------------------------------------------------- */

  /** @return {Array} */
  static get columnRequests() {
    return this.getArray(COLUMN_REQUESTS)
  }

  static set columnRequests(value) {
    this.setArray(COLUMN_REQUESTS, value)
  }

  /* Column Users -------------------------------------------------------------------------- */

  /** @return {Array} */
  static get columnUsers() {
    return this.getArray(COLUMN_USERS)
  }

  static set columnUsers(value) {
    this.setArray(COLUMN_USERS, value)
  }

  /* Column Equipments -------------------------------------------------------------------- */

  /** @return {Array} */
  static get columnEquipments() {
    return this.getArray(COLUMN_EQUIPMENTS)
  }

  static set columnEquipments(value) {
    this.setArray(COLUMN_EQUIPMENTS, value)
  }

  /* Column Roles ------------------------------------------------------------------------- */

  /** @return {Array} */
  static get columnRoles() {
    return this.getArray(COLUMN_ROLES)
  }

  static set columnRoles(value) {
    this.setArray(COLUMN_ROLES, value)
  }

  /* Token -------------------------------------------------------------------------------- */

  /** @return {string} */
  static get token() {
    return this.getString(TOKEN)
  }

  static set token(value) {
    this.setString(TOKEN, value)
  }

  /* Profile ------------------------------------------------------------------------------ */

  /** @return {object} */
  static get profile() {
    return this.getObject(PROFILE)
  }

  static set profile(value) {
    this.setObject(PROFILE, value)
  }

  /* Permissions ---------------------------------------------------------------------------------- */

  /** @return {array} */
  static get permissions() {
    return this.getArray(PERMISSIONS)
  }

  static set permissions(value) {
    this.setArray(PERMISSIONS, value)
  }

  /* | -------------------------------------------------------------------------------------
	 * | - Common functions -
	 * | -------------------------------------------------------------------------------------
	 */

  /**
   * @param {string} key
   * @return {Array}
   */
  static getArray(key) {
    if (localStorage.getItem(key)) {
      try {
        const data = JSON.parse(localStorage.getItem(key))
        return isArray(data) ? data : []

      } catch (e) {
        return []
      }
    }

    return []
  }

  /**
   * @param {string} key
   * @param value
   */
  static setArray(key, value) {
    if (!value) {
      return
    }

    localStorage.setItem(key, JSON.stringify(isArray(value) ? value : [value]))
  }

  /**
   * @param {string} key
   * @returns {string}
   */
  static getString(key) {
    const item = localStorage.getItem(key)

    if (!item) {
      return ''
    }

    if (typeof item === 'object') {
      return ''
    }

    if (typeof item === 'number') {
      return item.toString() || ''
    }

    return item || ''
  }

  /**
   * @param {string} key
   * @param value
   */
  static setString(key, value) {
    localStorage.setItem(key, value)
  }

  /**
   * @param {string} key
   * @param value
   */
  static setObject(key, value) {
    if (!value || typeof value !== 'object') {
      return
    }

    localStorage.setItem(key, JSON.stringify(value))
  }

  /**
   * @param {string} key
   * @return {object}
   */
  static getObject(key) {
    if (localStorage.getItem(key)) {
      try {
        const data = JSON.parse(localStorage.getItem(key))
        return isObject(data) ? data : {}

      } catch (e) {
        return {}
      }
    }

    return {}
  }

  /**
   * @param key
   * @return {boolean}
   */
  static _remove(key) {
    if (localStorage.getItem(key) === null) {
      return false
    }

    localStorage.removeItem(key)
    return true
  }
}
