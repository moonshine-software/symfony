/* Toasts (notices) */

export default () => ({
  toasts: [],
  visible: [],

  add(toast) {
    toast.id = Date.now()
    this.toasts.push(toast)
    this.fire(toast.id)
  },

  fire(id) {
    const toast = this.toasts.find(toast => toast.id === id)
    this.visible.push(toast)
    const timeShown =
      toast.duration ?? MoonShine.config().getToastDuration() ?? 2000 * this.visible.length

    if (timeShown > 0) {
      setTimeout(() => {
        this.remove(id)
      }, timeShown)
    }
  },

  remove(id) {
    const toast = this.visible.find(toast => toast.id == id)
    const index = this.visible.indexOf(toast)
    this.visible.splice(index, 1)
  },
})
