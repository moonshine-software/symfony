export class UI {
  toast(text, type = 'default', duration = null) {
    dispatchEvent(
      new CustomEvent('toast', {
        detail: {
          type: type,
          text: text,
          duration: duration,
        },
      }),
    )
  }

  toggleModal(name) {
    dispatchEvent(new CustomEvent(`modal_toggled:${name}`))
  }

  toggleOffCanvas(name) {
    dispatchEvent(new CustomEvent(`off_canvas_toggled:${name}`))
  }
}
