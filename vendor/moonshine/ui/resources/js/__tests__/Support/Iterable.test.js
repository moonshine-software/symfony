import {expect, jest} from '@jest/globals'

import {Iterable} from '../../Support/Iterable.js'

jest.useFakeTimers()

describe('Iterable', () => {
  let iterable

  beforeEach(() => {
    iterable = new Iterable()
  })

  test('reindex should set attributes correctly', async () => {
    document.body.innerHTML =
      '<table data-top-level="true" id="table" data-name="table">' +
      '<tr>' +
      '<td><input data-name="table[${index0}][input1]" id="input-1-1" data-level="0" /></td>' +
      '<td><input data-name="table[${index0}][input2]" id="input-1-2" data-level="0" /></td>' +
      '</tr>' +
      '<tr>' +
      '<td><input data-name="table[${index0}][input1]" id="input-2-1" data-level="0" /></td>' +
      '<td><input data-name="table[${index0}][input2]" id="input-2-2" data-level="0" /></td>' +
      '</tr>' +
      '</table>'

    const table = document.getElementById('table')
    await iterable.reindex(table, 'tr', 'tr')

    jest.runAllTicks()

    expect(table.getAttribute('data-r-block')).toBe('true')
    expect(table.getAttribute('data-r-item-selector')).toBe('tr')

    expect(table.querySelector('#input-1-1').getAttribute('name')).toBe('table[0][input1]')
    expect(table.querySelector('#input-1-2').getAttribute('name')).toBe('table[0][input2]')

    expect(table.querySelector('#input-2-1').getAttribute('name')).toBe('table[1][input1]')
    expect(table.querySelector('#input-2-2').getAttribute('name')).toBe('table[1][input2]')
  })
})
