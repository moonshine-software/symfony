import {ComponentRequestData} from '../DTOs/ComponentRequestData.js'
import request from '../Request/Core.js'
import {formToJSON} from 'axios'
import {prepareFormExtraData} from '../Support/Forms.js'

export default () => ({
  saveField(route, column, value = null) {
    if (value === null && this.$el.type === 'checkbox') {
      const checkboxes = document.querySelectorAll(
        `input[type="checkbox"][name="${this.$el.name}"]`,
      )

      value =
        checkboxes.length > 1
          ? Array.from(checkboxes)
              .filter(cb => cb.checked)
              .map(cb => cb.value)
          : this.$el.value
    }

    if (value === null) {
      value = this.$el.value
    }

    if (value === null && (this.$el.type === 'radio' || this.$el.type === 'checkbox')) {
      value = this.$el.checked
    }

    if (this.$el.tagName.toLowerCase() === 'select' && this.$el.multiple) {
      value = []
      for (let i = 0; i < this.$el.options.length; i++) {
        let option = this.$el.options[i]
        if (option.selected) {
          value.push(option.value)
        }
      }
    }

    const componentRequestData = new ComponentRequestData()
    componentRequestData.fromDataset(this.$el?.dataset ?? {})

    const form = this.$el.closest('form')
    let extra = {}

    if (form) {
      extra = formToJSON(
        prepareFormExtraData(new FormData(form), '_component_name,_token,_method,page'),
      )
    }

    request(
      this,
      route,
      this.$el?.dataset?.asyncMethod ?? 'put',
      {
        value: value,
        field: column,
        _data: extra,
      },
      {},
      componentRequestData,
    )
  },
})
