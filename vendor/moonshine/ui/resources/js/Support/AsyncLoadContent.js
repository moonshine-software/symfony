import axios from 'axios'

import {prepareUrl} from '../Request/Core.js'

// Modal/OffCanvas async content
export default async function load(url, id) {
  const {data, status} = await axios.get(prepareUrl(url))

  if (status === 200) {
    let containerElement = document.getElementById(id)

    containerElement.innerHTML = data?.html ?? data

    const scriptElements = containerElement.querySelectorAll('script')

    Array.from(scriptElements).forEach(scriptElement => {
      const clonedElement = document.createElement('script')

      Array.from(scriptElement.attributes).forEach(attribute => {
        clonedElement.setAttribute(attribute.name, attribute.value)
      })

      clonedElement.text = scriptElement.text

      scriptElement.parentNode.replaceChild(clonedElement, scriptElement)
    })
  }
}
