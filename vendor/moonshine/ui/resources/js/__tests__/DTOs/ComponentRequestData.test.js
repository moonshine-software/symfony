import {ComponentRequestData} from '../../DTOs/ComponentRequestData.js'
import {beforeEach, describe, expect, it} from '@jest/globals'

describe('ComponentRequestData', () => {
  let componentRequestData

  beforeEach(() => {
    componentRequestData = new ComponentRequestData()
  })

  it('should initialize with default values', () => {
    expect(componentRequestData.events).toBe('')
    expect(componentRequestData.selector).toBe('')
    expect(componentRequestData.beforeRequest).toBeNull()
    expect(componentRequestData.responseHandler).toBeNull()
    expect(componentRequestData.beforeHandleResponse).toBeNull()
    expect(componentRequestData.afterResponse).toBeNull()
    expect(componentRequestData.errorCallback).toBeNull()
    expect(componentRequestData.extraProperties).toEqual({})
  })

  it('should set events using withEvents', () => {
    componentRequestData.withEvents('event1')
    expect(componentRequestData.events).toBe('event1')
  })

  it('should set selector using withSelector', () => {
    componentRequestData.withSelector('#my-selector')
    expect(componentRequestData.selector).toBe('#my-selector')
  })

  it('should set beforeRequest using withBeforeRequest', () => {
    const beforeRequestFunc = () => {}
    componentRequestData.withBeforeRequest(beforeRequestFunc)
    expect(componentRequestData.beforeRequest).toBe(beforeRequestFunc)
  })

  it('should populate from dataset', () => {
    const dataset = {
      asyncEvents: 'event2',
      asyncSelector: '#async-selector',
      asyncResponseHandler: () => {},
      asyncBeforeRequest: () => {},
    }
    componentRequestData.fromDataset(dataset)
    expect(componentRequestData.events).toBe('event2')
    expect(componentRequestData.selector).toBe('#async-selector')
  })

  it('should populate from object', () => {
    const object = {
      events: 'event3',
      selector: '#object-selector',
      beforeRequest: () => {},
      beforeHandleResponse: () => {},
      responseHandler: () => {},
      afterResponse: () => {},
      errorCallback: () => {},
      extraProperties: {key: 'value'},
    }
    componentRequestData.fromObject(object)
    expect(componentRequestData.events).toBe('event3')
    expect(componentRequestData.selector).toBe('#object-selector')
    expect(componentRequestData.beforeRequest).toBe(object.beforeRequest)
    expect(componentRequestData.beforeHandleResponse).toBe(object.beforeHandleResponse)
    expect(componentRequestData.responseHandler).toBe(object.responseHandler)
    expect(componentRequestData.afterResponse).toBe(object.afterResponse)
    expect(componentRequestData.errorCallback).toBe(object.errorCallback)
    expect(componentRequestData.extraProperties).toEqual(object.extraProperties)
  })
})
