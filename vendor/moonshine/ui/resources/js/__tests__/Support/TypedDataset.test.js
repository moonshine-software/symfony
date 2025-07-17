import {expect} from '@jest/globals'

import typedDataset from './../../Support/TypedDataset.js'

describe('typedDataset', () => {
  test('should convert string booleans to actual booleans', () => {
    const dataset = {
      active: 'true',
      disabled: 'false',
      name: 'Test',
    }
    const result = typedDataset(dataset)
    expect(result).toEqual({
      active: true,
      disabled: false,
      name: 'Test',
    })
  })

  test('should handle empty dataset', () => {
    const result = typedDataset({})
    expect(result).toEqual({})
  })

  test('should not convert non-boolean strings', () => {
    const dataset = {
      id: '123',
      status: 'pending',
    }
    const result = typedDataset(dataset)
    expect(result).toEqual({
      id: '123',
      status: 'pending',
    })
  })
})
