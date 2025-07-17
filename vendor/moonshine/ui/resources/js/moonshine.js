import request from './Request/Core.js'
import {Iterable} from './Support/Iterable.js'
import {UI} from './Support/UI.js'
import {ComponentRequestData} from './DTOs/ComponentRequestData.js'
import {dispatchEvents} from './Support/DispatchEvents.js'

const _config = {
  toastDuration: undefined,
  forceRelativeUrls: false,
}

export class MoonShine {
  constructor() {
    this.callbacks = {}
    this.iterable = new Iterable()
    this.ui = new UI()
  }

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
  }

  onCallback(name, callback) {
    if (typeof callback === 'function') {
      this.callbacks[name] = callback
    }
  }

  request(t, url, method = 'get', body = {}, headers = {}, data = {}) {
    if (!(data instanceof ComponentRequestData)) {
      data = new ComponentRequestData().fromObject(data)
    }

    request(t, url, method, body, headers, data)
  }

  dispatchEvents(events, type, component, extraProperties = {}) {
    dispatchEvents(events, type, component, extraProperties)
  }
}
