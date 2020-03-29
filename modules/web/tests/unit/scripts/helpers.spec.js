'use strict'

import * as methods from '@/scripts/helpers'

/**
 * @param {Function} fn
 * @param {Array} arr
 */
const expectListTruthy = (fn, arr) => {
  arr.forEach((val) => {
    it(`${val} - must be true`, () => expect(fn(val)).toBeTruthy())
  })
}

/**
 * @param {Function} fn
 * @param {Array} arr
 */
const expectListFalsy = (fn, arr) => {
  arr.forEach((val) => {
    it(`${val} - must be false`, () => expect(fn(val)).toBeFalsy())
  })
}

describe('Check scripts/helpers.js file', () => {
  describe('isObject function', () => {
    expectListTruthy(methods.isObject, [{}, { test: 123 }, { arr: [] }])
    expectListFalsy(methods.isObject, [null, 'some text', [], [{}], 325, false, true])
  })

  describe('isArray function', () => {
    expectListTruthy(methods.isArray, [[], [{}], ['text #1', 'text #2']])
    expectListFalsy(methods.isArray, [null, '', {}, { arr: [] }, 325236, false, true])
  })

  describe('isEmpty function', () => {
    expectListTruthy(methods.isEmpty, [null, [], {}, false, 0])
    expectListFalsy(methods.isEmpty, [{ test: 123 }, ['some text'], [{ test: 123 }], 1, true])
  })

  describe('getRndInteger function', () => {
    it('Min: 0, max: 0 - must be 0', () => expect(methods.getRndInteger(0, 0)).toBe(0))
    it('Min: -1, max: -1 - must be -1', () => expect(methods.getRndInteger(-1, -1)).toBe(-1))
  })
})
