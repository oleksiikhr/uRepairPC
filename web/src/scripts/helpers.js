'use strict'

/**
 * @param {*} input
 * @return {boolean}
 */
export function isObject(input) {
  return input && typeof input === 'object' && input.constructor === Object
}

/**
 * @param {*} input
 * @return {boolean}
 */
export function isArray(input) {
  return Array.isArray(input)
}

/**
 * @param {*} input
 * @return {boolean}
 */
export function isEmpty(input) {
  if (isArray(input)) {
    return !input.length
  }

  if (isObject(input)) {
    return !Object.keys(input).length
  }

  return !input
}

/**
 * Return a random number between min and max (both included).
 * @param {number} min
 * @param {number} max
 * @return {number}
 */
export function getRndInteger(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min
}
