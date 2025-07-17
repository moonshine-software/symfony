import debounce from './../../Support/Debounce.js'
import {afterEach, beforeEach, expect, jest} from '@jest/globals'

jest.useFakeTimers()

describe('debounce', () => {
  let callback

  beforeEach(() => {
    callback = jest.fn()
  })

  afterEach(() => {
    jest.clearAllTimers()
  })

  test('should call the callback after the delay', () => {
    const debouncedFunction = debounce(callback, 500)
    debouncedFunction()
    expect(callback).not.toHaveBeenCalled()
    jest.advanceTimersByTime(500)
    expect(callback).toHaveBeenCalledTimes(1)
  })

  test('should call the callback with the correct arguments', () => {
    const debouncedFunction = debounce(callback, 500, [1, 2, 3])
    debouncedFunction()
    jest.advanceTimersByTime(500)
    expect(callback).toHaveBeenCalledWith(1, 2, 3)
  })

  test('should reset the timer if called again before delay', () => {
    const debouncedFunction = debounce(callback, 500)
    debouncedFunction()
    jest.advanceTimersByTime(300)
    debouncedFunction()
    jest.advanceTimersByTime(300)
    expect(callback).not.toHaveBeenCalled()
    jest.advanceTimersByTime(200)
    expect(callback).toHaveBeenCalledTimes(1)
  })

  test('should not call the callback if not enough time has passed', () => {
    const debouncedFunction = debounce(callback, 500)
    debouncedFunction()
    jest.advanceTimersByTime(400)
    expect(callback).not.toHaveBeenCalled()
  })

  test('should apply the correct context when the function is called', () => {
    const context = {value: 42}
    const debouncedFunction = debounce(
      function () {
        expect(this.value).toBe(42)
      }.bind(context),
      500,
    )
    debouncedFunction()
    jest.advanceTimersByTime(500)
  })
})
