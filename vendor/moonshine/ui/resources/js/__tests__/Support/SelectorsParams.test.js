import {expect} from '@jest/globals'

import selectorsParams from './../../Support/SelectorsParams.js'

describe('selectorsParams', () => {
  test('should return an empty object if params are undefined', () => {
    const result = selectorsParams(undefined)
    expect(result).toEqual({})
  })

  test('should return data based on selectors', () => {
    document.body.innerHTML = '<input id="input1" value="value1" />'
    const result = selectorsParams('#input1')
    expect(result).toEqual({'#input1': 'value1'})
  })

  test('should handle multiple selectors correctly', () => {
    document.body.innerHTML =
      '<input id="input1" value="value1" />' + '<input id="input2" value="value2" />'
    const result = selectorsParams('#input1,#input2')
    expect(result).toEqual({'#input1': 'value1', '#input2': 'value2'})
  })

  test('should handle param name override with "/" syntax', () => {
    document.body.innerHTML = '<input id="input1" value="value1" />'
    const result = selectorsParams('#input1/overrideName')
    expect(result).toEqual({overrideName: 'value1'})
  })

  test('should return an empty object if no matching elements found', () => {
    document.body.innerHTML = ''
    const result = selectorsParams('#nonexistent')
    expect(result).toEqual({})
  })

  test('should work with custom root element', () => {
    const root = document.createElement('div')
    root.innerHTML = '<input id="input1" value="value1" />'
    const result = selectorsParams('#input1', root)
    expect(result).toEqual({'#input1': 'value1'})
  })
})
