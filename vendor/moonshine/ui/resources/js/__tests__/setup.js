import {jest} from '@jest/globals'

const _config = {
  toastDuration: undefined,
  forceRelativeUrls: false,
}

global.MoonShine = {
  ui: {
    toast: jest.fn(), // Mock the toast function
  },
  callbacks: {},
  config() {
    return {
      getToastDuration: () => _config.toastDuration,
      setToastDuration: value => {
        _config.toastDuration = value
      },

      isForceRelativeUrls: () => _config.forceRelativeUrls,
      forceRelativeUrls: condition => {
        _config.forceRelativeUrls = condition
      },
    }
  },
}

jest.spyOn(console, 'error').mockImplementation(msg => {})
