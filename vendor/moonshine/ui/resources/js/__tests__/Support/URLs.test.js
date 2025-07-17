import {expect} from '@jest/globals'

import {
  excludeFromParams,
  limitQueryParams,
  mergeURLString,
  prepareQueryParams,
} from '../../Support/URLs.js'

describe('excludeFromParams', () => {
  test('should exclude specified parameters from URLSearchParams', () => {
    const params = new URLSearchParams({key1: 'value1', key2: 'value2'})
    const result = excludeFromParams(params, 'key1')
    expect(result.get('key1')).toBeNull()
    expect(result.get('key2')).toBe('value2')
  })

  test('should not modify params if exclude is null', () => {
    const params = new URLSearchParams({key1: 'value1'})
    const result = excludeFromParams(params)
    expect(result.get('key1')).toBe('value1')
  })
})

describe('limitQueryParams', () => {
  test('should limit parameter values to the specified length', () => {
    const params = new URLSearchParams({short: '12345', long: 'a'.repeat(60)})
    const result = limitQueryParams(params, 50)
    expect(result.get('short')).toBe('12345')
    expect(result.get('long')).toBeNull()
  })

  test('should handle empty params', () => {
    const params = new URLSearchParams()
    const result = limitQueryParams(params)
    expect(result.toString()).toBe('')
  })
})

describe('prepareQueryParams', () => {
  test('should prepare URLSearchParams and exclude parameters', () => {
    const params = new URLSearchParams({key1: 'value1', key2: 'value2'})
    const result = prepareQueryParams(params, 'key2')
    expect(result.get('key1')).toBe('value1')
    expect(result.get('key2')).toBeNull()
  })

  test('should return empty URLSearchParams if input is empty', () => {
    const params = new URLSearchParams()
    const result = prepareQueryParams(params)
    expect(result.toString()).toBe('')
  })
})

describe('mergeURLString', () => {
  test('should merge URL with query string correctly', () => {
    const url = 'http://example.com'
    const result = mergeURLString(url, 'key=value')
    expect(result).toBe('http://example.com?key=value')
  })

  test('should handle empty merge string', () => {
    const url = 'http://example.com'
    const result = mergeURLString(url, '')
    expect(result).toBe(url)
  })

  test('should append to existing query string', () => {
    const url = 'http://example.com?existing=value'
    const result = mergeURLString(url, 'key=new')
    expect(result).toBe('http://example.com?existing=value&key=new')
  })
})
