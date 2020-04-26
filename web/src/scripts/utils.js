'use strict'

import { isArray, isObject } from '@/scripts/helpers'
import StorageData from '@/classes/StorageData'
import store from '@/store'

/**
 * @param {string} path
 * @returns {string}
 */
export function getApiAuth(path) {
  return '/api/' + path + '?token=' + StorageData.token
}

/**
 * @param {number} bytes
 * @param {number} decimals
 * @return {string}
 * @see https://stackoverflow.com/questions/15900485/
 */
export function formatBytes(bytes, decimals = 2) {
  if (!bytes) {
    return '0 Bytes'
  }

  const k = 1024
  const dm = decimals < 0 ? 0 : decimals
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

  const i = Math.floor(Math.log(bytes) / Math.log(k))

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
}

/**
 * Check permission(s) with user permissions.
 * findPermissions is null - available to all.
 *
 * Pass boolean - for manual access check, for example:
 * [USERS_VIEW_ALL, user.id === 5]
 *
 * Pass callback and cbData to check through function, for example:
 * [USERS_VIEW_ALL, (cbData) => cbData.id === user.id]
 *
 * @param {array|string|boolean|function|null} findPermissions
 * @param {*} cbData
 * @param {array|object} myPermissions
 * @return {boolean}
 */
export function hasPerm(findPermissions, cbData = null, myPermissions = store.state.profile.permissionsObj) {
  if (!findPermissions && typeof findPermissions !== 'boolean') {
    return true
  }

  const checkPermission = (permission) => {
    if (typeof permission === 'boolean') {
      return permission
    }

    if (typeof permission === 'function') {
      const fnData = permission(cbData)

      if (isArray(fnData)) {
        return hasPerm(fnData, cbData)
      }

      return fnData
    }

    if (isArray(myPermissions)) {
      return myPermissions.includes(permission)
    }

    return !!myPermissions[permission]
  }

  if (isArray(findPermissions)) {
    return findPermissions.some(permission => checkPermission(permission))
  }

  return checkPermission(findPermissions)
}

/**
 * Recursively go around the object (children)
 * and check for permissions.
 *
 * @param {*} cbData
 * @param {object} data
 * @param {array|object} comparePermissions
 * @return {?object}
 * @example
 *  data - { test: 123, children: { test: 321, permissions: 'p1' } } =>
 *  comparePermissions - ['t1']
 *  return { test: 123 }
 */
export function filterByPerm(data, cbData = null, comparePermissions = store.state.profile.permissionsObj) {
  if (!data) {
    return data
  }

  if (!hasPerm(data.permissions, cbData, comparePermissions)) {
    return {}
  }

  const result = { ...data }

  if (isObject(data.children)) {
    result.children = filterByPerm(data.children, cbData, comparePermissions)
  }

  return result
}
