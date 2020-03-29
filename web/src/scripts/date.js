'use strict'

/**
 * @param {string|null} value
 * @returns {string|null}
 */
export function getDate(value) {
  if (!value) {
    return null
  }

  const date = new Date(value)

  if (isNaN(date)) {
    return null
  }

  return date.toLocaleString('uk')
}
