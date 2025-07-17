import {listComponentRequest} from '../../Request/Sets.js'
import {expect, it, jest} from '@jest/globals'

it('should call preventDefault and set loading to true', () => {
  const component = {
    $event: {preventDefault: jest.fn(), detail: {}},
    $el: {href: null},
    asyncUrl: '/example',
    loading: false,
  }

  listComponentRequest(component)

  expect(component.$event.preventDefault).toHaveBeenCalled()
  expect(component.loading).toBe(true)
})
