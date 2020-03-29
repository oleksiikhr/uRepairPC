'use strict'

import * as methods from '@/scripts/utils'

describe('Check scripts/utils.js file', () => {
  describe('formatBytes function', () => {
    const checkTest = (bytes, decimals, result) => {
      it(`${bytes} bytes, ${decimals} decimals => ${result} | true`, () => {
        expect(methods.formatBytes(bytes, decimals)).toBe(result)
      })
    }

    checkTest(0, 0, '0 Bytes')
    checkTest(1024, 0, '1 KB')
    checkTest(1024 * 1024, 0, '1 MB')
    checkTest(1024 * 1024 * 1024, 0, '1 GB')
    checkTest(1025, 3, '1.001 KB')
    checkTest(1025, 2, '1 KB')
  })

  describe('hasPerm function', () => {
    const fn = methods.hasPerm

    it('test1 => [test1, test2] | true', () => {
      expect(fn('test1', null, ['test1', 'test2'])).toBeTruthy()
    })
    it('[test1] => [test1, test2] | true', () => {
      expect(fn(['test1'], null, ['test1', 'test2'])).toBeTruthy()
    })
    it('true => [test1, test2] | true', () => {
      expect(fn(true, null, ['test', 'test2'])).toBeTruthy()
    })
    it('false => [test1, test2] | false', () => {
      expect(fn(false, null, ['test1', 'test2'])).toBeFalsy()
    })
    it('[test3, () => true] => [test1] | true', () => {
      expect(fn(['test3', () => true], null, ['test1'])).toBeTruthy()
    })
    it('null => [] | true', () => {
      expect(fn(null, null, [])).toBeTruthy()
    })
    it('test3 => [test1, test2] | false', () => {
      expect(fn(['test3'], null, ['test1', 'test2'])).toBeFalsy()
    })
    it('(data) => !!data.test, [] | true', () => {
      expect(fn((data) => !!data.test, { test: true }, [])).toBeTruthy()
    })
  })

  describe('filterByPerm function', () => {
    const fn = methods.filterByPerm

    it('{} => [] | equal', () => {
      expect(fn({}, null, [])).toEqual({})
    })
    it('{ test: 123 } => [] | equal', () => {
      expect(fn({ test: 123 }, null, [])).toEqual({ test: 123 })
    })
    it('{ test: 123, permissions: test1 } => [test1] | equal', () => {
      expect(fn({ test: 123, permissions: 'test1' }, null, ['test1'])).toEqual({ test: 123, permissions: 'test1' })
    })
    it('{ test: 123, permissions: test1, children: {test: 321} } => [test1] | equal', () => {
      const obj = {
        test: 123,
        permissions: 'test1',
        children: { test: 321 }
      }

      expect(fn(obj, null, ['test1'])).toEqual(obj)
    })
    it('{ test: 123, per..s: test1, children: {test: 321, per..s: test2} } => [test1] | not equal', () => {
      const obj = {
        test: 123,
        permissions: 'test1',
        children: { test: 321, permissions: 'test2' }
      }

      expect(fn(obj, null, ['test1'])).toEqual({ test: 123, permissions: 'test1', children: {} })
    })
    it('{ test: 123, per..s: test2, children: {test: 321} } => [test1] | not equal', () => {
      const obj = {
        test: 123,
        permissions: 'test2',
        children: { test: 321 }
      }

      expect(fn(obj, null, ['test1'])).toEqual({})
    })
    it('{ per..s: test1, children: {content: text, children: {per..s: test2}} } => [test1] | not equal', () => {
      const obj = {
        permissions: 'test1',
        children: {
          content: 'text',
          children: {
            permissions: 'test2'
          }
        }
      }

      expect(fn(obj, null, ['test1'])).toEqual({
        permissions: 'test1',
        children: {
          content: 'text',
          children: {}
        }
      })
    })
  })
})
